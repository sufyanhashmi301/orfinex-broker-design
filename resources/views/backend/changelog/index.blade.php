@extends('backend.setting.system.index')
@section('title')
    {{ __('Changelog') }}
@endsection
@section('system-content')
    <div class="grid grid-cols-12 gap-5">
        <div class="lg:col-span-8 col-span-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __("Version's") }}</h4>
                </div>
                <div class="card-body p-6">
                    <div class="accordion accordion-flush space-y-3" id="accordionFlushExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header bg-slate-50 text-base font-medium rounded-lg px-4 py-3">
                                <button class="accordion-button collapsed flex items-center justify-between w-full" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                    <span>
                                        {{ __('Version 3.0') }}
                                        <span class="font-semibold text-xs text-slate-400">{{ __('- Feature Expansion & New Trading Tools') }}</span>
                                    </span>
                                    <iconify-icon icon="lucide:chevron-down"></iconify-icon>
                                </button>
                            </h2>
                            <div id="flush-collapseOne" class="accordion-collapse collapse border border-slate-100 border-t-0" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body p-4">
                                    <ul class="space-y-3">
                                        <li class="flex items-center space-x-3 text-xs text-slate-600 dark:text-slate-300">
                                            <span class="h-2 w-2 bg-primary-500 rounded-full"></span>
                                            <span>{{ __('Added X9Trader Trading Platform: Launched the X9Trader platform, offering enhanced trading capabilities and tools for clients.') }}</span>
                                        </li>
                                        <li class="flex items-center space-x-3 text-xs text-slate-600 dark:text-slate-300">
                                            <span class="h-2 w-2 bg-primary-500 rounded-full"></span>
                                            <span>{{ __('New Tools and Modules:') }}</span>
                                        </li>
                                        <li class="flex items-center space-x-3 text-xs text-slate-600 dark:text-slate-300">
                                            <span class="h-2 w-2 bg-primary-500 rounded-full"></span>
                                            <span>{{ __('Discount Coupon System: Enabled brokers to create and distribute discount coupons for various account levels and features.') }}</span>
                                        </li>
                                        <li class="flex items-center space-x-3 text-xs text-slate-600 dark:text-slate-300">
                                            <span class="h-2 w-2 bg-primary-500 rounded-full"></span>
                                            <span>{{ __('Trial Accounts: Introduced trial accounts to provide clients with the ability to explore the platform’s functionalities risk-free.') }}</span>
                                        </li>
                                        <li class="flex items-center space-x-3 text-xs text-slate-600 dark:text-slate-300">
                                            <span class="h-2 w-2 bg-primary-500 rounded-full"></span>
                                            <span>{{ __('Risk Management & Security Enhancements:') }}</span>
                                        </li>
                                        <li class="flex items-center space-x-3 text-xs text-slate-600 dark:text-slate-300">
                                            <span class="h-2 w-2 bg-primary-500 rounded-full"></span>
                                            <span>{{ __('Added advanced Risk Tools to monitor and manage trading behaviors.') }}</span>
                                        </li>
                                        <li class="flex items-center space-x-3 text-xs text-slate-600 dark:text-slate-300">
                                            <span class="h-2 w-2 bg-primary-500 rounded-full"></span>
                                            <span>{{ __('Fraud Protection Tools implemented to secure the platform against suspicious activities and safeguard clients’ data.') }}</span>
                                        </li>
                                        <li class="flex items-center space-x-3 text-xs text-slate-600 dark:text-slate-300">
                                            <span class="h-2 w-2 bg-primary-500 rounded-full"></span>
                                            <span>{{ __('User Interface Improvements: Further polished the client dashboard for streamlined navigation and better access to trading features and reports.') }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header bg-slate-50 text-base font-medium rounded-lg px-4 py-3">
                                <button class="accordion-button collapsed flex items-center justify-between w-full" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                    <span>
                                        {{ __('Version 2.0') }}
                                        <span class="font-semibold text-xs text-slate-400">{{ __('- Complete Infrastructure Overhaul') }}</span>
                                    </span>
                                    <iconify-icon icon="lucide:chevron-down"></iconify-icon>
                                </button>
                            </h2>
                            <div id="flush-collapseTwo" class="accordion-collapse collapse border border-slate-100 border-t-0" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body p-4">
                                    <ul class="space-y-3">
                                        <li class="flex items-center space-x-3 text-xs text-slate-600 dark:text-slate-300">
                                            <span class="h-2 w-2 bg-primary-500 rounded-full"></span>
                                            <span>{{ __('Revamped Infrastructure: Migrated to a modern, scalable framework to enhance performance and reliability.') }}</span>
                                        </li>
                                        <li class="flex items-center space-x-3 text-xs text-slate-600 dark:text-slate-300">
                                            <span class="h-2 w-2 bg-primary-500 rounded-full"></span>
                                            <span>{{ __('Enhanced User Interface: Updated the client area for a cleaner and more intuitive user experience.') }}</span>
                                        </li>
                                        <li class="flex items-center space-x-3 text-xs text-slate-600 dark:text-slate-300">
                                            <span class="h-2 w-2 bg-primary-500 rounded-full"></span>
                                            <span>{{ __('New Functionalities Introduced:') }}</span>
                                        </li>
                                        <li class="flex items-center space-x-3 text-xs text-slate-600 dark:text-slate-300">
                                            <span class="h-2 w-2 bg-primary-500 rounded-full"></span>
                                            <span>{{ __('Multi-language support to cater to a diverse clientele.') }}</span>
                                        </li>
                                        <li class="flex items-center space-x-3 text-xs text-slate-600 dark:text-slate-300">
                                            <span class="h-2 w-2 bg-primary-500 rounded-full"></span>
                                            <span>{{ __('Improved KYC and compliance process for better user onboarding.') }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header bg-slate-50 text-base font-medium rounded-lg px-4 py-3">
                                <button class="accordion-button collapsed flex items-center justify-between w-full" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                    <span>
                                        {{ __('Version 1.0') }}
                                        <span class="font-semibold text-xs text-slate-400">{{ __('- Initial Release') }}</span>
                                    </span>
                                    <iconify-icon icon="lucide:chevron-down"></iconify-icon>
                                </button>
                            </h2>
                            <div id="flush-collapseThree" class="accordion-collapse collapse border border-slate-100 border-t-0" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body p-4">
                                    <ul class="space-y-3">
                                        <li class="flex items-center space-x-3 text-xs text-slate-600 dark:text-slate-300">
                                            <span class="h-2 w-2 bg-primary-500 rounded-full"></span>
                                            <span>{{ __('Launched the initial version of the Prop Firm platform.') }}</span>
                                        </li>
                                        <li class="flex items-center space-x-3 text-xs text-slate-600 dark:text-slate-300">
                                            <span class="h-2 w-2 bg-primary-500 rounded-full"></span>
                                            <span>{{ __('Infrastructure built on legacy technology.') }}</span>
                                        </li>
                                        <li class="flex items-center space-x-3 text-xs text-slate-600 dark:text-slate-300">
                                            <span class="h-2 w-2 bg-primary-500 rounded-full"></span>
                                            <span>{{ __('Core features included account creation, basic trading functionalities, and support ticket management.') }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-4 col-span-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('Changelog') }}</h4>
                </div>
                <div class="card-body p-6">
                    <h5 class="text-xs font-medium">{{ __('Version History') }}</h5>
                    <ul class="space-y-3 mt-6 divide-y dark:divide-slate-700 divide-slate-100">
                        <li class="flex justify-between items-center text-xs text-slate-600 dark:text-slate-300 pt-3">
                            <span>Version 3.0 </span>
                            <span>15 September 2024</span>
                        </li>
                        <li class="flex justify-between items-center text-xs text-slate-600 dark:text-slate-300 pt-3">
                            <span>Version 2.0 </span>
                            <span>28 October 2023</span>
                        </li>
                        <li class="flex justify-between items-center text-xs text-slate-600 dark:text-slate-300 pt-3">
                            <span>Version 1.0 </span>
                            <span>1 February 2022</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
