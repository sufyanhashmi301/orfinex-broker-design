<div class="card">
    <div class="card-body p-6">
        <h4 class="card-title mb-3">{{ __('2FA Security') }}</h4>
        <div class="progress-steps-form space-y-3">
            <p class="dark:text-white">{{ __('Two Factor Authentication (2FA) Strengthens Access Security By Requiring Two Methods (also Referred To As Factors) To Verify Your Identity. Two Factor Authentication Protects Against Phishing, Social Engineering And Password Brute Force Attacks And Secures Your Logins From Attackers Exploiting Weak Or Stolen Credentials.') }}</p>
            <p class="dark:text-white">{{ __('Scane the QR code with you Google Authenticator App') }}</p>

            @php
                $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());

                $inlineUrl = $google2fa->getQRCodeInline(
                   setting('site_title','global'),
                    $user->email,
                    $user->google2fa_secret
                );
            @endphp

            {!! app('pragmarx.google2fa')->getQRCodeInline(config('app.name'), $user->email, $user->google2fa_secret) !!}

            {{--                <img src="{{ $inlineUrl }}">--}}

            <p class="dark:text-white py-2">
                @if($user->two_fa)
                    {{ __('Enter Your Password') }}
                @else
                    {{ __('Enter the PIN from Google Authenticator App') }}
                @endif
            </p>

            <form action="{{ route('admin.staff.action-2fa') }}" method="POST">
                @csrf

                <div class="input-area">
                    <input type="password" name="one_time_password" class="form-control !text-lg">
                </div>
                <div class="buttons mt-10">
                    @if($user->two_fa)
                        <button type="submit" class="btn btn-dark" value="disable" name="status">{{ __('Disable 2FA') }}
                            <i class="anticon anticon-double-right"></i>
                        </button>
                    @else
                        <button type="submit" class="btn btn-dark" value="enable" name="status">
                            {{ __('Enable 2FA') }}
                            <i class="anticon anticon-double-right"></i>
                        </button>
                    @endif
                </div>

            </form>

        </div>

{{--        @if( null != $user->google2fa_secret)--}}
{{--            --}}

{{--        @else--}}
{{--            <a href="{{ route('admin.staff.2fa') }}"--}}
{{--               class="btn btn-dark">{{ __('Obtaining a Secret Key for Two-Factor Authentication') }}</a>--}}
{{--        @endif--}}
    </div>
</div>
