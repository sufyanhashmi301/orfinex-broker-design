@extends('backend.setting.integrations.index')
@section('title')
    {{ __('Plugin Settings') }}
@endsection
@section('integrations-content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ $title }}
        </h4>
        @if($isLink)
            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                <a href="{{ route('admin.settings.notification.tune') }}" class="btn btn-sm btn-primary inline-flex items-center justify-center new-referral" type="button" data-type="investment">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:volume-1"></iconify-icon>
                    {{ __('Set Tune') }}
                </a>
            </div>
        @endif
    </div>
    {{--@include('backend.setting.plugin.include.__menu')--}}

    <div class="grid xl:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
        @foreach($plugins as $plugin)
            <div class="card border hover:shadow-lg">
                <div class="card-header items-center noborder !p-4">
                    <img class="inline-block h-10" src="{{ filter_var($plugin->icon, FILTER_VALIDATE_URL) ? $plugin->icon : asset($plugin->icon) }}" alt=""/>
                    <button type="button" class="action-btn cursor-pointer editPlugin dark:text-slate-300" data-id="{{$plugin->id}}">
                        <iconify-icon icon="lucide:settings-2"></iconify-icon>
                    </button>
                </div>
                <div class="card-body p-4 pt-2">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-base font-medium dark:text-white mr-1">{{ $plugin->name }}</h4>
                        @if($plugin->status)
                            <div class="badge bg-success text-success bg-opacity-30 capitalize">
                                {{ __('Activated') }}
                            </div>
                        @else
                            <div class="badge bg-danger text-danger bg-opacity-30 capitalize">
                                {{ __('DeActivated') }}
                            </div>
                        @endif
                    </div>
                    <p class="text-sm dark:text-slate-300">{{ $plugin->description }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal for Edit Plugin -->
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="editPlugin" tabindex="-1" aria-labelledby="editPlugin" aria-hidden="true">
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
                <div class="modal-body popup-body">
                    <div class="popup-body-text p-6 pt-5 edit-plugin-section">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Edit Plugin-->
@endsection
@section('integrations-script')

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
