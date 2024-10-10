@extends('backend.theme.index')
@section('theme-title')
    {{ __('Dynamic Landing Theme') }}
@endsection
@section('theme-content')
    <div class="col-span-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('Dynamic Landing Theme') }}</h4>
                <form action="{{ route('admin.theme.dynamic-landing-update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-header-links new-referral">
                        <div class="joint-input relative">
                            <input type="file" placeholder="hfh" name="theme_file" class="form-control !pr-12" required/>
                            <button type="submit" class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center">
                                <iconify-icon icon="lucide:upload-cloud"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body p-6">
                <p class="paragraph flex items-center text-sm">
                    <iconify-icon class="text-warning text-sm mr-1" icon="lucide:info"></iconify-icon>{{ __('You can upload your own HTML template here as a website Home page and other pages. You need to add ') }}
                    <strong> @@lasset('landing asset')</strong> {{  __(' for any assets support on the theme') }}
                </p>
                @foreach($landingThemes as $theme)
                    <div class="single-gateway">
                        <div class="gateway-name">
                            <div class="gateway-icon">
                                <i icon-name="feather"></i>
                            </div>
                            <div class="gateway-title">
                                <h4>{{ $theme->name }}</h4>
                            </div>
                        </div>
                        <div class="gateway-right">
                            <div class="gateway-status">
                                <div class="switch-field mb-0">
                                    <input
                                        type="radio"
                                        id="theme-status{{ $theme->id }}"
                                        class="theme-status"
                                        name="status{{ $theme->id }}"
                                        value="1"
                                        data-id="{{ $theme->id }}"
                                        @if($theme->status) checked @endif
                                    />
                                    <label for="theme-status{{ $theme->id }}">{{ __('Active') }}</label>
                                    <input
                                        type="radio"
                                        id="theme-status-no{{ $theme->id }}"
                                        name="status{{ $theme->id }}"
                                        class="theme-status"
                                        value="0"
                                        data-id="{{ $theme->id }}"
                                        @if(!$theme->status) checked @endif

                                    />
                                    <label for="theme-status-no{{ $theme->id }}">{{ __('DeActive') }}</label>
                                </div>

                            </div>
                            <div class="gateway-edit">
                                <button type="button" data-id="{{ $theme->id }}"
                                        data-name="{{ $theme->name }}"
                                        class="round-icon-btn red-btn deleteLandingTheme">
                                    <i icon-name="trash-2"></i> {{ __('Delete') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

    <!-- Modal for Delete Theme Lending -->
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
        id="deleteLandingTheme"
        tabindex="-1"
        aria-labelledby="deleteLandingTheme"
        aria-hidden="true"
    >
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="modal-body p-6 py-8 text-center space-y-5">
                    <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-danger text-danger bg-opacity-30">
                        <iconify-icon class="text-4xl" icon="lucide:alert-triangle"></iconify-icon>
                    </div>
                    <div class="title">
                        <h4 class="text-xl font-medium dark:text-white capitalize">
                            {{ __('Reject IB Member') }}
                        </h4>
                    </div>
                    <p>
                        {{ __('You want to Delete') }}
                        <strong class="name"></strong> {{ __('landing Theme?') }}
                    </p>
                    <form method="post" id="themeLandingDeleteForm">
                        @csrf
                        <div class="action-btns">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __(' Confirm') }}
                            </button>
                            <a href="" class="btn btn-danger inline-flex items-center justify-center" type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Delete Plugin-->
@endsection
@section('script')

    <script>
        $('.theme-status').on('click', function (e) {
            "use strict"
            var id = $(this).data('id');
            var status = parseInt($(this).val());
            var url = '{{ route("admin.theme.status-update") }}';
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    id: id,
                    status: status
                },
                success: function (data) {
                    var oldStatus = data.old_status
                    for (var i = 0; i < oldStatus.length; i++) {
                        $('#theme-status' + oldStatus[i]).prop('checked', false);
                        $('#theme-status-no' + oldStatus[i]).prop('checked', true);

                    }
                    tNotify('success', data.message);
                }
            });


        })


        $('.deleteLandingTheme').on('click', function (e) {
            "use strict";
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            var url = '{{ route("admin.theme.dynamic-landing-delete", ":id") }}';
            url = url.replace(':id', id);
            $('#themeLandingDeleteForm').attr('action', url)

            $('.name').html(name);
            $('#deleteLandingTheme').modal('show');
        })

    </script>

@endsection
