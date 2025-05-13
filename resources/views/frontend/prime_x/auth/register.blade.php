@extends('frontend::layouts.auth')
@section('title')
    {{ __('Sign Up') }}
@endsection
@section('description')
    {{ __('Register to Access Funded Trading Accounts and Professional Tools.') }}
@endsection
@section('content')
    <div class="">
        @if ($errors->any())
            <div class="alert alert-warning alert-dismissible fade show mt-2 text-sm" role="alert">
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <p class="flex-1 font-Inter">
                        @foreach($errors->all() as $error)
                            {{$error}}
                        @endforeach
                    </p>
                    <div class="flex-0 text-lg cursor-pointer">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <iconify-icon icon="line-md:close"></iconify-icon>
                        </button>
                    </div>
                </div>
            </div>
        @endif
        <!-- BEGIN: Login Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="level" value="{{ request('level') ?? old('level') }}" >
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="fromGroup">
                    <label class="block capitalize form-label">
                        {{ __('First Name*') }}
                    </label>
                    <div class="relative ">
                        <input type="text" class="form-control py-2 h-[48px]" placeholder="Your First Name" name="first_name" value="{{ old('first_name') }}" required>
                    </div>
                </div>
                <div class="fromGroup">
                    <label class="block capitalize form-label">{{ __('Last Name*') }}</label>
                    <div class="relative ">
                        <input type="text" class="form-control py-2 h-[48px]" placeholder="Your Last Name" name="last_name" value="{{ old('last_name') }}" required>
                    </div>
                </div>
            </div>
            <div class="fromGroup">
                <label class="block capitalize form-label">{{ __('Email Address*') }}</label>
                <div class="relative ">
                    <input type="email" class="form-control py-2 h-[48px]" name="email" value="{{ old('email') }}" placeholder="Enter Your Email Address" required>
                </div>
            </div>
            @if(getPageSetting('username_show'))
                <div class="fromGroup">
                    <label class="block capitalize form-label">{{ __('User Name*') }}</label>
                    <div class="relative ">
                        <input class="form-control py-2 h-[48px]" type="text" placeholder="Enter Your User Name" name="username" value="{{ old('username') }}" required/>
                    </div>
                </div>
            @endif

            @if(getPageSetting('country_show'))
                <div class="formGroup">
                    <label class="block capitalize form-label">{{ __('Select Country*') }}</label>
                    <div class="relative ">
                        <select name="country" id="countrySelect" class="form-control py-2 h-[48px] w-full mt-2">
                            @foreach( getCountries() as $country)
                                <option @if( $location->country_code == $country['code']) selected
                                        @endif value="{{ $country['name'].':'.$country['dial_code'] }}"
                                        class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                    {{ $country['name']  }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if(getPageSetting('phone_show'))
                <div class="formGroup phone-input-wrapper">
                    <label class="block capitalize form-label">{{ __('Phone Number*') }}</label>
                    <div class="relative">
                        <input type="text" class="form-control py-2 h-[48px]" placeholder="Phone Number" name="phone" id="phone" value="{{ old('phone') }}" aria-label="Username" aria-describedby="basic-addon1" value="{{ getLocation()->dial_code }}">
                    </div>
                </div>
            @endif

            @if(getPageSetting('referral_code_show'))
                <div class="formGroup">
                    <div class="flex items-center justify-between">
                        <label class="block capitalize form-label">{{ __('Referral Code') }}</label>
                        <a href="javascript:;" class="btn-link referralToggle">{{ __('Show') }}</a>
                    </div>
                    <div class="relative hidden" id="referral-input">
                        <input class="form-control py-2 h-[48px]" type="text" placeholder="Enter Your Referral Code" name="referral" {{ request('referral') !== null ? 'readonly' : '' }} value="{{ request('referral') ?? old('referral') }}"/>
                    </div>
                </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="formGroup">
                    <label class="block capitalize form-label">{{ __('Password*') }}</label
                    >
                    <div class="relative">
                        <input class="form-control py-2 h-[48px]" type="password" name="password" id="password" placeholder="Enter your password" required/>
                        <button type="button" class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center toggle-password dark:text-slate-200" toggle="#password">
                            <iconify-icon icon="heroicons:eye-slash"></iconify-icon>
                        </button>
                    </div>
                </div>
                <div class="formGroup">
                    <label class="block capitalize form-label" for="password">{{ __('Confirm Password*') }}</label>
                    <div class="relative">
                        <input class="form-control py-2 h-[48px]" type="password" name="password_confirmation" id="confirm-pass" placeholder="Enter your password" required/>
                        <button type="button" class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center toggle-password dark:text-slate-200" toggle="#confirm-pass">
                            <iconify-icon icon="heroicons:eye-slash"></iconify-icon>
                        </button>
                    </div>
                </div>
            </div>
            <div class="formGroup">
                {{-- @if($googleReCaptcha)
                    <div class="g-recaptcha" id="feedback-recaptcha" data-sitekey="{{ json_decode($googleReCaptcha->data,true)['google_recaptcha_key'] }}"></div>
                @endif --}}

                @if(config('services.turnstile.sitekey'))
                    <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.sitekey') }}"></div>
                @endif
            </div>
            <div class="flex justify-between">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="i_agree" class="hiddens mr-2" value="yes" required>
                    <span class="text-slate-500 dark:text-slate-400 text-xs leading-6 capitalize">
                        {{ __('I agree with') }}

                        @foreach ($legal_links as $link)

                            @php
                                $is_enabled = false;
                                $settings_name = '';
                                if(str_contains($link->name, '_signup') ) {
                                    if(setting($link->name) == 1) {
                                        $is_enabled = true;
                                        $settings_name = str_replace('_on_signup', '', $link->name);
                                        $label = str_replace( '_', ' ', str_replace( 'legal_', '', $settings_name) );
                                    }
                                    // dd($settings_name);
                                }
                            @endphp
                            @if ($is_enabled)
                                <a href="{{ setting($settings_name . '_link', 'global') }}" class="btn-link" target="_blank">{{ $label }}</a>
                                @if ($loop->remaining == 2)
                                    and
                                @elseif ($loop->remaining > 2)
                                    ,
                                @endif
                            @endif
                            
                        @endforeach

                        
                        {{-- <a href="{{ setting('client_agreement_link', 'global') }}" class="btn-link" target="_blank">{{ __('Client Agreement') }}</a> --}}
                    </span>
                </label>
            </div>
            <button type="submit" class="btn btn-primary block w-full text-center">
                {{ __('Create Account') }}
            </button>
        </form>
        <!-- END: Login Form -->
        <div class="relative border-b-[#9AA2AF] border-opacity-[16%] border-b pt-6">
            <div class="absolute inline-block bg-white dark:bg-slate-800 left-1/2 top-1/2 transform -translate-x-1/2 px-4 min-w-max text-sm text-slate-500 dark:text-slate-400font-normal">
                {{ __('Or') }}
            </div>
        </div>
        <div class="md:max-w-[345px] mt-6 mx-auto font-normal text-slate-500 text-center dark:text-slate-400mt-12 uppercase text-sm">
            {{ __('Already Registered?') }}
            <a href="{{ route('login') }}" class="text-slate-900 dark:text-white font-medium hover:underline">
                {{ __('Sign In') }}
            </a>
        </div>

        @include('frontend::auth.join_us')
    </div>
@endsection
@section('script')
    {{-- @if($googleReCaptcha)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif --}}
    @if(config('services.turnstile.sitekey'))
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif
    <script src="{{ asset('frontend/js/intlTelInput.min.js') }}"></script>
    <script>
        $('#countrySelect').on('change', function (e) {
            "use strict";
            e.preventDefault();
            var country = $(this).val();
            $('#dial-code').html(country.split(":")[1])
        });

        $(document).ready(function() {
            $('.referralToggle').on('click', function (){
                $('#referral-input').toggleClass('hidden');

                if ($('#referral-input').hasClass('hidden')) {
                    $(this).text('Show');
                } else {
                    $(this).text('Hide');
                }
            })
        });

    </script>
@endsection

