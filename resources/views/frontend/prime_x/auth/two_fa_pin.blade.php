@extends('frontend::layouts.auth')
@section('title')
    {{ __('2FA Security') }}
@endsection
@section('content')
    <div class="shadow-xl rounded-xl border p-8">
        <div class="text-center 2xl:mb-10 mb-5">
            <h4 class="font-medium">👋 {{ __('Welcome Back!') }}</h4>
            <div class="text-slate-500 dark:text-slate-400 text-base">
                {{ __('Sign in to continue with') }} {{ setting('site_title','global') }} {{ __('User Panel') }}
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                @foreach($errors->all() as $error)
                    <strong>{{ __('You entered') }} {{ $error }}</strong>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Close') }}"></button>
            </div>
        @endif
        <div class="site-auth-form">
            <form method="POST" action="{{ route('user.setting.2fa.verify') }}">
                @csrf

                <div class="single-field mb-4">
                    <p class="text-sm mb-3">{{ __('Please enter the') }}
                        <strong>{{ __('OTP') }}</strong> {{ __('generated on your Authenticator App.') }}
                        <br> {{ __('Ensure you submit the current one because it refreshes every 30 seconds.') }}
                    </p>

                    <label class="form-label" for="password">{{ __('One Time Password') }}</label>
                    <div id="otp-wrapper" class="flex gap-2">
                        @for ($i = 0; $i < 6; $i++)
                            <input
                                type="text"
                                maxlength="1"
                                class="form-control w-12 h-12 text-center text-lg font-semibold rounded-md border border-gray-300 focus:ring focus:ring-indigo-500 focus:border-indigo-500 otp-input"
                                required
                            />
                        @endfor
                    </div>

                    <input type="hidden" name="one_time_password" id="one_time_password">
                </div>
                <button type="submit" class="btn btn-dark block w-full text-center">
                    {{ __('Authenticate Now') }}
                </button>
            </form>
        </div>
    </div>
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