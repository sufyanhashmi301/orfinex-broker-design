<div class="card">
    <div class="card-body p-6">
        <h4 class="card-title mb-3">{{ __('2FA Security') }}</h4>
        <div class="progress-steps-form space-y-3">
            <p class="dark:text-white">{{ __('Two Factor Authentication (2FA) Strengthens Access Security By Requiring Two Methods (also Referred To As Factors) To Verify Your Identity. Two Factor Authentication Protects Against Phishing, Social Engineering And Password Brute Force Attacks And Secures Your Logins From Attackers Exploiting Weak Or Stolen Credentials.') }}</p>
            <p class="dark:text-white">{{ __('Scan the QR code with you Google Authenticator App') }}</p>

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

            <p class="form-label !mb-0">
                @if($user->two_fa)
                    {{ __('Enter Your Password') }}
                @else
                    {{ __('Enter the PIN from Google Authenticator App') }}
                @endif
            </p>

            <form action="{{ route('admin.staff.action-2fa') }}" method="POST">
                @csrf
                <div id="otp-wrapper" class="flex gap-2">
                    @for ($i = 0; $i < 6; $i++)
                        <input
                            type="text"
                            maxlength="1"
                            class="w-16 h-16 text-center text-xl font-semibold rounded-md border border-gray-300 focus:ring focus:ring-indigo-500 focus:border-indigo-500 otp-input"
                            required
                        />
                    @endfor
                </div>

                <input type="hidden" name="one_time_password" id="one_time_password">
                    
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
