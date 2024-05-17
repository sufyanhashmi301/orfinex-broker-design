@extends('frontend::layouts.user')

@section('title', __(':Name | Funded Plan', ['name' => data_get($invest, 'summary_title')]))

@php
    use App\Enums\PricingInvestmentStatus;
    use App\Enums\InterestRateType;

    $currency = base_currency();

@endphp

@section('content')
    <div class="nk-block-head relative pb-5">
        <div class="nk-block-head-sub mb-2">
            <a href="{{ route('user.pricing.dashboard') }}" class="inline-flex items-center back-to">
                <iconify-icon icon="material-symbols:arrow-back" class="text-lg mr-2"></iconify-icon>
                <span>{{ __("Funded") }}</span>
            </a>
        </div>
        <div class="flex justify-between flex-wrap items-center gap-4">
            <div class="nk-block-head-content">
                <h3 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block mb-2">
                    {{ data_get($invest, 'summary_title') }}
                </h3>
                <p class="text-sm">
                    {{ the_inv($invest->pvx) }}
                    <span class="badge {{ the_state($invest->status, ['prefix' => 'bg']) }}-500 {{ the_state($invest->status, ['prefix' => 'text']) }}-500 bg-opacity-30 capitalize">
                        {{ ucfirst($invest->status) }}
                    </span>
                </p>
            </div>

            @if($invest->status==PricingInvestmentStatus::VIOLATED )
                <div class="nk-block-head-content">
                    <ul class="nk-block-tools gx-3">
                        <li class="order-md-last">
                            <p>Violated Due To {{data_get($invest,'drawdown_reason')}}</p>
                        </li>
                    </ul>
                </div>
            @endif
            @if($invest->status==PricingInvestmentStatus::PENDING || $invest->status==PricingInvestmentStatus::ACTIVE || $invest->status==PricingInvestmentStatus::INACTIVE)
                <a href="{{ route('user.pricing.show.details', ['id' => the_hash($invest->id)]) }}" class="btn btn-sm btn-white w-8 h-8 inline-flex items-center justify-center">
                    <iconify-icon icon="uiw:reload"></iconify-icon>
                </a>
            @endif
        </div>
    </div>

    <div class="space-y-5 mb-6">
        <div class="card">
            <div class="card-body p-6">
                <div class="grid md:grid-cols-2 grid-cols-1 gap-6">
                    <div class="nk-wgacc">
                        <div class="flex items-end flex-wrap lg:flex-nowrap gx-4">
                            <div class="nk-wgacc-sub">
                                <div class="shrink-0">
                                    <div class="number text-2xl font-bold whitespace-nowrap">
                                        {{ amount_z($invest->total, $currency) }}
                                        <span class="font-normal">{{ $currency }}</span>
                                    </div>
                                </div>
                                <div class="nk-wgacc-subtitle">{{ __('Paid Service Fee') }}</div>
                            </div>
                            {{--                                    <div class="nk-wgacc-sub">--}}
                            {{--                                        <span class="nk-wgacc-sign text-soft"><em class="icon ni ni-plus"></em></span>--}}
                            {{--                                        <div class="nk-wgacc-amount">--}}
                            {{--                                            <div class="number">{{ amount_z($invest->profit, $currency) }}</div>--}}
                            {{--                                        </div>--}}
                            {{--                                        <div class="nk-wgacc-subtitle">{{ __('Expected profit (Max)') }}</div>--}}
                            {{--                                    </div>--}}
                        </div>
                    </div>
                    {{--                        <div class="col-md-6 col-lg-4 offset-lg-2">--}}
                    {{--                            <div class="nk-wgacc pl-md-3">--}}
                    {{--                                <div class="nk-wgacc-group flex-lg-nowrap gx-4">--}}
                    {{--                                    <div class="nk-wgacc-sub">--}}
                    {{--                                        <div class="nk-wgacc-amount">--}}
                    {{--                                            <div class="number">--}}
                    {{--                                                {{ amount_z($invest->received, $currency) }} <span class="fw-normal text-base">{{ $currency }}</span>--}}
                    {{--                                            </div>--}}
                    {{--                                        </div>--}}
                    {{--                                        <div class="nk-wgacc-subtitle">--}}
                    {{--                                            {{ __('Returned until now') }}--}}
                    {{--                                            @if($invest->profit_locked > 0)--}}
                    {{--                                                <em class="icon ni ni-info nk-tooltip text-soft" title="{{ __('The amount (:profit) may locked or pending to adjust into your investment account.', ['profit' => money($invest->profit_locked, $currency)]) }}"></em>--}}
                    {{--                                            @endif--}}
                    {{--                                        </div>--}}
                    {{--                                    </div>--}}
                    {{--                                </div>--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                </div>
            </div>
            <div class="nk-plan-details flex">
                <ul class="nk-wgacc-list grow divide-y divide-slate-100 dark:divide-slate-700">
                    <li class="flex justify-between items-center py-[8px] px-5">
                        <div class="sub-text">{{ __('Login') }}</div>
                        <div class="lead-text">{{ data_get($invest, 'login') ?? 'N/A' }} </div>
                    </li>
                    <li class="flex justify-between items-center py-[8px] px-5">
                        <div class="sub-text">{{ __('Leverage') }}</div>
                        <div class="lead-text">
                            <span class="currency">1 :</span>
                            {{ data_get($invest, 'leverage') }}
                        </div>
                    </li>
                    <li class="flex justify-between items-center py-[8px] px-5">
                        <div class="sub-text">{{ __('Profit Share (%)') }}</div>
                        <div class="lead-text">
                            {{ data_get($invest, 'profit_share_user') }} / {{ data_get($invest, 'profit_share_admin') }}
                        </div>
                    </li>
                    <li class="flex justify-between items-center py-[8px] px-5">
                        <div class="sub-text">{{ __('Term duration') }}</div>
                        <div class="lead-text"> {{ ucfirst(data_get($invest, 'days_to_pass')) }} </div>
                    </li>

                </ul>
                <ul class="nk-wgacc-list grow divide-y divide-slate-100 dark:divide-slate-700">
                    <li class="flex justify-between items-center py-[8px] px-5">
                        <div class="sub-text">{{ __('Payouts') }}</div>
                        <div class="lead-text">{{ ucfirst(__(data_get($invest, 'payouts'))) }}</div>
                    </li>
                    <li class="flex justify-between items-center py-[8px] px-5">
                        <div class="sub-text">{{ __('Daily Drawdown Limit') }}</div>
                        <div class="lead-text">{{ amount_z(data_get($invest, 'daily_drawdown_limit'), $currency) }} {{ $currency  }}</div>
                    </li>
                    <li class="flex justify-between items-center py-[8px] px-5">
                        <div class="sub-text">{{ __('Max Drawdown Limit') }}</div>
                        <div class="lead-text">{{ amount_z(data_get($invest, 'max_drawdown_limit'), $currency) }} {{ $currency  }}</div>
                    </li>
                    <li class="flex justify-between items-center py-[8px] px-5">
                        <div class="sub-text">{{ __('Account Type') }}</div>
                        <div class="lead-text">{{ ucfirst(__(data_get($invest, 'account_type'))) }}</div>
                    </li>
                    {{--                        <li>--}}
                    {{--                            <div class="sub-text">{{ __('Term duration') }}</div>--}}
                    {{--                            <div class="lead-text">{{ data_get($invest, 'term') }}</div>--}}
                    {{--                        </li>--}}

                </ul>
                <ul class="nk-wgacc-list grow divide-y divide-slate-100 dark:divide-slate-700">
                    <li class="flex justify-between items-center py-[8px] px-5">
                        <div class="sub-text">{{ __('Scheme Name') }}</div>
                        <div class="lead-text">{{ ucfirst(data_get($invest->scheme, 'name')) ?? 'N/A' }}</div>
                    </li>
                    <li class="flex justify-between items-center py-[8px] px-5">
                        {{--                            {{dd(data_get($invest, 'payment_source'))}}--}}
                        <div class="sub-text">{{ __('Payment source') }}</div>
                        <div
                            class="lead-text">{{ (data_get($invest, 'payment_source')) ? (w2n(data_get($invest, 'payment_source')) ? w2n(data_get($invest, 'payment_source')) : data_get($invest, 'payment_source')) : __("N/A") }}</div>
                    </li>
                    <li class="flex justify-between items-center py-[8px] px-5">
                        <div class="sub-text">{{ __('Term start at') }}</div>
                        <div class="lead-text">{{ show_date(data_get($invest, 'term_start'), true) ?? 'N/A' }}</div>
                    </li>

                    <li class="flex justify-between items-center py-[8px] px-5">
                        <div class="sub-text">{{ __('Ordered date') }}</div>
                        <div class="lead-text">{{ show_date(data_get($invest, 'created_at'), true) }}</div>
                    </li>


                    {{--                        <li>--}}
                    {{--                            <div class="sub-text">{{ __('Paid amount') }}</div>--}}
                    {{--                            <div class="lead-text">--}}
                    {{--                                @if($invest->status!=PricingInvestmentStatus::PENDING)--}}
                    {{--                                    <span class="currency">{{ $currency  }}</span> {{ amount_z(data_get($invest, 'paid_amount'), $currency) }}--}}
                    {{--                                @else--}}
                    {{--                                    {{ __("N/A") }}--}}
                    {{--                                @endif--}}
                    {{--                            </div>--}}
                    {{--                        </li>--}}
                </ul>
            </div>
        </div>
    </div>
    {{--        <div class="nk-block nk-block-lg">--}}
    {{--            <div class="nk-block-head">--}}
    {{--                <h5 class="nk-block-title">{{ __('Graph View') }}</h5>--}}
    {{--            </div>--}}
    {{--            <div class="row g-gs">--}}
    {{--                <div class="col-lg-5">--}}
    {{--                    <div class="card card-bordered h-100">--}}
    {{--                        <div class="card-inner justify-center text-center h-100">--}}
    {{--                            <div class="nk-wgpg">--}}
    {{--                                <div class="nk-wgpg-head">--}}
    {{--                                    <h5 class="nk-wgpg-title">{{ __('Overview') }}</h5>--}}
    {{--                                </div>--}}
    {{--                                <div class="nk-wgpg-graph">--}}
    {{--                                    <input type="text" class="knob-half" value="{{ data_get($invest, 'progress') }}" data-fgColor="#6576ff" data-bgColor="#d9e5f7" data-thickness=".06" data-width="300" data-height="155" data-displayInput="false">--}}
    {{--                                    <div class="nk-wgpg-graph-result">--}}
    {{--                                        <div class="text-lead">{{ data_get($invest, 'progress') }}%</div>--}}
    {{--                                        <div class="text-sub">{{ data_get($invest, 'rate_text') }} / {{ strtolower(data_get($invest, 'period_text')) }}</div>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="nk-wgpg-graph-minmax"><span>{{ money(0.0, $currency) }}</span><span>{{ money(data_get($invest, 'total'), $currency) }}</span></div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--                <div class="col-lg col-sm-6">--}}
    {{--                    <div class="card card-bordered h-100">--}}
    {{--                        <div class="card-inner justify-center text-center h-100">--}}
    {{--                            <div class="nk-wgpg">--}}
    {{--                                <div class="nk-wgpg-head">--}}
    {{--                                    <h5 class="nk-wgpg-title">{{ __('Net Profit') }}</h5>--}}
    {{--                                    <div class="nk-wgpg-subtitle">{!! __('Earn so far :amount', ['amount' => '<strong>' . money(data_get($invest, 'received'), $currency) . '</strong>']) !!}</div>--}}
    {{--                                </div>--}}
    {{--                                <div class="nk-wgpg-graph sm">--}}
    {{--                                    <input type="text" class="knob-half" value="{{ data_get($invest, 'progress') }}" data-fgColor="#33d895" data-bgColor="#d9e5f7" data-thickness=".07" data-width="240" data-height="125" data-displayInput="false">--}}
    {{--                                    <div class="nk-wgpg-graph-result">--}}
    {{--                                        <div class="text-lead sm">{{ str_replace($currency, '', data_get($invest, 'rate_text')) }}</div>--}}
    {{--                                        <div class="text-sub">{{ __(':calc profit', ['calc' => strtolower(data_get($invest, 'scheme.calc_period'))]) }}</div>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="nk-wgpg-graph-minmax"><span>{{ money(0.0, $currency) }}</span><span>{{ money(data_get($invest, 'profit'), $currency) }}</span></div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--                <div class="col-lg col-sm-6">--}}
    {{--                    <div class="card card-bordered h-100">--}}
    {{--                        <div class="card-inner justify-center text-center h-100">--}}
    {{--                            <div class="nk-wgpg">--}}
    {{--                                <div class="nk-wgpg-head">--}}
    {{--                                    <h5 class="nk-wgpg-title">{{ __('Remain') }}</h5>--}}
    {{--                                    <div class="nk-wgpg-subtitle">{!! __('Adjusted so far :count', ['count' => '<strong>' . data_get($invest, 'term_count') . ' '.__('times').'</strong>']) !!}</div>--}}
    {{--                                </div>--}}
    {{--                                <div class="nk-wgpg-graph sm">--}}
    {{--                                    <input type="text" class="knob-half" value="{{ data_get($invest, 'progress') }}" data-fgColor="#816bff" data-bgColor="#d9e5f7" data-thickness=".07" data-width="240" data-height="125" data-displayInput="false">--}}
    {{--                                    <div class="nk-wgpg-graph-result">--}}
    {{--                                        <div class="text-lead sm">{{ data_get($invest, 'remaining_term') }}</div>--}}
    {{--                                        <div class="text-sub">{{ __('remain to adjust') }}</div>--}}
    {{--                                    </div>--}}
    {{--                                    <div class="nk-wgpg-graph-minmax"><span>{{ __('0 Time') }}</span><span>{{ __(':count Times', ['count' => data_get($invest, 'term_total')]) }}</span></div>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}

    @if(!empty(data_get($invest, 'profits')))
        <div class="card">
            <div class="card-header noborder">
                <h5 class="card-title">{{ __('Transactions') }}</h5>
            </div>
            <div class="card-body px-6 pb-6">
                <div class="overflow-x-auto -mx-6">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden ">
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 table-plans{{ user_meta('iv_history_display') != 'compact' ? ' table-lg': '' }}">
                                <thead class=" border-t border-slate-100 dark:border-slate-800">
                                    <th scope="col" class="table-th">{{ __('Details') }}</th>
                                    <th scope="col" class="table-th">{{ __('Date & Time') }}</th>
                                    <th scope="col" class="table-th">{{ __('Amount') }}</th>
                                    <th scope="col" class="table-th">
                                        <iconify-icon class="icon ntext-lg" icon="lucide:info" title="{{ __("The profit transfered into account balance or not.") }}"></iconify-icon>
                                    </th>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                    <tr>
                                        <td class="table-td">
                                            {{ __("Funded") }}
                                        </td>
                                        <td class="table-td">
                                            {{ show_date(data_get($invest, 'created_at'), true) }}
                                        </td>
                                        <td class="table-td">
                                            <span class="text-danger-500">
                                                - {{ amount_z(data_get($invest, 'total'), $currency) }}
                                            </span>
                                        </td>
                                        <td class="table-td">
                                            <iconify-icon class="text-lg" icon="lucide:info" title="{{ __("Received from :account", ['account' => w2n(data_get($invest, 'payment_source')) ]) }}"></iconify-icon>
                                        </td>
                                    </tr>

                                    @if(data_get($invest, 'scheme.capital') && $invest->status==PricingInvestmentStatus::COMPLETED)
                                        <tr>
                                            <td class="table-td">
                                                {{ __("Captial Return") }}
                                            </td>
                                            <td class="table-td">
                                                {{ show_date(data_get($invest, 'updated_at'), true) }}
                                            </td>
                                            <td class="table-td">
                                                + {{ amount_z(data_get($invest, 'total'), $currency) }}
                                            </td>
                                            <td class="table-td">
                                                <iconify-icon class="text-lg" icon="lucide:info" title="{{ __("Add to :account", ['account' => w2n(data_get($invest, 'payment_dest')) ]) }}"></iconify-icon>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @include('backend.pricing.invest.plan_migrate_model',['invest','plans'])

@endsection

@push('scripts')
    <script>
        const routes = {
                cancelled: "{{ route('user.pricing.invest.cancel', ['id' => the_hash($invest->id)]) }}"
            },
            msgs = {
                cancelled: {
                    title: "{{ __('Cancel Funded?') }}",
                    btn: {cancel: "{{ __('No') }}", confirm: "{{ __('Yes, Cancel') }}"},
                    context: "{!! __("You cannot revert back this action, so please confirm that you want to cancel.") !!}",
                    custom: "danger", type: "warning"
                }
            }
        $("body").on("click", ".m-ivs-migrate-plan", function () {
            $('.m-ivs-migrate-plan').prop('disabled', true);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var invetsment_id = $('#invetsment_id').val();
            var plan_id = $('#plan_id').val();
            console.log(invetsment_id, plan_id);
            var SITEURL = "{{ url('/') }}";
            $.ajax({
                url: SITEURL + "/plan/migrate",
                data: {
                    invetsment_id: invetsment_id, plan_id: plan_id,
                },
                type: "POST",
                success: function (res) {
                    console.log(res.success);
                    if (res.success) {
                        NioApp.Toast(res.success, 'success');
                        if (res.reload) {
                            setTimeout(function () {
                                let url = "{{ route('user.pricing.dashboard') }}";
                                document.location.href = url;
                            }, 900);
                        }
                    } else if (res.error) {
                        NioApp.Toast(res.error, 'warning');
                        $('.m-ivs-migrate-plan').prop('disabled', false);
                        // setTimeout(function(){ location.reload(); }, 900);
                    } else if (res.errors) {
                        NioApp.Form.errors(res, true);
                        $('.m-ivs-migrate-plan').prop('disabled', false);

                    }
                },
                error: function (data) {
                    NioApp.Toast("{{ __("Sorry, something went wrong! Please reload the page and try again.") }}", 'warning');
                }
            });
        });

    </script>
@endpush
