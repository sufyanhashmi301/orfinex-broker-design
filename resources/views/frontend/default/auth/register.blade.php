@extends('frontend::layouts.auth')

@section('title')
    {{ __('Register') }}
@endsection
@section('content')

    <!-- Login Section -->
    <div class="loginwrapper bg-cover bg-no-repeat bg-center" style="background-image: url({{ asset('frontend/images/all-img/page-bg.png') }});">
        <div class="lg-inner-column">
            <div class="left-columns lg:w-1/2 lg:block hidden">
                <div class="logo-box-3">
                    <a href="{{ route('home')}}" class="">
                        <img src="{{ asset(setting('site_logo','global')) }}" alt="">
                    </a>
                </div>
            </div>
            <div class="lg:w-1/2 w-full flex flex-col items-center justify-center">
                <div class="auth-box-3">
                    <div class="mobile-logo text-center mb-6 lg:hidden block">
                        <a href="{{ route('home')}}">
                            <img src="{{ asset(setting('site_logo','global')) }}" alt="" class="mb-10 dark_logo">
                            <img src="{{ asset(setting('site_logo','global')) }}" alt="" class="mb-10 white_logo">
                        </a>
                    </div>
                    <div class="text-center 2xl:mb-10 mb-5">
                        <h4 class="font-medium">{{ $data['title'] }}</h4>
                        <div class="text-slate-500 dark:text-slate-400 text-base">
                            {{ $data['bottom_text'] }}
                        </div>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            @foreach($errors->all() as $error)
                                <strong>{{$error}}</strong>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <!-- BEGIN: Login Form -->
                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf
                        <div class="fromGroup">
                            <label class="block capitalize form-label">
                              {{ __('First Name*') }}
                            </label>
                            <div class="relative ">
                                <input type="text" class="form-control py-2 h-[48px]" placeholder="Your First Name"
                                name="first_name"
                                value="{{ old('first_name') }}"
                                required>
                            </div>
                        </div>
                        <div class="fromGroup">
                            <label class="block capitalize form-label">{{ __('Last Name*') }}</label>
                            <div class="relative ">
                                <input type="text" class="form-control py-2 h-[48px]" placeholder="Your Last Name"
                                name="last_name"
                                value="{{ old('last_name') }}"
                                required>
                            </div>
                        </div>
                        <div class="fromGroup">
                          <label class="block capitalize form-label">{{ __('Email Address*') }}</label>
                          <div class="relative ">
                              <input type="email" class="form-control py-2 h-[48px]"
                              name="email"
                              value="{{ old('email') }}"
                              placeholder="Enter Your Email Address"
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
                                placeholder="Enter Your User Name"
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
                        <div class="formGroup">
                          <label class="block capitalize form-label">{{ __('Phone Number*') }}</label>
                          <div class="relative">
                            <input id="prefix" type="text"
                              class="form-control py-2 h-[48px]"
                              placeholder="Phone Number"
                              name="phone"
                              value="{{ old('phone') }}"
                              aria-label="Username"
                              aria-describedby="basic-addon1"
                              value="{{ getLocation()->dial_code }}"
                            >
                          </div>
                        </div>
                      @endif

                      @if(getPageSetting('referral_code_show'))
                        <div class="formGroup">
                          <label class="block capitalize form-label">{{ __('Referral Code') }}</label>
                          <div class="relative">
                            <input
                                class="form-control py-2 h-[48px]"
                                type="text"
                                placeholder="Enter Your Referral Code"
                                name="invite"
                                value="{{ request('invite') ?? old('invite') }}"
                            />
                          </div>
                        </div>
                      @endif

                      <div class="formGroup">
                        <label class="block capitalize form-label">{{ __('Password*') }}</label
                        >
                            <div class="relative">
                                <input
                                    class="form-control py-2 h-[48px]"
                                    type="password"
                                    name="password"
                                    placeholder="Enter your password"
                                    required
                                />
                            </div>
                        </div>
                        <div class="formGroup">
                            <label class="block capitalize form-label" for="password">{{ __('Confirm Password*') }}</label>
                            <div class="relative">
                                <input
                                    class="form-control py-2 h-[48px]"
                                    type="password"
                                    name="password_confirmation"
                                    placeholder="Enter your password"
                                    required
                                />
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
                          <span class="text-slate-500 dark:text-slate-400 text-sm leading-6 capitalize">
                            {{ __('I agree with') }}
                            <a href="{{url('/privacy-policy')}}">{{ __('Privacy & Policy') }}</a> {{ __('and') }}
                            <a href="{{url('/terms-and-conditions')}}">{{ __('Terms & Condition') }}</a>
                          </span>
                        </label>
                      </div>
                        <button type="submit" class="btn btn-dark block w-full text-center">
                          {{ __('Create Account') }}
                        </button>
                    </form>
                    <!-- END: Login Form -->
                    <div class=" relative border-b-[#9AA2AF] border-opacity-[16%] border-b pt-6">
                        <div class=" absolute inline-block bg-white dark:bg-slate-800 dark:text-slate-400 left-1/2 top-1/2 transform -translate-x-1/2
                                    px-4 min-w-max text-sm text-slate-500 dark:text-slate-400font-normal ">
                            {{ __('Or continue with') }}
                        </div>
                    </div>
                    <div class="max-w-[242px] mx-auto mt-8 w-full">

                        <!-- BEGIN: Social Log in Area -->
                        <ul class="flex">
                            <li class="flex-1">
                                <a href="#"
                                    class="inline-flex h-10 w-10 bg-[#1C9CEB] text-white text-2xl flex-col items-center justify-center rounded-full">
                                    <img src="{{ asset('frontend/images/icon/tw.svg') }}" alt="">
                                </a>
                            </li>
                            <li class="flex-1">
                                <a href="#"
                                    class="inline-flex h-10 w-10 bg-[#395599] text-white text-2xl flex-col items-center justify-center rounded-full">
                                    <img src="{{ asset('frontend/images/icon/fb.svg') }}" alt="">
                                </a>
                            </li>
                            <li class="flex-1">
                                <a href="#"
                                    class="inline-flex h-10 w-10 bg-[#0A63BC] text-white text-2xl flex-col items-center justify-center rounded-full">
                                    <img src="{{ asset('frontend/images/icon/in.svg') }}" alt="">
                                </a>
                            </li>
                            <li class="flex-1">
                                <a href="#"
                                    class="inline-flex h-10 w-10 bg-[#EA4335] text-white text-2xl flex-col items-center justify-center rounded-full">
                                    <img src="{{ asset('frontend/images/icon/gp.svg') }}" alt="">
                                </a>
                            </li>
                        </ul>
                        <!-- END: Social Log In Area -->
                    </div>
                    <div class="mx-auto font-normal text-slate-500 dark:text-slate-400 2xl:mt-12 mt-6 uppercase text-sm text-center">
                      {{ __('Already have an account?') }} 
                      <a href="{{ route('login') }}" class="text-slate-900 dark:text-white font-medium hover:underline">{{ __('Login') }}</a>
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
    <script>
        $('#countrySelect').on('change', function (e) {
            "use strict";
            e.preventDefault();
            var country = $(this).val();
            $('#dial-code').html(country.split(":")[1])
        })
    </script>
@endsection

