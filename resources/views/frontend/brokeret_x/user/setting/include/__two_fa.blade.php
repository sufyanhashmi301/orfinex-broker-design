<div class="card shadow-base">
    <div class="card-body p-6">
        <p class="font-normal text-sm text-slate-500 mb-1">{{ __('Security') }}</p>
        <h4 class="card-title mb-3">{{ __('2FA Security') }}</h4>
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
