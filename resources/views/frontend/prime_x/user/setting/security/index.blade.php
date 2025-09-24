@extends('frontend::user.setting.index')
@section('title')
    {{ __('Security Settings') }}
@endsection
@section('settings-content')

    <div class="card">
        <div class="card-body p-6">
            <div class="mb-4">
                <h4 class="card-title mb-2">{{ __('Security Settings') }}</h4>
                <p class="block font-normal text-sm text-slate-500 dark:text-slate-200">
                    {{ __("Strengthen Your Online Security: It's your primary defense.") }}
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="card border dark:border-slate-700 h-full">
                    <div class="card-body h-full flex flex-col items-start p-6 gap-3">
                        <div>
                            <p class="font-normal text-sm text-slate-500 dark:text-slate-200 mb-1">{{ __('Security') }}</p>
                            <h4 class="card-title">{{ __('Authorization') }}</h4>
                        </div>
                        <div>
                            <p class="dark:text-white">
                                {{ __('Information for logging in to :site.', ['site' => setting('site_title', 'global')]) }}
                            </p>
                            <p class="dark:text-white">
                                {{ __('Change your password whenever you think it might have been compromised.') }}
                            </p>
                        </div>
                        <div class="input-area w-full">
                            <div class="relative">
                                <input type="text" class="form-control form-control-lg !pr-32" value="{{ $user->email }}" disabled>
                                <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                    <a href="javascript:;" type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#emailEditModal" class="text-sm text-success">
                                        {{ __('Change') }}
                                    </a>
                                </span>
                            </div>
                        </div>
                        <div class="input-area w-full">
                            <div class="relative">
                                <input type="password" class="form-control form-control-lg !pr-32" value="12345678" disabled>
                                <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                    <a href="javascript:;" type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#changePasswordModal" class="text-sm text-success">
                                        {{ __('Change') }}
                                    </a>
                                </span>
                            </div>
                        </div>
                        <div class="mt-auto w-full">
                            <a href="" class="btn btn-primary block-btn mt-5">{{ __('Update') }}</a>
                        </div>
                    </div>
                </div>

                <!-- Two Factor Verification -->
                @include('frontend::user.setting.include.__two_fa')

            </div>
        </div>
    </div>
    
    <!-- Modal for Edit Email -->
    @include('frontend::user.setting.security.modal.__edit_email')

    <!-- Modal for Change Password -->
    @include('frontend::user.setting.security.modal.__change_password')

@endsection
@section('script')
    <script>
        $(document).ready(function() {
            let $inputs = $(".otp-input");

            // move to next on input
            $inputs.on("input", function() {
                let $this = $(this);
                let value = $this.val();

                if (value.length === 1) {
                    $this.next(".otp-input").focus();
                }

                updateHiddenInput();
            });

            // move to prev on backspace
            $inputs.on("keydown", function(e) {
                if (e.key === "Backspace" && !$(this).val()) {
                    $(this).prev(".otp-input").focus();
                }
            });

            // allow paste of full OTP
            $inputs.first().on("paste", function(e) {
                let paste = (e.originalEvent || e).clipboardData.getData('text').trim();
                if (/^\d+$/.test(paste) && paste.length === $inputs.length) {
                    $inputs.each(function(i) {
                        $(this).val(paste[i]);
                    });
                    updateHiddenInput();
                }
            });

            function updateHiddenInput() {
                let otp = "";
                $inputs.each(function() {
                    otp += $(this).val();
                });
                $("#one_time_password").val(otp);
            }
        });
    </script>
@endsection