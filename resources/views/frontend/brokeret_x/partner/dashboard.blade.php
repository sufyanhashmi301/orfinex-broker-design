@extends('frontend::layouts.partner')
@section('title')
    {{ __('Partner Dashboard') }}
@endsection
@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <div class="col-span-12 xl:col-span-8">
            <div class="h-full rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                            {{ __('Overview') }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Track your registrations, referrals, lot size, and commissions.') }}
                        </p>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3 sm:gap-5">
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                        <p class="text-theme-sm text-gray-500 dark:text-gray-400">
                            {{ __('Referrals') }}
                        </p>

                        <div class="mt-3 flex items-end justify-between">
                            <div>
                                <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                                    {{ $dataCount['monthly_referrals'] }}
                                </h4>
                            </div>

                            <div class="flex items-center gap-1">
                                <span class="text-theme-xs text-gray-500 dark:text-gray-400">
                                    {{ __('this month') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                        <p class="text-theme-sm text-gray-500 dark:text-gray-400">
                            {{ __('Lot size') }}
                        </p>

                        <div class="mt-3 flex items-end justify-between">
                            <div>
                                <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                                    {{ $dataCount['current_month_lots'] }}
                                </h4>
                            </div>

                            <div class="flex items-center gap-1">
                                <span class="text-theme-xs text-gray-500 dark:text-gray-400">
                                    {{ __('this month') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                        <p class="text-theme-sm text-gray-500 dark:text-gray-400">
                            {{ __('Commission') }}
                        </p>

                        <div class="mt-3 flex items-end justify-between">
                            <div>
                                <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                                    {{ $dataCount['current_month_ib_bonus'] }} {{$currency}}
                                </h4>
                            </div>

                            <div class="flex items-center gap-1">
                                <span class="text-theme-xs text-gray-500 dark:text-gray-400">
                                    {{ __('this month') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                        <p class="text-theme-sm text-gray-500 dark:text-gray-400">
                            {{ __('Referrals') }}
                        </p>

                        <div class="mt-3 flex items-end justify-between">
                            <div>
                                <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                                    {{ $dataCount['total_referrals'] }}
                                </h4>
                            </div>

                            <div class="flex items-center gap-1">
                                <span class="text-theme-xs text-gray-500 dark:text-gray-400">
                                    {{ __('total referrals') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                        <p class="text-theme-sm text-gray-500 dark:text-gray-400">
                            {{ __('Lot size') }}
                        </p>

                        <div class="mt-3 flex items-end justify-between">
                            <div>
                                <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                                    {{ $dataCount['total_lots'] }}
                                </h4>
                            </div>

                            <div class="flex items-center gap-1">
                                <span class="text-theme-xs text-gray-500 dark:text-gray-400">
                                    {{ __('total lot size') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                        <p class="text-theme-sm text-gray-500 dark:text-gray-400">
                            {{ __('Commission') }}
                        </p>

                        <div class="mt-3 flex items-end justify-between">
                            <div>
                                <h4 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                                    {{ $dataCount['total_ib_bonus'] }} {{$currency}}
                                </h4>
                            </div>

                            <div class="flex items-center gap-1">
                                <span class="text-theme-xs text-gray-500 dark:text-gray-400">
                                    {{ __('total commission') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-12 xl:col-span-4" x-data="partnerDashboard()">
            <div class="h-full rounded-2xl border border-gray-200 bg-gray-100 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="shadow-default rounded-2xl bg-white px-5 pb-11 pt-5 dark:bg-gray-900 sm:px-6 sm:pt-6">
                    <div class="flex items-center justify-between gap-2">
                        <div>
                            <h3 class="text-theme-sm font-semibold text-gray-800 dark:text-white/90">
                                {{ __('Vault ID: :id',['id'=>$account->wallet_id]) }}
                            </h3>
                        </div>
                        <div class="flex space-x-2 sm:justify-end items-center">
                            <button 
                                type="button"
                                @click="copyText('{{ $account->wallet_id }}', $event.target)" 
                                class="text-theme-sm shadow-theme-xs inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                                <i data-lucide="copy" class="hidden md:block h-4"></i>
                                {{ __('Copy ID') }}
                            </button>
                            <button 
                                type="button"
                                @click="copyText('{{ $getReferral->link }}', $event.target)" 
                                class="text-theme-sm shadow-theme-xs inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                                <i data-lucide="copy" class="hidden md:block h-4"></i>
                                {{ __('Copy Link') }}
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-6">
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Your Current Balance') }}
                            </span>
                            <h4 class="text-title-xs sm:text-title-sm font-bold text-gray-800 dark:text-white/90">
                                {{ $affiliateBalance }} {{$currency}}
                            </h4>
                        </div>
                        <a href="{{route('user.withdraw.view')}}" class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600">
                            <i data-lucide="upload" class="h-4"></i>
                            {{ __('Withdraw') }}
                        </a>
                    </div>
                </div>

                <div class="px-6 py-3.5 sm:gap-8 sm:py-5" x-data="partnerDashboard()">
                    <div class="flex items-center justify-between gap-5 mb-5">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ __('Signup') }}</h3>
                        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center">
                            <div x-data="{openDropDown: false}" class="dropdown relative">
                                <button 
                                    type="button" 
                                    @click="openDropDown = !openDropDown"
                                    class="flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]"
                                >
                                    <i data-lucide="share-2" class="h-4"></i>
                                    {{ __('Share') }}
                                </button>
                                <ul x-show="openDropDown" @click.outside="openDropDown = false" class="shadow-theme-lg dark:bg-gray-dark absolute top-full right-0 z-40 w-max space-y-1 rounded-2xl border border-gray-200 bg-white p-2 dark:border-gray-800 a2a_kit a2a_default_style" data-a2a-url="{{$getReferral->link}}">
                                    <li>
                                        <a class="a2a_button_email text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                            <span class="flex items-center gap-2">
                                                <img src="{{ asset('frontend/images/logo/email-item.png') }}" border="0" alt="Facebook" width="14" height="14">
                                                {{ __('Email') }}
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="a2a_button_sms text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                            <span class="flex items-center gap-2">
                                                <img src="{{ asset('frontend/images/logo/sms-item.png') }}" border="0" alt="Facebook" width="14" height="14">
                                                {{ __('SMS') }}
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="a2a_button_whatsapp text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                            <span class="flex items-center gap-2">
                                                <img src="{{ asset('frontend/images/logo/whatsapp-item.png') }}" border="0" alt="Facebook" width="14" height="14">
                                                {{ __('Whatsapp') }}
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="a2a_button_facebook text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                            <span class="flex items-center gap-2">
                                                <img src="{{ asset('frontend/images/logo/facebook-item.png') }}" border="0" alt="Facebook" width="14" height="14">
                                                {{ __('Facebook') }}
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="a2a_button_twitter text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                            <span class="flex items-center gap-2">
                                                <img src="{{ asset('frontend/images/logo/twitter-item.png') }}" border="0" alt="Facebook" width="14" height="14">
                                                {{ __('Twitter') }}
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <button type="button" class="flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                                <i data-lucide="mail-plus" class="h-4"></i>
                                {{ __('Invite') }}
                            </button>
                        </div>
                    </div>
                    <div class="input-area">
                        <div class="relative">
                            <input 
                                type="text" 
                                id="referral-input" 
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-3 pr-[90px] pl-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                value="{{ $getReferral->link }}" 
                                readonly>
                            <button type="button" 
                                @click="copyText('{{ $getReferral->link }}', $event.target)"
                                class="absolute top-1/2 right-0 inline-flex -translate-y-1/2 cursor-pointer items-center gap-1 border-l border-gray-200 py-3 pr-3 pl-3.5 text-sm font-medium text-gray-700 dark:border-gray-800 dark:text-gray-400">
                                <i data-lucide="copy" class="h-4"></i>
                                {{ __('Copy') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-5 mb-6">
        <article class="flex items-center gap-5 rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/3">
            <div class="inline-flex h-16 w-16 items-center justify-center rounded-xl bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-white/90">
                <i data-lucide="banknote-arrow-down" class="h-7 w-7"></i>
            </div>
            <div>
                <h3 class="text-2xl font-semibold text-gray-800 dark:text-white/90">
                    {{ $dataCount['total_deposit'] }} {{$currency}}
                </h3>
                <p class="flex items-center gap-3 text-gray-500 dark:text-gray-400">
                    {{ __('Net Deposit') }}
                </p>
            </div>
        </article>
        <article class="flex items-center gap-5 rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/3">
            <div class="inline-flex h-16 w-16 items-center justify-center rounded-xl bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-white/90">
                <i data-lucide="banknote-arrow-up" class="h-7 w-7"></i>
            </div>
            <div>
                <h3 class="text-2xl font-semibold text-gray-800 dark:text-white/90">
                    {{ $dataCount['total_withdraw'] }} {{$currency}}
                </h3>
                <p class="flex items-center gap-3 text-gray-500 dark:text-gray-400">
                    {{ __('Net Withdrawal') }}
                </p>
            </div>
        </article>
        <article class="flex items-center gap-5 rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/3">
            <div class="inline-flex h-16 w-16 items-center justify-center rounded-xl bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-white/90">
                <i data-lucide="gift" class="h-7 w-7"></i>
            </div>
            <div>
                <h3 class="text-2xl font-semibold text-gray-800 dark:text-white/90">
                    {{ $dataCount['net_rebate'] }} {{$currency}}
                </h3>
                <p class="flex items-center gap-3 text-gray-500 dark:text-gray-400">
                    {{ __('Net Rebate') }}
                </p>
            </div>
        </article>
        <article class="flex items-center gap-5 rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/3">
            <div class="inline-flex h-16 w-16 items-center justify-center rounded-xl bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-white/90">
                <i data-lucide="arrow-left-right" class="h-7 w-7"></i>
            </div>
            <div>
                <h3 class="text-2xl font-semibold text-gray-800 dark:text-white/90">
                    {{ $dataCount['net_referrals_volume'] }} {{$currency}}
                </h3>
                <p class="flex items-center gap-3 text-gray-500 dark:text-gray-400">
                    {{ __('Net Volume') }}
                </p>
            </div>
        </article>
    </div>

    <div x-data="partnerDashboard()" x-init="initChart()" class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/3">
        <div class="relative">
            <div x-ref="chartContainer" id="registredActiveCleints" style="opacity: 0.125;"></div>
            <div class="flex flex-col items-center justify-center text-center absolute h-full top-0 bottom-0 left-0 right-0 gap-3 p-5">
                <i data-lucide="info" class="w-6 h-6 text-gray-400 dark:text-gray-500"></i>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __("We'll show your partner revenue graph here once there is enough data") }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
@section('style')
    <style>
        .a2a_default_style:not(.a2a_flex_style) a {
            float: none !important;
            line-height: 16px;
            padding: 8px 16px !important;
        }
    </style>
@endsection
@section('script')
    <script async src="https://static.addtoany.com/menu/page.js"></script>
    <script>
        // Make function globally available immediately
        window.partnerDashboard = function() {
            return {
                initChart() {
                    const isDark = document.documentElement.classList.contains('dark');
                    let options = {
                        chart: {
                            height: 450,
                            type: "area",
                            toolbar: {
                                show: false
                            }
                        },
                        series: [
                            {
                                name: "{{ __('Registration') }}",
                                type: "area",
                                data: [44, 55, 41, 67, 22, 43, 21, 41, 56, 27, 43]
                            },
                            {
                                name: "{{ __('Active Clients') }}",
                                type: "line",
                                data: [30, 25, 36, 30, 45, 35, 64, 52, 59, 36, 39]
                            }
                        ],
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            curve: "smooth",
                            width: 2
                        },
                        colors: ["#5EC26A", "#E97B35"],
                        tooltip: {
                            theme: "dark"
                        },
                        legend: {
                            show: true,
                            position: "top",
                            horizontalAlign: "right",
                            fontSize: "12px",
                            fontFamily: "Outfit, sans-serif",
                            offsetY: -60,
                            markers: {
                                width: 8,
                                height: 8,
                                offsetY: -1,
                                offsetX: -5,
                                radius: 12
                            },
                            labels: {
                                colors: isDark ? "#CBD5E1" : "#475569"
                            },
                            itemMargin: {
                                horizontal: 18,
                                vertical: 0
                            }
                        },
                        title: {
                            text: "{{ __('Revenue Report') }}",
                            align: "left",
                            offsetX: 0,
                            offsetY: 13,
                            floating: false,
                            style: {
                                fontSize: "20px",
                                fontWeight: "500",
                                fontFamily: "Outfit, sans-serif",
                                color: isDark ? "#fff" : "#0f172a"
                            }
                        },
                        subtitle: {
                            text: "{{ __('Check all your Total Registration & Active Clients') }}",
                            align: "left",
                            offsetX: 0,
                            offsetY: 42,
                            floating: false,
                            style: {
                                fontSize: '16px',
                                fontWeight: 'normal',
                                fontFamily: undefined,
                                color: '#9699a2'
                            },
                        },
                        grid: {
                            show: true,
                            borderColor: isDark ? "#334155" : "#e2e8f0",
                            strokeDashArray: 10,
                            position: "back"
                        },
                        fill: {
                            type: ['gradient', 'solid'],
                            colors: ["#5EC26A", "#E97B35"],
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.4,
                                opacityTo: 0.5,
                                stops: [50, 100, 0]
                            }
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: isDark ? "#CBD5E1" : "#475569",
                                    fontFamily: "Outfit, sans-serif"
                                }
                            }
                        },
                        xaxis: {
                            categories: [
                                "{{ __('Jan') }}", "{{ __('Feb') }}", "{{ __('Mar') }}", "{{ __('Apr') }}", "{{ __('May') }}", "{{ __('Jun') }}",
                                "{{ __('Jul') }}", "{{ __('Aug') }}", "{{ __('Sep') }}", "{{ __('Oct') }}", "{{ __('Nov') }}", "{{ __('Dec') }}"
                            ],
                            labels: {
                                style: {
                                    colors: isDark ? "#CBD5E1" : "#475569",
                                    fontFamily: "Outfit, sans-serif"
                                }
                            },
                            axisBorder: {
                                show: false
                            },
                            axisTicks: {
                                show: false
                            }
                        },
                        padding: {
                            top: 0,
                            right: 0,
                            bottom: 0,
                            left: 0
                        },
                        responsive: [
                            {
                                breakpoint: 1000,
                                options: {
                                    chart: {
                                        width: '100%',
                                        height: 350,
                                    },
                                    legend: {
                                        position: "bottom",
                                        offsetY: 0,
                                    },
                                }
                            }
                        ]
                    };

                    const chart = new ApexCharts(document.querySelector("#registredActiveCleints"), options);
                    chart.render();
                },

                // Copy-to-Clipboard logic
                copyText(text, btn) {
                    try {
                        if (navigator.clipboard && navigator.clipboard.writeText && window.isSecureContext) {
                            navigator.clipboard.writeText(text).then(() => {
                                this.showCopied(btn);
                            }).catch(() => {
                                this.fallbackCopy(text, btn);
                            });
                        } else {
                            this.fallbackCopy(text, btn);
                        }
                    } catch (err) {
                        this.fallbackCopy(text, btn);
                    }
                },
                fallbackCopy(text, btn) {
                    try {
                        // Create temporary textarea for fallback copy
                        const textarea = document.createElement('textarea');
                        textarea.value = text;
                        document.body.appendChild(textarea);
                        textarea.select();
                        textarea.setSelectionRange(0, 99999);
                        document.execCommand('copy');
                        document.body.removeChild(textarea);
                        this.showCopied(btn);
                    } catch (err) {
                        console.error('Copy failed:', err);
                        alert('Copy failed. Please copy manually.');
                    }
                },
                showCopied(btn) {
                    if (!btn) return;
                    const oldText = btn.innerHTML;
                    btn.innerHTML = '<i data-lucide="check" class="h-4"></i> Copied';
                    btn.disabled = true;
                    setTimeout(() => {
                        btn.innerHTML = oldText;
                        btn.disabled = false;
                        // Reinitialize Lucide icons
                        if (window.lucide) lucide.createIcons();
                    }, 1500);
                }
            };
        }

    </script>
@endsection
