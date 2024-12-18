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
        $inviteCode = \Cookie::get('invite');
    @endphp

    <!-- Registration Form -->
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

                        <!-- First and Last Name -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="fromGroup">
                                <label class="block capitalize form-label">
                                    {{ __('First Name*') }}
                                </label>
                                <div class="relative">
                                    <input type="text" class="form-control py-2 h-[48px]" name="first_name" value="{{ old('first_name') }}" placeholder="{{ __('Your First Name') }}" required>
                                </div>
                            </div>
                            <div class="fromGroup">
                                <label class="block capitalize form-label">{{ __('Last Name*') }}</label>
                                <div class="relative">
                                    <input type="text" class="form-control py-2 h-[48px]" name="last_name" value="{{ old('last_name') }}" placeholder="{{ __('Your Last Name') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="fromGroup">
                            <label class="block capitalize form-label">{{ __('Email Address*') }}</label>
                            <div class="relative">
                                <input type="email" class="form-control py-2 h-[48px]" name="email" value="{{ old('email') }}" placeholder="{{ __('Enter Your Email Address') }}" required>
                            </div>
                        </div>

                        <!-- Username -->
                        @if(getPageSetting('username_show'))
                            <div class="fromGroup">
                                <label class="block capitalize form-label">{{ __('User Name*') }}</label>
                                <div class="relative">
                                    <input type="text" class="form-control py-2 h-[48px]" name="username" value="{{ old('username') }}" placeholder="{{ __('Enter Your User Name') }}" required>
                                </div>
                            </div>
                        @endif

                    <!-- Country -->
                        @if(getPageSetting('country_show'))
                            <div class="formGroup">
                                <label class="block capitalize form-label">{{ __('Select Country*') }}</label>
                                <div class="relative">
                                    <select name="country" id="countrySelect" class="select2 form-control py-2 h-[48px] w-full mt-2">
                                        @foreach( getCountries() as $country)
                                            <option @if( $location->country_code == $country['code']) selected @endif
                                            value="{{ $country['name'].':'.$country['dial_code'] }}"
                                                    class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $country['name']  }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                    <!-- Phone -->
                        @if(getPageSetting('phone_show'))
                            <div class="formGroup phone-input-wrapper">
                                <label class="block capitalize form-label">{{ __('Phone Number*') }}</label>
                                <div class="relative">
                                    <input type="text" class="form-control py-2 h-[48px]" name="phone" id="phone" value="{{ old('phone') }}" placeholder="{{ __('Phone Number') }}" aria-label="{{ __('Phone Number') }}">
                                </div>
                            </div>
                        @endif

                    <!-- Referral Code -->
                        @if(getPageSetting('referral_code_show'))
                            <div class="formGroup">
                                <div class="flex items-center justify-between">
                                    <label class="block capitalize form-label">{{ __('Referral Code') }}</label>
                                    <a href="javascript:;" class="btn-link referralToggle">{{ __('Show') }}</a>
                                </div>
                                <div class="relative hidden" id="referral-input">
                                    <input class="form-control py-2 h-[48px]" type="text" name="invite" value="{{ old('invite') ?? $inviteCode }}" placeholder="{{ __('Enter Your Referral Code') }}">
                                </div>
                            </div>
                    @endif

                    <!-- Password and Confirmation -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="formGroup">
                                <label class="block capitalize form-label">{{ __('Password*') }}</label>
                                <div class="relative">
                                    <input type="password" class="form-control py-2 h-[48px]" name="password" placeholder="{{ __('Enter your password') }}" required>
                                </div>
                            </div>
                            <div class="formGroup">
                                <label class="block capitalize form-label">{{ __('Confirm Password*') }}</label>
                                <div class="relative">
                                    <input type="password" class="form-control py-2 h-[48px]" name="password_confirmation" placeholder="{{ __('Confirm your password') }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn btn-primary block w-full text-center">
                            {{ __('Create Account') }}
                        </button>
                    </form>
                    <!-- END: Login Form -->
                </div>
            </div>
        </div>
    </div>
    <!-- Registration Section End -->
@endsection
