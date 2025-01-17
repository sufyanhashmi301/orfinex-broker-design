@extends('frontend::layouts.auth')

@section('title')
    {{ __('Login') }}
@endsection
@section('content')

    <div class="loginwrapper">
        <div class="lg-inner-column">
            <div class="relative lg:w-2/5 w-full">
                <div class="inner-content h-full flex flex-col bg-white dark:bg-slate-800">
                    <div class="p-5">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset(setting('site_favicon', 'global')) }}" alt="" class="">
                        </a>
                    </div>
                    <div class="auth-box h-full flex flex-col justify-center">
                        <div class="text-left 2xl:mb-10 mb-4">
                            <h4 class="font-medium">Reset your password</h4>
                            <div class="text-slate-500 text-base">
                                Enter your email and we'll send you a link to your email address with instructions on how to reset your password.
                            </div>
                        </div>
                        <!-- BEGIN: Login Form -->
                        <form class="space-y-4" action=''>
                            <div class="fromGroup">
                                <label class="block capitalize form-label">email</label>
                                <div class="relative">
                                    <input type="email" name="email" class="form-control py-2" placeholder="Add placeholder" value="dashcode@gmail.com">
                                </div>
                            </div>
                            <button class="btn btn-dark block w-full text-center">Send password reset link</button>
                        </form>
                        <!-- END: Login Form -->
                        <a href="" class="text-primary text-sm font-medium inline-flex items-center hover:underline mt-5">
                            <iconify-icon class="text-lg" icon="lucide:arrow-left"></iconify-icon>
                            <span class="ml-2">{{ __('Return to login') }}</span>
                        </a>
                    </div>
                    <div class="auth-footer text-center">
                        Copyright 2021, Dashcode All Rights Reserved.
                    </div>
                </div>
            </div>
            <div class="lg:flex items-center justify-center overflow-hidden hidden relative lg:w-3/5 w-full z-[1]">
                <img class="absolute h-full right-0" src="data:image/svg+xml,%3csvg%20width='691'%20height='1080'%20viewBox='0%200%20691%201080'%20fill='none'%20xmlns='http://www.w3.org/2000/svg'%3e%3cg%20opacity='0.65'%3e%3ccircle%20cx='551.499'%20cy='545.525'%20r='162.292'%20stroke='%23C4CAD4'%20stroke-width='0.98658'/%3e%3ccircle%20opacity='0.8'%20cx='550.511'%20cy='546.512'%20r='212.608'%20stroke='%23C4CAD4'%20stroke-width='0.98658'/%3e%3ccircle%20opacity='0.9'%20cx='550.512'%20cy='546.511'%20r='287.588'%20stroke='%23C4CAD4'%20stroke-width='0.98658'/%3e%3ccircle%20opacity='0.6'%20cx='550.511'%20cy='546.512'%20r='390.192'%20stroke='%23C4CAD4'%20stroke-width='0.98658'/%3e%3ccircle%20opacity='0.6'%20cx='550.5'%20cy='546.5'%20r='473.007'%20stroke='%23C4CAD4'%20stroke-width='0.98658'/%3e%3ccircle%20opacity='0.4'%20cx='550.512'%20cy='546.512'%20r='550.018'%20stroke='%23C4CAD4'%20stroke-width='0.98658'/%3e%3c/g%3e%3c/svg%3e">
                <div class="flex justify-end w-full relative">
                    <picture class="dashboard-image">
                        <source srcset="{{ asset('frontend/images/brokeret-dashboard.png') }}" media="(max-resolution: 1.5x)">
                        <img class="w-full shadow-elevation-4" src="{{ asset('frontend/images/brokeret-dashboard.png') }}">
                    </picture>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('style')
    <style>
        .dashboard-image {
            width: 85%;
            max-width: 1080px;
        }
        .shadow-elevation-4 {
            --tw-shadow: 0px 2px 6px 1px rgba(4, 29, 47, .1);
            --tw-shadow-colored: 0px 2px 6px 1px var(--tw-shadow-color);
            box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
        }
    </style>
@endsection
