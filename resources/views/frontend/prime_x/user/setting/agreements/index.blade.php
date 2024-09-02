@extends('frontend::user.setting.index')
@section('title')
    {{ __('Legal Agreements') }}
@endsection
@section('settings-content')
    <div class="card">
        <div class="card-body p-6">
            <div class="mb-4">
                <h4 class="card-title mb-2">{{ __('Legal Agreements') }}</h4>
                <p class="block font-normal text-sm text-slate-500">
                    {{ __("Stay informed and compliant; review all legal agreements linked to your profile.") }}
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                @if(setting('aml_policy_show','document_links',false))
                    <div class="border border-slate-100 dark:border-slate-700 rounded p-4">
                        <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-body text-3xl mb-4">
                            <img src="{{ asset('frontend/images/icon/shield.svg') }}" alt="">
                        </div>
                        <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                            AML Policy <br>
                            <span class="text-slate-400 text-sm font-normal">PDF</span>
                        </span>
                        <div class="mt-5">
                            <a href="{{setting('aml_policy_link','document_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                                <span class="mr-1">Read Now</span>
                                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon>
                            </a>
                        </div>
                    </div>
                @endif
                @if(setting('client_agreement_link','document_links',false))
                    <div class="border border-slate-100 dark:border-slate-700 rounded p-4">
                        <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-body text-3xl mb-4">
                            <img src="{{ asset('frontend/images/icon/like-shapes.svg') }}" alt="">
                        </div>
                        <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                            Client Agreement <br>
                            <span class="text-slate-400 text-sm font-normal">PDF</span>
                        </span>
                        <div class="mt-5">
                            <a href="{{setting('client_agreement_link','document_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                                <span class="mr-1">Read Now</span>
                                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon>
                            </a>
                        </div>
                    </div>
                @endif
                @if(setting('complaints_handling_policy_show','document_links',false))
                    <div class="border border-slate-100 dark:border-slate-700 rounded p-4">
                        <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-body text-3xl mb-4">
                            <img src="{{ asset('frontend/images/icon/document-text.svg') }}" alt="">
                        </div>
                        <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                            Complaints Handling Policy <br>
                            <span class="text-slate-400 text-sm font-normal">PDF</span>
                        </span>
                        <div class="mt-5">
                            <a href="{{setting('complaints_handling_policy_link','document_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                                <span class="mr-1">Read Now</span>
                                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon>
                            </a>
                        </div>
                    </div>
                @endif
                @if(setting('cookies_policy_show','document_links',false))
                    <div class="border border-slate-100 dark:border-slate-700 rounded p-4">
                        <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-body text-3xl mb-4">
                            <img src="{{ asset('frontend/images/icon/shield.svg') }}" alt="">
                        </div>
                        <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                            Cookies Policy <br>
                            <span class="text-slate-400 text-sm font-normal">PDF</span>
                        </span>
                        <div class="mt-5">
                            <a href="{{setting('cookies_policy_link','document_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                                <span class="mr-1">Read Now</span>
                                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon>
                            </a>
                        </div>
                    </div>
                @endif
                @if(setting('IB_partner_agreement_show','document_links',false))
                    <div class="border border-slate-100 dark:border-slate-700 rounded p-4">
                        <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-body text-3xl mb-4">
                            <img src="{{ asset('frontend/images/icon/shield.svg') }}" alt="">
                        </div>
                        <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                            IB Partner Agreement <br>
                            <span class="text-slate-400 text-sm font-normal">PDF</span>
                        </span>
                        <div class="mt-5">
                            <a href="{{setting('IB_partner_agreement_link','document_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                                <span class="mr-1">Read Now</span>
                                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon>
                            </a>
                        </div>
                    </div>
                @endif
                @if(setting('order_execution_policy_show','document_links',false))
                    <div class="border border-slate-100 dark:border-slate-700 rounded p-4">
                        <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-body text-3xl mb-4">
                            <img src="{{ asset('frontend/images/icon/document-copy.svg') }}" alt="">
                        </div>
                        <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                            Order Execution Policy <br>
                            <span class="text-slate-400 text-sm font-normal">PDF</span>
                        </span>
                        <div class="mt-5">
                            <a href="{{setting('order_execution_policy_link','document_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                                <span class="mr-1">Read Now</span>
                                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon>
                            </a>
                        </div>
                    </div>
                @endif
                @if(setting('privacy_policy_show','document_links',false))
                    <div class="border border-slate-100 dark:border-slate-700 rounded p-4">
                        <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-body text-3xl mb-4">
                            <img src="{{ asset('frontend/images/icon/document.svg') }}" alt="">
                        </div>
                        <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                            Privacy Policy <br>
                            <span class="text-slate-400 text-sm font-normal">PDF</span>
                        </span>
                        <div class="mt-5">
                            <a href="{{setting('privacy_policy_link','document_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                                <span class="mr-1">Read Now</span>
                                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon>
                            </a>
                        </div>
                    </div>
                @endif
                @if(setting('risk_disclosure_show','document_links',false))
                    <div class="border border-slate-100 dark:border-slate-700 rounded p-4">
                        <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-body text-3xl mb-4">
                            <img src="{{ asset('frontend/images/icon/warning-2.svg') }}" alt="">
                        </div>
                        <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                            Risk Disclosure <br>
                            <span class="text-slate-400 text-sm font-normal">PDF</span>
                        </span>
                        <div class="mt-5">
                            <a href="{{setting('risk_disclosure_link','document_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                                <span class="mr-1">Read Now</span>
                                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon>
                            </a>
                        </div>
                    </div>
                @endif
                @if(setting('US_clients_policy_show','document_links',false))
                    <div class="border border-slate-100 dark:border-slate-700 rounded p-4">
                        <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-body text-3xl mb-4">
                            <img src="{{ asset('frontend/images/icon/document-cloud.svg') }}" alt="">
                        </div>
                        <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                            US Clients Policy <br>
                            <span class="text-slate-400 text-sm font-normal">PDF</span>
                        </span>
                        <div class="mt-5">
                            <a href="{{setting('US_clients_policy_link','document_links','javascript:void(0);')}}" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                                <span class="mr-1">Read Now</span>
                                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
