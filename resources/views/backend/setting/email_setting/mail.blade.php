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
                    {{ __('Configure your SMTP server to enable automated email notifications for your customers, keeping them informed about transactions, payments, and important reminders.') }}
                </p>
            </div>
            <span class="badge bg-success text-success bg-opacity-30 capitalize">
                {{ __('Activated') }}
            </span>
        </div>
        <div class="card-body p-6">
            <div class="space-y-5">
                <div>
                    <h6 class="text-lg font-medium mb-[6px] dark:text-white text-slate-900 mb-3">
                        {{ __('Key Benefits:') }}
                    </h6>
                    <ul>
                        <li class="text-sm text-slate-600 dark:text-slate-300 flex space-x-2 items-center py-1">
                            <span class="h-[6px] w-[6px] bg-slate-900 dark:bg-slate-400 rounded-full inline-block"></span>
                            <span>{{ __('Instantly notify customers about transaction statuses, payment confirmations, and reminders via email.') }}</span>
                        </li>
                        <li class="text-sm text-slate-600 dark:text-slate-300 flex space-x-2 items-center py-1">
                            <span class="h-[6px] w-[6px] bg-slate-900 dark:bg-slate-400 rounded-full inline-block"></span>
                            <span>{{ __('Enable email notifications for both customers and their designated contact persons, ensuring smooth communication.') }}</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <h6 class="text-lg font-medium mb-[6px] dark:text-white text-slate-900">
                        {{ __('Before Connecting Your SMTP Server:') }}
                    </h6>
                    <p class="text-sm font-semibold dark:text-white mb-2">{{ __('To integrate the SMTP mail server with your CRM, please follow these steps.') }}</p>
                    <ul>
                        <li class="text-sm text-slate-600 dark:text-slate-300 flex space-x-2 items-center py-1">
                            <span class="h-[6px] w-[6px] bg-slate-900 dark:bg-slate-400 rounded-full inline-block"></span>
                            <span>{{ __('Create a Mail Server Account If you don’t already have one, sign up for a third-party mail server provider (e.g., SendGrid, Mailgun).') }}</span>
                            <a href="https://sendgrid.com/en-us" class="text-success">{{ __('Sign up Now') }}</a>
                        </li>
                        <li class="text-sm text-slate-600 dark:text-slate-300 flex space-x-2 items-center py-1">
                            <span class="h-[6px] w-[6px] bg-slate-900 dark:bg-slate-400 rounded-full inline-block"></span>
                            <span>{{ __('Authenticate Your Domain Ensure your domain or individual email addresses are authenticated within your email provider’s settings to prevent delivery issues.') }}</span>
                        </li>
                        <li class="text-sm text-slate-600 dark:text-slate-300 flex space-x-2 items-center py-1">
                            <span class="h-[6px] w-[6px] bg-slate-900 dark:bg-slate-400 rounded-full inline-block"></span>
                            <span>{{ __('Generate SMTP Credentials Create and securely store the SMTP credentials specific to your chosen email provider.') }}</span>
                        </li>
                        <li class="text-sm text-slate-600 dark:text-slate-300 flex space-x-2 items-center py-1">
                            <span class="h-[6px] w-[6px] bg-slate-900 dark:bg-slate-400 rounded-full inline-block"></span>
                            <span>{{ __('For more information on how to integrate SMTP with your CRM, refer to the platform documentation or visit.') }}</span>
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
