@extends('frontend::layouts.user')
@section('title')
    {{ __(':full_name\'s Dashboard', ['full_name' => auth()->user()->full_name]) }}
@endsection
@section('content')
    <div class="block md:hidden">
        <div class="card user-ranking-mobile flex justify-between items-center p-3 mb-3 rounded-lg">
            <div class="flex items-center">
                <div class="flex-none">
                    <div class="h-10 w-10 rounded-full flex-1 border-2" style="border-color: #0ebe3b;">
                        <img src="@if (auth()->user()->avatar && file_exists('assets/' . auth()->user()->avatar)) {{ asset($user->avatar) }} @else {{ asset('frontend/images/all-img/user.png') }} @endif"
                            alt="user" class="block w-full h-full object-cover rounded-full">
                    </div>
                </div>
                <div class="flex-1 text-start ml-2">
                    <h4 class="text-sm font-medium dark:text-white whitespace-nowrap">
                        {{ $user->full_name }}
                    </h4>
                    <span class="flex items-center text-slate-400 text-xs font-normal">
                        @if(isset($user->kyc) && $user->kyc->status == \App\Enums\KycStatusEnums::VERIFIED)
                            {{ __('Verified') }}
                            <img src="https://cdn.brokeret.com/web/icons/yes-tick.svg" class="ml-1" alt=""
                                style="height: 14px;">
                        @else
                            {{ __('Unverified') }}
                            <img src="https://cdn.brokeret.com/web/icons/no-tick.svg" class="ml-1" alt=""
                                style="height: 14px;">
                        @endif
                    </span>
                </div>
            </div>
            <div class="ltr:mr-[10px] rtl:ml-[10px]">
                @auth
                    @php
                        $userId = auth()->id();
                        $notifications = App\Models\Notification::where('for', 'user')
                            ->where('user_id', $userId)
                            ->latest()
                            ->take(4)
                            ->get();
                        $totalUnread = App\Models\Notification::where('for', 'user')
                            ->where('user_id', $userId)
                            ->where('read', 0)
                            ->count();
                        $totalCount = App\Models\Notification::where('for', 'user')
                            ->where('user_id', $userId)
                            ->get()
                            ->count();
                    @endphp
                    @if ($notifications->isNotEmpty())
                        <a href="{{ route($notifications->first()->for . '.notification.all') }}"
                            class="h-[32px] w-[32px] bg-slate-100 dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center">
                            <iconify-icon class="animate-tada text-slate-800 dark:text-white text-xl"
                                icon="heroicons-outline:bell"></iconify-icon>
                        </a>
                    @else
                        <a href="#"
                            class="h-[32px] w-[32px] bg-slate-100 dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center"
                            title="No notifications available">
                            <iconify-icon class="text-slate-400 dark:text-slate-600 text-xl"
                                icon="heroicons-outline:bell-slash"></iconify-icon>
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <div class="md:block hidden desktop-screen-show">
        <div class="grid grid-cols-{{ !empty($slider) ? '2' : '1'  }} gap-3 mb-3">

            {{-- Top Left Block --}}
            @include('frontend::dashboard.includes.__accounts_info')
            
            {{-- Top Right Block --}}
            @if ($slider)
                @include('frontend::dashboard.includes.__promotional_slider')
            @endif
        </div>

        {{-- Banners --}}
        <div class="grid md:grid-cols-2 grid-cols-1 gap-3 mb-3">
            @include('frontend.prime_x.dashboard.includes.__banners')
        </div>

        {{-- Optimized --}}
        <div class="grid lg:grid-cols-5 md:grid-cols-3 grid-cols-2 gap-3 mb-3">
            <a href="{{ route('user.withdraw.step1') }}" class="card">
                <div class="card-body flex flex-col items-center justify-center p-8">
                    <div
                        class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-body text-primary mb-3">
                        <iconify-icon icon="heroicons-outline:upload"></iconify-icon>
                    </div>
                    <div class="text-lg text-slate-900 dark:text-white font-medium">
                        {{ __('Payout') }}
                    </div>
                </div>
            </a>
            <a href="{{ route('user.leaderboard') }}" class="card">
                <div class="card-body flex flex-col items-center justify-center p-8">
                    <div
                        class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-body text-primary mb-3">
                        <iconify-icon icon="lucide:trophy"></iconify-icon>
                    </div>
                    <div class="text-lg text-slate-900 dark:text-white font-medium">
                        {{ __('Leaderboard') }}
                    </div>
                </div>
            </a>
            <a href="{{ route('user.investments.index') }}" class="card">
                <div class="card-body flex flex-col items-center justify-center p-8">
                    <div
                        class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-body text-primary mb-3">
                        <iconify-icon icon="uil:chart-line"></iconify-icon>
                    </div>
                    <div class="text-lg text-slate-900 dark:text-white font-medium">
                        {{ __('Accounts') }}
                    </div>
                </div>
            </a>
            <a href="{{ route('user.verification.index') }}" class="card">
                <div class="card-body flex flex-col items-center justify-center p-8">
                    <div
                        class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-body text-primary mb-3">
                        <iconify-icon icon="mdi:user-check-outline"></iconify-icon>
                    </div>
                    <div class="text-lg text-slate-900 dark:text-white font-medium">
                        {{ __('Verification') }}
                    </div>
                </div>
            </a>
            <a href="{{ route('user.ticket.index') }}" class="card">
                <div class="card-body flex flex-col items-center justify-center p-8">
                    <div
                        class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-body text-primary mb-3">
                        <iconify-icon icon="heroicons-outline:support"></iconify-icon>
                    </div>
                    <div class="text-lg text-slate-900 dark:text-white font-medium">
                        {{ __('Support') }}
                    </div>
                </div>
            </a>
        </div>

        {{-- Optimized --}}
        @php
            $dashboard_icons = 0;
            foreach(config('setting.social_links')['elements'] as $key => $field) {
                
                if(str_contains($field['name'], '_dashboard') ) {
                    if(setting($field['name']) == 1) {
                        $dashboard_icons += 1;
                    }
                }
            }
        @endphp
        <div class="grid grid-cols-12 gap-3 mb-3">
            <div class="lg:col-span-{{ 12 - $dashboard_icons }} col-span-12">
                <div class="card h-full flex flex-col">
                    <header class="card-header noborder">
                        <h4 class="card-title">{{ __('Wallets') }}</h4>
                    </header>
                    <div class="card-body p-6 pt-0 mt-auto">
                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-3">
                            <div class="bg-slate-100 dark:bg-body">
                                <div class="card-body p-4 py-6">
                                    <div class="flex items-center justify-between gap-5 mb-10">
                                        <h5 class="text-lg text-slate-900 dark:text-white font-medium">Payout Wallet</h5>
                                    </div>
                                    <div class="flex items-center justify-between gap-5">
                                        @php
                                            $payout_wallet_balance = Auth::user()->wallets->where('slug', 'payout_wallet')->first()->available_balance ?? 0;
                                            $affiliate_wallet_balance = Auth::user()->wallets->where('slug', 'affiliate_wallet')->first()->available_balance ?? 0;
                                        @endphp
                                        <h5 class="text-xl text-slate-900 dark:text-white font-medium">{{ $payout_wallet_balance }} {{ $currency }}</h5>
                                        <a href="{{ route('user.withdraw.step1') }}"
                                            class="btn btn-primary btn-sm inline-flex items-center justify-center">
                                            {{ __('Create Payout Request') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-slate-100 dark:bg-body">
                                <div class="card-body p-4 py-6">
                                    <div class="flex items-center justify-between gap-5 mb-10">
                                        <h5 class="text-lg text-slate-900 dark:text-white font-medium">Affiliate Wallet
                                        </h5>
                                    </div>
                                    <div class="flex items-center justify-between gap-5">
                                        <h5 class="text-xl text-slate-900 dark:text-white font-medium">{{ $affiliate_wallet_balance }} {{ $currency }}</h5>
                                        <a href="{{ route('user.affiliate-area.index') }}"
                                            class="btn btn-primary btn-sm inline-flex items-center justify-center">
                                            {{ __('See Affiliate Area') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-{{ $dashboard_icons }} col-span-12">
                <div class="card h-full">

                    <header class="card-header noborder">
                        <h4 class="card-title">{{ __('Join Us On') }}</h4>
                    </header>
                    <div class="card-body p-6 pb-0">
                        <style>
                            .social-icons-container {
                                display: flex;
                                justify-content: space-around;
                                align-items: center;
                                flex-wrap: wrap;
                                gap: 1rem; /* optional for spacing between rows if wrapping */
                            }
                    
                            .social-icon {
                                border-radius: 50% !important;
                                display: inline-flex;
                                align-items: center;
                                justify-content: center;
                                transition: filter 0.3s ease, opacity 0.3s ease;
                            }
                    
                            .social-icon svg {
                                height: 50px !important;
                                width: 50px !important;
                            }

                        </style>
                    
                        @php
                            $fields2 = config('setting.social_links');
                        @endphp
                    
                        <div class="social-icons-container">
                            @foreach ($fields2['elements'] as $key => $field)
                                @php
                                    $is_enabled = false;
                                    $settings_name = '';
                                    if(str_contains($field['name'], '_dashboard') ) {
                                        if(setting($field['name']) == 1) {
                                            $is_enabled = true;
                                            $settings_name = str_replace('_on_dashboard', '', $field['name']);
                                            $label = str_replace( '_', ' ', str_replace( 'social_', '', $settings_name) );
                                        }
                                    }
                                @endphp
                                @if ($is_enabled)
                                    <div class="social-icon">
                                        <a href="{{ setting($fields2['elements'][$loop->index - 2]['name'], 'social_links') }}" target="_blank">
                                            {!! $fields2['elements'][$loop->index - 2]['icon'] !!}
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        {{-- Optimized --}}
        @include('frontend::dashboard.includes.__recent_transaction')
    </div>

    {{-- for mobile --}}
    <div class="md:hidden block mobile-screen-show">
        @include('frontend::dashboard.mobile_screen_include.dashboard.__index')
    </div>

    @include('frontend::dashboard.includes.__coming_soon')

@endsection
@section('script')
    <script>
        function copyRef() {
            /* Get the text field */
            var textToCopy = $('#refLink').val();
            // Create a temporary input element
            var tempInput = $('<input>');
            $('body').append(tempInput);
            tempInput.val(textToCopy).select();
            // Copy the text from the temporary input
            document.execCommand('copy');
            // Remove the temporary input element
            tempInput.remove();
            $('#copy').text('Copied');
            var copyApi = document.getElementById("refLink");
            /* Select the text field */
            copyApi.select();
            copyApi.setSelectionRange(0, 999999999); /* For mobile devices */
            /* Copy the text inside the text field */
            document.execCommand('copy');
            $('#copy').text('Copied')

        }

        // Load More
        $('.moreless-button').click(function() {
            $('.moretext').slideToggle();
            if ($('.moreless-button').text() == "Load more") {
                $(this).text("Load less")
            } else {
                $(this).text("Load more")
            }
        });

        $('.moreless-button-2').click(function() {
            $('.moretext-2').slideToggle();
            if ($('.moreless-button-2').text() == "Load more") {
                $(this).text("Load less")
            } else {
                $(this).text("Load more")
            }
        });

        var options = {
            series: [{
                name: 'Profit',
                data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
            }, {
                name: 'Loss',
                data: [76, 85, 100, 98, 87, 95, 91, 75, 94]
            }],
            chart: {
                type: 'bar',
                height: 220,
                toolbar: {
                    show: false // Hide the default toolbar
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    borderRadiusApplication: 'end',
                }
            },
            colors: ['#2EBD85', '#F6465D'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
            },
            fill: {
                opacity: 1,
            },
            grid: {
                show: false,
            },
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
                    formatter: function(val) {
                        return "$ " + val + " thousands"
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#profitLossChart"), options);
        chart.render();
    </script>
@endsection
