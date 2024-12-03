@extends('frontend::layouts.auth')

@section('title')
    {{ __('Register') }}
@endsection
@section('content')

    <!-- Login Section -->
    <div class="h-screen md:flex">
        <div class="hidden w-1/2 overflow-hidden md:block p-3">
            <div class="w-full h-full flex items-center justify-around bg-cover bg-no-repeat bg-center rounded-lg" style="background-image:url('https://cdn.brokeret.com/crm-assets/login-image/c19.png')">
                <div class="mx-auto max-w-xs text-center">
                    <a href="{{ route('home')}}" class="">
                        <img src="{{ asset(setting('site_logo','global')) }}" class="h-[56px]" alt="{{ __('Logo') }}">
                    </a>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center py-10 px-10 md:w-1/2">
            <div class="w-full max-w-lg">
                <div class="mobile-logo text-center mb-6 lg:hidden block">
                    <a href="{{ route('home')}}">
                        <img src="{{ asset(setting('site_logo','global')) }}" alt="{{ __('Logo') }}" class="h-[56px]">
                    </a>
                </div>
                <h2 class="text-2xl font-semibold text-gray-700">{{ __('Sign Up') }}</h2>
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
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('Close') }}">
                                        <iconify-icon icon="line-md:close"></iconify-icon>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                    <!-- BEGIN: Login Form -->
                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="schema" value="{{ request('schema') ?? old('schema') }}" >
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="fromGroup">
                                <label class="block capitalize form-label">
                                  {{ __('First Name*') }}
                                </label>
                                <div class="relative ">
                                    <input type="text" class="form-control py-2 h-[48px]" placeholder="{{ __('Your First Name') }}"
                                    name="first_name"
                                    value="{{ old('first_name') }}"
                                    required>
                                </div>
                            </div>
                            <div class="fromGroup">
                                <label class="block capitalize form-label">{{ __('Last Name*') }}</label>
                                <div class="relative ">
                                    <input type="text" class="form-control py-2 h-[48px]" placeholder="{{ __('Your Last Name') }}"
                                    name="last_name"
                                    value="{{ old('last_name') }}"
                                    required>
                                </div>
                            </div>
                        </div>
                        <div class="fromGroup">
                          <label class="block capitalize form-label">{{ __('Email Address*') }}</label>
                          <div class="relative ">
                              <input type="email" class="form-control py-2 h-[48px]"
                              name="email"
                              value="{{ old('email') }}"
                              placeholder="{{ __('Enter Your Email Address') }}"
                              required>
                          </div>
                      </div>
                      @if(getPageSetting('username_show'))
                        <div class="fromGroup">
                          <label class="block capitalize form-label">{{ __('User Name*') }}</label>
                          <div class="relative ">
                            <input
                                class="form-control py-2 h-[48px]"
                                type="text"
                                placeholder="{{ __('Enter Your User Name') }}"
                                name="username"
                                value="{{ old('username') }}"
                                required
                            />
                          </div>
                        </div>
                      @endif

                      @if(getPageSetting('country_show'))
                        <div class="formGroup">
                            <label class="block capitalize form-label">{{ __('Select Country*') }}</label>
                            <div class="relative">
                              <select name="country" id="countrySelect" class="select2 form-control py-2 h-[48px] w-full mt-2">
                                @foreach( getCountries() as $country)
                                    <option @if( $location->country_code == $country['country_code']) selected @endif value="{{ $country['name'].':'.$country['dial_code'] }}" class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
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
                            <input type="text"
                              class="form-control py-2 h-[48px]"
                              placeholder="{{ __('Phone Number') }}"
                              name="phone"
                              id="phone"
                              value="{{ old('phone') }}"
                              aria-label="{{ __('Phone Number') }}"
                              aria-describedby="basic-addon1"
                              value="{{ getLocation()->dial_code }}"
                            >
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
                            <input
                                class="form-control py-2 h-[48px]"
                                type="text"
                                placeholder="{{ __('Enter Your Referral Code') }}"
                                name="invite"
                                value="{{ request('invite') ?? old('invite') }}"
                            />
                          </div>
                        </div>
                      @endif
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="formGroup">
                                <label class="block capitalize form-label">{{ __('Password*') }}</label
                                >
                                    <div class="relative">
                                        <input
                                            class="form-control py-2 h-[48px]"
                                            type="password"
                                            id="password"
                                            name="password"
                                            placeholder="{{ __('Enter your password') }}"
                                            required
                                        />
                                        <button type="button" class="toggle-password absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center" data-toggle="#password">
                                            <iconify-icon class="text-lg" icon="heroicons:eye-slash"></iconify-icon>
                                        </button>
                                    </div>
                                </div>
                                <div class="formGroup">
                                    <label class="block capitalize form-label" for="password">{{ __('Confirm Password*') }}</label>
                                    <div class="relative">
                                        <input
                                            class="form-control py-2 h-[48px]"
                                            type="password"
                                            id="confirm-pass"
                                            name="password_confirmation"
                                            placeholder="{{ __('Enter your password') }}"
                                            required
                                        />
                                        <button type="button" class="toggle-password absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center" data-toggle="#confirm-pass">
                                            <iconify-icon class="text-lg" icon="heroicons:eye-slash"></iconify-icon>
                                        </button>
                                    </div>
                                </div>
                        </div>
                      <div class="formGroup">
                        @if($googleReCaptcha)
                            <div class="g-recaptcha" id="feedback-recaptcha"
                                 data-sitekey="{{ json_decode($googleReCaptcha->data,true)['google_recaptcha_key'] }}">
                            </div>
                        @endif
                      </div>
                      <div class="flex justify-between">
                        <label class="flex items-center cursor-pointer">
                          <input type="checkbox"
                            name="i_agree"
                            class="hiddens mr-2"
                            value="yes"
                            required
                          >
                          <span class="text-slate-500 dark:text-slate-400 text-xs leading-6 capitalize">
                            {{ __('I agree with') }}
                            @php
                              $privacyPolicyLink = document_link_by_slug('privacy_policy');
                            @endphp
                            <a href="{{ $privacyPolicyLink ? $privacyPolicyLink->link : '#' }}" class="btn-link" target="_blank">
                                {{ __('Privacy & Policy') }}
                            </a>
                            {{ __('and') }}
                            @php
                              $clientAgreementLink = document_link_by_slug('client_agreement');
                            @endphp
                            <a href="{{ $clientAgreementLink ? $clientAgreementLink->link : '#' }}" class="btn-link" target="_blank">
                                {{ __('Client Agreement') }}
                            </a>
                          </span>
                        </label>
                      </div>
                        <button type="submit" class="btn btn-primary block w-full text-center">
                          {{ __('Create Account') }}
                        </button>
                    </form>
                    <!-- END: Login Form -->
                    <div class="relative border-b-[#9AA2AF] border-opacity-[16%] border-b pt-6">
                        <div class="absolute inline-block bg-body dark:bg-body dark:text-slate-400 left-1/2 top-1/2 transform -translate-x-1/2 px-4 min-w-max text-sm text-slate-500 font-normal">
                            {{ __('Already have an account?') }}
                        </div>
                    </div>
                    <div class="mx-auto font-normal text-slate-500 dark:text-slate-400 mt-6 uppercase text-sm text-center">
                        <a href="{{ route('login') }}" class="btn btn-base inline-flex items-center justify-center w-full">
                            {{ __('Login') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Login Section End -->
@endsection
@section('script')
    @if($googleReCaptcha)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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

