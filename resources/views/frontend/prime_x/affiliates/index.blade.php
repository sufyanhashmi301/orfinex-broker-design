@extends('frontend::layouts.user')
@section('title')
    {{ __('Affiliate Area') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-700 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ __('Affiliate Information') }}
        </h4>
    </div>
    <div class="grid grid-cols-12 gap-5 mb-6">
        <div class="lg:col-span-8 col-span-12 space-y-5">
            <div class="grid lg:grid-cols-2 grid-cols-1 gap-5">
                <div class="card">
                    <div class="card-body p-6">
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-6">
                            {{ __('Total Referrals') }}
                        </p>
                        <div class="flex items-end space-x-3 mb-2">
                            <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                                {{ $referrals->count() }}
                            </h6>
                        </div>
                        {{-- <p class="font-normal text-xs text-slate-600 dark:text-slate-300">
                            {{ __('This Month') }}
                        </p> --}}
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-6">
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-6">
                            {{ __('Active Referrals') }}
                        </p>
                        <div class="flex items-end space-x-3 mb-2">
                            <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                                @php
                                    $active_referrals = 0;
                                    foreach ($referrals as $referral) {
                                        if( count($referral->accountTypeInvestment->where('status', 'active')) > 0 ){
                                            $active_referrals++;
                                        }
                                        
                                    }
                                @endphp
                                {{  $active_referrals }}
                            </h6>
                        </div>
                        {{-- <p class="font-normal text-xs text-slate-600 dark:text-slate-300">
                            {{ __('This Month') }}
                        </p> --}}
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-6">
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-6">
                            {{ __('Purchased Accounts') }}
                        </p>
                        <div class="flex items-end space-x-3 mb-2">
                            <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                                {{ $affiliate_info->refer_count }}
                            </h6>
                        </div>
                        {{-- <p class="font-normal text-xs text-slate-600 dark:text-slate-300">
                            {{ __('This Month') }}
                        </p> --}}
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-6">
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-6">
                            {{ __('Commission Percentage') }}
                        </p>
                        <div class="flex items-end space-x-3 mb-2">
                            <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                                @php
                                    $refer_count = $affiliate_info->refer_count;
                                    if($refer_count == 0){
                                        $refer_count = 1;
                                    }

                                    $commission_percentage = $affiliate_rule_configuration->where('count_start', '<=', $refer_count)->where('count_end', '>=', $refer_count)->first();
                                @endphp
                                {{ $commission_percentage->commission_percentage  }}%

                            </h6>
                        </div>
                        <p class="font-normal text-xs " style="color: #198754">
                            @php
                                $next_level = $commission_percentage->count_end + 1;
                                $next_commission_percentage = $affiliate_rule_configuration->where('count_start', '<=', $next_level)->where('count_end', '>=', $next_level)->first();
                            @endphp
                            @if ( !empty($next_commission_percentage) )
                                <iconify-icon class="" icon="lucide:arrow-up" style="position: relative; top: 3px; font-size: 15px"></iconify-icon>
                                Earn {{ $next_commission_percentage->commission_percentage }}% commission on {{ $next_commission_percentage->count_start }}+ purchased accounts by referrals
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="xl:col-span-4 col-span-12">
            <div class="card h-full">
                <div class="card-body h-full flex flex-col p-6" style="position: relative">

                    <div class="" style="position: absolute; right: 15px; top: 15px">
                        <button type="button" class="btn btn-light btn-sm inline-flex items-center justify-center" onclick="copyToClipboard('{{ $affiliate_info->referral_link }}')">
                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:copy" ></iconify-icon>
                            {{ __('Copy ID') }}
                        </button>
                        @php
                            $referral_link = route('register', ['referral' => $affiliate_info->referral_link ]);
                        @endphp
                        <button type="button" class="btn btn-light btn-sm inline-flex items-center justify-center" onclick="copyToClipboard('{{ $referral_link }}')">
                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:copy"></iconify-icon>
                            {{ __('Copy Link') }}
                        </button>
                    </div>
                    <p class="text-slate-900 dark:text-white text-sm font-medium mb-10">
                        {{ __('Referral ID') }} {{ $affiliate_info->referral_link }}
                    </p>
                    <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-2">
                        {{ __('Withdrawable Balance') }}
                    </p>
                    <h6 class="block mb- text-3xl text-slate-900 dark:text-white font-medium leading-none">
                        {{ $affiliate_info->withdrawable_balance }} {{$currency}}
                    </h6>
                    <a href="{{route('user.withdraw.step1')}}" class="btn btn-dark block-btn inline-flex items-center justify-center mt-auto mb-2">
                        {{ __('Withdraw') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-5 grid-cols-1 gap-5 mb-6">
        <div class="card">
            <div class="card-body p-6">
                <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-3">
                    {{ __('Total Purchase Amount') }}
                </p>
                <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                    {{ $affiliate_info->total_purchase_amount }} {{$currency}}
                </h6>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-6">
                <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-3">
                    {{ __('Total Commission') }}
                </p>
                <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                    {{ $affiliate_info->total_commission }} {{$currency}}
                </h6>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-6">
                <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-3">
                    {{ __('Commission Withdrawn') }}
                </p>
                <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                    {{ $affiliate_info->commission_withdrawn }} {{$currency}}
                </h6>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-6">
                <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-3">
                    {{ __('Pending Commission') }}
                </p>
                <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                    @php
                        $total_pending = 0;
                        if($affiliate_info->commission_pending != '[]'){
                            foreach( $affiliate_info->commission_pending as $obj ) {
                                if($obj['status'] == 'pending'){
                                    $total_pending += $obj['commission'];
                                }
                            }
                        }
                        
                    @endphp
                    {{ number_format($total_pending, 2) }} {{$currency}}
                </h6>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-6">
                <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-3">
                    {{ __('Highest Commission Earned') }}
                </p>
                <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                    {{ $affiliate_info->highest_commission_earned }} {{$currency}}
                </h6>
            </div>
        </div>
    </div>

    <div style="position: relative">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10; font-weight: 500; font-size: 20px">
            <iconify-icon class="text-lg mr-1" style="font-size: 36px; position: relative; top: 3px; left: 50%; transform: traslateX(-50%); margin-bottom: 40px" icon="lucide:info"></iconify-icon>
            
            Commission Analytics will be displayed once there will be enough data
        </div>
        <div class="card mb-6" style="position: relative; filter: blur(4px)">
            <div class="white-overlay">
                <style>
                    .white-overlay {
                        background: rgba(255, 255, 255, 0.7);
                        height: 100%;
                        width: 100%;
                        position: absolute;
                        z-index: 9;
                        
                    }
                </style>
                
            </div>
            <div class="card-body p-6">
                <div id="registredActiveCleints"></div>
            </div>
        </div>
    </div>
    

    {{-- <h4 class="font-medium text-xl capitalize text-slate-700 inline-block mb-3">
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
                        <input type="text" class="form-control !pr-32" id="referral-input" value="abc123" readonly>
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
                            @for ($i = 0; $i <= 3; $i++)
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
    </div> --}}

@endsection
@section('script')
    <script>

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text)
        }


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
                name: "Referrals",
                type: "area",
                data: [44, 55, 41, 67, 22, 43, 21, 41, 56, 27, 43]
            }, {
                name: "Commission",
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
                text: "Commission Analytics",
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
                text: "Analyse Commissions and Referrals on monthly basis",
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
                        url: "",
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
