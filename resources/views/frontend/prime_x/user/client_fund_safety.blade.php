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
                background-attachment: fixed !important;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                line-height: 1.6;
                color: #2c3e50;
                min-height: 100vh;
            }
            .bg-gradient {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
            .hover\:bg-primary-500:hover {
                background-color: #667eea;
            }

            .hover\:-translate-y-5:hover {
                transform: translateY(-0.5rem);
            }

            .hover\:shadow-xl:hover {
                --tw-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
                --tw-shadow-colored: 0 20px 25px -5px var(--tw-shadow-color), 0 8px 10px -6px var(--tw-shadow-color);
                box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000),
                    var(--tw-ring-shadow, 0 0 #0000),
                    var(--tw-shadow);
            }

        </style>
    </head>
    <body class="font-inter dashcode-app bg-gradient">
        <div id="page-loader" style="display: none;">
            <div class="dot bg-primary"></div>
            <div class="dot bg-primary"></div>
            <div class="dot bg-primary"></div>
        </div>
        <!--Full Layout-->
        <main class="app-wrapper py-5">
            <div class="container">
                <div class="bg-white rounded-xl shadow-lg border-success-500 py-12 px-10 mb-6" style="border-top-width: 4px; border-top-style: solid;">
                    <div class="flex flex-col items-center gap-3">
                        <h3 class="font-bold">{{ __('Your Funds Are Secure & Protected') }}</h3>
                        <p class="text-lg text-slate-500 dark:text-slate-400 mt-1">
                            {{ __('Banking with institutional-grade security and regulatory compliance') }}
                        </p>
                        <div class="flex flex-wrap items-center justify-center gap-4 mt-3">
                            <div class="flex items-center justify-center gap-2 p-4 px-6 bg-slate-100 border border-slate-200 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" class="text-success-600">
                                    <path fill="currentColor" fill-rule="evenodd" d="M12 21a9 9 0 1 0 0-18a9 9 0 0 0 0 18m4.768-11.36a1 1 0 1 0-1.536-1.28l-3.598 4.317c-.347.416-.542.647-.697.788l-.006.006l-.007-.005c-.168-.127-.383-.339-.765-.722l-1.452-1.451a1 1 0 0 0-1.414 1.414l1.451 1.451l.041.041c.327.327.64.641.933.862c.327.248.756.48 1.305.456c.55-.025.956-.296 1.26-.572c.27-.247.555-.588.85-.943l.037-.044z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ __('FDIC Insured Banking') }}</span>
                            </div>
                            
                            <div class="flex items-center justify-center gap-2 p-4 px-6 bg-slate-100 border border-slate-200 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" class="text-success-600">
                                    <path fill="currentColor" fill-rule="evenodd" d="M12 21a9 9 0 1 0 0-18a9 9 0 0 0 0 18m4.768-11.36a1 1 0 1 0-1.536-1.28l-3.598 4.317c-.347.416-.542.647-.697.788l-.006.006l-.007-.005c-.168-.127-.383-.339-.765-.722l-1.452-1.451a1 1 0 0 0-1.414 1.414l1.451 1.451l.041.041c.327.327.64.641.933.862c.327.248.756.48 1.305.456c.55-.025.956-.296 1.26-.572c.27-.247.555-.588.85-.943l.037-.044z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ __('Regulatory Compliance') }}</span>
                            </div>

                            <div class="flex items-center justify-center gap-2 p-4 px-6 bg-slate-100 border border-slate-200 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" class="text-success-600">
                                    <path fill="currentColor" fill-rule="evenodd" d="M12 21a9 9 0 1 0 0-18a9 9 0 0 0 0 18m4.768-11.36a1 1 0 1 0-1.536-1.28l-3.598 4.317c-.347.416-.542.647-.697.788l-.006.006l-.007-.005c-.168-.127-.383-.339-.765-.722l-1.452-1.451a1 1 0 0 0-1.414 1.414l1.451 1.451l.041.041c.327.327.64.641.933.862c.327.248.756.48 1.305.456c.55-.025.956-.296 1.26-.572c.27-.247.555-.588.85-.943l.037-.044z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ __('Segregated Client Funds') }}</span>
                            </div>

                            <div class="flex items-center justify-center gap-2 p-4 px-6 bg-slate-100 border border-slate-200 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" class="text-success-600">
                                    <path fill="currentColor" fill-rule="evenodd" d="M12 21a9 9 0 1 0 0-18a9 9 0 0 0 0 18m4.768-11.36a1 1 0 1 0-1.536-1.28l-3.598 4.317c-.347.416-.542.647-.697.788l-.006.006l-.007-.005c-.168-.127-.383-.339-.765-.722l-1.452-1.451a1 1 0 0 0-1.414 1.414l1.451 1.451l.041.041c.327.327.64.641.933.862c.327.248.756.48 1.305.456c.55-.025.956-.296 1.26-.572c.27-.247.555-.588.85-.943l.037-.044z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ __('Bank-Grade Security') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                    <div class="bg-white rounded-xl shadow-lg transition duration-300 hover:-translate-y-5 py-12 px-6 mb-6">
                        <div class="flex items-center gap-3 mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 32 32">
                                <g fill="none">
                                    <path fill="#636363" d="M26 10.028H6v17h20z"/>
                                    <path fill="#d3d3d3" d="M3 10.008v.97h1v15.76h3V13.69h1v13.047h3V13.69h10v13.337h3V13.69h1v13.307h3v-16.02h1v-.97l-13-8z"/>
                                    <path fill="#e6e6e6" d="m3.85 9.138l10.79-6.74c.84-.53 1.92-.53 2.76 0l10.79 6.74v1.89l-11.5-7.14c-.41-.25-.92-.25-1.33 0l-11.5 7.14v-1.89z"/>
                                    <path fill="#9b9b9b" d="M19.985 11.028a5 5 0 0 1-4.005 2a5 5 0 0 1-4.005-2H3c-.55 0-1-.45-1-1s.45-1 1-1h8.072a5.01 5.01 0 1 1 9.816 0H29c.55 0 1 .45 1 1s-.45 1-1 1zM2 14.028c0-.55.45-1 1-1h26c.55 0 1 .45 1 1s-.45 1-1 1H3c-.55 0-1-.45-1-1m0 15h27.99v-.93c0-.59-.48-1.07-1.07-1.07H29v-.93c0-.59-.48-1.07-1.07-1.07H4.04c-.59 0-1.07.48-1.07 1.07v.93h.1c-.59 0-1.07.48-1.07 1.07z"/>
                                    <path fill="#321b41" d="M17.21 9.538c.15-.19.23-.41.23-.66c0-.28-.07-.5-.2-.67c-.12-.17-.28-.29-.46-.38a3 3 0 0 0-.4-.147a6 6 0 0 1-.24-.073c-.18-.05-.32-.09-.41-.12a.6.6 0 0 1-.21-.12c-.01-.01-.02-.02-.02-.03a.23.23 0 0 1-.04-.14c0-.04 0-.1.04-.15c.05-.06.15-.11.37-.11c.15 0 .29.02.4.08c.08.03.14.07.2.13c.03.02.06.05.09.08c.16.16.44.18.62.03c.12-.09.17-.21.17-.35c0-.11-.03-.22-.11-.31c-.14-.19-.35-.33-.6-.43l-.036-.013a1 1 0 0 0-.134-.037v-.64c0-.24-.2-.45-.45-.45h-.07c-.25 0-.45.21-.45.45v.6c-.1.02-.2.06-.3.1c-.23.1-.41.23-.54.41c-.15.19-.22.4-.22.65c0 .28.07.5.21.67c.13.17.29.29.48.37c.076.037.169.07.27.105q.048.015.1.035q.135.045.3.09c.24.06.43.13.54.19c.09.05.13.12.13.21c0 .08 0 .28-.43.3c-.19 0-.36-.04-.5-.1q-.008-.007-.02-.01a1.2 1.2 0 0 1-.37-.28c-.19-.19-.42-.22-.63-.09c-.15.11-.22.24-.22.4c0 .11.02.2.08.28c.18.24.41.42.69.52c.14.06.28.1.43.12v.54c0 .24.2.44.45.44h.07c.25 0 .45-.2.45-.44v-.58q.09-.015.18-.06c.23-.09.42-.23.56-.41M13 25.028h6v-6.36c0-.35-.29-.64-.64-.64h-4.73c-.35 0-.63.28-.63.63z"/>
                                </g>
                            </svg>
                            <h5 class="font-bold">{{ __('Our Banking Partner: ZENUS Bank') }}</h5>
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-3">
                            {{ __('We partner with ZENUS Bank, a fully licensed and regulated financial institution based in Puerto Rico, operating under the strict oversight of the Office of the Commissioner of Financial Institutions of Puerto Rico (OCIF).') }}
                        </p>
                        <div class="bg-gradient rounded-xl p-6 mb-3">
                            <h5 class="text-white font-bold mb-5">{{ __('Why ZENUS Bank?') }}</h5>
                            <p class="text-sm text-white">
                                {{ __("ZENUS Bank provides the same FDIC-equivalent protection through Puerto Rico's deposit insurance, offering up to $250,000 in coverage per account. As a US territory, Puerto Rico banking follows US federal banking regulations and standards.") }}
                            </p>
                        </div>
                        <div class="bg-slate-100 rounded-xl border-primary-500 p-6" style="border-left-width: 4px; border-left-style: solid;">
                            <h5 class="text-xl font-bold mb-5">{{ __('Key Banking Protections:') }}</h5>
                            <ul class="space-y-3">
                                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2">
                                    <span class="flex-shrink-0 h-[6px] w-[6px] bg-slate-900 dark:bg-slate-400 rounded-full inline-block mt-2"></span>
                                    <span>{{ __('Full regulatory compliance with US banking standards') }}</span>
                                </li>

                                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2">
                                    <span class="flex-shrink-0 h-[6px] w-[6px] bg-slate-900 dark:bg-slate-400 rounded-full inline-block mt-2"></span>
                                    <span>{{ __('Client funds held in segregated accounts') }}</span>
                                </li>

                                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2">
                                    <span class="flex-shrink-0 h-[6px] w-[6px] bg-slate-900 dark:bg-slate-400 rounded-full inline-block mt-2"></span>
                                    <span>{{ __('Regular regulatory audits and reporting') }}</span>
                                </li>

                                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2">
                                    <span class="flex-shrink-0 h-[6px] w-[6px] bg-slate-900 dark:bg-slate-400 rounded-full inline-block mt-2"></span>
                                    <span>{{ __('Multi-layered fraud protection systems') }}</span>
                                </li>

                                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2">
                                    <span class="flex-shrink-0 h-[6px] w-[6px] bg-slate-900 dark:bg-slate-400 rounded-full inline-block mt-2"></span>
                                    <span>{{ __('Real-time transaction monitoring') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg transition duration-300 hover:-translate-y-5 py-12 px-6 mb-6">
                        <div class="flex items-center gap-3 mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 48 48">
                                <path fill="#424242" d="M24 4c-5.5 0-10 4.5-10 10v4h4v-4c0-3.3 2.7-6 6-6s6 2.7 6 6v4h4v-4c0-5.5-4.5-10-10-10"/>
                                <path fill="#fb8c00" d="M36 44H12c-2.2 0-4-1.8-4-4V22c0-2.2 1.8-4 4-4h24c2.2 0 4 1.8 4 4v18c0 2.2-1.8 4-4 4"/>
                                <circle cx="24" cy="31" r="3" fill="#c76e00"/>
                            </svg>
                            <h5 class="font-bold">{{ __('Advanced Security Measures') }}</h5>
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-3">
                            {{ __('We partner with ZENUS Bank, a fully licensed and regulated financial institution based in Puerto Rico, operating under the strict oversight of the Office of the Commissioner of Financial Institutions of Puerto Rico (OCIF).') }}
                        </p>
                        <div class="space-y-3">
                            <div class="text-center rounded-lg shadow-base p-3 py-4">
                                <h5 class="text-xl font-bold mb-3">{{ __('256-bit SSL') }}</h5>
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    {{ __('Bank-level encryption') }}
                                </p>
                            </div>
                            
                            <div class="text-center rounded-lg shadow-base p-3 py-4">
                                <h5 class="text-xl font-bold mb-3">{{ __('2FA Required') }}</h5>
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    {{ __('Two-factor authentication') }}
                                </p>
                            </div>

                            <div class="text-center rounded-lg shadow-base p-3 py-4">
                                <h5 class="text-xl font-bold mb-3">{{ __('24/7 Monitoring') }}</h5>
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    {{ __('Fraud detection systems') }}
                                </p>
                            </div>

                            <div class="text-center rounded-lg shadow-base p-3 py-4">
                                <h5 class="text-xl font-bold mb-3">{{ __('Compliance Team') }}</h5>
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    {{ __('AML/KYC verification') }}
                                </p>
                            </div>

                            <div class="bg-warning-500 bg-opacity-[14%] rounded-xl p-6">
                                <div class="flex items-center space-x-3 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <path fill="#ffab56" fill-rule="evenodd" d="M9.73 3.993a2.75 2.75 0 0 1 4.54 0l.432.632a76 76 0 0 1 6.944 12.563l.09.208a2.51 2.51 0 0 1-2.024 3.497a69.4 69.4 0 0 1-15.424 0a2.51 2.51 0 0 1-2.024-3.497l.09-.208A76 76 0 0 1 9.298 4.625zM13 9a1 1 0 1 1-2 0a1 1 0 0 1 2 0m-1 2.75a.75.75 0 0 1 .75.75v5a.75.75 0 1 1-1.5 0v-5a.75.75 0 0 1 .75-.75" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="flex-1 text-warning-500 font-Inter font-bold">
                                        {{ __('Important Security Note') }}
                                    </p>
                                </div>
                                <p class="text-sm">
                                    {{ __('We will NEVER ask you to wire funds to personal accounts or request banking changes via email or phone. All wire instructions are provided through your secure client portal only.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg transition duration-300 hover:-translate-y-5 py-12 px-6 mb-6">
                        <div class="flex items-center gap-3 mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 512 512">
                                <path fill="#ffab56" d="M376 211H256V16L136 301h120v195z"/>
                            </svg>
                            <h5 class="font-bold">{{ __('Secure Wire Transfer Process') }}</h5>
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-3">
                            {{ __('Our streamlined process ensures every transaction is verified and protected while maintaining the speed you need for trading opportunities.') }}
                        </p>

                        <div class="space-y-3">
                            <div class="bg-slate-100 rounded-lg text-center p-5">
                                <div class="h-8 w-8 rounded-full bg-primary-500 text-white text-xl font-bold flex items-center justify-center mb-3 mx-auto">
                                    1
                                </div>
                                <h5 class="text-lg font-bold mb-1">{{ __('Initiate Request') }}</h5>
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    {{ __('Submit wire request through your secure client portal') }}
                                </p>
                            </div>

                            <div class="bg-slate-100 rounded-lg text-center p-5">
                                <div class="h-8 w-8 rounded-full bg-primary-500 text-white text-xl font-bold flex items-center justify-center mb-3 mx-auto">
                                    2
                                </div>
                                <h5 class="text-lg font-bold mb-1">{{ __('Verification') }}</h5>
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    {{ __('Our compliance team verifies your identity and request') }}
                                </p>
                            </div>

                            <div class="bg-slate-100 rounded-lg text-center p-5">
                                <div class="h-8 w-8 rounded-full bg-primary-500 text-white text-xl font-bold flex items-center justify-center mb-3 mx-auto">
                                    3
                                </div>
                                <h5 class="text-lg font-bold mb-1">{{ __('Secure Transfer') }}</h5>
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    {{ __('Funds transferred via encrypted, monitored channels') }}
                                </p>
                            </div>

                            <div class="bg-slate-100 rounded-lg text-center p-5">
                                <div class="h-8 w-8 rounded-full bg-primary-500 text-white text-xl font-bold flex items-center justify-center mb-3 mx-auto">
                                    4
                                </div>
                                <h5 class="text-lg font-bold mb-1">{{ __('Confirmation') }}</h5>
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    {{ __('Receive immediate confirmation and tracking details') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg transition duration-300 hover:-translate-y-5 py-12 px-6 mb-6">
                        <div class="flex items-center gap-3 mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 24 24">
                                <path fill="#4669fa" fill-rule="evenodd" d="M3.378 5.082C3 5.62 3 7.22 3 10.417v1.574c0 5.638 4.239 8.375 6.899 9.536c.721.315 1.082.473 2.101.473V2c-.811 0-1.595.268-3.162.805L8.265 3c-3.007 1.03-4.51 1.545-4.887 2.082" clip-rule="evenodd"/>
                                <path fill="#4669fa" d="M21 11.991v-1.574c0-3.198 0-4.797-.378-5.335c-.377-.537-1.88-1.052-4.887-2.081l-.573-.196C13.595 2.268 12.812 2 12 2v20c1.02 0 1.38-.158 2.101-.473C16.761 20.365 21 17.63 21 11.991" opacity="0.5"/>
                            </svg>
                            <h5 class="font-bold">{{ __('Dedicated Support & Transparency') }}</h5>
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-3">
                            {{ __('Questions about banking? Our support team provides complete transparency about our banking processes and can verify any wire instructions in real-time.') }}
                        </p>
                        <div class="bg-gradient rounded-xl p-6 mb-3">
                            <h5 class="text-white font-bold mb-5">{{ __('Before You Wire - Always Verify') }}</h5>
                            <p class="text-sm text-white">
                                {{ __('Call our dedicated banking support line to confirm wire instructions. We encourage this extra step to ensure your complete peace of mind and prevent any potential fraud.') }}
                            </p>
                        </div>
                        <div class="bg-slate-100 rounded-xl border-primary-500 p-6" style="border-left-width: 4px; border-left-style: solid;">
                            <h5 class="text-xl font-bold mb-5">{{ __('What We Provide:') }}</h5>
                            <ul class="space-y-3">
                                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2">
                                    <span class="flex-shrink-0 h-[6px] w-[6px] bg-slate-900 dark:bg-slate-400 rounded-full inline-block mt-2"></span>
                                    <span>{{ __('Written confirmation of all banking details') }}</span>
                                </li>

                                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2">
                                    <span class="flex-shrink-0 h-[6px] w-[6px] bg-slate-900 dark:bg-slate-400 rounded-full inline-block mt-2"></span>
                                    <span>{{ __('Direct phone verification available') }}</span>
                                </li>

                                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2">
                                    <span class="flex-shrink-0 h-[6px] w-[6px] bg-slate-900 dark:bg-slate-400 rounded-full inline-block mt-2"></span>
                                    <span>{{ __('Real-time wire status updates') }}</span>
                                </li>

                                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2">
                                    <span class="flex-shrink-0 h-[6px] w-[6px] bg-slate-900 dark:bg-slate-400 rounded-full inline-block mt-2"></span>
                                    <span>{{ __('Complete transaction history and documentation') }}</span>
                                </li>

                                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2">
                                    <span class="flex-shrink-0 h-[6px] w-[6px] bg-slate-900 dark:bg-slate-400 rounded-full inline-block mt-2"></span>
                                    <span>{{ __('Dedicated relationship manager for larger accounts') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg py-12 px-10 mb-6">
                    <div class="flex items-center justify-center gap-3 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 32 32">
                            <g fill="none">
                                <path fill="#636363" d="M26 10.028H6v17h20z"></path>
                                <path fill="#d3d3d3" d="M3 10.008v.97h1v15.76h3V13.69h1v13.047h3V13.69h10v13.337h3V13.69h1v13.307h3v-16.02h1v-.97l-13-8z"></path>
                                <path fill="#e6e6e6" d="m3.85 9.138l10.79-6.74c.84-.53 1.92-.53 2.76 0l10.79 6.74v1.89l-11.5-7.14c-.41-.25-.92-.25-1.33 0l-11.5 7.14v-1.89z"></path>
                                <path fill="#9b9b9b" d="M19.985 11.028a5 5 0 0 1-4.005 2a5 5 0 0 1-4.005-2H3c-.55 0-1-.45-1-1s.45-1 1-1h8.072a5.01 5.01 0 1 1 9.816 0H29c.55 0 1 .45 1 1s-.45 1-1 1zM2 14.028c0-.55.45-1 1-1h26c.55 0 1 .45 1 1s-.45 1-1 1H3c-.55 0-1-.45-1-1m0 15h27.99v-.93c0-.59-.48-1.07-1.07-1.07H29v-.93c0-.59-.48-1.07-1.07-1.07H4.04c-.59 0-1.07.48-1.07 1.07v.93h.1c-.59 0-1.07.48-1.07 1.07z"></path>
                                <path fill="#321b41" d="M17.21 9.538c.15-.19.23-.41.23-.66c0-.28-.07-.5-.2-.67c-.12-.17-.28-.29-.46-.38a3 3 0 0 0-.4-.147a6 6 0 0 1-.24-.073c-.18-.05-.32-.09-.41-.12a.6.6 0 0 1-.21-.12c-.01-.01-.02-.02-.02-.03a.23.23 0 0 1-.04-.14c0-.04 0-.1.04-.15c.05-.06.15-.11.37-.11c.15 0 .29.02.4.08c.08.03.14.07.2.13c.03.02.06.05.09.08c.16.16.44.18.62.03c.12-.09.17-.21.17-.35c0-.11-.03-.22-.11-.31c-.14-.19-.35-.33-.6-.43l-.036-.013a1 1 0 0 0-.134-.037v-.64c0-.24-.2-.45-.45-.45h-.07c-.25 0-.45.21-.45.45v.6c-.1.02-.2.06-.3.1c-.23.1-.41.23-.54.41c-.15.19-.22.4-.22.65c0 .28.07.5.21.67c.13.17.29.29.48.37c.076.037.169.07.27.105q.048.015.1.035q.135.045.3.09c.24.06.43.13.54.19c.09.05.13.12.13.21c0 .08 0 .28-.43.3c-.19 0-.36-.04-.5-.1q-.008-.007-.02-.01a1.2 1.2 0 0 1-.37-.28c-.19-.19-.42-.22-.63-.09c-.15.11-.22.24-.22.4c0 .11.02.2.08.28c.18.24.41.42.69.52c.14.06.28.1.43.12v.54c0 .24.2.44.45.44h.07c.25 0 .45-.2.45-.44v-.58q.09-.015.18-.06c.23-.09.42-.23.56-.41M13 25.028h6v-6.36c0-.35-.29-.64-.64-.64h-4.73c-.35 0-.63.28-.63.63z"></path>
                            </g>
                        </svg>
                        <h3 class="text-2xl text-center font-bold">{{ __('Official Banking Information') }}</h3>
                    </div>

                    <div class="bg-slate-100 rounded-xl border-success-500 p-6 mb-3" style="border-left-width: 4px; border-left-style: solid;">
                        <h5 class="text-xl font-bold mb-5">{{ __('ZENUS Bank Wire Details (USD)') }}</h5>
                        <div class="grid grid-cols-3 gap-5">
                            <div>
                                <span class="font-bold">{{ __('Bank Name') }}</span>
                                <span>{{ __('ZENUS Bank') }}</span>
                            </div>
                            <div>
                                <span class="font-bold">{{ __('Bank Address') }}</span>
                                <span>{{ __('San Juan, Puerto Rico') }}</span>
                            </div>
                            <div>
                                <span class="font-bold">{{ __('Routing Number') }}</span>
                                <span>{{ __('[Your routing number]') }}</span>
                            </div>
                            <div>
                                <span class="font-bold">{{ __('Account Number') }}</span>
                                <span>{{ __('[Your account number]') }}</span>
                            </div>
                            <div>
                                <span class="font-bold">{{ __('Account Name') }}</span>
                                <span>{{ __('[Your company name]') }}</span>
                            </div>
                            <div>
                                <span class="font-bold">{{ __('Swift Code') }}</span>
                                <span>{{ __('[Your swift code]') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-warning-500 bg-opacity-[14%] rounded-xl p-6">
                        <div class="flex items-center space-x-3 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 48 48">
                                <path fill="#424242" d="M24 4c-5.5 0-10 4.5-10 10v4h4v-4c0-3.3 2.7-6 6-6s6 2.7 6 6v4h4v-4c0-5.5-4.5-10-10-10"></path>
                                <path fill="#fb8c00" d="M36 44H12c-2.2 0-4-1.8-4-4V22c0-2.2 1.8-4 4-4h24c2.2 0 4 1.8 4 4v18c0 2.2-1.8 4-4 4"></path>
                                <circle cx="24" cy="31" r="3" fill="#c76e00"></circle>
                            </svg>
                            <p class="flex-1 text-warning-500 font-Inter font-bold">
                                {{ __('Security Reminder') }}
                            </p>
                        </div>
                        <p class="text-sm">
                            <span class="font-bold">{{ __('These banking details are also available in your secure client portal and can be verified by calling our support team. ') }}</span>
                            <span>{{ __('If you receive banking information from any other source, do not use it and contact us immediately.') }}</span>
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg py-12 px-10">
                    <div class="flex flex-col items-center gap-3 mb-6">
                        <h3 class="text-2xl text-center font-bold">{{ __('Questions About Our Banking Security?') }}</h3>
                        <p class="text-sm text-center text-slate-500 dark:text-slate-400">
                            {{ __('Our team is available to address any concerns and provide additional verification of our banking processes. Your confidence in our security measures is our priority.') }}
                        </p>
                    </div>

                    <div class="flex items-center justify-center gap-4 mb-5">
                        <a href="javascript:void(0)" class="inline-flex justify-center items-center gap-2 bg-slate-100 rounded-full border border-slate-200 transition duration-300 hover:shadow-xl hover:-translate-y-1 hover:bg-primary-500 hover:text-white px-4 py-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24c1.12.37 2.33.57 3.57.57c.55 0 1 .45 1 1V20c0 .55-.45 1-1 1c-9.39 0-17-7.61-17-17c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1c0 1.25.2 2.45.57 3.57c.11.35.03.74-.25 1.02z"/>
                            </svg>
                            {{ __('Call Banking Support') }}
                        </a>

                        <a href="javascript:void(0)" class="inline-flex justify-center items-center gap-2 bg-slate-100 rounded-full border border-slate-200 transition duration-300 hover:shadow-xl hover:-translate-y-1 hover:bg-primary-500 hover:text-white px-4 py-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2m0 4l-8 5l-8-5V6l8 5l8-5z"/>
                            </svg>
                            {{ __('Email Banking Team') }}
                        </a>

                        <a href="javascript:void(0)" class="inline-flex justify-center items-center gap-2 bg-slate-100 rounded-full border border-slate-200 transition duration-300 hover:shadow-xl hover:-translate-y-1 hover:bg-primary-500 hover:text-white px-4 py-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M12 3c5.5 0 10 3.58 10 8s-4.5 8-10 8c-1.24 0-2.43-.18-3.53-.5C5.55 21 2 21 2 21c2.33-2.33 2.7-3.9 2.75-4.5C3.05 15.07 2 13.13 2 11c0-4.42 4.5-8 10-8"/>
                            </svg>
                            {{ __('Live Chat Support') }}
                        </a>
                    </div>

                    <div class="border-t border-slate-200 pt-6">
                        <p class="text-sm text-center text-slate-500 dark:text-slate-400">
                            {{ __('Banking support available Monday-Friday 8AM-8PM EST. Emergency wire verification available 24/7.') }}
                        </p>
                    </div>
                </div>
            </div>
        </main>
        

    </body>
</html>
