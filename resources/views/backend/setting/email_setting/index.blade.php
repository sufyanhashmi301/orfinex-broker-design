@extends('backend.setting.communication.index')
@section('title')
    {{ __('Email Setting') }}
@endsection
@section('style')
    <style>
        .nav-pills .nav-link.active {
            background: transparent;
            border: 1px solid;
            border-color: rgba({{ implode(' ', getColorFromSettings('primary_color')) }});
        }
    </style>
@endsection
@section('communication-content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="javascript:;" class="btn btn-sm btn-outline-dark inline-flex items-center" type="button" data-bs-toggle="modal" data-bs-target="#mailConnection">
                {{ __('Connection Check') }}
            </a>
        </div>
    </div>
    <div class="card p-2 mb-5">
        <ul class="nav nav-pills flex flex-wrap sm:flex-nowrap items-center justify-stretch list-none pl-0 menu-open gap-3">
            <li class="nav-item w-full">
                <a href="{{ route('admin.settings.mail') }}" class="nav-link flex flex-col items-center justify-center font-medium font-Inter text-base text-center leading-tight capitalize rounded-md p-5 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.mail') }}">
                    <img src="https://cdn.brokeret.com/crm-assets/admin/mail/smtp.webp" class="h-20" alt="">
                </a>
            </li>
            <li class="nav-item w-full">
                <a href="{{ route('admin.settings.googleMail') }}" class="nav-link flex flex-col items-center justify-center font-medium font-Inter text-base text-center leading-tight capitalize rounded-md p-5 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.googleMail') }}">
                    <img src="https://cdn.brokeret.com/crm-assets/admin/mail/gmail.webp" class="h-20" alt="">
                </a>
            </li>
            <li class="nav-item w-full">
                <a href="{{ route('admin.settings.sendGrid') }}" class="nav-link flex flex-col items-center justify-center font-medium font-Inter text-base text-center leading-tight capitalize rounded-md p-5 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.sendGrid') }}">
                    <img src="https://cdn.brokeret.com/crm-assets/admin/mail/sendgrid.webp" class="h-20" alt="">
                </a>
            </li>
            <li class="nav-item w-full">
                <a href="{{ route('admin.settings.ses') }}" class="nav-link flex flex-col items-center justify-center font-medium font-Inter text-base text-center leading-tight capitalize rounded-md p-5 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ isActive('admin.settings.ses') }}">
                    <img src="https://cdn.brokeret.com/crm-assets/admin/mail/awsses.webp" class="h-20" alt="">
                </a>
            </li>
        </ul>
    </div>
    @yield('email-content')


    <!-- Modal for mail settings -->
    @include('backend.setting.email_setting.modal.__mail_settings')

    <!-- Modal for connection check -->
    @include('backend.setting.email_setting.modal.__connection_check')

@endsection
