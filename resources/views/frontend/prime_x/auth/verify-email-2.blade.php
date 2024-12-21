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
                        <div class="">
                            <div class="text-left mb-5">
                                <h4 class="card-title mb-5">👋 Welcome to Brokeret</h4>
                                <p class="text-slate-500 dark:text-slate-400 text-sm">
                                    To start using your account, we need to verify your email address. Please check your inbox for the verification email we just sent.
                                </p>
                            </div>
                            <div class="text-left space-y-3 mb-5">
                                <p class="dark:white text-sm font-semibold">
                                    Didn't receive the email?
                                </p>
                                <p class="text-slate-500 dark:text-slate-400 text-sm">
                                    Click the button below to resend the verification link.
                                </p>
                                <form method="POST" action="">
                                    <button type="submit" class="btn btn-dark block w-full text-center mb-3">
                                        Resend Verification Email
                                    </button>
                                </form>
                            </div>
                            <div class="text-center space-y-3">
                                <p class="dark:white text-sm font-semibold">
                                    Need Help?
                                </p>
                                <p class="text-slate-500 dark:text-slate-400 text-sm">
                                    If you're having trouble or need assistance, contact our support team at
                                    <a href="mailto:support@brokeret.com" class="underline">
                                        support@brokeret.com
                                    </a>
                                </p>
                                <div class="relative border-b-[#9AA2AF] border-opacity-[16%] border-b pt-6">
                                    <div class="absolute inline-block bg-white dark:bg-slate-800 dark:text-slate-400 left-1/2 top-1/2 transform -translate-x-1/2 px-4 min-w-max text-sm text-slate-500 font-normal">
                                        Connect With Us
                                    </div>
                                </div>
                                <div class="mt-8 w-full">
                                    <ul class="flex items-center justify-center mt-5 gap-2">
                                        <li>
                                            <a href="https://facebook.com/" target="_blank">
                                                <img src="https://cdn.brokeret.com/crm-assets/admin/social/facebook.webp" class="h-6" alt="Facebook">
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://instagram.com/" target="_blank">
                                                <img src="https://cdn.brokeret.com/crm-assets/admin/social/instagram.webp" class="h-6" alt="Instagram">
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://linkedin.com/" target="_blank">
                                                <img src="https://cdn.brokeret.com/crm-assets/admin/social/linkedin.webp" class="h-6" alt="LinkedIn">
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://telegram.org/" target="_blank">
                                                <img src="https://cdn.brokeret.com/crm-assets/admin/social/telegram.webp" class="h-6" alt="Telegram">
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://discord.com/" target="_blank">
                                                <img src="https://cdn.brokeret.com/crm-assets/admin/social/discord.webp" class="h-6" alt="Discord">
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://www.skype.com/en/" target="_blank">
                                                <img src="https://cdn.brokeret.com/crm-assets/admin/social/skype.webp" class="h-6" alt="Skype">
                                            </a>
                                        </li>
                                        <li>
                                            <a href="https://cdn.brokeret.com/doc/example.pdf" target="_blank">
                                                <img src="https://cdn.brokeret.com/crm-assets/admin/social/whatsapp.webp" class="h-6" alt="Whatsapp">
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
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
