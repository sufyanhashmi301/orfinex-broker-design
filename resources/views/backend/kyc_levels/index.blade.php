@extends('backend.setting.user_management.index')
@section('title')
    {{ __('KYC & Compliance') }}
@endsection
@section('user-management-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-10">
        <div>
            <h4 class="font-medium text-xl capitalize dark:text-white inline-block ltr:pr-4 rtl:pl-4 mb-1">
                @yield('title')
            </h4>
            <p class="text-sm text-slate-500 dark:text-slate-300">
                {{ __('Configure verification levels and requirements') }}
            </p>
        </div>
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
                    <li class="card single-gateway border rounded dark:border-slate-700 p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-none">
                                    <div class="flex items-center justify-center w-8 h-8 rounded-[100%] bg-body font-medium dark:text-white dark:bg-body ltr:mr-3 rtl:ml-3">
                                        {{ $count }}
                                    </div>
                                </div>
                                <div class="flex-1 text-start">
                                    <h4 class="text-base font-semibold text-slate-600 whitespace-nowrap">
                                        {{ $kyc->name }}
                                    </h4>
                                </div>
                            </div>
                            <div class="gateway-right flex items-center gap-2">
                                @if( $kyc->status)
                                    <div class="badge badge-success capitalize">
                                        {{ __('Active') }}
                                    </div>
                                @else
                                    <div class="badge badge-danger capitalize">
                                        {{ __('Disabled') }}
                                    </div>
                                @endif
                                @can('kyc-levels-edit')
                                <a href="{{ route('admin.kyclevels.edit',$kyc->id) }}" class="toolTip onTop action-btn dark:text-slate-300">
                                    <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                </a>
                                @endcan
                            </div>
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-300 my-3">
                            {{ $kyc->description }}
                        </p>
                        <div class="flex items-center space-x-7 flex-wrap">
                            @foreach ($kyc->kyc_sub_levels as $subLevel)
                                @if($kyc->slug==\App\Enums\KycLevelSlug::LEVEL2)
                                    <div class="basicRadio">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" class="hidden" name="sub_level" value="secondary-500" @if($subLevel->status) checked @endif>
                                            <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all
                                            duration-150 h-[12px] w-[12px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                            <span class="text-secondary-500 text-sm leading-6 capitalize">{{ $subLevel->name }}</span>
                                        </label>
                                    </div>
                                @elseif($kyc->slug==\App\Enums\KycLevelSlug::LEVEL3)
                                    <div class="checkbox-area primary-checkbox">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="hidden" name="sub_level" @if($subLevel->status) checked @endif>
                                            <span class="h-[12px] w-[12px] border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                <img src="{{ asset('frontend/images/icon/ck-white.svg') }}" alt="" class="h-[10px] w-[10px] block m-auto opacity-0">
                                            </span>
                                            <span class="text-slate-500 dark:text-slate-400 text-sm leading-6">{{ $subLevel->name }}</span>
                                        </label>
                                    </div>
                                @endif
                            @endforeach
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
