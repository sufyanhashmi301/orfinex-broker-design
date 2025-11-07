<!DOCTYPE html>
<html lang="zxx" dir="ltr" class="{{ auth()->user()->user_theme == 'light' ? 'light' : 'dark' }}">
@include('frontend::include.__head')
<body class="font-inter dashcode-app" id="body_class">
    @include('notify::components.notify')
    <div id="page-loader" style="display: none;">
        <div class="dot bg-primary"></div>
        <div class="dot bg-primary"></div>
        <div class="dot bg-primary"></div>
    </div>
    <!--Full Layout-->
    <main class="app-wrapper">

        <div class="sidebar-wrapper group dark:shadow-slate-700">
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
                                    <!--Page Content-->
                                    @yield('content')
                                    <!--Page Content-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <footer class="md:block hidden sticky bottom-0" id="footer">
                <div class="site-footer px-6 text-slate-500 dark:text-slate-100 py-4 ltr:ml-[248px] rtl:mr-[248px]" style="height: 48px;">
                    <div class="grid md:grid-cols-2 grid-cols-1 md:gap-5">
                        <div class="text-center ltr:md:text-start rtl:md:text-right text-sm">
                            {{ setting('copyright_text', 'common_settings') }}
                        </div>
                        <div class="ltr:md:text-right rtl:md:text-end text-center text-sm">
                            <span class="toolTip onTop" style="line-height: 0" data-tippy-content="Your data is fully secure with advanced end-to-end encryption between you and your broker, ensuring that all sensitive client information and trading activities remain confidential. Technology Provider guarantees no access to or visibility of your encrypted data, safeguarding your privacy and trust.">
                                <span id="secure-data" style="display: inline-flex; width: 24px; height: 24px;"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </footer>

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
                // Initialize select2 inside modal so dropdown renders above overlay
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
