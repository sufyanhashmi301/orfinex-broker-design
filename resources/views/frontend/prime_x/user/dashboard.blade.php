@extends('frontend::layouts.user')
@section('title')
    {{ __(':full_name\'s Dashboard', ['full_name' => auth()->user()->full_name]) }}
@endsection
@section('content')
    @if($banners->count() > 0)
        <div class="grid grid-cols-{{ $banners->count() }} gap-3 mb-3">
            @foreach($banners as $banner)
                <div class="card flex flex-wrap items-center justify-between md:nowrap  px-4 py-5 gap-5">
                    <div class="">
                        <p class="text-base text-slate-900 dark:text-white text-opacity-80 mb-2">
                            {{ $banner->subtitle }}
                        </p>
                        <h4 class="text-lg font-medium text-slate-900 dark:text-white">
                            {{ $banner->title }}
                        </h4>
                    </div>
                    @if($banners->count() <= 2)
                        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                            <a href="{{ $banner->button_link }}"
                               class="btn btn-dark inline-flex items-center justify-center">
                                {{ $banner->button_text }}
                            </a>
                            <a href="{{ $banner->primary_link }}" class="btn inline-flex items-center justify-center dark:text-white">
                                <span class="flex items-center">
                                    <span>{{ __('Learn More') }}</span>
                                    <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon>
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
        @include('frontend::user.include.__user_card')

        <div class="grid md:grid-cols-2 grid-cols-1 gap-3 mb-3">
            <div class="card border dark:border-slate-700">
                <div class="card-body p-6">
                    <div class="mb-5">
                        <h4 class="mb-1">{{ __('Free Trail') }}</h4>
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
                    <a href="javascript:;" class="btn inline-flex justify-center btn-dark w-full mt-5" type="button" data-bs-toggle="modal" data-bs-target="#comingSoonModal">
                        {{ __('Start Free Trial') }}
                    </a>
                </div>
            </div>
            <div class="card border dark:border-slate-700">
                <div class="card-body p-6">
                    <div class="mb-5">
                        <h4 class="mb-1">{{ setting('site_title', 'global') }} {{ __('Challenge') }}</h4>
                        <p class="text-sm text-success-500 mb-2">
                            {{ __('Trade with up to $100,000 in Funding') }}
                        </p>
                        <p class="text-slate-900 dark:text-white text-sm min-h-[3.75rem]">
                            {{ __('Showcase your trading proficiency by completing the evaluation and qualify for a funded account.') }}
                        </p>
                    </div>
                    <ul class="bg-slate-50 dark:bg-dark divide-y divide-slate-100 dark:divide-slate-700 px-3 rounded">
                        <li class="flex items-center text-sm text-slate-600 dark:text-slate-300 py-3">
                            <iconify-icon class="text-lg text-primary mr-2" icon="lucide:check"></iconify-icon>
                            {{ __('Receive funding up to $100,000') }}
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
                    <a href="javascript:;" class="btn inline-flex justify-center btn-primary w-full mt-5">
                        {{ __('Start Challenge') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-6 md:grid-cols-3 grid-cols-2 gap-3 mb-3">
            <a href="{{ route('user.deposit.amount') }}" class="card">
                <div class="card-body flex flex-col items-center justify-center p-8">
                    <div
                        class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-body text-primary mb-3">
                        <iconify-icon icon="heroicons-outline:download"></iconify-icon>
                    </div>
                    <div class="text-lg text-slate-900 dark:text-white font-medium">
                        {{ __('Payment') }}
                    </div>
                </div>
            </a>
            <a href="{{ route('user.withdraw.view') }}" class="card">
                <div class="card-body flex flex-col items-center justify-center p-8">
                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-body text-primary mb-3">
                        <iconify-icon icon="heroicons-outline:upload"></iconify-icon>
                    </div>
                    <div class="text-lg text-slate-900 dark:text-white font-medium">
                        {{ __('Payout') }}
                    </div>
                </div>
            </a>
            <a href="{{ route('user.leaderboard') }}" class="card">
                <div class="card-body flex flex-col items-center justify-center p-8">
                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-body text-primary mb-3">
                        <iconify-icon icon="lucide:trophy"></iconify-icon>
                    </div>
                    <div class="text-lg text-slate-900 dark:text-white font-medium">
                        {{ __('Leaderboard') }}
                    </div>
                </div>
            </a>
            <a href="{{ route('user.forex-account-logs') }}" class="card">
                <div class="card-body flex flex-col items-center justify-center p-8">
                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-body text-primary mb-3">
                        <iconify-icon icon="uil:chart-line"></iconify-icon>
                    </div>
                    <div class="text-lg text-slate-900 dark:text-white font-medium">
                        {{ __('Accounts') }}
                    </div>
                </div>
            </a>
            <a href="{{ route('user.kyc') }}" class="card">
                <div class="card-body flex flex-col items-center justify-center p-8">
                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-body text-primary mb-3">
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

        <div class="grid grid-cols-12 gap-3 mb-3">
            <div class="lg:col-span-7 col-span-12">
                <div class="card h-full flex flex-col">
                    <header class="card-header noborder">
                        <h4 class="card-title">{{ __('Accounts') }}</h4>
                    </header>
                    <div class="card-body p-6 pt-0 mt-auto">
                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-3">
                            <div class="bg-slate-100 dark:bg-body">
                                <div class="card-body p-4 py-6">
                                    <div class="flex items-center justify-between gap-5 mb-10">
                                        <h5 class="text-lg text-slate-900 dark:text-white font-medium">{{ __('Challenge Accounts:') }}</h5>
                                        <a href="{{ route('user.forex-account-logs') }}"
                                           class="btn-link inline-flex items-center">
                                            {{ __('See All') }}
                                            <iconify-icon class="text-lg ltr:ml-1 rtl:mr-1"
                                                          icon="lucide:chevron-right"></iconify-icon>
                                        </a>
                                    </div>
                                    <div class="flex items-center justify-between gap-5">
                                        <h5 class="text-xl text-slate-900 dark:text-white font-medium">0</h5>
                                        <a href="{{route('user.schema')}}"
                                           class="btn btn-primary btn-sm inline-flex items-center justify-center">
                                            {{ __('Start Challenge') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-slate-100 dark:bg-body">
                                <div class="card-body p-4 py-6">
                                    <div class="flex items-center justify-between gap-5 mb-10">
                                        <h5 class="text-lg text-slate-900 dark:text-white font-medium">{{ __('Funded Accounts:') }}</h5>
                                        <a href="{{ route('user.forex-account-logs') }}"
                                           class="btn-link inline-flex items-center">
                                            {{ __('See All') }}
                                            <iconify-icon class="text-lg ltr:ml-1 rtl:mr-1"
                                                          icon="lucide:chevron-right"></iconify-icon>
                                        </a>
                                    </div>
                                    <div class="flex items-center justify-between gap-5">
                                        <h5 class="text-xl text-slate-900 dark:text-white font-medium">0</h5>
                                        <a href="{{route('user.schema')}}"
                                           class="btn btn-primary btn-sm inline-flex items-center justify-center">
                                            {{ __('Challenge Accounts') }}
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
                    <div class="card-body p-6 pb-0">
                        <div id="profitLossChart"></div>
                    </div>
                </div>
            </div>
        </div>
        @include('frontend::user.include.__recent_transaction')
        @include('frontend::user.include.__pending_challenge')
        @include('frontend::user.include.__active_challenge')
        @include('frontend::user.include.__violated_challenge')
    </div>

    {{--for mobile--}}
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
@endsection
