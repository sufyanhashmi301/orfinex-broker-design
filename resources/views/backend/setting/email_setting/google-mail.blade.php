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
                    {{ __('Configure your Google Mail (Gmail) server to send automated email notifications regarding transactions, payments, and reminders to your customers.') }}
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
                        {{ __('Key Benefits:') }}
                    </h6>
                    <ul>
                        <li class="text-sm text-slate-600 dark:text-slate-300 flex space-x-2 items-center py-1">
                            <span class="h-[6px] w-[6px] bg-slate-900 dark:bg-slate-400 rounded-full inline-block"></span>
                            <span>{{ __('Notify your customers instantly about their transaction statuses, payments, and important reminders via Gmail.') }}</span>
                        </li>
                        <li class="text-sm text-slate-600 dark:text-slate-300 flex space-x-2 items-center py-1">
                            <span class="h-[6px] w-[6px] bg-slate-900 dark:bg-slate-400 rounded-full inline-block"></span>
                            <span>{{ __('Enable seamless email notifications for both customers and their assigned contact persons, ensuring effective communication.') }}</span>
                        </li>
                    </ul>
                </div>
                <div class="py-[18px] px-6 font-normal font-Inter text-sm rounded-md bg-warning-500 bg-opacity-[14%] text-warning-500">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse mb-3">
                        <iconify-icon class="text-2xl flex-0" icon="lucide:triangle-alert"></iconify-icon>
                        <p class="flex-1 text-lg font-medium">
                            {{ __('Important Guidelines:') }}
                        </p>
                    </div>
                    <p class="text-sm text-warning-500">
                        {{ __('Sending emails via Gmail is not recommended by us due to the security restrictions imposed by Google. These security measures may prevent the sending of emails from your CRM, even after making the necessary settings. Google may block email sending on certain servers, leading to unreliable delivery. While you can use Gmail if it works on your server, our support team will not assist in troubleshooting Gmail-related issues. For a more reliable solution, we recommend using a professional email provider such as SMTP servers (e.g., SendGrid, Mailgun) for better performance and support.') }}
                    </p>
                </div>
                <div>
                    <h6 class="text-lg font-medium mb-[6px] dark:text-white text-slate-900">
                        {{ __('Before Connecting Your Gmail Account:') }}
                    </h6>
                    <p class="text-sm font-semibold dark:text-white mb-2">
                        {{ __('To integrate Gmail with your CRM, follow these steps:') }}
                    </p>
                    <ul>
                        <li class="text-sm text-slate-600 dark:text-slate-300 flex space-x-2 items-center py-1">
                            <span class="h-[6px] w-[6px] bg-slate-900 dark:bg-slate-400 rounded-full inline-block"></span>
                            <span>{{ __('Enable IMAP in Gmail Go to your Gmail settings and enable IMAP to allow email sending through third-party platforms.') }}</span>
                        </li>
                        <li class="text-sm text-slate-600 dark:text-slate-300 flex space-x-2 items-center py-1">
                            <span class="h-[6px] w-[6px] bg-slate-900 dark:bg-slate-400 rounded-full inline-block"></span>
                            <span>{{ __('Generate an App Password Due to Gmail’s enhanced security, you need to generate an app-specific password to connect Gmail with your CRM.') }}</span>
                        </li>
                        <li class="text-sm text-slate-600 dark:text-slate-300 flex space-x-2 items-center py-1">
                            <span class="h-[6px] w-[6px] bg-slate-900 dark:bg-slate-400 rounded-full inline-block"></span>
                            <span>{{ __('Enter Credentials Enter your Gmail credentials and the generated app password into the CRM to initiate the connection.') }}</span>
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
