@extends('backend.setting.email_setting.index')
@section('title')
    {{ __('Mail Settings') }}
@endsection
@section('email-content')
    <div class="card">
        <div class="card-header noborder">
            <div class="flex-1">
                <h4 class="font-medium text-xl capitalize dark:text-white inline-block mb-1">{{ __('Amazon SES Configuration') }}</h4>
                <p class="card-text">
                    {{ __('Configure your Amazon SES server for reliable email delivery') }}
                </p>
            </div>
            <span class="badge badge-primary capitalize">
                {{ __('Recommended') }}
            </span>
        </div>
        <div class="card-body p-6 pt-3">
            <div class="font-normal text-sm rounded-md bg-white border border-secondary-500 dark:bg-slate-800 p-4">
                <div class="flex space-x-3">
                    <iconify-icon class="text-lg flex-0" icon="lucide:triangle-alert"></iconify-icon>
                    <p class="flex-1 text-sm dark:text-slate-300">
                        {{ __('Amazon SES servers provide the most reliable way to send automated emails. We recommend using professional providers like SendGrid or Mailgun for best delivery rates.') }}
                    </p>
                </div>
            </div>
            <div class="mt-5">
                <ul class="space-y-5">
                    <li class="bg-body dark:bg-body rounded-lg p-4">
                        <div class="flex">
                            <div class="w-6 h-6 flex items-center justify-center bg-primary bg-opacity-30 text-primary rounded-full mr-2">
                                {{ 1 }}
                            </div>
                            <div class="flex-1 text-sm">
                                <h6 class="text-sm font-semibold dark:text-white mb-1">
                                    {{ __('Create Mail Server Account') }}
                                </h6>
                                <p class="dark:text-slate-300 mb-1">
                                    {{ __('Sign up for a third-party mail server provider (SendGrid, Mailgun, etc.)') }}
                                </p>
                                <a href="https://sendgrid.com/en-us" class="inline-flex items-center text-sm font-medium dark:text-white" target="_blank">
                                    {{ __('Sign up Now') }}
                                    <iconify-icon class="ml-1" icon="lucide:external-link"></iconify-icon>
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="bg-body dark:bg-body rounded-lg p-4">
                        <div class="flex">
                            <div class="w-6 h-6 flex items-center justify-center bg-primary bg-opacity-30 text-primary rounded-full mr-2">
                                {{ __('2') }}
                            </div>
                            <div class="flex-1 text-sm">
                                <h6 class="text-sm font-semibold dark:text-white mb-1">
                                    {{ __('Domain Authentication') }}
                                </h6>
                                <p class="dark:text-slate-300">
                                    {{ __('Verify your domain ownership through DNS records to ensure proper delivery') }}
                                </p>
                            </div>
                        </div>
                    </li>
                    <li class="bg-body dark:bg-body rounded-lg p-4">
                        <div class="flex">
                            <div class="w-6 h-6 flex items-center justify-center bg-primary bg-opacity-30 text-primary rounded-full mr-2">
                                {{ __('3') }}
                            </div>
                            <div class="flex-1 text-sm">
                                <h6 class="text-sm font-semibold dark:text-white mb-1">
                                    {{ __('Generate Amazon SES Credentials') }}
                                </h6>
                                <p class="dark:text-slate-300">
                                    {{ __('Create and securely store your Amazon SES credentials from your provider') }}
                                </p>
                            </div>
                        </div>
                    </li>
                    <li class="bg-body dark:bg-body rounded-lg p-4">
                        <div class="flex">
                            <div class="w-6 h-6 flex items-center justify-center bg-primary bg-opacity-30 text-primary rounded-full mr-2">
                                {{ __('4') }}
                            </div>
                            <div class="flex-1 text-sm">
                                <h6 class="text-sm font-semibold dark:text-white mb-1">
                                    {{ __('Configure Settings') }}
                                </h6>
                                <p class="dark:text-slate-300">
                                    {{ __('Enter your Amazon SES server details, port, encryption type, and authentication') }}
                                </p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="flex items-center justify-end gap-3 mt-10">
                <a href="javascript:;" class="btn btn-dark inline-flex items-center" type="button" data-bs-toggle="modal" data-bs-target="#mailSettings">
                    <iconify-icon class="text-lg mr-2" icon="mdi:connection"></iconify-icon>
                    {{ __('Connect') }}
                </a>
            </div>
        </div>
    </div>


@endsection
