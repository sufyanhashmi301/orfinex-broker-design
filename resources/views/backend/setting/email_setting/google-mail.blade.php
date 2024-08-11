@extends('backend.setting.email_setting.index')
@section('title')
    {{ __('Google Mail Settings') }}
@endsection
@section('email-content')
    <div class="card">
        <div class="card-header">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-3">
                    <img src="{{ asset('backend/images/gmail-icon.png') }}" class="h-6" alt="">
                    <span class="text-2xl font-medium">{{ __('Gmail') }}</span>
                </div>
                <p class="card-text">
                    {{ __('Set up the Google Mail server for email and send automated notifications to your customers about transactions, payments, and reminders.') }}
                </p>
            </div>
            <span class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">
                {{ __('Deactivated') }}
            </span>
        </div>
        <div class="card-body p-6">
            <div class="space-y-5">
                <div>
                    <h6 class="text-lg font-medium mb-[6px] dark:text-white text-slate-900 mb-3">
                        {{ __('Benefits:') }}
                    </h6>
                    <ul>
                        <li class="text-sm text-slate-600 dark:text-slate-300 py-1">
                            {{ __('Notify customers instantly about transactions, payments and reminders via SMS.') }}
                        </li>
                        <li class="text-sm text-slate-600 dark:text-slate-300 py-1">
                            {{ __('Configure SMS notifications at customer and contact person level.') }}
                        </li>
                    </ul>
                </div>
                <div>
                    <h6 class="text-lg font-medium mb-[6px] dark:text-white text-slate-900">
                        {{ __('Before you can connect Twilio with zojo invoice, you must') }}
                    </h6>
                    <ul>
                        <li class="text-sm text-slate-600 dark:text-slate-300 py-1">
                            {{ __('Create a Twilio account. ') }}
                            <a href="" class="text-success-500">Sign up Now</a>
                        </li>
                        <li class="text-sm text-slate-600 dark:text-slate-300 py-1">
                            {{ __('Go to console in Twilio and get your account SID and Auth Token.') }}
                        </li>
                        <li class="text-sm text-slate-600 dark:text-slate-300 py-1">
                            {{ __('Have an active phone number that works with Twilio. ') }}
                            <a href="" class="text-success-500">Read More</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="flex items-center justify-between gap-5 mt-10">
                <a href="javascript:;" class="btn btn-dark inline-flex items-center" type="button" data-bs-toggle="modal" data-bs-target="#mailSettings">
                    {{ __('Connect') }}
                </a>
                <a href="javascript:;" class="btn btn-outline-dark inline-flex items-center" type="button" data-bs-toggle="modal" data-bs-target="#mailConnection">
                    {{ __('Connection Check') }}
                </a>
            </div>
        </div>
    </div>


@endsection
