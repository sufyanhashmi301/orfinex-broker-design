@extends('backend.setting.communication.index')
@section('title')
    {{ __('Notification Settings') }}
@endsection

@section('communication-content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Notification Tune Settings') }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a  href="{{ route('admin.settings.plugin','notification') }}" class="btn btn-sm btn-primary inline-flex items-center justify-center new-referral" type="button" data-type="investment">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                {{ __('Back') }}
            </a>
        </div>
    </div>
    <div class="col-span-12">
        <div class="card">
            <div class="card-body p-6 space-y-4">
                @foreach($set_tunes as $set_tune)
                <div class="single-gateway flex flex-wrap items-center justify-between border rounded dark:border-slate-700 gap-3 py-3 px-4">
                    <div class="gateway-name flex items-center gap-2">
                        <div class="gateway-icon mr-4">
                            <img class="h-7" src="{{ asset($set_tune->icon) }}" alt=""/>
                        </div>
                        <div class="gateway-title">
                            <h4 class="text-sm">{{ $set_tune->name }}</h4>
                        </div>
                    </div>
                    <div class="gateway-right flex items-center gap-2">
                        <div class="gateway-status m-0 me-2">
                            <button type="button" value="{{ $set_tune->id }}" data-tune-preview="{{ asset($set_tune->tune) }}" class="btn btn-dark btn-sm inline-flex justify-center audioPlay">
                                <span class="flex items-center">
                                    <span class="play-{{$set_tune->id}} play" style="line-height: 0;"> <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2 play" icon="lucide:play"></iconify-icon></span>
                                    <span class="stop-{{$set_tune->id}} hidden stop" style="line-height: 0;"> <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:pause"></iconify-icon></span>
                                    <span class="tune-status-{{$set_tune->id}} status-text">{{ __('Play') }}</span>
                                </span>
                            </button>
                        </div>
                        <div class="gateway-status m-0">
                            @if($set_tune->status == true)
                                <a href="{{ route('admin.settings.notification.tune.status', $set_tune->id) }}" class="btn btn-success btn-sm inline-flex items-center justify-center">
                                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                    {{ __('Active in') }}
                                </a>
                            @else
                                <a href="{{ route('admin.settings.notification.tune.status', $set_tune->id) }}" class="btn btn-danger btn-sm inline-flex items-center justify-center">
                                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                    {{ __('Inactive') }}
                                </a>
                            @endif
                        </div>

                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection
@section('communication-script')
<script>
    (function ($) {
        'use strict';
    var audio;
    var isPlaying = false;
    var oldTuneSrc = null
    $('.audioPlay').on('click', function (e) {
         e.preventDefault();
        var id = $(this).val();
        $('.stop').addClass('hidden');
        $('.play').removeClass('hidden');

        let tunePreview = $(this).data('tune-preview');
        var tuneStatus = $('.tune-status-'+$(this).val());
        var status = tuneStatus.text();

        if (status === 'Play') {
            if (isPlaying && tunePreview === oldTuneSrc) {
                $('.play-'+$(this).val()).addClass('hidden');
                $('.stop-'+$(this).val()).removeClass('hidden');
                tuneStatus.text('Stop');
                audio.pause();
                audio.currentTime = 0;

            }else {
                $('.play-'+$(this).val()).addClass('hidden');
                $('.stop-'+$(this).val()).removeClass('hidden');

                $('.status-text').text('Play');
                tuneStatus.text('Stop');

                if (audio) {
                    audio.pause();
                    audio.currentTime = 0;
                }
                audio = new Audio(tunePreview);

                $(audio).on('ended', function() {
                    tuneStatus.text('Play');
                    $('.play-'+id).removeClass('hidden');
                    $('.stop-'+id).addClass('hidden');
                    isPlaying = false;
                });

                audio.play();
                isPlaying = true;
                oldTuneSrc = tunePreview
            }

        } else if (status === 'Stop') {
            $('.play-'+$(this).val()).removeClass('hidden');
            $('.stop-'+$(this).val()).addClass('hidden');
            audio.pause();
            audio.currentTime = 0;
            tuneStatus.text('Play');
            isPlaying = false;
        }
    })

    })(jQuery)
</script>

@endsection

