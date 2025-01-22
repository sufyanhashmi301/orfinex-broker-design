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
                        @if ($user->kyc != \App\Enums\KYCStatus::Pending->value)
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

    {{-- Dynamic Banners --}}
    @if ($banners->count() > 0)
        <div class="grid md:grid-cols-{{ $banners->count() }} grid-cols-1 gap-3 mb-3">
            @foreach ($banners as $banner)
                <div class="card flex flex-wrap items-center justify-between md:nowrap  px-4 py-5 gap-5">
                    <div class="">
                        <p class="text-base text-slate-900 dark:text-white text-opacity-80 mb-2">
                            {{ $banner->subtitle }}
                        </p>
                        <h4 class="text-lg font-medium text-slate-900 dark:text-white">
                            {{ $banner->title }}
                        </h4>
                    </div>
                    @if ($banners->count() <= 2)
                        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                            <a href="{{ $banner->button_link }}"
                                class="btn btn-dark inline-flex items-center justify-center">
                                {{ $banner->button_text }}
                            </a>
                            <a href="{{ $banner->primary_link }}"
                                class="btn inline-flex items-center justify-center dark:text-white">
                                <span class="flex items-center">
                                    <span>{{ __('Learn More') }}</span>
                                    <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2"
                                        icon="lucide:chevron-right"></iconify-icon>
                                </span>
                            </a>
                        </div>
                    @else
                        <a href="{{ $banner->primary_link }}"
                            class="btn inline-flex items-center justify-center dark:text-white">
                            <span>{{ __('Learn More') }}</span>
                            <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon>
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <div class="md:block hidden desktop-screen-show">
        <div class="grid grid-cols-12 gap-3 mb-3">
            <div class="lg:col-span-7 col-span-12">
                <div class="card">
                    <div class="card-header noborder">
                        <h4 class="card-title">{{ __('Trading Objective') }}</h4>
                    </div>
                    <div class="card-body p-6 pt-0">
                        @include('frontend::user.include.__trading_objective')
                        <div class="overflow-x-auto -mx-6">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden">
                                    <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                        <thead class="bg-slate-200 dark:bg-slate-700">
                                            <tr>
                                                <th scope="col" class="table-th">{{ __('Title') }}</th>
                                                <th scope="col" class="table-th">{{ __('Login') }}</th>
                                                <th scope="col" class="table-th">{{ __('Allotted Funds') }}</th>
                                                <th scope="col" class="table-th">{{ __('Phase Type') }}</th>
                                                <th scope="col" class="table-th">{{ __('Status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="table-td font-semibold">2 Step challenge</td>
                                                <td class="table-td">N/A</td>
                                                <td class="table-td">5000</td>
                                                <td class="table-td">
                                                    <span class="badge bg-primary" style="color: #fff">evaluation phase</span>
                                                </td>
                                                <td class="table-td">
                                                    <span class="badge bg-primary" style="color: #fff">pending</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="table-td font-semibold">2 Step challenge</td>
                                                <td class="table-td">N/A</td>
                                                <td class="table-td">5000</td>
                                                <td class="table-td">
                                                    <span class="badge bg-primary" style="color: #fff">evaluation phase</span>
                                                </td>
                                                <td class="table-td">
                                                    <span class="badge bg-primary" style="color: #fff">pending</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="table-td font-semibold">2 Step challenge</td>
                                                <td class="table-td">N/A</td>
                                                <td class="table-td">5000</td>
                                                <td class="table-td">
                                                    <span class="badge bg-primary" style="color: #fff">evaluation phase</span>
                                                </td>
                                                <td class="table-td">
                                                    <span class="badge bg-primary" style="color: #fff">pending</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-5 col-span-12">
                <div class="card h-2/5 mb-3">
                    <div class="card-body h-full flex flex-col p-6">
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-10">
                            <p class="text-slate-900 dark:text-white text-sm font-medium">
                                {{ __('Referral ID:') }} {{ __('UG729124') }}
                            </p>
                            <div>
                                <button type="button" class="btn btn-light btn-sm inline-flex items-center justify-center" onclick="copyToClipboard('UG729124')">
                                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:copy" ></iconify-icon>
                                    {{ __('Copy ID') }}
                                </button>
                                <button type="button" class="btn btn-light btn-sm inline-flex items-center justify-center" onclick="copyToClipboard('http://orfinex-broker/register?referral=UG729124')">
                                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:copy"></iconify-icon>
                                    {{ __('Copy Link') }}
                                </button>
                            </div>
                        </div>
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-2">
                            {{ __('Withdrawable Balance') }}
                        </p>
                        <h6 class="block mb- text-3xl text-slate-900 dark:text-white font-medium leading-none mb-6">
                            {{ __('40.00 USD') }}
                        </h6>
                        <a href="{{route('user.withdraw.step1')}}" class="btn btn-dark block-btn inline-flex items-center justify-center mt-auto">
                            {{ __('Withdraw') }}
                        </a>
                    </div>
                </div>
                <div class="slider carousel-interval owl-carousel">
                    <img class="w-full" src="{{ asset('frontend/images/all-img/c1.png') }}" alt="image">
                    <img class="w-full" src="{{ asset('frontend/images/all-img/c2.png') }}" alt="image">
                    <img class="w-full" src="{{ asset('frontend/images/all-img/c3.png') }}" alt="image">
                    <img class="w-full" src="{{ asset('frontend/images/all-img/c4.png') }}" alt="image">
                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-2 grid-cols-1 gap-3 mb-3">
            <div class="card border dark:border-slate-700">
                <div class="card-body p-6">
                    <div class="mb-5">
                        <h4 class="mb-1">{{ __('Free Trial') }}</h4>
                        <p class="text-sm text-success-500 mb-2">
                            {{ __('Enhance Your Trading Skills') }}
                        </p>
                        <p class="text-slate-900 dark:text-white text-sm min-h-[3.75rem]">
                            {{ __('Experience risk-free trading and hone your skills without any financial commitment.') }}
                        </p>
                    </div>
                    <ul class="bg-slate-50 dark:bg-dark divide-y divide-slate-100 dark:divide-slate-700 px-3 rounded">
                        <li class="flex items-center text-sm text-slate-600 dark:text-slate-300 py-3">
                            <iconify-icon class="text-lg text-primary mr-2" icon="lucide:check"></iconify-icon>
                            {{ __('Not eligible for a live trading account') }}
                        </li>
                        <li class="flex items-center text-sm text-slate-600 dark:text-slate-300 py-3">
                            <iconify-icon class="text-lg text-primary mr-2" icon="lucide:check"></iconify-icon>
                            {{ __('14-day trial period') }}
                        </li>
                        <li class="flex items-center text-sm text-slate-600 dark:text-slate-300 py-3">
                            <iconify-icon class="text-lg text-primary mr-2" icon="lucide:check"></iconify-icon>
                            {{ __('Access to basic account analysis tools') }}
                        </li>
                        <li class="flex items-center text-sm text-slate-600 dark:text-slate-300 py-3">
                            <iconify-icon class="text-lg text-primary mr-2" icon="lucide:check"></iconify-icon>
                            {{ __('Limited app usage') }}
                        </li>
                    </ul>
                    <a href="javascript:;" class="btn inline-flex justify-center btn-dark dark:bg-body w-full mt-5"
                        type="button" data-bs-toggle="modal" data-bs-target="#comingSoonModal">
                        {{ __('Start Free Trial') }}
                    </a>
                </div>
            </div>
            <div class="card border dark:border-slate-700">
                <div class="card-body p-6">
                    <div class="mb-5">
                        <h4 class="mb-1">{{ setting('site_title', 'global') }} {{ __('Challenge') }}</h4>
                        <p class="text-sm text-success-500 mb-2">
                            {{ __('Trade with up to $150,000 in Funding') }}
                        </p>
                        <p class="text-slate-900 dark:text-white text-sm min-h-[3.75rem]">
                            {{ __('Showcase your trading proficiency by completing the evaluation and qualify for a funded account.') }}
                        </p>
                    </div>
                    <ul class="bg-slate-50 dark:bg-dark divide-y divide-slate-100 dark:divide-slate-700 px-3 rounded">
                        <li class="flex items-center text-sm text-slate-600 dark:text-slate-300 py-3">
                            <iconify-icon class="text-lg text-primary mr-2" icon="lucide:check"></iconify-icon>
                            {{ __('Receive funding up to $150,000') }}
                        </li>
                        <li class="flex items-center text-sm text-slate-600 dark:text-slate-300 py-3">
                            <iconify-icon class="text-lg text-primary mr-2" icon="lucide:check"></iconify-icon>
                            {{ __('Prove your trading expertise') }}
                        </li>
                        <li class="flex items-center text-sm text-slate-600 dark:text-slate-300 py-3">
                            <iconify-icon class="text-lg text-primary mr-2" icon="lucide:check"></iconify-icon>
                            {{ __('Full account performance analysis') }}
                        </li>
                        <li class="flex items-center text-sm text-slate-600 dark:text-slate-300 py-3">
                            <iconify-icon class="text-lg text-primary mr-2" icon="lucide:check"></iconify-icon>
                            {{ __('Access to premium apps and tools') }}
                        </li>
                    </ul>
                    <a href="{{ route('user.account.buy') }}"
                        class="btn inline-flex justify-center btn-primary w-full mt-5">
                        {{ __('Start Challenge') }}
                    </a>
                </div>
            </div>
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
            <a href="{{ route('user.kyc') }}" class="card">
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
        <div class="grid grid-cols-12 gap-3 mb-3">
            <div class="lg:col-span-7 col-span-12">
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
                                        {{-- <a href="{{ route('user.investments.index') }}"
                                            class="btn-link inline-flex items-center">
                                            {{ __('See All') }}
                                            <iconify-icon class="text-lg ltr:ml-1 rtl:mr-1"
                                                icon="lucide:chevron-right"></iconify-icon>
                                        </a> --}}
                                    </div>
                                    <div class="flex items-center justify-between gap-5">
                                        <h5 class="text-xl text-slate-900 dark:text-white font-medium">0 {{ $currency }}</h5>
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
                                        {{-- <a href="{{ route('user.investments.index') }}"
                                            class="btn-link inline-flex items-center">
                                            {{ __('See All') }}
                                            <iconify-icon class="text-lg ltr:ml-1 rtl:mr-1"
                                                icon="lucide:chevron-right"></iconify-icon>
                                        </a> --}}
                                    </div>
                                    <div class="flex items-center justify-between gap-5">
                                        <h5 class="text-xl text-slate-900 dark:text-white font-medium">0 {{ $currency }}</h5>
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
            <div class="lg:col-span-5 col-span-12">
                <div class="card h-full">
                    <div class="card-body relative p-6 pb-0">
                        <div id="profitLossChart" style="opacity: 0.05"></div>
                        <div
                            class="flex flex-col items-center justify-center text-center absolute h-full top-0 bottom-0 left-0 right-0 gap-3 p-5">
                            <iconify-icon class="text-xl dark:text-white" icon="lucide:info"></iconify-icon>
                            <p class="text-sm dark:text-white">
                                {{ __("We'll show your balance graph here once there is enough data") }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Optimized --}}
        @include('frontend::user.include.__recent_transaction')

        {{-- @include('frontend::user.include.__pending_challenge')
        @include('frontend::user.include.__active_challenge')
        @include('frontend::user.include.__violated_challenge') --}}
    </div>

    {{-- for mobile --}}
    <div class="md:hidden block mobile-screen-show">
        @include('frontend::user.mobile_screen_include.dashboard.__index')
    </div>

    @include('frontend::user.include.__coming_soon')

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
