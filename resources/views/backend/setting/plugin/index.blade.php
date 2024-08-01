@extends('backend.setting.index')
@section('title')
    {{ __('Plugin Settings') }}
@endsection
@section('setting-content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ $title }}
        </h4>
        @if($isLink)
            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                <a href="{{ route('admin.settings.notification.tune') }}" class="btn btn-primary inline-flex items-center justify-center new-referral" type="button" data-type="investment">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:volume-1"></iconify-icon>
                    {{ __('Set Tune') }}
                </a>
            </div>
        @endif
    </div>
    @include('backend.setting.plugin.include.__menu')

    <div class="col-span-12">
        <div class="card">
            <div class="card-body p-6 space-y-4">
                <p class="paragraph text-xs">
                    <iconify-icon class="text-sm mr-2 text-warning-500" icon="lucide:info"></iconify-icon>{{ __('You can') }}
                    <strong>{{ __('Enable or Disable') }}</strong> {{ __('any of the plugin') }}
                </p>
                @foreach($plugins as $plugin)
                    <div class="single-gateway flex items-center justify-between border rounded py-3 px-4">
                        <div class="gateway-name flex items-center gap-2">
                            <div class="gateway-icon mr-4">
                                <img class="h-7" src="{{ asset($plugin->icon) }}" alt=""/>
                            </div>
                            <div class="gateway-title">
                                <h4 class="text-sm">{{ $plugin->name }}</h4>
                                <p class="text-xs">{{ $plugin->description }}</p>
                            </div>
                        </div>
                        <div class="gateway-right flex items-center gap-2">
                            <div class="gateway-status">
                                @if($plugin->status)
                                    <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
                                        {{ __('Activated') }}
                                    </div>
                                @else
                                    <div class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">
                                        {{ __('DeActivated') }}
                                    </div>
                                @endif
                            </div>
                            <div class="gateway-edit">
                                <a type="button" class="action-btn cursor-pointer editPlugin" data-id="{{$plugin->id}}">
                                    <iconify-icon icon="lucide:settings-2"></iconify-icon>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

    <!-- Modal for Edit Plugin -->
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="editPlugin" tabindex="-1" aria-labelledby="editPlugin" aria-hidden="true">
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="modal-body popup-body">
                    <div class="popup-body-text p-6 pt-5 edit-plugin-section">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Edit Plugin-->
@endsection
@section('script')

    <script>
        $('.editPlugin').on('click', function (e) {
            "use strict"
            var id = $(this).data('id');
            $('.edit-plugin-section').empty();

            var url = '{{ route("admin.settings.plugin.data",":id") }}';
            url = url.replace(':id', id);
            $.get(url, function ($data) {
                $('.edit-plugin-section').append($data)
                $('#editPlugin').modal('show');

            })
        })
    </script>

@endsection
