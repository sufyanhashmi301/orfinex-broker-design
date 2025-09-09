@extends('frontend::layouts.user')
@section('title')
    {{ __(':full_name\'s Dashboard', ['full_name' => auth()->user()->full_name]) }}
@endsection
@section('content')
    <div class="md:hidden block">
        <div class="rounded-2xl border border-gray-200 bg-gradient-to-r from-blue-50/80 to-indigo-50/80 dark:from-blue-900/10 dark:to-indigo-900/10 dark:border-gray-700 backdrop-blur-sm p-4 mb-6 shadow-sm user-ranking-mobile">
            <div class="flex items-center justify-between">
                <div class="flex items-center flex-1 min-w-0">
                    <div class="flex-none">
                        <div class="h-14 w-14 rounded-full border-2 border-blue-500/20 dark:border-blue-400/20 overflow-hidden bg-white dark:bg-gray-800">
                            <img src="{{ getFilteredPath(auth()->user()->avatar, 'fallback/user.png') }}" alt="user" class="block w-full h-full object-cover">
                        </div>
                    </div>
                    <div class="flex-1 text-start ml-4 min-w-0">
                        <h4 class="text-base font-semibold dark:text-white truncate mb-1">
                            {{ $user->full_name }}
                        </h4>
                        <div class="flex items-center text-sm">
                            @if($user->kyc >= \App\Enums\KYCStatus::Level2->value)
                                <x-badge variant="success" size="sm" icon="check-circle" icon-position="right">
                                    {{ __('Verified') }}
                                </x-badge>
                            @else
                                <x-badge variant="warning" size="sm" icon="x-circle" icon-position="right">
                                    {{ __('Unverified') }}
                                </x-badge>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0 ml-4">
                    @auth
                        @php
                            $userId = auth()->id();
                            $notifications = App\Models\Notification::where('for','user')->where('user_id', $userId)->latest()->take(4)->get();
                            $totalUnread = App\Models\Notification::where('for','user')->where('user_id', $userId)->where('read', 0)->count();
                            $totalCount = App\Models\Notification::where('for','user')->where('user_id', $userId)->get()->count();
                        @endphp
                        @if($notifications->isNotEmpty())
                            <a href="{{ route($notifications->first()->for.'.notification.all') }}" class="relative flex h-11 w-11 items-center justify-center rounded-xl bg-white/80 shadow-sm border border-gray-200 dark:bg-gray-800/80 dark:border-gray-700 transition-all active:scale-95">
                                <i data-lucide="bell" class="w-5 h-5 text-gray-600 dark:text-gray-400"></i>
                                @if($totalUnread > 0)
                                    <span class="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-red-500 flex items-center justify-center">
                                        <span class="text-xs font-bold text-white">{{ $totalUnread > 9 ? '9+' : $totalUnread }}</span>
                                    </span>
                                @endif
                            </a>
                        @else
                            <a href="#" class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/80 shadow-sm border border-gray-200 dark:bg-gray-800/80 dark:border-gray-700 opacity-60" title="No notifications available">
                                <i data-lucide="bell-off" class="w-5 h-5 text-gray-400"></i>
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
    @if($banners->count() > 0)
        <div class="grid md:grid-cols-{{ $banners->count() }} grid-cols-1 gap-3 mb-3">
            @foreach($banners as $banner)
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] flex flex-wrap items-center justify-between md:nowrap px-4 py-3 gap-3">
                    <div class="">
                        <p class="text-base text-slate-900 dark:text-white text-opacity-80 mb-1">
                            {{ $banner->subtitle }}
                        </p>
                        <h4 class="text-lg font-medium text-slate-900 dark:text-white">
                            {{ $banner->title }}
                        </h4>
                    </div>
                    <a href="{{ $banner->button_link }}" class="loaderBtn text-sm inline-flex items-center justify-center dark:text-white">
                        <span>{{ $banner->button_text }}</span>
                        <i data-lucide="chevron-right" class="text-lg ltr:ml-2 rtl:mr-2"></i>
                    </a>
                </div>
            @endforeach
        </div>
    @endif

    
    @include('frontend::user.include.__user_card')
    
    <div class="md:block hidden desktop-screen-show">
        <div class="grid {{ setting('user_tickets_feature', 'customer_permission') ? 'xl:grid-cols-6' : 'xl:grid-cols-5' }} md:grid-cols-3 grid-cols-2 gap-3 mb-3">
            @php
                $quickActions = [
                    [
                        'title' => __('Deposit'),
                        'icon' => 'download',
                        'route' => 'user.deposit.methods',
                    ],
                    [
                        'title' => __('Withdraw'),
                        'icon' => 'upload',
                        'route' => 'user.withdraw.view',
                    ],
                    [
                        'title' => __('Transfer'),
                        'icon' => 'arrow-right-left',
                        'route' => 'user.transfer',
                    ],
                    [
                        'title' => __('Accounts'),
                        'icon' => 'chart-line',
                        'route' => 'user.forex-account-logs',
                    ],
                    [
                        'title' => __('Verification'),
                        'icon' => 'user-check',
                        'route' => 'user.kyc',
                    ]
                ];
                
                if(setting('user_tickets_feature', 'customer_permission')) {
                    $quickActions[] = [
                        'title' => __('Support'),
                        'icon' => 'life-buoy',
                        'route' => 'user.ticket.index',
                    ];
                }
            @endphp
            
            @foreach($quickActions as $action)
                <x-link-button 
                    href="{{ route($action['route']) }}" 
                    variant="ghost" 
                    :full-width="true"
                    class="group !h-auto !p-5 md:!p-6 !rounded-2xl !border !border-gray-200 !bg-white dark:!border-gray-800 dark:!bg-white/[0.03] hover:!shadow-md transition-all duration-300"
                >
                    <div class="flex flex-col items-center justify-center">
                        <div class="h-12 w-12 rounded-full flex items-center justify-center text-2xl mb-3 bg-gray-100 text-gray-800 transition-colors duration-300 dark:bg-gray-800 dark:text-white/90">
                            <i data-lucide="{{ $action['icon'] }}"></i>
                        </div>
                        <div class="text-lg text-slate-900 dark:text-white font-medium text-center">
                            {{ $action['title'] }}
                        </div>
                    </div>
                </x-link-button>
            @endforeach
        </div>

        <div class="grid grid-cols-12 gap-3 mb-3">
            <div class="xl:col-span-7 col-span-12">
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-5 pt-5 sm:px-6 sm:pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <header class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ __('Trading Accounts') }}</h3>
                        <div x-data="{openDropDown: false}" class="relative h-fil">
                            <button @click="openDropDown = !openDropDown" type="button" class="text-gray-700 dark:text-white">
                                <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.2441 6C10.2441 5.0335 11.0276 4.25 11.9941 4.25H12.0041C12.9706 4.25 13.7541 5.0335 13.7541 6C13.7541 6.9665 12.9706 7.75 12.0041 7.75H11.9941C11.0276 7.75 10.2441 6.9665 10.2441 6ZM10.2441 18C10.2441 17.0335 11.0276 16.25 11.9941 16.25H12.0041C12.9706 16.25 13.7541 17.0335 13.7541 18C13.7541 18.9665 12.9706 19.75 12.0041 19.75H11.9941C11.0276 19.75 10.2441 18.9665 10.2441 18ZM11.9941 10.25C11.0276 10.25 10.2441 11.0335 10.2441 12C10.2441 12.9665 11.0276 13.75 11.9941 13.75H12.0041C12.9706 13.75 13.7541 12.9665 13.7541 12C13.7541 11.0335 12.9706 10.25 12.0041 10.25H11.9941Z" fill=""></path>
                                </svg>
                            </button>
                            <div x-show="openDropDown" class="absolute right-0 z-40 min-w-40 p-2 space-y-1 bg-white border border-gray-200 shadow-theme-lg dark:bg-gray-dark top-full rounded-2xl dark:border-gray-800">
                                <a href="{{ route('user.schema') }}" class="flex w-full px-3 py-2 font-medium text-left text-nowrap text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                                    {{ __('Create New Account') }}
                                </a>
                                <a href="{{ route('user.forex-account-logs') }}" class="flex w-full px-3 py-2 font-medium text-left text-nowrap text-gray-500 rounded-lg text-theme-xs hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300">
                                    {{ __('View Existing Accounts') }}
                                </a>
                            </div>
                        </div>
                    </header>
                    <div class="py-5 sm:py-6 mt-auto">
                        <div class="grid md:grid-cols-2 grid-cols-1 gap-3">
                            <div class="bg-gray-50 dark:bg-gray-900">
                                <div class="card-body p-4 py-6">
                                    <div class="flex items-center justify-between gap-5 mb-10">
                                        <h5 class="text-lg text-slate-900 dark:text-white font-medium">{{ __('Live Accounts:') }}</h5>
                                        <x-text-link 
                                            href="{{ route('user.forex-account-logs') }}" 
                                            variant="ghost" 
                                            size="sm"
                                            icon="arrow-right"
                                            icon-position="right"
                                        >
                                            {{ __('See All') }}
                                        </x-text-link>
                                    </div>
                                    <div class="flex items-center justify-between gap-5">
                                        <h5 class="text-xl text-slate-900 dark:text-white font-medium">
                                            {{ $realForexAccountsCount }}
                                        </h5>
                                        <x-link-button 
                                            href="{{ route('user.schema') }}" 
                                            variant="primary" 
                                            size="sm"
                                            icon="plus"
                                        >
                                            {{ __('Create Account') }}
                                        </x-link-button>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-900">
                                <div class="card-body p-4 py-6">
                                    <div class="flex items-center justify-between gap-5 mb-10">
                                        <h5 class="text-lg text-slate-900 dark:text-white font-medium">{{ __('Demo Accounts:') }}</h5>
                                        <x-text-link 
                                            href="{{ route('user.forex-account-logs') }}" 
                                            variant="ghost" 
                                            size="sm"
                                            icon="arrow-right"
                                            icon-position="right"
                                        >
                                            {{ __('See All') }}
                                        </x-text-link>
                                    </div>
                                    <div class="flex items-center justify-between gap-5">
                                        <h5 class="text-xl text-slate-900 dark:text-white font-medium">
                                            {{ $demoForexAccountsCount }}
                                        </h5>
                                        <x-link-button 
                                            href="{{ route('user.schema') }}" 
                                            variant="primary" 
                                            size="sm"
                                            icon="plus"
                                        >
                                            {{ __('Create Account') }}
                                        </x-link-button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="xl:col-span-5 col-span-12">
                <div class="h-full rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    <div x-data="profitLossChartComponent()" x-init="initChart()" class="relative">
                        <div id="profitLossChart" style="opacity: 0.05"></div>
                        <div class="flex flex-col items-center justify-center text-center absolute h-full top-0 bottom-0 left-0 right-0 gap-3 p-5">
                            <iconify-icon class="text-xl dark:text-white" icon="lucide:info"></iconify-icon>
                            <p class="text-sm dark:text-white">
                                {{ __("We'll show your balance graph here once there is enough data") }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('frontend::user.include.__recent_transaction')
    </div>

     {{--for mobile--}}
    <div class="md:hidden block mobile-screen-show">
        @include('frontend::user.mobile_screen_include.dashboard.__index')
    </div>

    {{--offer modal--}}
    @if(setting('popup_status', 'popup'))
        @include('frontend::user.include.__popup')
    @endif

@endsection
@section('script')
    <script>
        function profitLossChartComponent() {
            return {
                initChart() {
                    const options = {
                        series: [
                            {
                                name: 'Profit',
                                data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
                            },
                            {
                                name: 'Loss',
                                data: [76, 85, 100, 98, 87, 95, 91, 75, 94]
                            }
                        ],
                        chart: {
                            type: 'bar',
                            height: 220,
                            toolbar: { show: false }
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                borderRadiusApplication: 'end',
                            }
                        },
                        colors: ['#2EBD85', '#F6465D'],
                        dataLabels: { enabled: false },
                        stroke: {
                            show: true,
                            width: 2,
                            colors: ['transparent']
                        },
                        xaxis: {
                            categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
                        },
                        fill: { opacity: 1 },
                        grid: { show: false },
                        legend: {
                            position: 'top',
                            horizontalAlign: 'left',
                            offsetX: -20,
                            fontSize: '16px',
                            fontWeight: 500,
                            markers: {
                                width: 8,
                                height: 8,
                                offsetY: -1,
                                offsetX: -5,
                                radius: 12
                            },
                            itemMargin: {
                                horizontal: 10,
                                vertical: 0
                            },
                        },
                        tooltip: {
                            y: {
                                formatter: function (val) {
                                    return "$ " + val + " thousands";
                                }
                            }
                        }
                    };

                    const chart = new ApexCharts(document.querySelector("#profitLossChart"), options);
                    chart.render();
                }
            }
        }

    </script>

    @if(setting('popup_status', 'popup'))
        <script>
            $(document).ready(function() {
                $('#offerModal').modal('show');
            });
        </script>
    @endif

@endsection
