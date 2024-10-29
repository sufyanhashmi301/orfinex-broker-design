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

    <div class="grid grid-cols-12 gap-5">
        <div class="lg:col-span-4 col-span-12">
            <div class="card h-full">
                <div class="card-body flex items-center justify-center p-6">
                    <div id="lottie-container" class="inline-flex" style="width: 350px; height: 350px;"></div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-8 col-span-12">
            <ul class="list-item space-y-3 h-full overflow-x-auto">
                @php $count = 1; @endphp
                @foreach($kycLevels as $kyc)
                    <li class="card single-gateway flex items-center justify-between border rounded dark:border-slate-700 py-3 px-4">
                        <div class="flex items-center">
                            <div class="flex-none">
                                <div class="flex flex-items justify-center w-6 h-6 rounded-[100%] bg-body dark:text-white dark:bg-body ltr:mr-3 rtl:ml-3">
                                    {{ $count }}
                                </div>
                            </div>
                            <div class="flex-1 text-start">
                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                    {{ $kyc->name }}
                                </h4>
                            </div>
                        </div>
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
                            <a href="{{ route('admin.kyclevels.edit',$kyc->id) }}" class="toolTip onTop action-btn dark:text-slate-300">
                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                            </a>
                        </div>
                    </li>
                    @php $count++; @endphp
                @endforeach
            </ul>
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
