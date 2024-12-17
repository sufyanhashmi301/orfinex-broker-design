@extends('frontend::layouts.auth')

@section('title')
    {{ __('Login') }}
@endsection
@section('content')

    <div class="loginwrapper">
        <div class="lg-inner-column">
            <div class="relative lg:w-2/5 w-full">
                <div class="inner-content h-full flex flex-col bg-white dark:bg-slate-800">
                    <div class="auth-box h-full flex flex-col justify-center">
                        <div class="text-center mb-10">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset(setting('site_logo', 'global')) }}" alt="" class="max-w-[160px] dark_logo">
                                <img src="{{ asset(setting('site_logo_light', 'global')) }}" alt="" class="max-w-[160px] white_logo">
                            </a>
                        </div>
                        <div class="text-left 2xl:mb-10 mb-4">
                            <h4 class="font-medium">Sign in</h4>
                            <div class="text-slate-500 text-base">
                                Sign in to your account to start using Dashcode
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
                            <div class="fromGroup">
                                <label class="block capitalize form-label">passwrod</label>
                                <div class="relative">
                                    <input type="password" name="password" class="form-control py-2" placeholder="Add placeholder" value="dashcode">
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" class="hiddens">
                                    <span class="text-slate-500 dark:text-slate-400 text-sm leading-6 capitalize ml-1">Keep me signed in</span>
                                </label>
                                <a href="" class="text-sm text-slate-800 dark:text-slate-400 leading-6 font-medium">
                                    Forgot Password?
                                </a>
                            </div>
                            <button class="btn btn-dark block w-full text-center">Sign in</button>
                        </form>
                        <!-- END: Login Form -->
                        <div class="relative border-b-[#9AA2AF] border-opacity-[16%] border-b pt-6">
                            <div class="absolute inline-block bg-white dark:bg-slate-800 dark:text-slate-400 left-1/2 top-1/2 transform -translate-x-1/2 px-4 min-w-max text-sm text-slate-500 font-normal">
                                Or continue with
                            </div>
                        </div>
                        <div class="max-w-[242px] mx-auto mt-8 w-full">

                            <!-- BEGIN: Social Log in Area -->
                            <ul class="flex">
                                <li class="flex-1">
                                    <a href="#" class="inline-flex h-10 w-10 bg-[#1C9CEB] text-white text-2xl flex-col items-center justify-center rounded-full">
                                        <img src="{{ asset('frontend/images/icon/tw.svg') }}" alt="">
                                    </a>
                                </li>
                                <li class="flex-1">
                                    <a href="#" class="inline-flex h-10 w-10 bg-[#395599] text-white text-2xl flex-col items-center justify-center rounded-full">
                                        <img src="{{ asset('frontend/images/icon/fb.svg') }}" alt="">
                                    </a>
                                </li>
                                <li class="flex-1">
                                    <a href="#" class="inline-flex h-10 w-10 bg-[#0A63BC] text-white text-2xl flex-col items-center justify-center rounded-full">
                                        <img src="{{ asset('frontend/images/icon/in.svg') }}" alt="">
                                    </a>
                                </li>
                                <li class="flex-1">
                                    <a href="#" class="inline-flex h-10 w-10 bg-[#EA4335] text-white text-2xl flex-col items-center justify-center rounded-full">
                                        <img src="{{ asset('frontend/images/icon/gp.svg') }}" alt="">
                                    </a>
                                </li>
                            </ul>
                            <!-- END: Social Log In Area -->
                        </div>
                        <div class="md:max-w-[345px] mx-auto font-normal text-slate-500 dark:text-slate-400 mt-12 uppercase text-sm">
                            Don’t have an account?
                            <a href="signup-one.html" class="text-slate-900 dark:text-white font-medium hover:underline">
                                Sign up
                            </a>
                        </div>
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
