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

                        <!-- Invite Code -->
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
                                        value="{{ old('invite') ?? $inviteCode }}"
                                    />
                                </div>
                            </div>
                    @endif

                    <!-- Other Registration Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="formGroup">
                                <label class="block capitalize form-label">{{ __('First Name*') }}</label>
                                <div class="relative">
                                    <input type="text" class="form-control py-2 h-[48px]" name="first_name" value="{{ old('first_name') }}" placeholder="{{ __('Your First Name') }}" required>
                                </div>
                            </div>
                            <div class="formGroup">
                                <label class="block capitalize form-label">{{ __('Last Name*') }}</label>
                                <div class="relative">
                                    <input type="text" class="form-control py-2 h-[48px]" name="last_name" value="{{ old('last_name') }}" placeholder="{{ __('Your Last Name') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="fromGroup">
                            <label class="block capitalize form-label">{{ __('Email Address*') }}</label>
                            <div class="relative">
                                <input type="email" class="form-control py-2 h-[48px]" name="email" value="{{ old('email') }}" placeholder="{{ __('Enter Your Email Address') }}" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary block w-full text-center">
                            {{ __('Create Account') }}
                        </button>
                    </form>
                    <!-- END: Login Form -->
                </div>
            </div>
        </div>
    </div>
    <!-- Login Section End -->
@endsection
