@extends('backend.setting.payment.withdraw.index')
@section('title')
    {{ __('Notification Tune Settings') }}
@endsection
@section('page-title')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
@endsection
@section('withdraw-content')
    <?php
        $section = 'notification_tune';
        $fields = config('setting.notification_tune');
        //   dd($fields);
    ?>
    <div class="card">
        <div class="card-body p-6 space-y-4">
            @foreach($tunes as $tune)
                <div class="single-gateway flex flex-wrap items-center justify-between border rounded dark:border-slate-700 gap-3 py-3 px-4">
                    <div class="gateway-name flex items-center gap-2">
                        <div class="gateway-icon mr-4">
                            <img class="h-7" src="{{ asset($tune->icon) }}" alt=""/>
                        </div>
                        <div class="gateway-title">
                            <h4 class="text-sm">{{ $tune->name }}</h4>
                        </div>
                    </div>
                    <div class="gateway-right flex items-center gap-2">
                        <div class="gateway-status m-0 me-2">
                            <button type="button" value="{{ $tune->id }}" data-tune-preview="{{ asset($tune->tune) }}" class="btn btn-dark btn-sm inline-flex justify-center audioPlay">
                                <span class="flex items-center">
                                    <span class="play-{{$tune->id}} play" style="line-height: 0;"> <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2 play" icon="lucide:play"></iconify-icon></span>
                                    <span class="stop-{{$tune->id}} hidden stop" style="line-height: 0;"> <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:pause"></iconify-icon></span>
                                    <span class="tune-status-{{$tune->id}} status-text">{{ __('Play') }}</span>
                                </span>
                            </button>
                        </div>
                        <div class="gateway-status m-0">
                            <form action="{{ route('admin.settings.update') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="section" value="{{$section}}">
                                <input type="hidden" name="withdraw_notification_tune" value="{{ asset($tune->tune) }}">
                                @if(setting('withdraw_notification_tune', 'notification_tune') == asset($tune->tune))
                                    <button type="button" href="" class="btn btn-success btn-sm inline-flex items-center justify-center">
                                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                        {{ __('Active in') }}
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-danger btn-sm inline-flex items-center justify-center">
                                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                        {{ __('Inactive') }}
                                    </button>
                                @endif
                            </form>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@section('payment-script')
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
