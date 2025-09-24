<div class="card border dark:border-slate-700 h-full">
    <div class="card-body h-full flex flex-col items-start p-6 gap-3">
        <div>
            <p class="font-normal text-sm text-slate-500 dark:text-slate-200">{{ __('Security') }}</p>
            <h4 class="card-title">{{ __('2-Step verification') }}</h4>
        </div>
        <div>
            @if(! setting('fa_verification','permission'))
                <p class="dark:text-white">
                    {{ __('Two‑Factor Enforcement Is Currently Disabled by Admin') }}
                </p>
                <p class="dark:text-white">
                    {{ __('You can still enable 2FA to protect your account, but it may not be required at login or sensitive actions until the admin enables enforcement.') }}
                </p>
            @endif

            @if( null != $user->google2fa_secret)
                <p class="dark:text-white">
                    {{ __('Two-Factor Authentication (2FA) enhances security by requiring two forms of verification, protecting your account against phishing, social engineering, and stolen passwords.') }}
                </p>
                <p class="dark:text-white">
                    {{ __('Scan the QR code with your Google Authenticator App')}}
                </p>
            @else
                <p class="dark:text-white">
                    {{ __('Two-Factor Authentication (2FA) is not enabled.') }}
                </p>
                <p class="dark:text-white">
                    {{ __('Enable 2FA to significantly strengthen your account security and safeguard against unauthorized access.') }}
                </p>
            @endif
        </div>
        @if( null != $user->google2fa_secret)
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

            <p class="form-label !mb-0">
                @if($user->two_fa)
                    {{ __('Enter Your Password') }}
                @else
                    {{ __('Enter the PIN from Google Authenticator App') }}
                @endif
            </p>

            <form action="{{ route('user.setting.action-2fa') }}" method="POST" class="w-full">
                @csrf

                <div class="input-area">
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
                </div>
                <div class="buttons mt-4">
                    @if($user->two_fa)
                        <button type="submit" class="btn btn-dark block-btn" value="disable" name="status">
                            <div class="flex items-center justify-center gap-2">
                                {{ __('Disable 2FA') }}
                                <i class="anticon anticon-double-right"></i>
                            </div>
                        </button>
                    @else
                        <button type="submit" class="btn btn-dark block-btn" value="enable" name="status">
                            <div class="flex items-center justify-center gap-2">
                                {{ __('Enable 2FA') }}
                                <i class="anticon anticon-double-right"></i>
                            </div>
                        </button>
                    @endif
                </div>
            </form>
        @else
            <div class="mt-auto w-full">
                <a href="{{ route('user.setting.2fa') }}" class="btn btn-dark block-btn">
                    {{ __('Obtaining a Secret Key for Two-Factor Authentication') }}
                </a>
            </div>
        @endif
    </div>
</div>
