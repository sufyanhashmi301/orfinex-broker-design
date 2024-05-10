@extends('frontend::layouts.user')
@section('title', __('Funded Overview'))
@section('content')
    <div class="md:flex justify-between items-center mb-5">
        <div class="">
            <ul class="m-0 p-0 list-none">
                <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter ">
                    <a href="{{route('user.dashboard')}}">
                        <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                        <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                    </a>
                </li>
                <li class="inline-block relative text-sm text-primary-500 font-Inter ">
                    Dashboard
                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
                </li>
                <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                    Fund Board
                </li>
            </ul>
        </div>
        <div class="flex flex-wrap ">
            <a href="{{ route('user.deposit.amount') }}" class="btn btn-sm inline-flex justify-center btn-white dark:bg-slate-700 dark:text-slate-300 m-1">
                <span class="flex items-center">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus"></iconify-icon>
                    <span>Deposit Funds</span>
                </span>
            </a>
            <a href="{{ route('user.pricing.plans') }}" class="btn btn-sm inline-flex justify-center btn-dark dark:bg-slate-700 dark:text-slate-300 m-1">
                <span class="flex items-center">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:hand-coins-light"></iconify-icon>
                    <span>Get Funded</span>
                </span>
            </a>
        </div>
    </div>

    <div class="grid xl:grid-cols-2 grid-cols-1 gap-5">
        <div class="card">
            <div class="card-body p-6">
                <div class="text-slate-600 dark:text-slate-400 mb-3 font-medium">
                    {{ __("Total Funded Balance") }}
                </div>
                <div class="flex items-end flex-nowrap space-x-4">
                    <div class="text-slate-400">
                        <div class="text-2xl font-medium">
                            {{ amount($amounts['amount_allotted'], base_currency(), ['zero' => true]) }}
                            <small class="text-base text-slate-600">{{ base_currency() }}</small>
                        </div>
                        <div class="text-sm text-slate-500 mt-1">{{ __("Allotted Fund") }}</div>
                    </div>
                    <div class="text-slate-400">
                        <span class="absolute">
                            <iconify-icon class="text-xl" icon="ph:plus-bold"></iconify-icon>
                        </span>
                        <div class="pl-8">
                            <div class="text-xl font-medium">
                                {{ amount($amounts['profit'], base_currency(), ['zero' => true]) }}
                                <small class="text-base text-slate-600">{{ base_currency() }}</small>
                            </div>
                        </div>
                        <div class="text-sm text-slate-500 pl-8 mt-1">{{ __("Profit Earned") }}</div>
                    </div>    
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-6">
                <div class="text-slate-600 dark:text-slate-400 mb-3 font-medium">
                    {{ __('Profit Share') }}
                </div>
                <div class="flex items-end flex-nowrap space-x-4">
                    <div class="text-slate-400">
                        <div class="text-2xl font-medium">
                            {{ amount(\Brick\Math\BigDecimal::of($amounts['profit'])->dividedBy(2,2,\Brick\Math\RoundingMode::CEILING), base_currency(), ['zero' => true]) }}
                            <small class="text-base text-slate-600">{{ base_currency() }}</small>
                        </div>
                        <div class="text-sm text-slate-500 mt-1">
                            User
                            {{-- {{ucfirst(auth()->user()->name)}}  --}}
                        </div>
                    </div>
                    <div class="text-slate-400">
                        <span class="absolute">
                            <iconify-icon class="text-xl" icon="ph:plus-bold"></iconify-icon>
                        </span>
                        <div class="pl-8">
                            <div class="text-xl font-medium">
                                {{ amount(\Brick\Math\BigDecimal::of($amounts['profit'])->dividedBy(2,2,\Brick\Math\RoundingMode::CEILING), base_currency(), ['zero' => true]) }}
                                <small class="text-base text-slate-600">{{ base_currency() }}</small>
                            </div>
                        </div>
                        <div class="text-sm text-slate-500 pl-8 mt-1">
                            Orfinex
                            {{-- {{ site_info('apps') }}   --}}
                        </div>
                    </div>    
                </div>
            </div>
            <div class="card-footer py-4">
                <a href="{{ route('user.pricing.history') }}" class="inline-flex leading-5 text-slate-600 dark:text-slate-400 text-sm font-normal hover:text-slate-900">
                    <iconify-icon icon="ph:file-text-light" class="text-secondary-600 ltr:mr-2 rtl:ml-2 text-lg"></iconify-icon>
                    History
                </a>
            </div>
        </div>
    </div>

    @if(count($investments) > 0 )

        @if(!blank($pendingPlans = data_get($investments, 'pending', [])))
            <div class="card mt-5">
                <header class="card-header noborder">
                    <h4 class="card-title">
                        {{ __('Pending Challenge') }} 
                        ({{ count($pendingPlans) }})
                    </h4>
                </header>
                <div class="card-body p-6 pt-0">
                    <div class="overflow-x-auto -mx-6">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden ">
                                <!-- Table header -->
                                <div class="flex flex-row bg-slate-200 dark:bg-slate-700">
                                    <div class="flex-1 text-xs uppercase font-semibold py-3 px-6">{{ __('Fund Title') }}</div>
                                    <div class="flex-1 text-xs uppercase font-semibold py-3 px-6">{{ __('Activation Date') }}</div>
                                    <div class="flex-1 text-xs uppercase font-semibold py-3 px-6">{{ __('Returned until now') }}</div>
                                    <div class="flex-1 text-xs uppercase font-semibold py-3 px-6">{{ __('Detail') }}</div>
                                </div>

                                <div class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">

                                    @foreach($pendingPlans as $plan)
                                        @include('frontend.default.investment.plan-row')
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(!blank($activePlans = data_get($investments, 'active', [])))
            <div class="card mt-5">
                <header class="card-header noborder">
                    <h4 class="card-title">
                        {{ __('Active Challenge') }} 
                        ({{ count($activePlans) }})
                    </h4>
                </header>
                <div class="card-body p-6 pt-0">
                    <div class="overflow-x-auto -mx-6">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden ">
                                <!-- Table header -->
                                <div class="flex flex-row bg-slate-200 dark:bg-slate-700">
                                    <div class="flex-1 text-xs uppercase font-semibold py-3 px-6">{{ __('Fund Title') }}</div>
                                    <div class="flex-1 text-xs uppercase font-semibold py-3 px-6">{{ __('Activation Date') }}</div>
                                    <div class="flex-1 text-xs uppercase font-semibold py-3 px-6">{{ __('Returned until now') }}</div>
                                    <div class="flex-1 text-xs uppercase font-semibold py-3 px-6">{{ __('Detail') }}</div>
                                </div>
                                    
                                <form action="" method="post" id="mergePlansForm" class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                    @csrf
                                    @foreach($activePlans as $plan)
                                        @include('frontend.default.investment.plan-row', $plan)
                                    @endforeach
                                    <div class="text-right mt-3 form-hidden-inputs hidden">
                                        <input type="submit" name="" class="btn btn-primary mr-2" id="get-mergeable-plans" value="Merge" bs-data-toggle="modal" bs-data-target="#select-mergeable-plan-modal">
                                        <a href="javascript:;" class="btn btn-light btn-white" id="cancelPlansMerge">Cancel</a>
                                    </div>
                                    <div class="text-right mt-3 form-hidden-inputs-convert hidden">
                                        <input type="button" name="" class="btn btn-primary mr-2" value="Covert" bs-data-toggle="modal" bs-data-target="#convert-to-orfin-confirm-modal">
                                        <a href="javascript:;" class="btn btn-light btn-white" id="cancelPlansConvert">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(!blank($drawdownPlans = data_get($investments, 'violated', [])))
            <div class="card mt-5">
                <header class="card-header noborder">
                    <h4 class="card-title">
                        {{ __('Violated Challenge') }} 
                        ({{ count($drawdownPlans) }})
                    </h4>
                </header>
                <div class="card-body p-6 pt-0">
                    <div class="overflow-x-auto -mx-6">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden ">
                                <!-- Table header -->
                                <div class="flex flex-row bg-slate-200 dark:bg-slate-700">
                                    <div class="flex-1 text-xs uppercase font-semibold py-3 px-6">{{ __('Fund Title') }}</div>
                                    <div class="flex-1 text-xs uppercase font-semibold py-3 px-6">{{ __('Activation Date') }}</div>
                                    <div class="flex-1 text-xs uppercase font-semibold py-3 px-6">{{ __('Returned until now') }}</div>
                                    <div class="flex-1 text-xs uppercase font-semibold py-3 px-6">{{ __('Detail') }}</div>
                                </div>
                                <form action="" method="post" id="mergePlansForm" class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                    @csrf
                                    @foreach($drawdownPlans as $plan)
                                        @include('frontend.default.investment.plan-row', $plan)
                                    @endforeach
                                    <div class="text-right mt-3 form-hidden-inputs hidden">
                                        <input type="submit" name="" class="btn btn-primary mr-2" id="get-mergeable-plans"
                                            value="Merge" data-toggle="modal" data-target="#select-mergeable-plan-modal">
                                        <a href="javascript:;" class="btn btn-light btn-white" id="cancelPlansMerge">Cancel</a>
                                    </div>
                                    <div class="text-right mt-3 form-hidden-inputs-convert hidden">
                                        <input type="button" name="" class="btn btn-primary mr-2" value="Covert" data-toggle="modal"
                                            data-target="#convert-to-orfin-confirm-modal">
                                        <a href="javascript:;" class="btn btn-light btn-white" id="cancelPlansConvert">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(!blank($recents))
            <div class="card mt-5">
                <header class="card-header noborder">
                    <h4 class="card-title">
                        {{ __('Recently End') }} 
                        ({{ count($recents) }})
                    </h4>
                    <a href="{{route('user.pricing.history', 'completed')}}" class="btn btn-sm btn-dark inline-flex items-center justify-center">
                        {{ __('Go to Archive') }}
                    </a>
                </header>
                <div class="card-body p-6 pt-0">
                    <div class="overflow-x-auto -mx-6">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden ">
                                <!-- Table header -->
                                <div class="flex flex-row bg-slate-200 dark:bg-slate-700">
                                    <div class="flex-1 text-xs uppercase font-semibold py-3 px-6">{{ __('Fund Title') }}</div>
                                    <div class="flex-1 text-xs uppercase font-semibold py-3 px-6">{{ __('Activation Date') }}</div>
                                    <div class="flex-1 text-xs uppercase font-semibold py-3 px-6">{{ __('Returned until now') }}</div>
                                    <div class="flex-1 text-xs uppercase font-semibold py-3 px-6">{{ __('Detail') }}</div>
                                </div>

                                <div class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                    @foreach($recents as $plan)
                                        @include('frontend.default.investment.plan-row', $plan)
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="card mt-5">
            <div class="card-body p-6 pt-0">
                <div class="flex flex-col items-center justify-center">
                    <p class="text-lg text-slate-600 dark:text-slate-100 mb-3">
                        {{ __("No Funded Account Active yet!") }}
                    </p>
                    <a href="" class="btn btn-outline-dark inline-flex items-center justify-center">
                        Start a New Challenge
                    </a>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('modal')
    <div class="modal fade" tabindex="-1" role="dialog" id="ajax-modal"></div>
    <div class="modal fade" tabindex="-1" id="select-mergeable-plan-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <a href="#" class="close" data-dismiss="modal">
                    <em class="icon ni ni-cross-sm"></em>
                </a>
                <div class="modal-body modal-body-md">
                    <div>
                        <div class="mt-3" id="mergeable_plans">

                        </div>
                    </div>
                    <div>
                        <ul class="text-center flex-nowrap gx-2 pt-4">
                            <li>
                                <button type="button" class="btn btn-lg btn-primary" id="merge-confirmation">
                                    {{ __('Confirm Merge') }}
                                </button>
                            </li>
                            <li>
                                <button data-dismiss="modal" type="button" class="btn btn-trans btn-light">
                                    {{ __('Cancel') }}
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')

    <script>

        {{--    var investment=JSON.parse('{!! json_encode($investChart) !!}');--}}

        {{--    --}}{{--var profit=JSON.parse('{!! json_encode($profitChart) !!}');--}}

        {{--    var dailyInvestment = {--}}
        {{--    tooltip: false,--}}
        {{--    legend: true,--}}
        {{--    labels: Object.keys(investment),--}}
        {{--    dataUnit: '{{base_currency()}}',--}}
        {{--    stacked: true,--}}
        {{--    lineTension: .3,--}}
        {{--    datasets: [{--}}
        {{--        label: "Investment",--}}
        {{--        color: "#816bff",--}}
        {{--        background: 'transparent',--}}
        {{--        borderWidth: 2,--}}
        {{--        data: Object.values(investment)--}}
        {{--    }, {--}}
        {{--        label: "Profit",--}}
        {{--        color: "#c4cefe",--}}
        {{--        background: 'transparent',--}}
        {{--        borderWidth: 2,--}}
        {{--        data: Object.values(profit)--}}
        {{--    }]--}}
        {{--};--}}

        $(document).ready(function () {
            $("#mergePlans").click(function () {
                $("#mergePlansForm .form-hidden-inputs").removeClass("hidden");
            });
            $("#convertPlans").click(function () {
                $("#mergePlansForm .form-hidden-inputs-convert").removeClass("hidden");
            });
            $("#cancelPlansMerge").click(function () {
                $("#mergePlansForm .form-hidden-inputs").addClass("hidden");
            });
            $("#cancelPlansConvert").click(function () {
                $("#mergePlansForm .form-hidden-inputs-convert").addClass("hidden");
            });
        });

        $('body').on('click', '#get-mergeable-plans', function (e) {
            e.preventDefault();
            let plans = [];
            $('input[name="selectedPlans[]"]:checked').each(function () {
                console.log(this.value);
                plans.push(this.value);
            });
            let formData = new FormData();
            formData.append('plans', plans);
            get_mergeable_plans(formData);
        });

        function get_mergeable_plans(formData) {
            // debugger;
            $.ajax({
                url: "{{ route('user.pricing.mergeable.plans') }}",
                type: 'POST', data: formData, processData: false, contentType: false,
                // type : 'POST', data : formData, processData: false, contentType: false,
                success: function (res) {
                    if (res.data) {
                        // console.log(res.data);
                        $('#mergeable_plans').html(res.data);
                    } else if (res.error) {
                        NioApp.Toast(res.error, 'warning');
                        setTimeout(function () {
                            location.reload();
                        }, 900);
                    } else if (res.errors) {
                        NioApp.Form.errors(res, true);
                        $('.upload-donation').prop('disabled', false);
                    }
                },
                error: function (data) {
                    $('.upload-donation').prop('disabled', false);
                    NioApp.Toast("{{ __("Sorry, something went wrong! Please reload the page and try again.") }}", 'warning');
                }
            })
        }

        $('body').on('click', '#merge-confirmation', function (e) {
            e.preventDefault();
            $('#merge-confirmation').prop('disabled', true);

            let plans = [];
            $('input[name="selectedPlans[]"]:checked').each(function () {
                // console.log(this.value);
                plans.push(this.value);
            });

            let selected_plan = $('#selected_plan').val();
            console.log(plans, selected_plan);

            if (selected_plan) {
                let formData = new FormData();
                formData.append('plans', plans);
                formData.append('selectedPlan', selected_plan);
                merge_confirmation(formData);
            } else {
                NioApp.Toast('No plan selected', 'warning');

                $('#merge-confirmation').prop('disabled', false);
            }
        });

        function merge_confirmation(formData) {
            // debugger;
            $.ajax({
                url: "{{ route('user.pricing.merge') }}",
                type: 'POST', data: formData, processData: false, contentType: false,
                // type : 'POST', data : formData, processData: false, contentType: false,
                success: function (res) {
                    if (res.success) {
                        // console.log(res.data);
                        NioApp.Toast(res.success, 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 900);
                    } else if (res.error) {
                        NioApp.Toast(res.error, 'warning');
                        setTimeout(function () {
                            location.reload();
                        }, 900);
                    } else if (res.errors) {
                        NioApp.Form.errors(res, true);
                        $('#merge-confirmation').prop('disabled', false);
                    }
                },
                error: function (data) {
                    $('#merge-confirmation').prop('disabled', false);
                    NioApp.Toast("{{ __("Sorry, something went wrong! Please reload the page and try again.") }}", 'warning');
                }
            })
        }

        $('body').on('click', '#convert-confirmation', function (e) {
            var btn = $(this);
            btn.prop('disabled', true);
            // console.log($('#amount').val(),'amount')
            e.preventDefault();
            // $('#merge-confirmation').prop('disabled', true);

            let plans = [];
            $('input[name="selectedPlans[]"]:checked').each(function () {
                // console.log(this.value);
                plans.push(this.value);
            });

            // let selected_plan = $('#selected_plan').val();
            console.log(plans);

            if (plans) {
                let formData = new FormData();
                formData.append('plans', plans);
                // formData.append('selectedPlan', selected_plan);
                var url = "{{ route('user.pricing.convert.orfin') }}";
                submit_form1(formData, btn, url);
            } else {
                NioApp.Toast('No plan selected', 'warning');

                $('#convert-confirmation').prop('disabled', false);
            }

        });

        function submit_form1(formData, btn, url) {
            // debugger;
            $.ajax({
                url: url,
                type: 'POST', data: formData, processData: false, contentType: false,
                success: function (res) {
                    if (res.success) {
                        NioApp.Toast(res.success, 'success');
                        if (res.reload) {
                            setTimeout(function () {
                                location.reload();
                            }, 900);
                        }
                    } else if (res.error) {
                        NioApp.Toast(res.error, 'warning');
                        if (res.reload) {
                            setTimeout(function () {
                                location.reload();
                            }, 900);
                        }
                    } else if (res.errors) {
                        NioApp.Form.errors(res, true);
                        btn.prop('disabled', false);
                    }
                },
                error: function (data) {
                    btn.prop('disabled', false);
                    NioApp.Toast("{{ __("Sorry, something went wrong! Please reload the page and try again.") }}", 'warning');
                }
            })
        }

    </script>

@endpush
