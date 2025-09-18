<!DOCTYPE html>
<html lang="zxx" dir="ltr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="keywords" content="@yield('meta_keywords',setting('site_title','global'))">
        <meta name="description" content="@yield('meta_description',setting('site_title','global'))">
        <meta property="og:image" content="{{ getFilteredPath(setting('link_thumbnail','global'), 'fallback/branding/thumbnail.png') }}">
        <link rel="canonical" href="{{ url()->current() }}"/>
        <link rel="shortcut icon" href="{{ getFilteredPath(setting('site_favicon','global'), 'fallback/branding/favicon.png') }}" type="image/x-icon"/>
        <link rel="icon" href="{{ getFilteredPath(setting('site_favicon','global'), 'fallback/branding/favicon.png') }}" type="image/x-icon"/>
        <title>{{ __('Client Fund Safety') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">


        <link rel="stylesheet" href="{{ asset('frontend/css/app.css') }}">

        <style>
            body {
                background: #f8fafc;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                line-height: 1.6;
                color: #334155;
                min-height: 100vh;
            }
            
            .simple-card {
                background: white;
                border-radius: 16px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                transition: all 0.2s ease;
            }
            
            .simple-card:hover {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                transform: translateY(-2px);
            }
            
            .primary-color {
                color: #3b82f6;
            }
            
            .primary-bg {
                background-color: #3b82f6;
            }
            
            .light-bg {
                background-color: #f1f5f9;
            }
            
            .success-bg {
                background-color: #f0fdf4;
                border-left: 3px solid #22c55e;
            }
            
            .warning-bg {
                background-color: #fffbeb;
                border-left: 3px solid #f59e0b;
            }
        </style>
    </head>
    <body class="font-inter dashcode-app">
        <div id="page-loader" style="display: none;">
            <div class="dot bg-primary"></div>
            <div class="dot bg-primary"></div>
            <div class="dot bg-primary"></div>
        </div>
        <!--Full Layout-->
        <main class="app-wrapper py-8">
            <div class="container max-w-6xl mx-auto px-4">
                <!-- Hero Header Section -->
                <div class="simple-card border-success-500 py-12 px-8 mb-8 text-center" style="border-top-width: 3px; border-style: solid;">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        {{ __('Your Funds Are Secure & Protected') }}
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                        {{ __('Banking with institutional-grade security and regulatory compliance') }}
                    </p>
                    
                    <!-- Feature Badges -->
                    <div class="flex flex-wrap items-center justify-center gap-3">
                        <div class="light-bg flex items-center gap-2 px-4 py-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24" class="text-success-600">
                                <path fill="currentColor" fill-rule="evenodd" d="M12 21a9 9 0 1 0 0-18a9 9 0 0 0 0 18m4.768-11.36a1 1 0 1 0-1.536-1.28l-3.598 4.317c-.347.416-.542.647-.697.788l-.006.006l-.007-.005c-.168-.127-.383-.339-.765-.722l-1.452-1.451a1 1 0 0 0-1.414 1.414l1.451 1.451l.041.041c.327.327.64.641.933.862c.327.248.756.48 1.305.456c.55-.025.956-.296 1.26-.572c.27-.247.555-.588.85-.943l.037-.044z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">{{ __('FDIC Insured Banking') }}</span>
                        </div>
                        
                        <div class="light-bg flex items-center gap-2 px-4 py-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24" class="text-success-600">
                                <path fill="currentColor" fill-rule="evenodd" d="M12 21a9 9 0 1 0 0-18a9 9 0 0 0 0 18m4.768-11.36a1 1 0 1 0-1.536-1.28l-3.598 4.317c-.347.416-.542.647-.697.788l-.006.006l-.007-.005c-.168-.127-.383-.339-.765-.722l-1.452-1.451a1 1 0 0 0-1.414 1.414l1.451 1.451l.041.041c.327.327.64.641.933.862c.327.248.756.48 1.305.456c.55-.025.956-.296 1.26-.572c.27-.247.555-.588.85-.943l.037-.044z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">{{ __('Regulatory Compliance') }}</span>
                        </div>

                        <div class="light-bg flex items-center gap-2 px-4 py-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24" class="text-success-600">
                                <path fill="currentColor" fill-rule="evenodd" d="M12 21a9 9 0 1 0 0-18a9 9 0 0 0 0 18m4.768-11.36a1 1 0 1 0-1.536-1.28l-3.598 4.317c-.347.416-.542.647-.697.788l-.006.006l-.007-.005c-.168-.127-.383-.339-.765-.722l-1.452-1.451a1 1 0 0 0-1.414 1.414l1.451 1.451l.041.041c.327.327.64.641.933.862c.327.248.756.48 1.305.456c.55-.025.956-.296 1.26-.572c.27-.247.555-.588.85-.943l.037-.044z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">{{ __('Segregated Client Funds') }}</span>
                        </div>

                        <div class="light-bg flex items-center gap-2 px-4 py-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24" class="text-success-600">
                                <path fill="currentColor" fill-rule="evenodd" d="M12 21a9 9 0 1 0 0-18a9 9 0 0 0 0 18m4.768-11.36a1 1 0 1 0-1.536-1.28l-3.598 4.317c-.347.416-.542.647-.697.788l-.006.006l-.007-.005c-.168-.127-.383-.339-.765-.722l-1.452-1.451a1 1 0 0 0-1.414 1.414l1.451 1.451l.041.041c.327.327.64.641.933.862c.327.248.756.48 1.305.456c.55-.025.956-.296 1.26-.572c.27-.247.555-.588.85-.943l.037-.044z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">{{ __('Bank-Grade Security') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Section -->
                <div class="simple-card p-8 mb-8 text-center">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        {{ __('Questions About Our Banking Security?') }}
                    </h1>
                    <p class="text-lg text-gray-600 mb-6 max-w-2xl mx-auto">
                        {{ __('Our team is available to address any concerns and provide additional verification of our banking processes. Your confidence in our security measures is our priority.') }}
                    </p>

                    <div class="flex flex-wrap items-center justify-center gap-3 mb-6">
                        <a href="javascript:void(0)" class="inline-flex items-center gap-2 light-bg hover:-translate-y-1 hover:shadow-lg duration-200 rounded-lg px-4 py-3 text-sm font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24c1.12.37 2.33.57 3.57.57c.55 0 1 .45 1 1V20c0 .55-.45 1-1 1c-9.39 0-17-7.61-17-17c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1c0 1.25.2 2.45.57 3.57c.11.35.03.74-.25 1.02z"/>
                            </svg>
                            {{ __('Call Banking Support') }}
                        </a>

                        <a href="javascript:void(0)" class="inline-flex items-center gap-2 light-bg hover:-translate-y-1 hover:shadow-lg duration-200 rounded-lg px-4 py-3 text-sm font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2m0 4l-8 5l-8-5V6l8 5l8-5z"/>
                            </svg>
                            {{ __('Email Banking Team') }}
                        </a>

                        <a href="javascript:void(0)" class="inline-flex items-center gap-2 light-bg hover:-translate-y-1 hover:shadow-lg duration-200 rounded-lg px-4 py-3 text-sm font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M12 3c5.5 0 10 3.58 10 8s-4.5 8-10 8c-1.24 0-2.43-.18-3.53-.5C5.55 21 2 21 2 21c2.33-2.33 2.7-3.9 2.75-4.5C3.05 15.07 2 13.13 2 11c0-4.42 4.5-8 10-8"/>
                            </svg>
                            {{ __('Live Chat Support') }}
                        </a>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <p class="text-sm text-gray-500">
                            {{ __('Banking support available Monday-Friday 8AM-8PM EST. Emergency wire verification available 24/7.') }}
                        </p>
                    </div>
                </div>

                <!-- Main Content Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Banking Partner Card -->
                    <div class="simple-card p-6">
                        <div class="flex items-center space-x-3 mb-5">
                            <div class="flex-none h-12 w-12 flex items-center justify-center bg-primary-500 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" class="text-white">
                                    <path fill="currentColor" d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10z"/>
                                </svg>
                            </div>
                            <h3 class="flex-1 text-xl font-bold text-gray-900 leading-tight">
                                {{ __('Our Banking Partner') }}
                            </h3>
                        </div>
                        <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                            {{ __('We partner with established, fully licensed and regulated US financial institutions operating under the strict oversight of federal banking regulators including the FDIC, OCC, and Federal Reserve System.') }}
                        </p>
                        
                        <div class="bg-primary-500 rounded-lg p-4 mb-4">
                            <h4 class="text-white font-semibold mb-2">{{ __('Why US Banks?') }}</h4>
                            <p class="text-white text-sm opacity-90 leading-relaxed">
                                {{ __("US banks provide comprehensive FDIC protection with up to $250,000 in coverage per account. As domestically regulated institutions, they follow strict US federal banking regulations and standards, ensuring the highest level of security and compliance for your funds.") }}
                            </p>
                        </div>
                        
                        <div class="success-bg rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">{{ __('Key Banking Protections:') }}</h4>
                            <ul class="space-y-2">
                                <li class="flex items-start gap-2 text-sm text-gray-700">
                                    <div class="w-1.5 h-1.5 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                    <span>{{ __('Full FDIC insurance coverage up to $250,000 per account') }}</span>
                                </li>
                                <li class="flex items-start gap-2 text-sm text-gray-700">
                                    <div class="w-1.5 h-1.5 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                    <span>{{ __('Client funds held in segregated accounts') }}</span>
                                </li>
                                <li class="flex items-start gap-2 text-sm text-gray-700">
                                    <div class="w-1.5 h-1.5 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                    <span>{{ __('Regular regulatory audits and reporting') }}</span>
                                </li>
                                <li class="flex items-start gap-2 text-sm text-gray-700">
                                    <div class="w-1.5 h-1.5 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                    <span>{{ __('Multi-layered fraud protection systems') }}</span>
                                </li>
                                <li class="flex items-start gap-2 text-sm text-gray-700">
                                    <div class="w-1.5 h-1.5 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                    <span>{{ __('Real-time transaction monitoring') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Security Measures Card -->
                    <div class="simple-card p-6">
                        <div class="flex items-center space-x-3 mb-5">
                            <div class="flex-none h-12 w-12 flex items-center justify-center bg-primary-500 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48" class="text-white">
                                    <path fill="currentColor" d="M24 4c-5.5 0-10 4.5-10 10v4h4v-4c0-3.3 2.7-6 6-6s6 2.7 6 6v4h4v-4c0-5.5-4.5-10-10-10"/>
                                    <path fill="currentColor" d="M36 44H12c-2.2 0-4-1.8-4-4V22c0-2.2 1.8-4 4-4h24c2.2 0 4 1.8 4 4v18c0 2.2-1.8 4-4 4"/>
                                    <circle cx="24" cy="31" r="3" fill="currentColor"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold leading-tight text-gray-900">
                                {{ __('Advanced Security Measures') }}
                            </h3>
                        </div>
                        <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                            {{ __('We partner with US banks that maintain the highest security standards, implementing comprehensive protection measures to safeguard your financial transactions and personal information.') }}
                        </p>
                        
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="light-bg text-center p-3 rounded-lg">
                                <h4 class="font-semibold text-gray-900 text-sm mb-1">{{ __('256-bit SSL') }}</h4>
                                <p class="text-xs text-gray-600">{{ __('Bank-level encryption') }}</p>
                            </div>
                            
                            <div class="light-bg text-center p-3 rounded-lg">
                                <h4 class="font-semibold text-gray-900 text-sm mb-1">{{ __('2FA Required') }}</h4>
                                <p class="text-xs text-gray-600">{{ __('Two-factor authentication') }}</p>
                            </div>
                            
                            <div class="light-bg text-center p-3 rounded-lg">
                                <h4 class="font-semibold text-gray-900 text-sm mb-1">{{ __('24/7 Monitoring') }}</h4>
                                <p class="text-xs text-gray-600">{{ __('Fraud detection systems') }}</p>
                            </div>
                            
                            <div class="light-bg text-center p-3 rounded-lg">
                                <h4 class="font-semibold text-gray-900 text-sm mb-1">{{ __('Compliance Team') }}</h4>
                                <p class="text-xs text-gray-600">{{ __('AML/KYC verification') }}</p>
                            </div>
                        </div>

                        <div class="warning-bg rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <div class="w-5 h-5 bg-orange-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="text-warning-600">
                                        <path fill="currentColor" d="M1 21h22L12 2L1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-warning-600 mb-1 text-sm">{{ __('Important Security Note') }}</h4>
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        {{ __('We will NEVER ask you to wire funds to personal accounts or request banking changes via email or phone. All wire instructions are provided through your secure client portal only.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Wire Transfer Process Card -->
                    <div class="simple-card p-6">
                        <div class="flex items-center space-x-3 mb-5">
                            <div class="flex-none h-12 w-12 flex items-center justify-center bg-primary-500 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512" class="text-white">
                                    <path fill="currentColor" d="M376 211H256V16L136 301h120v195z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold leading-tight text-gray-900">
                                {{ __('Secure Wire Transfer Process') }}
                            </h3>
                        </div>
                        <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                            {{ __('Our streamlined process ensures every transaction is verified and protected while maintaining the speed you need for trading opportunities.') }}
                        </p>

                        <div class="space-y-3">
                            <div class="light-bg rounded-lg p-4 flex items-center gap-3">
                                <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-primary-500 text-lg font-bold text-white">
                                    1
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm">{{ __('Initiate Request') }}</h4>
                                    <p class="text-xs text-gray-600">{{ __('Submit wire request through your secure client portal') }}</p>
                                </div>
                            </div>

                            <div class="light-bg rounded-lg p-4 flex items-center gap-3">
                                <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-primary-500 text-lg font-bold text-white">
                                    2
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm">{{ __('Verification') }}</h4>
                                    <p class="text-xs text-gray-600">{{ __('Our compliance team verifies your identity and request') }}</p>
                                </div>
                            </div>

                            <div class="light-bg rounded-lg p-4 flex items-center gap-3">
                                <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-primary-500 text-lg font-bold text-white">
                                    3
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm">{{ __('Secure Transfer') }}</h4>
                                    <p class="text-xs text-gray-600">{{ __('Funds transferred via encrypted, monitored channels') }}</p>
                                </div>
                            </div>

                            <div class="light-bg rounded-lg p-4 flex items-center gap-3">
                                <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-primary-500 text-lg font-bold text-white">
                                    4
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 text-sm">{{ __('Confirmation') }}</h4>
                                    <p class="text-xs text-gray-600">{{ __('Receive immediate confirmation and tracking details') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Support & Transparency Card -->
                    <div class="simple-card p-6">
                        <div class="flex items-center space-x-3 mb-5">
                            <div class="flex-none h-12 w-12 flex items-center justify-center bg-primary-500 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" class="text-white">
                                    <path fill="currentColor" fill-rule="evenodd" d="M3.378 5.082C3 5.62 3 7.22 3 10.417v1.574c0 5.638 4.239 8.375 6.899 9.536c.721.315 1.082.473 2.101.473V2c-.811 0-1.595.268-3.162.805L8.265 3c-3.007 1.03-4.51 1.545-4.887 2.082" clip-rule="evenodd"/>
                                    <path fill="currentColor" d="M21 11.991v-1.574c0-3.198 0-4.797-.378-5.335c-.377-.537-1.88-1.052-4.887-2.081l-.573-.196C13.595 2.268 12.812 2 12 2v20c1.02 0 1.38-.158 2.101-.473C16.761 20.365 21 17.63 21 11.991" opacity="0.5"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold leading-tight text-gray-900">
                                {{ __('Dedicated Support & Transparency') }}
                            </h3>
                        </div>
                        <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                            {{ __('Questions about banking? Our support team provides complete transparency about our banking processes and can verify any wire instructions in real-time.') }}
                        </p>
                        
                        <div class="bg-primary-500 rounded-lg p-4 mb-4">
                            <h4 class="text-white font-semibold mb-2">{{ __('Before You Wire - Always Verify') }}</h4>
                            <p class="text-white text-sm opacity-90 leading-relaxed">
                                {{ __('Call our dedicated banking support line to confirm wire instructions. We encourage this extra step to ensure your complete peace of mind and prevent any potential fraud.') }}
                            </p>
                        </div>
                        
                        <div class="success-bg rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">{{ __('What We Provide:') }}</h4>
                            <ul class="space-y-2">
                                <li class="flex items-start gap-2 text-sm text-gray-700">
                                    <div class="w-1.5 h-1.5 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                    <span>{{ __('Written confirmation of all banking details') }}</span>
                                </li>
                                <li class="flex items-start gap-2 text-sm text-gray-700">
                                    <div class="w-1.5 h-1.5 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                    <span>{{ __('Direct phone verification available') }}</span>
                                </li>
                                <li class="flex items-start gap-2 text-sm text-gray-700">
                                    <div class="w-1.5 h-1.5 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                    <span>{{ __('Real-time wire status updates') }}</span>
                                </li>
                                <li class="flex items-start gap-2 text-sm text-gray-700">
                                    <div class="w-1.5 h-1.5 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                    <span>{{ __('Complete transaction history and documentation') }}</span>
                                </li>
                                <li class="flex items-start gap-2 text-sm text-gray-700">
                                    <div class="w-1.5 h-1.5 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                    <span>{{ __('Dedicated relationship manager for larger accounts') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Banking Information Section -->
                <div class="simple-card p-8">
                    <div class="flex justify-center items-center space-x-3 mb-5">
                        <div class="flex-none h-12 w-12 flex items-center justify-center bg-primary-500 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" class="text-white">
                                <path fill="currentColor" d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold leading-tight text-gray-900">
                            {{ __('Official Banking Information') }}
                        </h2>
                    </div>

                    <div class="py-[18px] px-4 font-normal font-Inter text-sm rounded-md bg-primary-500 bg-opacity-[14%] text-primary-500 mb-4">
                        <div class="flex items-start gap-3">
                            <div class="w-5 h-5 bg-orange-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="text-primary-600">
                                    <path fill="currentColor" d="M1 21h22L12 2L1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-primary-600 mb-1 text-sm">{{ __('Banking Details Available After KYC') }}</h4>
                                <p class="text-sm text-gray-700 leading-relaxed">
                                    {{ __('Your personalized wire transfer details will be provided in your secure client portal once you complete the KYC verification process.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="light-bg rounded-lg p-4 flex flex-col items-center text-center gap-3">
                            <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-primary-500 text-lg font-bold text-white">
                                1
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 text-sm">{{ __('Complete KYC') }}</h4>
                                <p class="text-xs text-gray-600">{{ __('Submit required identification and verification documents') }}</p>
                            </div>
                        </div>

                        <div class="light-bg rounded-lg p-4 flex flex-col items-center text-center gap-3">
                            <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-primary-500 text-lg font-bold text-white">
                                2
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 text-sm">{{ __('Account Approval') }}</h4>
                                <p class="text-xs text-gray-600">{{ __('Our compliance team reviews and approves your application') }}</p>
                            </div>
                        </div>

                        <div class="light-bg rounded-lg p-4 flex flex-col items-center text-center gap-3">
                            <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-primary-500 text-lg font-bold text-white">
                                3
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 text-sm">{{ __('Receive Details') }}</h4>
                                <p class="text-xs text-gray-600">{{ __('Banking information appears in your secure client portal') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="success-bg rounded-md py-[18px] px-4 mb-4">
                        <div class="flex items-start gap-3">
                            <div class="w-5 h-5 bg-orange-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24" class="text-success-600">
                                    <g fill="none">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M16 8V7a4 4 0 0 0-4-4v0a4 4 0 0 0-4 4v1"/>
                                        <path fill="currentColor" fill-rule="evenodd" d="M3.879 7.879C3 8.757 3 10.172 3 13v1c0 3.771 0 5.657 1.172 6.828S7.229 22 11 22h2c3.771 0 5.657 0 6.828-1.172S21 17.771 21 14v-1c0-2.828 0-4.243-.879-5.121C19.243 7 17.828 7 15 7H9c-2.828 0-4.243 0-5.121.879M12 15a1 1 0 1 0 0-2a1 1 0 0 0 0 2m3-1a3 3 0 0 1-2 2.83V19h-2v-2.17A3.001 3.001 0 1 1 15 14" clip-rule="evenodd"/>
                                    </g>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-success-600 mb-1 text-sm">{{ __('Secure Portal Access') }}</h4>
                                <p class="text-sm text-gray-700 leading-relaxed">
                                Your <span class="font-medium">{{ __('personalized banking details') }}</span> including account numbers, routing information, and wire instructions will be securely stored in your client portal dashboard.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="warning-bg rounded-md py-[18px] px-4">
                        <div class="flex items-start gap-3">
                            <div class="w-5 h-5 bg-orange-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="text-warning-600">
                                    <path fill="currentColor" d="M1 21h22L12 2L1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-warning-600 mb-1 text-sm">{{ __('Security Reminder') }}</h4>
                                <p class="text-sm text-gray-700 leading-relaxed">
                                    {{ __('These banking details will be available in your secure client portal and can be verified by calling our support team. If you receive banking information from any other source, do not use it and contact us immediately.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        

    </body>
</html>
