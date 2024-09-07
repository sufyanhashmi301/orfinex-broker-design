@extends('backend.setting.email_setting.index')
@section('title')
    {{ __('Mail Settings') }}
@endsection
@section('email-content')
    <div class="card">
        <div class="card-header">
            <div class="flex-1">
                <img src="{{ asset('backend/images/smtp-icon.png') }}" class="mb-3" alt="">
                <p class="card-text">
                    {{ __('Configure the SMTP server for email and send automated notifications to your customers regarding transactions, payments, and reminders.') }}
                </p>
            </div>
            <span class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
                {{ __('Activated') }}
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
                            {{ __('Instantly notify customers about transactions, payments, and reminders via email.') }}
                        </li>
                        <li class="text-sm text-slate-600 dark:text-slate-300 py-1">
                            {{ __('Set up email notifications for both customers and their contact persons.') }}
                        </li>
                    </ul>
                </div>
                <div>
                    <h6 class="text-lg font-medium mb-[6px] dark:text-white text-slate-900">
                        {{ __('Before you can connect the SMTP mail server with your CRM (recommended):') }}
                    </h6>
                    <ul>
                        <li class="text-sm text-slate-600 dark:text-slate-300 py-1">
                            {{ __('Create a SendGrid Account ') }}
                            <a href="" class="text-success-500">Sign up Now</a>
                        </li>
                        <li class="text-sm text-slate-600 dark:text-slate-300 py-1">
                            {{ __('Authenticate your domain-level or single-level email in the settings.') }}
                        </li>
                        <li class="text-sm text-slate-600 dark:text-slate-300 py-1">
                            {{ __('Generate new SMTP credentials in SendGrid tailored for this platform. ') }}
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
