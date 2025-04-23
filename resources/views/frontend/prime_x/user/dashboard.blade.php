@extends('frontend::layouts.user')
@section('title')
    {{ __(':full_name\'s Dashboard', ['full_name' => auth()->user()->full_name]) }}
@endsection
@section('content')
    <div class="md:hidden block">
        <div class="card user-ranking-mobile flex justify-between items-center p-3 mb-3 rounded-lg">
            <div class="flex items-center">
                <div class="flex-none">
                    <div class="h-12 w-12 rounded-full flex-1 border-2 border-primary">
                        <img src="{{ getFilteredPath(auth()->user()->avatar, 'fallback/user.png') }}" alt="user" class="block w-full h-full object-cover rounded-full">
                    </div>
                </div>
                <div class="flex-1 text-start ml-2">
                    <h4 class="text-base font-medium dark:text-white whitespace-nowrap">
                        {{ $user->full_name }}
                    </h4>
                    <div class="flex items-center text-slate-400 dark:text-slate-50 text-sm font-normal">
                        @if($user->kyc >= \App\Enums\KYCStatus::Level2->value)
                            {{ __('Verified') }}
                            <img src="https://cdn.brokeret.com/web/icons/yes-tick.svg" class="ml-1" alt="" style="height: 14px;">
                        @else
                            {{ __('Unverified') }}
                            <img src="https://cdn.brokeret.com/web/icons/no-tick.svg" class="ml-1" alt="" style="height: 14px;">
                        @endif
                    </div>
                </div>
            </div>
            <div class="ltr:mr-[10px] rtl:ml-[10px]">
                @auth
                    @php
                        $userId = auth()->id();
                        $notifications = App\Models\Notification::where('for','user')->where('user_id', $userId)->latest()->take(4)->get();
                        $totalUnread = App\Models\Notification::where('for','user')->where('user_id', $userId)->where('read', 0)->count();
                        $totalCount = App\Models\Notification::where('for','user')->where('user_id', $userId)->get()->count();
                    @endphp
                    @if($notifications->isNotEmpty())
                        <a href="{{ route($notifications->first()->for.'.notification.all') }}" class="h-[32px] w-[32px] bg-slate-100 dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center">
                            <iconify-icon class="animate-tada text-slate-800 dark:text-white text-xl" icon="heroicons-outline:bell"></iconify-icon>
                        </a>
                    @else
                        <a href="#" class="h-[32px] w-[32px] bg-slate-100 dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center" title="No notifications available">
                            <iconify-icon class="text-slate-400 dark:text-slate-600 text-xl" icon="heroicons-outline:bell-slash"></iconify-icon>
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
    @if($banners->count() > 0)
        <div class="grid md:grid-cols-{{ $banners->count() }} grid-cols-1 gap-3 mb-3">
            @foreach($banners as $banner)
                <div class="card flex flex-wrap items-center justify-between md:nowrap  px-4 py-3 gap-3">
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
                        <iconify-icon class="text-lg ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon>
                    </a>
                </div>
            @endforeach
        </div>
    @endif

    <div class="md:block hidden desktop-screen-show">
        @include('frontend::user.include.__user_card')
        <div class="grid lg:grid-cols-6 md:grid-cols-3 grid-cols-2 gap-3 mb-3">
            <a href="{{ route('user.deposit.methods') }}" class="card loaderBtn">
                <div class="card-body flex flex-col items-center justify-center p-8">
                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-body text-primary mb-3">
                        <iconify-icon icon="heroicons-outline:download"></iconify-icon>
                    </div>
                    <div class="text-lg text-slate-900 dark:text-white font-medium">
                        {{ __('Deposit') }}
                    </div>
                </div>
            </a>
            <a href="{{ route('user.withdraw.view') }}" class="card loaderBtn">
                <div class="card-body flex flex-col items-center justify-center p-8">
                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-body text-primary mb-3">
                        <iconify-icon icon="heroicons-outline:upload"></iconify-icon>
                    </div>
                    <div class="text-lg text-slate-900 dark:text-white font-medium">
                        {{ __('Withdraw') }}
                    </div>
                </div>
            </a>
            <a href="{{ route('user.transfer') }}" class="card loaderBtn">
                <div class="card-body flex flex-col items-center justify-center p-8">
                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-body text-primary mb-3">
                        <iconify-icon icon="akar-icons:arrow-repeat"></iconify-icon>
                    </div>
                    <div class="text-lg text-slate-900 dark:text-white font-medium">
                        {{ __('Transfer') }}
                    </div>
                </div>
            </a>
            <a href="{{ route('user.forex-account-logs') }}" class="card loaderBtn">
                <div class="card-body flex flex-col items-center justify-center p-8">
                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-body text-primary mb-3">
                        <iconify-icon icon="uil:chart-line"></iconify-icon>
                    </div>
                    <div class="text-lg text-slate-900 dark:text-white font-medium">
                        {{ __('Accounts') }}
                    </div>
                </div>
            </a>
            <a href="{{ route('user.kyc') }}" class="card loaderBtn">
                <div class="card-body flex flex-col items-center justify-center p-8">
                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-body text-primary mb-3">
                        <iconify-icon icon="mdi:user-check-outline"></iconify-icon>
                    </div>
                    <div class="text-lg text-slate-900 dark:text-white font-medium">
                        {{ __('Verification') }}
                    </div>
                </div>
            </a>
            <a href="{{ route('user.ticket.index') }}" class="card loaderBtn">
                <div class="card-body flex flex-col items-center justify-center p-8">
                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-body text-primary mb-3">
                        <iconify-icon icon="heroicons-outline:support"></iconify-icon>
                    </div>
                    <div class="text-lg text-slate-900 dark:text-white font-medium">
                        {{ __('Support') }}
                    </div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-12 gap-3 mb-3">
            <div class="lg:col-span-7 col-span-12">
                <div class="card h-full flex flex-col">
                    <header class="card-header noborder">
                        <h4 class="card-title">{{ __('Trading Accounts') }}</h4>
                        <div>
                            <!-- BEGIN: Card Dropdown -->
                            <div class="relative">
                                <div class="dropdown relative">
                                    <button class="text-xl text-center block w-full " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="text-xl inline-flex h-6 w-6 flex-col items-center justify-center rounded dark:text-slate-400">
                                            <iconify-icon icon="heroicons-outline:dots-vertical"></iconify-icon>
                                        </span>
                                    </button>
                                    <ul class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                        <li>
                                            <a href="{{ route('user.schema') }}" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                {{ __('Create New Account') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('user.forex-account-logs') }}" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                {{ __('View Existing Accounts') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- END: Card Droopdown -->
                        </div>
                    </header>
                    <div class="card-body p-6 pt-0 mt-auto">
                        <div class="grid md:grid-cols-2 grid-cols-1 gap-3">
                            <div class="bg-slate-100 dark:bg-body">
                                <div class="card-body p-4 py-6">
                                    <div class="flex items-center justify-between gap-5 mb-10">
                                        <h5 class="text-lg text-slate-900 dark:text-white font-medium">{{ __('Live Accounts:') }}</h5>
                                        <a href="{{ route('user.forex-account-logs') }}" class="btn-link loaderBtn inline-flex items-center">
                                            {{ __('See All') }}
                                            <iconify-icon class="text-lg ltr:ml-1 rtl:mr-1" icon="lucide:chevron-right"></iconify-icon>
                                        </a>
                                    </div>
                                    <div class="flex items-center justify-between gap-5">
                                        <h5 class="text-xl text-slate-900 dark:text-white font-medium">
                                            {{ $realForexAccountsCount }}
                                        </h5>
                                        <a href="{{route('user.schema')}}" class="btn btn-primary loaderBtn btn-sm inline-flex items-center justify-center">
                                            {{ __('Create Account') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-slate-100 dark:bg-body">
                                <div class="card-body p-4 py-6">
                                    <div class="flex items-center justify-between gap-5 mb-10">
                                        <h5 class="text-lg text-slate-900 dark:text-white font-medium">{{ __('Demo Accounts:') }}</h5>
                                        <a href="{{ route('user.forex-account-logs') }}" class="btn-link loaderBtn inline-flex items-center">
                                            {{ __('See All') }}
                                            <iconify-icon class="text-lg ltr:ml-1 rtl:mr-1" icon="lucide:chevron-right"></iconify-icon>
                                        </a>
                                    </div>
                                    <div class="flex items-center justify-between gap-5">
                                        <h5 class="text-xl text-slate-900 dark:text-white font-medium">
                                            {{ $demoForexAccountsCount }}
                                        </h5>
                                        <a href="{{route('user.schema')}}" class="btn btn-primary loaderBtn btn-sm inline-flex items-center justify-center">
                                            {{ __('Create Account') }}
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
            $('#copy').text('Copied'); var copyApi = document.getElementById("refLink");
            /* Select the text field */
            copyApi.select();
            copyApi.setSelectionRange(0, 999999999); /* For mobile devices */
            /* Copy the text inside the text field */
            document.execCommand('copy');
            $('#copy').text('Copied')

        }

        // Load More
        $('.moreless-button').click(function () {
            $('.moretext').slideToggle();
            if ($('.moreless-button').text() == "Load more") {
                $(this).text("Load less")
            } else {
                $(this).text("Load more")
            }
        });

        $('.moreless-button-2').click(function () {
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
                    formatter: function (val) {
                        return "$ " + val + " thousands"
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#profitLossChart"), options);
        chart.render();

    </script>

    @if(setting('popup_status', 'popup'))
        <script>
            $(document).ready(function() {
                $('#offerModal').modal('show');
            });
        </script>
    @endif

@endsection
