@extends('frontend::layouts.auth')

@section('title')
    {{ __('Register') }}
@endsection
@section('content')
    @php
        // Manage the invite cookie
        $invite = request()->query('invite'); // Get the invite from the query string

        if ($invite) {
            // Store the invite code in the cookie for 60 minutes
            \Cookie::queue('invite', $invite, 60);
        } elseif (\Cookie::has('invite')) {
            // Remove the cookie if the invite is not present in the URL
            \Cookie::queue(\Cookie::forget('invite'));
        }

        // Retrieve the invite from the cookie for use in the form
        $inviteCode = $invite ?? \Cookie::get('invite');
    @endphp

    <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">
        {{ __('Create Your Account') }}
    </h1>

    <!-- auth tabs -->
    <div class="my-5 sm:my-8">
        @include('frontend::auth.include.__tabs')
    </div>

    <div>
        <!-- BEGIN: Login Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="schema" value="{{ request('schema') ?? old('schema') }}" >
            <div class="grid grid-cols-1 gap-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <x-frontend::forms.field
                        :fieldId="'first_name'"
                        :fieldLabel="'First Name'"
                        :fieldPlaceholder="'Your first name'"
                        :fieldRequired="true"
                        :type="'text'"
                        :fieldName="'first_name'"
                        :fieldValue="old('first_name')"
                    />
                    <x-frontend::forms.field
                        :fieldId="'last_name'"
                        :fieldLabel="'Last Name'"
                        :fieldPlaceholder="'Your last name'"
                        :fieldRequired="true"
                        :type="'text'"
                        :fieldName="'last_name'"
                        :fieldValue="old('last_name')"
                    />
                </div>
                <x-frontend::forms.field
                    :fieldId="'email'"
                    :fieldLabel="'Email Address'"
                    :fieldPlaceholder="'Your email address'"
                    :fieldRequired="true"
                    :type="'email'"
                    :fieldName="'email'"
                    :fieldValue="old('email')"
                />
                @if(getPageSetting('username_show'))
                    <x-frontend::forms.field
                        :fieldId="'username'"
                        :fieldLabel="'User Name'"
                        :fieldPlaceholder="'Your user name'"
                        :fieldRequired="true"
                        :type="'text'"
                        :fieldName="'username'"
                        :fieldValue="old('username')"
                    />
                @endif

                @if(getPageSetting('country_show'))
                    <div>
                        <x-frontend::forms.label
                            :fieldId="'country'"
                            :fieldLabel="'Select Country'"
                            :fieldRequired="true"
                        />
                        <x-frontend::forms.search-select
                            :fieldId="'country'"
                            :fieldName="'country'"
                            :fieldValue="old('country')"
                            :fieldPlaceholder="'Select your country'"
                            :fieldRequired="true"
                            :searchable="true"
                            :options="collect(getCountries())->pluck('name', 'code')->toArray()"
                    />
                    </div>
                @endif

                @if(getPageSetting('phone_show'))
                    <x-frontend::forms.field
                        :fieldId="'phone'"
                        :fieldLabel="'Phone Number'"
                        :fieldPlaceholder="'Your phone number'"
                        :fieldRequired="true"
                        :type="'text'"
                        :fieldName="'phone'"
                        :fieldValue="old('phone')"
                    />
                @endif

                @if(getPageSetting('referral_code_show'))
                    <div x-data="{ showReferral: {{ ($inviteCode || old('invite')) ? 'true' : 'false' }} }">
                        <div class="flex items-center justify-between mb-1.5">
                            <x-frontend::forms.label
                                fieldId="referral_code"
                                fieldLabel="{{ __('Referral Code') }}"
                                class="!mb-0"
                            />
                            <x-text-link
                                href="javascript:void(0)"
                                @click="showReferral = !showReferral"
                                variant="text"
                            >
                                <span x-text="showReferral ? '{{ __('Hide') }}' : '{{ __('Show') }}'"></span>
                            </x-text-link>
                        </div>
                        <div x-show="showReferral" 
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform translate-y-0"
                            x-transition:leave-end="opacity-0 transform -translate-y-2">
                            <x-frontend::forms.input
                                fieldId="referral_code"
                                fieldName="invite"
                                fieldPlaceholder="{{ __('Enter your referral code') }}"
                                fieldValue="{{ old('invite') ?? $inviteCode }}"
                                type="text"
                            />
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <x-frontend::forms.password-field
                        :fieldId="'password'"
                        :fieldLabel="'Password'"
                        :fieldName="'password'"
                        :fieldValue="old('password')"
                        :fieldPlaceholder="'Enter your password'"
                        :fieldRequired="true"
                        :type="'password'"
                    />

                    <x-frontend::forms.password-field
                        :fieldId="'password_confirmation'"
                        :fieldLabel="'Confirm Password'"
                        :fieldName="'password_confirmation'"
                        :fieldValue="old('password_confirmation')"
                        :fieldPlaceholder="'Confirm your password'"
                        :fieldRequired="true"
                        :type="'password'"
                    />
                </div>
                @if($cloudflareTurnstile)
                    <div>
                        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" defer></script>
                        <div class="cf-turnstile" data-sitekey="{{ $siteKey }}" data-theme="light"></div>
                    </div>
                @else
                    <div>
                        @if($googleReCaptcha)
                            <div class="g-recaptcha mb-3" id="feedback-recaptcha"
                                data-sitekey="{{ json_decode($googleReCaptcha->data,true)['google_recaptcha_key'] }}">
                            </div>
                        @endif
                    </div>
                @endif
                
                @php
                    $privacyPolicyShow = getPageSetting('privacy_policy_show');
                    $clientAgreementShow = getPageSetting('client_agreement_show');
                    $privacyPolicyLink = document_link_by_slug('privacy_policy');
                    $clientAgreementLink = document_link_by_slug('client_agreement');
                    $privacyPolicyTitle = trim(getPageSetting('privacy_policy_title')) ?: __('Privacy & Policy');
                    $clientAgreementTitle = trim(getPageSetting('client_agreement_title')) ?: __('Client Agreement');
                @endphp
                
                <x-frontend::forms.checkbox
                    fieldId="checkboxLabelOne"
                    fieldName="i_agree"
                    :fieldRequired="true"
                >
                    <span class="inline-block font-normal text-gray-500 dark:text-gray-400">
                        {{ __('I agree with') }}

                        @if($privacyPolicyShow)
                            <x-text-link
                                href="{{ $privacyPolicyLink ? $privacyPolicyLink->link : '#' }}"
                                variant="text"
                                target="_blank"
                            >
                                {{ $privacyPolicyTitle }}
                            </x-text-link>
                        @endif

                        @if($privacyPolicyShow && $clientAgreementShow)
                            {{ __(' and ') }}
                        @endif

                        @if($clientAgreementShow)
                            <x-text-link
                                href="{{ $clientAgreementLink ? $clientAgreementLink->link : '#' }}"
                                variant="text"
                                target="_blank"
                            >
                                {{ $clientAgreementTitle }}
                            </x-text-link>
                        @endif
                    </span>
                </x-forms.checkbox>
                
            </div>  

            <x-frontend::forms.button type="submit" class="w-full" size="md" variant="primary">
                {{ __('Create Account') }}
            </x-forms.button>
        </form>
        <!-- END: Login Form -->

        @php
            $socialLogins = App\Models\Social::activePlatforms();
        @endphp
        @if($socialLogins->isNotEmpty())
            <div class="relative py-3 sm:py-5">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200 dark:border-gray-800"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="p-2 text-gray-400 bg-white dark:bg-gray-900 sm:px-5 sm:py-2">{{ __('Or continue with') }}</span>
                </div>
            </div>
            <div class="flex flex-wrap justify-center gap-3 sm:gap-5">
                @foreach ($socialLogins as $socialLogin)
                    <a href="{{ route('social.redirect', $socialLogin->driver) }}" class="inline-flex h-10 w-10 flex-col items-center justify-center">
                        <img src="https://cdn.brokeret.com/crm-assets/admin/social/{{ strtolower($socialLogin->title) }}.webp" class="w-full" alt="{{ ucfirst($socialLogin->title) }}">
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
@section('script')
    @if($googleReCaptcha)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    @if(getPageSetting('phone_show'))
        <script src="{{ asset('frontend/js/intlTelInput.min.js') }}"></script>
        <script>
            const input = document.querySelector("#phone");
            window.intlTelInput(input, {
                initialCountry: "auto",
                showSelectedDialCode: true,
                utilsScript: "{{ asset('frontend/js/utils.js') }}",
            });
        </script>
    @endif

    <script>

        $('#countrySelect').on('change', function (e) {
            "use strict";
            e.preventDefault();
            var country = $(this).val();
            $('#dial-code').html(country.split(":")[1])
        });

    </script>
@endsection

