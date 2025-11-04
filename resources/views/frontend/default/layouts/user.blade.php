<!DOCTYPE html>
<html lang="zxx" dir="ltr" class="light">
@include('frontend::include.__head')
<body class="font-inter dashcode-app" id="body_class">
@include('notify::components.notify')
<!--Full Layout-->
<main class="app-wrapper">

    <div class="sidebar-wrapper group">
        @include('frontend::include.__user_side_nav')
    </div>

    <div class="flex flex-col justify-between min-h-screen">
        <div>
            <!--Header-->
            @include('frontend::include.__user_header')
            <!--/Header-->

            <div class="content-wrapper transition-all duration-150 ltr:ml-[248px] rtl:mr-[248px]" id="content_wrapper">
                <div class="page-content">
                  <div class="transition-all duration-150 container-fluid" id="page_layout">
                        <div id="content_layout">
                            <div>
                                @if(setting('kyc_verification','permission'))
                                    {{-- Kyc Info--}}
                                    <div class="md:block hidden">
                                        @include('frontend::user.include.__kyc_info')
                                    </div>
                                    <div class="md:hidden block">
                                        @include('frontend::user.mobile_screen_include.kyc.__user_kyc_mobile')
                                    </div>
                                @endif
                                <!--Page Content-->
                                @yield('content')
                                <!--Page Content-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- Show in 575px in Mobile Screen -->
        <div class="mobile-screen-show md:hidden">
            <div class="bg-white bg-no-repeat custom-dropshadow footer-bg dark:bg-slate-700 flex justify-around items-center backdrop-filter backdrop-blur-[40px] fixed left-0 bottom-0 w-full z-[9999] bothrefm-0 py-[12px] px-4">
                @include('frontend::user.mobile_screen_include.__menu')
            </div>
        </div>
        <!-- Show in 575px in Mobile Screen End -->

    </div>
    <!-- Automatic Popup -->
    @if(Session::get('signup_bonus'))
        @include('frontend::user.include.__signup_bonus')
    @endif

    <!-- /Automatic Popup End -->
    @php
        $branchFormToPrompt = null;
        $promptBranchId = null;
        $existingSubmission = null;
        $user = auth()->user();
        if ($user) {
            $userBranchId = getUserBranchId($user->id, $user);
            $hasUserReferral = !empty($user->ref_id);
            $hasStaffReferral = \Illuminate\Support\Facades\DB::table('staff_user')->where('user_id', $user->id)->exists();
            if (empty($userBranchId) && !$hasUserReferral && !$hasStaffReferral && !empty($user->country)) {
                $code = strtoupper((string) getCountryCode($user->country));
                if (!empty($code)) {
                    $bc = \App\Models\BranchCountry::where('country_code', $code)->first();
                    if ($bc) {
                        $form = \App\Models\BranchForm::where('branch_id', $bc->branch_id)->where('status', 1)->first();
                        if ($form) {
                            $existingSubmission = \App\Models\BranchFormSubmission::where('user_id', $user->id)->where('branch_id', $bc->branch_id)->first();
                            if (!$existingSubmission) {
                                $branchFormToPrompt = $form;
                                $promptBranchId = $bc->branch_id;
                            }
                        }
                    }
                }
            }
        }
    @endphp

    @if(!empty($branchFormToPrompt))
        @include('frontend::user.include.__branch_form_modal')
    @endif
</main>
<!--/Full Layout-->

@include('frontend::include.__script')

@if(!empty($branchFormToPrompt))
    <script>
        $(document).ready(function() {
            $('#branchFormModal').modal({backdrop: 'static', keyboard: false});
            $('#branchFormModal').modal('show');
            if (typeof flatpickr !== 'undefined') {
                $('.flatpickr-branch-date').flatpickr({
                    dateFormat: 'Y-m-d',
                    allowInput: false,
                    clickOpens: true,
                    enableTime: false,
                });
            }
            if ($.fn.select2) {
                const $modal = $('#branchFormModal');
                $modal.find('.select2').select2({
                    dropdownParent: $('body'),
                    width: '100%'
                });
            }
        });
    </script>
@endif

</body>
</html>

