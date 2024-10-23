@extends('backend.setting.user_management.index')
@section('title')
    {{ __('KYC Levels') }}
@endsection
@section('user-management-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('KYC Levels') }}
        </h4>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <div class="grid grid-cols-12 items-center gap-5">
                <div class="lg:col-span-5 col-span-12 relative text-center">
                    <div id="lottie-container" class="inline-flex" style="width: 350px; height: 350px;"></div>
                    <div class="absolute right-0 top-0 hidden h-full min-h-[1em] w-px self-stretch border-t-0 bg-gradient-to-tr from-transparent via-neutral-500 to-transparent opacity-25 dark:via-neutral-400 lg:block"></div>
                </div>
                <div class="lg:col-span-7 col-span-12">
                    <ul class="list-item space-y-3 h-full overflow-x-auto">
                        @foreach($kycLevels as $kyc)
                            <li class="single-gateway flex items-center justify-between border rounded dark:border-slate-300 py-3 px-4">
                                <p class="gateway-name text-lg text-slate-900 dark:text-slate-50">{{ $kyc->name }}</p>
                                <div class="gateway-right flex items-center gap-2">
                                    @if( $kyc->status)
                                        <div class="badge bg-success text-success bg-opacity-30 capitalize">
                                            {{ __('Active') }}
                                        </div>
                                    @else
                                        <div class="badge bg-danger text-danger bg-opacity-30 capitalize">
                                            {{ __('Disabled') }}
                                        </div>
                                    @endif
                                    <a href="{{ route('admin.kyclevels.edit',$kyc->id) }}" class="toolTip onTop action-btn">
                                        <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Delete deleteKycType -->
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
        id="deleteKyc"
        tabindex="-1"
        aria-labelledby="deleteKyc"
        aria-hidden="true"
    >
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
                <div class="relative rounded-lg shadow">
                    <div class="modal-body popup-body p-6 py-8 text-center space-y-5">
                        <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-danger text-danger bg-opacity-30">
                            <iconify-icon class="text-4xl" icon="lucide:alert-triangle"></iconify-icon>
                        </div>
                        <div class="title">
                            <h4 class="text-xl font-medium dark:text-white capitalize">
                                {{ __('Are you sure?') }}
                            </h4>
                        </div>
                        <p class="dark:text-slate-300">
                            {{ __('You want to Delete') }}
                            <strong class="name"></strong>
                            {{ __('KYC Verification Type?') }}
                        </p>
                        <form method="post" id="kycEditForm">
                            @method('DELETE')
                            @csrf
                            <div class="action-btns text-center">
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
    </div>
    <!-- Modal for Delete deleteKycType-->
@endsection
@section('user-management-script')
    <script>
        $('.deleteKyc').on('click',function (e) {
            "use strict";
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            var url = '{{ route("admin.kyc-form.destroy", ":id") }}';
            url = url.replace(':id', id);
            $('#kycEditForm').attr('action', url)

            $('.name').html(name);
            $('#deleteKyc').modal('show');
        })

        var animation = lottie.loadAnimation({
            container: document.getElementById('lottie-container'), // ID of the div where the animation will render
            renderer: 'svg',  // Render the animation in SVG format
            loop: true,       // Loop the animation
            autoplay: true,   // Autoplay the animation
            path: '{{ asset('global/json/kyc.json') }}' // Path to your JSON file
        });

    </script>
@endsection
