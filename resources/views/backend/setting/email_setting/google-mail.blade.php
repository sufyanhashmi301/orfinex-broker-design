@extends('backend.setting.email_setting.index')
@section('title')
    {{ __('Google Mail Settings') }}
@endsection
@section('email-content')
    <div class="card">
        <div class="card-header noborder">
            <div class="flex-1">
                <h4 class="font-medium text-xl capitalize dark:text-white inline-block mb-1">{{ __('Gmail Configuration') }}</h4>
                <p class="card-text">
                    {{ __('Set up Gmail for basic email notifications') }}
                </p>
            </div>
            <span class="badge badge-danger text-danger bg-opacity-30 capitalize">
                {{ __('Limited Support') }}
            </span>
        </div>
        <div class="card-body p-6 pt-3">
            <div class="space-y-5">
                <div class="font-normal text-sm text-warning rounded-md border border-warning bg-warning bg-opacity-[14%] p-4">
                    <div class="flex space-x-3 rtl:space-x-reverse">
                        <iconify-icon class="text-xl flex-0 mr-1" icon="lucide:triangle-alert"></iconify-icon>
                        <p class="flex-1 text-sm">
                            {{ __('Gmail has strict sending limits and security restrictions that may affect delivery reliability. For business use, we recommend using a professional SMTP provider instead.') }}
                        </p>
                    </div>
                </div>
                <ul class="space-y-5">
                    <li class="bg-body dark:bg-body rounded-lg p-4">
                        <div class="flex">
                            <div class="w-6 h-6 flex items-center justify-center bg-primary bg-opacity-30 text-primary rounded-full mr-2">
                                {{ 1 }}
                            </div>
                            <div class="flex-1 text-sm">
                                <h6 class="text-sm font-semibold dark:text-white mb-1">
                                    {{ __('Enable IMAP Access') }}
                                </h6>
                                <p class="dark:text-slate-300 mb-1">
                                    {{ __('Go to Gmail settings and enable IMAP to allow third-party access') }}
                                </p>
                                <a href="https://sendgrid.com/en-us" class="inline-flex items-center text-sm font-medium dark:text-white">
                                    {{ __('View Gmail Settings') }}
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
                                    {{ __('Create App Password') }}
                                </h6>
                                <p class="dark:text-slate-300 mb-0">
                                    {{ __('Generate a secure app-specific password for this integration') }}
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
                                    {{ __('Configure Authentication') }}
                                </h6>
                                <p class="dark:text-slate-300 mb-0">
                                    {{ __('Enter your Gmail address and the generated app password') }}
                                </p>
                            </div>
                        </div>
                    </li>
                <ul>
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
