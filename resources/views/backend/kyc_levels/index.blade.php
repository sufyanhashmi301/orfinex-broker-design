@extends('backend.setting.kyc_levels.index')
@section('title')
    {{ __('KYC & Compliance') }}
@endsection
@section('kyc-levels-content')
    <!-- Info Banner instead of duplicate title -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mb-6">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <iconify-icon icon="lucide:info" class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5"></iconify-icon>
            </div>
            <div>
                <h5 class="text-sm font-medium text-blue-900 dark:text-blue-200 mb-1">
                    {{ __('Verification Management') }}
                </h5>
                <p class="text-sm text-blue-700 dark:text-blue-300">
                    {{ __('Configure and manage verification levels, requirements, and compliance settings for user onboarding.') }}
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <!-- Animation Card -->
        <div class="lg:col-span-4 col-span-12">
            <div class="card enhanced-card h-full">
                <div class="card-body flex flex-col items-center justify-center p-8">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-white text-center mb-2">
                            {{ __('Verification Center') }}
                        </h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 text-center">
                            {{ __('Secure identity verification') }}
                        </p>
                    </div>
                    <div id="lottie-container" class="inline-flex bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl p-4" style="width: 280px; height: 280px;"></div>
                    <div class="mt-4 text-center">
                        <div class="flex items-center justify-center space-x-4 text-xs text-slate-500 dark:text-slate-400">
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                {{ __('Secure') }}
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                {{ __('Fast') }}
                            </div>
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-purple-500 rounded-full mr-2"></span>
                                {{ __('Compliant') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KYC Levels List -->
        <div class="lg:col-span-8 col-span-12">
            <div class="space-y-4">
                @php $count = 1; @endphp
                @foreach($kycLevels as $kyc)
                    <div class="card enhanced-card border-l-4 {{ $kyc->status ? 'border-l-green-500' : 'border-l-slate-300' }} hover:shadow-lg transition-all duration-300">
                        <div class="card-body p-6">
                            <!-- Header Section -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center justify-center w-12 h-12 rounded-full {{ $kyc->status ? 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }} font-semibold text-lg">
                                        {{ $count }}
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-semibold text-slate-800 dark:text-white">
                                            {{ $kyc->name }}
                                        </h4>
                                        <div class="flex items-center mt-1">
                                            @if( $kyc->status)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                    <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full"></span>
                                                    {{ __('Active') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                    <span class="w-1.5 h-1.5 mr-1.5 bg-red-500 rounded-full"></span>
                                                    {{ __('Disabled') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    @can('kyc-levels-edit')
                                    <a href="{{ route('admin.kyclevels.edit',$kyc->id) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 transition-colors duration-200 toolTip onTop" data-tippy-content="Edit Level">
                                        <iconify-icon icon="lucide:edit-3" class="w-4 h-4"></iconify-icon>
                                    </a>
                                    @endcan
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                                    {{ $kyc->description }}
                                </p>
                            </div>

                            <!-- Sub Levels -->
                            @if($kyc->kyc_sub_levels->count() > 0 || $count == 1)
                                <div class="border-t border-slate-200 dark:border-slate-700 pt-4">
                                    <h5 class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-3 flex items-center">
                                        <iconify-icon icon="lucide:list-checks" class="w-4 h-4 mr-2"></iconify-icon>
                                        {{ __('Requirements') }}
                                    </h5>
                                    <div class="grid md:grid-cols-2 gap-3">
                                        @if($count == 1 && $kyc->kyc_sub_levels->count() == 0)
                                            <!-- Default requirements for Level 1 if no sub-levels exist -->
                                            <div class="flex items-center p-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-green-50 dark:bg-green-900/20">
                                                <iconify-icon icon="lucide:mail" class="w-4 h-4 text-green-600 dark:text-green-400 mr-3"></iconify-icon>
                                                <span class="text-slate-700 dark:text-slate-300 text-sm font-medium">{{ __('Email Verification') }}</span>
                                            </div>
                                            <div class="flex items-center p-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-green-50 dark:bg-green-900/20">
                                                <iconify-icon icon="lucide:phone" class="w-4 h-4 text-green-600 dark:text-green-400 mr-3"></iconify-icon>
                                                <span class="text-slate-700 dark:text-slate-300 text-sm font-medium">{{ __('Phone Verification') }}</span>
                                            </div>
                                        @else
                                            @foreach ($kyc->kyc_sub_levels as $subLevel)
                                                @if($kyc->slug==\App\Enums\KycLevelSlug::LEVEL2)
                                                    <div class="basicRadio">
                                                        <label class="flex items-center cursor-pointer p-3 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors duration-200">
                                                            <input type="radio" class="hidden" name="sub_level" value="secondary-500" @if($subLevel->status) checked @endif>
                                                            <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600"></span>
                                                            <span class="text-slate-700 dark:text-slate-300 text-sm font-medium capitalize">{{ $subLevel->name }}</span>
                                                        </label>
                                                    </div>
                                                @elseif($kyc->slug==\App\Enums\KycLevelSlug::LEVEL3)
                                                    <div class="checkbox-area primary-checkbox">
                                                        <label class="inline-flex items-center cursor-pointer p-3 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors duration-200">
                                                            <input type="checkbox" class="hidden" name="sub_level" @if($subLevel->status) checked @endif>
                                                            <span class="h-[16px] w-[16px] border flex-none border-slate-300 dark:border-slate-600 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-white dark:bg-slate-700">
                                                                <img src="{{ asset('frontend/images/icon/ck-white.svg') }}" alt="" class="h-[12px] w-[12px] block m-auto opacity-0">
                                                            </span>
                                                            <span class="text-slate-700 dark:text-slate-300 text-sm font-medium">{{ $subLevel->name }}</span>
                                                        </label>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @php $count++; @endphp
                @endforeach
            </div>
        </div>
    </div>

    <!-- Enhanced Delete Modal -->
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
        id="deleteKyc"
        tabindex="-1"
        aria-labelledby="deleteKyc"
        aria-hidden="true"
    >
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-2xl relative flex flex-col w-full pointer-events-auto bg-white dark:bg-slate-800 bg-clip-padding rounded-xl outline-none text-current">
                <div class="relative rounded-xl shadow-xl">
                    <div class="modal-body popup-body p-8 text-center">
                        <div class="space-y-4">
                            <div class="info-icon h-20 w-20 rounded-full inline-flex items-center justify-center bg-red-50 text-red-500 dark:bg-red-900/30 dark:text-red-400 mb-2">
                                <iconify-icon class="text-5xl" icon="lucide:alert-triangle"></iconify-icon>
                            </div>
                            <div class="title">
                                <h4 class="text-2xl font-bold dark:text-white text-slate-800 mb-2">
                                    {{ __('Are you sure?') }}
                                </h4>
                                <p class="text-slate-600 dark:text-slate-300 text-base">
                                    {{ __('You want to Delete') }}
                                    <strong class="name text-red-600 dark:text-red-400"></strong>
                                    {{ __('KYC Verification Type?') }}
                                </p>
                            </div>
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                                <p class="text-sm text-red-700 dark:text-red-300">
                                    <iconify-icon icon="lucide:info" class="w-4 h-4 inline mr-1"></iconify-icon>
                                    {{ __('This action cannot be undone') }}
                                </p>
                            </div>
                        </div>
                        <form method="post" id="kycEditForm">
                            @method('DELETE')
                            @csrf
                            <div class="action-btns flex items-center justify-center space-x-3 mt-8">
                                <button type="submit" class="btn bg-red-500 hover:bg-red-600 text-white inline-flex items-center justify-center px-6 py-3 rounded-lg font-medium transition-colors duration-200">
                                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:trash-2"></iconify-icon>
                                    {{ __('Delete') }}
                                </button>
                                <button type="button" class="btn bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 dark:text-slate-300 inline-flex items-center justify-center px-6 py-3 rounded-lg font-medium transition-colors duration-200" data-bs-dismiss="modal" aria-label="Close">
                                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                    {{ __('Cancel') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
            container: document.getElementById('lottie-container'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '{{ asset('global/json/kyc.json') }}'
        });
    </script>
@endsection