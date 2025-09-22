<div class="flex flex-col sm:flex-row justify-between gap-y-3 border-b border-gray-100 px-4 py-5 last:border-b-0 dark:border-gray-800" x-data="{ showTwoFaForm: false }">
    <p class="text-gray-800 text-theme-sm dark:text-white/90 sm:basis-[100px] flex-shrink-1 flex-grow-1">
        {{ __('Security type') }}
    </p>
    <div class="w-full sm:w-[418px]">
        <!-- Password display (shown by default) -->
        <div x-show="!showTwoFaForm">
            <p class="text-gray-800 text-theme-sm font-medium dark:text-white/90">
                {{ __('2FA Security') }}
            </p>
        </div>

        @if( null != $user->google2fa_secret)
            <div x-show="showTwoFaForm" x-transition>
                <div class="space-y-3">
                    <p class="text-base font-medium dark:text-white/90">
                        {{ __('2FA Security') }}
                    </p>
                    <p class="text-theme-sm dark:text-white/90">
                        {{ __('Two Factor Authentication (2FA) Strengthens Access Security By Requiring Two Methods (also Referred To As Factors) To Verify Your Identity. Two Factor Authentication Protects Against Phishing, Social Engineering And Password Brute Force Attacks And Secures Your Logins From Attackers Exploiting Weak Or Stolen Credentials.') }}
                    </p>
                    <p class="text-theme-sm dark:text-white/90">
                        {{ __('Scan the QR code with your Google Authenticator App') }}
                    </p>

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

                    <p class="text-theme-sm dark:text-white">
                        @if($user->two_fa)
                            {{ __('Enter Your Password') }}
                        @else
                            {{ __('Enter the PIN from Google Authenticator App') }}
                        @endif
                    </p>

                    <form action="{{ route('user.setting.action-2fa') }}" method="POST">
                        @csrf

                        <div class="input-area">
                            <x-frontend::forms.input type="password" name="one_time_password" />
                        </div>
                        <div class="flex flex-col gap-3 mt-6">
                            @if($user->two_fa)
                                <x-frontend::forms.button type="submit" value="disable" name="status" class="w-full" size="md">
                                    {{ __('Disable 2FA') }}
                                </x-frontend::forms.button>
                            @else
                                <x-frontend::forms.button type="submit" value="enable" name="status" class="w-full" size="md">
                                    {{ __('Enable 2FA') }}
                                </x-frontend::forms.button>
                            @endif
                            
                            <x-frontend::forms.button type="button" variant="secondary" size="md" @click="showTwoFaForm = false">
                                {{ __('Cancel') }}
                            </x-frontend::forms.button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
    <div class="text-right sm:basis-[100px] flex-shrink-1 flex-grow-1">
        @if( null != $user->google2fa_secret)
            <div x-show="!showTwoFaForm">
                <x-frontend::forms.button type="button" variant="secondary" size="md" class="w-full sm:w-auto" @click="showTwoFaForm = true">
                    {{ __('Change Setting') }}
                </x-frontend::forms.button>
            </div>
        @else
            <x-frontend::link-button href="{{ route('user.setting.2fa') }}" variant="secondary" size="md" class="w-full sm:w-auto">
                {{ __('Get Secret Key for Two-Factor Authentication') }}
            </x-frontend::link-button>
        @endif
    </div>
</div>
