@extends('backend.setting.website.index')
@section('title')
    {{ __('GDPR Compliance') }}
@endsection
@section('website-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="card basicTable_wrapper">
        <div class="card-body p-6">
            <div class="grid md:grid-cols-3 grid-cols-1 gap-5 mt-10">
                <div class="flex flex-col items-center text-center gap-3">
                    <div class="gdpr_landing_illustration_img data_collection"></div>
                    <p class="text-sm font-semibold dark:text-white">
                        {{ __('Data Collection') }}
                    </p>
                    <p class="text-sm text-slate-600 dark:text-slate-300">
                        {{ __('Capture customer data from various sources in compliance with GDPR regulations to ensure secure and transparent data handling.') }}
                    </p>
                    <a href="https://brokeret.com/gdpr" class="btn-link inline-flex items-center" target="_blank">
                        {{ __('Know more') }}
                    </a>
                </div>
                <div class="flex flex-col items-center text-center gap-3">
                    <div class="gdpr_landing_illustration_img data_processing"></div>
                    <p class="text-sm font-semibold dark:text-white">
                        {{ __('Data Processing') }}
                    </p>
                    <p class="text-sm text-slate-600 dark:text-slate-300">
                        {{ __('Manage and process customer personal data securely within the CRM while adhering to GDPR compliance standards.') }}
                    </p>
                    <a href="https://brokeret.com/gdpr" class="btn-link inline-flex items-center" target="_blank">
                        {{ __('Know more') }}
                    </a>
                </div>
                <div class="flex flex-col items-center text-center gap-3">
                    <div class="gdpr_landing_illustration_img data_request"></div>
                    <p class="text-sm font-semibold dark:text-white">
                        {{ __('Data Request') }}
                    </p>
                    <p class="text-sm text-slate-600 dark:text-slate-300">
                        {{ __('Handle and respond to customer data requests efficiently, ensuring GDPR compliance with easy access and control over personal information.') }}
                    </p>
                    <a href="https://brokeret.com/gdpr" class="btn-link inline-flex items-center" target="_blank">
                        {{ __('Know more') }}
                    </a>
                </div>
            </div>
            <div class="text-center mt-10">
                <p class="card-text mb-5">
                    {{ __('GDPR Settings allow you to manage and configure data privacy compliance for your clients. Use this feature to ensure your system adheres to data protection regulations while safeguarding user information.') }}
                </p>
                <a href="{{ route('admin.settings.gdpr') }}" class="btn btn-sm btn-dark inline-flex items-center justify-center">
                    {{ __('Enable GDPR Compliance') }}
                </a>
            </div>
        </div>
    </div>
@endsection
@section('style')
    <style>
        .gdpr_landing_illustration_img {
            background: url({{ asset('backend/images/gdpr_illustration.svg') }}) no-repeat;
            margin: 0 auto;
        }
        .data_collection {
            width: 223px;
            height: 204px;
            background-position: -14px -14px;
        }
        .data_processing{
            width: 195px;
            height: 206px;
            background-position: -19px -249px;
        }
        .data_request {
            width: 199px;
            height: 207px;
            background-position: -21px -487px;
        }
    </style>
@endsection
