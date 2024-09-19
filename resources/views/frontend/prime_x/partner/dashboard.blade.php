@extends('frontend::layouts.partner')
@section('title')
    {{ __('Partner Dashboard') }}
@endsection
@section('content')

    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-700 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ __('Account Details') }}
        </h4>
    </div>
    <div class="grid grid-cols-12 gap-5 mb-6">
        <div class="lg:col-span-8 col-span-12 space-y-5">
            <div class="grid lg:grid-cols-2 grid-cols-1 gap-5">
                <div class="card">
                    <div class="card-body p-6">
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-6">
                            {{ __('Total Referral') }}
                        </p>
                        <div class="flex items-end space-x-3 mb-2">
                            <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                                {{ $dataCount['total_referral'] }}
                            </h6>
                            <span class="font-normal text-xs text-success-500 dark:text-slate-300 mb-1">
                                +452%
                            </span>
                        </div>
                        <p class="font-normal text-xs text-slate-600 dark:text-slate-300">
                            {{ __('This Month') }}
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-6">
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-6">
                            {{ __('Cumulative volume') }}
                        </p>
                        <div class="flex items-end space-x-3 mb-2">
                            <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                                {{ __('23,25.00 USD') }}
                            </h6>
                            <span class="font-normal text-xs text-success-500 dark:text-slate-300 mb-1">
                                +452%
                            </span>
                        </div>
                        <p class="font-normal text-xs text-slate-600 dark:text-slate-300">
                            {{ __('This Month') }}
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-6">
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-6">
                            {{ __('Cumulative balance of clients') }}
                        </p>
                        <div class="flex items-end space-x-3 mb-2">
                            <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                                {{ __('73,45.00 USD') }}
                            </h6>
                            <span class="font-normal text-xs text-success-500 dark:text-slate-300 mb-1">
                                +452%
                            </span>
                        </div>
                        <p class="font-normal text-xs text-slate-600 dark:text-slate-300">
                            {{ __('This Month') }}
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-6">
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-6">
                            {{ __('Cumulative rebate') }}
                        </p>
                        <div class="flex items-end space-x-3 mb-2">
                            <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                                {{ __('0.00 USD') }}
                            </h6>
                            <span class="font-normal text-xs text-danger-500 dark:text-slate-300 mb-1">
                                -52%
                            </span>
                        </div>
                        <p class="font-normal text-xs text-slate-600 dark:text-slate-300">
                            {{ __('This Month') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="xl:col-span-4 col-span-12">
            <div class="card h-full">
                <div class="card-body h-full flex flex-col p-6">
                    <p class="text-slate-900 dark:text-white text-sm font-medium mb-10">
                        {{ __('Vault ID: :id',['id'=>$account->wallet_id]) }}
                    </p>
                    <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-2">
                        {{ __('Your Current Balance') }}
                    </p>
                    <h6 class="block mb- text-3xl text-slate-900 dark:text-white font-medium leading-none">
                        {{ $affiliateBalance }} {{$currency}}
                    </h6>
                    <a href="{{route('user.withdraw.view')}}" class="btn btn-dark block-btn inline-flex items-center justify-center mt-auto mb-2">
                        {{ __('Withdraw') }}
                    </a>
                    <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                        <div class="bg-slate-100 dark:bg-slate-900 p-2 rounded text-center">
                            <span class="text-slate-600 dark:text-slate-300 text-sm block">
                                {{ __('Sales: 75%') }}
                            </span>
                        </div>
                        <div class="bg-slate-100 dark:bg-slate-900 p-2 rounded text-center">
                            <span class="text-slate-600 dark:text-slate-300 text-sm block">
                                {{ __('Referral: 75%') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-5 grid-cols-1 gap-5 mb-6">
        <div class="card">
            <div class="card-body p-6">
                <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-3">
                    {{ __('Deposit') }}
                </p>
                <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                    {{ $dataCount['total_deposit'] }} {{$currency}}
                </h6>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-6">
                <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-3">
                    {{ __('Withdrawal') }}
                </p>
                <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                    {{ $dataCount['total_withdraw'] }} {{$currency}}
                </h6>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-6">
                <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-3">
                    {{ __('Net Deposit') }}
                </p>
                <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                    {{ $dataCount['net_deposit'] }} {{$currency}}
                </h6>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-6">
                <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-3">
                    {{ __('Rebate') }}
                </p>
                <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                    {{ $dataCount['total_rebate'] }} {{$currency}}
                </h6>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-6">
                <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-3">
                    {{ __('Volume') }}
                </p>
                <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                    {{ $dataCount['total_volume'] }} {{$currency}}
                </h6>
            </div>
        </div>
    </div>

    <div class="card mb-6">
        <div class="card-body p-6">
            <div id="registredActiveCleints"></div>
        </div>
    </div>

    <h4 class="font-medium text-xl capitalize text-slate-700 inline-block mb-3">
        {{ __('Referral Links') }}
    </h4>

    <div class="card mb-6">
        <div class="card-body divide-y divide-slate-100 dark:divide-slate-700 px-6">

            <div class="py-6">
                <div class="flex justify-between flex-wrap items-center mb-5">
                    <h4 class="card-title">{{ __('Signup') }}</h4>
                    <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                        <button type="button" class="btn btn-light btn-sm inline-flex items-center justify-center">
                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="solar:share-circle-line-duotone"></iconify-icon>
                            {{ __('Share') }}
                        </button>
                        <button type="button" class="btn btn-light btn-sm inline-flex items-center justify-center">
                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:share-2"></iconify-icon>
                            {{ __('Invite') }}
                        </button>
                    </div>
                </div>
                <div class="input-area">
                    <div class="relative">
                        <input type="text" class="form-control !pr-32" id="referral-input" value="{{ $getReferral->link }}" readonly>
                        <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                            <a href="javascript:;" class="copy-button" type="button" data-target="#referral-input">{{ __('Copy Link') }}</a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="py-6">
                <div class="flex justify-between flex-wrap items-center mb-5">
                    <h4 class="card-title">{{ __('Account Based') }}</h4>
                    <div class="input-area relative min-w-[184px]">
                        <select name="level_order" class="select2 form-control w-full">
                            @for ($i = 0; $i <= $maxLevelOrder; $i++)
                                <option value="{{ $i }}">{{ __('Level ' . $i) }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <span id="schemes">
                    @include('frontend.prime_x.partner.include.__schemes')
                </span>
            </div>
            <div class="py-6 space-y-5">
                <div class="flex justify-between flex-wrap items-center">
                    <h4 class="card-title">{{ __('Agent') }}</h4>
                </div>
                <div class="input-area grid grid-cols-12 items-center gap-5">
                    <div class="lg:col-span-2 col-span-12 form-label !mb-0">
                        {{ __('Sub IB') }}
                    </div>
                    <div class="lg:col-span-10 col-span-12">
                        <div class="relative">
                            <input type="text" class="form-control !pr-32" id="subId-input" value="http://khjkahd3y9d30jdksads" readonly>
                            <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                <a href="javascript:;" class="copy-button" type="button" data-target="#subId-input">{{ __('Copy Link') }}</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        let l, e;
        var options = {
            chart: {
                height: 450,
                type: "area",
                toolbar: {
                    show: !1
                }
            },
            series: [{
                name: "Registration",
                type: "area",
                data: [44, 55, 41, 67, 22, 43, 21, 41, 56, 27, 43]
            }, {
                name: "Active Clients",
                type: "line",
                data: [30, 25, 36, 30, 45, 35, 64, 52, 59, 36, 39]
            }],
            dataLabels: {
                enabled: !1
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
                show: !0,
                position: "top",
                horizontalAlign: "right",
                fontSize: "12px",
                fontFamily: "Inter",
                offsetY: -60,
                markers: {
                    width: 8,
                    height: 8,
                    offsetY: -1,
                    offsetX: -5,
                    radius: 12
                },
                labels: {
                    colors: l ? "#CBD5E1" : "#475569"
                },
                itemMargin: {
                    horizontal: 18,
                    vertical: 0
                }
            },
            title: {
                text: "Revenue Report",
                align: "left",
                offsetX: e ? "0%" : 0,
                offsetY: 13,
                floating: !1,
                style: {
                    fontSize: "20px",
                    fontWeight: "500",
                    fontFamily: "Inter",
                    color: l ? "#fff" : "#0f172a"
                }
            },
            subtitle: {
                text: "Check all your Total Registration & Active Cleints",
                align: "left",
                offsetX: e ? "0%" : 0,
                offsetY: 42,
                floating: !1,
                style: {
                    fontSize:  '16px',
                    fontWeight:  'normal',
                    fontFamily:  undefined,
                    color:  '#9699a2'
                },
            },
            grid: {
                show: !0,
                borderColor: l ? "#334155" : "#e2e8f0",
                strokeDashArray: 10,
                position: "back"
            },
            fill: {
                type: ['gradient', 'solid'],
                colors: ["#5EC26A", "#E97B35"],
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: .4,
                    opacityTo: .5,
                    stops: [50, 100, 0]
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: l ? "#CBD5E1" : "#475569",
                        fontFamily: "Inter"
                    }
                }
            },
            xaxis: {
                categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                labels: {
                    style: {
                        colors: l ? "#CBD5E1" : "#475569",
                        fontFamily: "Inter"
                    }
                },
                axisBorder: {
                    show: !1
                },
                axisTicks: {
                    show: !1
                }
            },
            padding: {
                top: 0,
                right: 0,
                bottom: 0,
                left: 0
            }
        }

        var chart = new ApexCharts(document.querySelector("#registredActiveCleints"), options);
        chart.render();

        $(document).ready(function() {
            $('.copy-button').click(function() {

                var targetSelector = $(this).data('target');
                var $input = $(targetSelector);

                $input.select();
                document.execCommand('copy');

                // Change the button text and style
                var $button = $(this);
                var originalText = $button.text();
                $button.text('Copied');
                $button.addClass('copy-button');

                // Revert the button text and style after 2 seconds
                setTimeout(function() {
                    $button.text(originalText);
                    $button.removeClass('copy-button');
                }, 2000);

            });
            $(document).ready(function() {
                $('select[name="level_order"]').on('change', function() {
                    var selectedLevel = $(this).val();

                    $.ajax({
                        url: "{{ route('user.multi-level.ib.get.schemes') }}",
                        type: "POST",
                        data: {
                            level_order: selectedLevel,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            // Inject the returned HTML into the container
                            $('#schemes').html(response.html);

                            // Re-bind the copy button functionality if needed
                            bindCopyButtons();
                        }
                    });
                });

                function bindCopyButtons() {
                    $('.copy-button').click(function() {
                        var targetSelector = $(this).data('target');
                        var $input = $(targetSelector);

                        $input.select();
                        document.execCommand('copy');

                        // Change the button text and style
                        var $button = $(this);
                        var originalText = $button.text();
                        $button.text('Copied');
                        $button.addClass('copy-button');

                        // Revert the button text and style after 2 seconds
                        setTimeout(function() {
                            $button.text(originalText);
                            $button.removeClass('copy-button');
                        }, 2000);
                    });
                }

                // Initial binding
                bindCopyButtons();
            });

        });

    </script>
@endsection
