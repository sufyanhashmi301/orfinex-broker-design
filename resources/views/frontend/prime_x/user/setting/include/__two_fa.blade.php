<div class="card shadow-base">
    <div class="card-body p-6">
        <p class="font-normal text-sm text-slate-500 mb-1">{{ __('Security') }}</p>
        <h4 class="card-title mb-3">{{ __('2FA Security') }}</h4>
        @if(! setting('fa_verification','permission'))
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <iconify-icon icon="lucide:info" class="text-yellow-600 dark:text-yellow-400 text-xl"></iconify-icon>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">{{ __('Two‑Factor Enforcement Is Currently Disabled by Admin') }}</h3>
                        <p class="mt-1 text-sm text-yellow-700 dark:text-yellow-300">{{ __('You can still enable 2FA to protect your account, but it may not be required at login or sensitive actions until the admin enables enforcement.') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-5">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <iconify-icon icon="lucide:shield-check" class="text-blue-600 dark:text-blue-400 text-xl"></iconify-icon>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">{{ __('What does enabling 2FA secure?') }}</h3>
                    <ul class="list-disc pl-5 mt-2 text-sm text-blue-700 dark:text-blue-300 space-y-1">
                        <li>{{ __('Account Login') }}</li>
                        <li>{{ __('Withdraw Money') }}</li>
                        <li>{{ __('Withdraw Account Creation') }}</li>
                    </ul>
                </div>
            </div>
        </div>
        @if( null != $user->google2fa_secret)

            <div class="progress-steps-form">
                <p class="dark:text-white">{{ __('Two Factor Authentication (2FA) Strengthens Access Security By Requiring Two Methods (also Referred To As Factors) To Verify Your Identity. Two Factor Authentication Protects Against Phishing, Social Engineering And Password Brute Force Attacks And Secures Your Logins From Attackers Exploiting Weak Or Stolen Credentials.') }}</p>
                <p class="dark:text-white">{{ __('Scan the QR code with your Google Authenticator App') }}</p>

                @php
                    $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());

                    $inlineUrl = $google2fa->getQRCodeInline(
                       setting('site_title','global'),
                        $user->email,
                        $user->google2fa_secret
                    );
                @endphp

                {{-- {!! app('pragmarx.google2fa')->getQRCodeInline(config('app.name'), $user->email, $user->google2fa_secret) !!}--}}

                {!! $inlineUrl !!}

                <p class="dark:text-white py-2">
                    @if($user->two_fa)
                        {{ __('Enter Your Password') }}
                    @else
                        {{ __('Enter the PIN from Google Authenticator App') }}
                    @endif
                </p>

                <form action="{{ route('user.setting.action-2fa') }}" method="POST">
                    @csrf

                    <div class="input-area">
                        <input type="password" name="one_time_password" class="form-control !text-lg">
                    </div>
                    <div class="buttons mt-4">
                        @if($user->two_fa)
                            <button type="submit" class="btn btn-dark inline-flex items-center" value="disable" name="status">{{ __('Disable 2FA') }}
                                <i class="anticon anticon-double-right"></i>
                            </button>
                        @else
                            <button type="submit" class="btn btn-dark block-btn inline-flex items-center" value="enable" name="status">
                                {{ __('Enable 2FA') }}
                                <i class="anticon anticon-double-right"></i>
                            </button>
                        @endif
                    </div>

                </form>

            </div>

        @else
            <a href="{{ route('user.setting.2fa') }}" class="btn btn-dark block-btn inline-flex items-center">
                {{ __('Obtaining a Secret Key for Two-Factor Authentication') }}
            </a>
        @endif
    </div>
</div>
