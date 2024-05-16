@extends('backend.layouts.app')
@section('title', __('Investment Details'))

@php
    use App\Enums\PricingInvestmentStatus;
    use App\Enums\InterestRateType;
    use App\Enums\RefundType;

    $currency = base_currency();

@endphp

@section('content')
    <div class="main-content">
        <div class="page-title mb-5">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <div class="">
                                <h2 class="title">
                                    {!! __('Investment / :summary', ['summary' => '<span class="text-primary small">'.data_get($invest, 'summary_title').'</span>' ]) !!}
                                </h2>
                                <ul class="list-inline">
                                    <li>
                                        {{ __("Funded By:") }} 
                                        <span class="text-base">
                                            {{ the_uid($invest->user->id) }} ({{ str_protect($invest->user->email) }})
                                        </span>
                                    </li>
                                    <li>
                                        {{ __("Invest ID:") }} 
                                        <span class="text-base">{{ the_inv($invest->pvx) }}</span>
                                    </li>
                                    <li>
                                        <span class="site-badge{{ the_state($invest->status, ['prefix' => '']) }}">
                                            {{ ucfirst($invest->status) }}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                            <div class="nk-block-head-content">
                                <ul class="nk-block-tools gx-1">
                                    @if($invest->status != PricingInvestmentStatus::CANCELLED && $invest->status != PricingInvestmentStatus::COMPLETED)
                                        {{--@if($invest->status == PricingInvestmentStatus::ACTIVE)--}}
                                        {{--<li class="order-md-last">--}}
                                        {{--<a href="javascript:void(0)" class="btn btn-warning" data-toggle="modal" data-target="#migrate-investment">--}}
                                        {{--<span>{{ __('Migrate') }}</span> 
                                        {{--</a>
                                        {{--</li>--}}
                                        {{--<li class="order-md-last">--}}
                                        {{--<a href="javascript:void(0)" class="btn btn-danger" data-toggle="modal" data-target="#cancel-investment">--}}
                                        {{--<em class="icon ni ni-cross"></em>--}}
                                        {{--<span>{{ __('Cancel') }}</span>--}}
                                        {{--</a>--}}
                                        {{--</li>--}}
                                        {{--@else--}}
                                        
                                        {{--@endif--}}
                                    @endif
            
                                    @if($invest->status == PricingInvestmentStatus::PENDING)
                                        <li class="order-md-last">
                                            <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#approve-investment">
                                                <em class="icon ni ni-cross"></em> 
                                                <span>{{ __('Approve') }}</span> 
                                            </a>
                                        </li>
                                        <li class="order-md-last">
                                            <a href="javascript:void(0)" class="btn btn-danger m-ivs-cancel" data-confirm="yes" data-reload="yes" data-action="cancelled" data-uid="{{ the_hash($invest->id) }}">
                                                <em class="icon ni ni-cross"></em> 
                                                <span>{{ __('Cancel') }}</span> 
                                            </a>
                                        </li>
                                    @endif
            
                                    @if($invest->status == PricingInvestmentStatus::ACTIVE)
                                        <li>
                                            <a href="{{ route('admin.pricing.details', ['id' => the_hash($invest->id)]) }}" class="btn btn-icon btn-white btn-light">
                                               <em class="icon ni ni-reload"></em>
                                            </a>
                                        </li>
                                    @endif
                                    <li class="order-md-first ml-auto">
                                        <a href="{{ (url()->previous() && (url()->previous() != url()->current())) ? url()->previous() : route('admin.pricing.list') }}" class="btn btn-outline-light bg-white d-none d-sm-inline-flex">
                                           <em class="icon ni ni-arrow-left"></em>
                                           <span>{{ __('Back') }}</span>
                                        </a>
                                        <a href="{{ (url()->previous() && (url()->previous() != url()->current())) ? url()->previous() : route('admin.pricing.list') }}" class="btn btn-icon btn-outline-light bg-white d-inline-flex d-sm-none back-me">
                                           <em class="icon ni ni-arrow-left"></em>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="nk-block">
                <div class="row gx-gs gy-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row gy-gs">
                                    <div class="col-md-6 col-xl col-xxl-8">
                                        <div class="nk-wgacc">
                                            <div class="nk-wgacc-group flex-lg-nowrap gx-4">
                                                <div class="nk-wgacc-sub">
                                                    <div class="nk-wgacc-amount">
                                                        <div class="number">
                                                            {{ amount_z($invest->total, $currency) }} 
                                                            <span class="fw-normal text-base">{{ $currency }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="nk-wgacc-subtitle">{{ __('Funded') }}</div>
                                                </div>
                                                <div class="nk-wgacc-sub">
                                                    <span class="nk-wgacc-sign text-soft">
                                                        <i class="icon" icon-name="plus"></i>
                                                    </span>
                                                    <div class="nk-wgacc-amount">
                                                        <div class="number">{{ amount_z($invest->profit, $currency) }}</div>
                                                    </div>
                                                    <div class="nk-wgacc-subtitle">{{ __('Profit') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xl col-xxl-4">
                                        <div class="nk-wgacc pl-md-3">
                                            <div class="nk-wgacc-group flex-lg-nowrap gx-4">
                                                <div class="nk-wgacc-sub">
                                                    <div class="nk-wgacc-amount">
                                                        <div class="number">
                                                            {{ amount_z($invest->received, $currency) }} 
                                                            <span class="fw-normal text-base">{{ $currency }}</span>
                                                            {{--@if($invest->pending_amount > 0)--}}
                                                            {{--<em class="icon ni ni-info nk-tooltip" title="{{ __('Remain :amount', ['amount' => money($invest->pending_amount, $currency)]) }}"></em>--}}
                                                            {{--@endif--}}
                                                        </div>
                                                    </div>
                                                    {{--<div class="nk-wgacc-subtitle">--}}
                                                    {{--{{ __('Paid Amount') }} {{ (data_get($invest, 'scheme.capital', 0)==0) ? __("(inc. cap)") : (($invest->status != PricingInvestmentStatus::COMPLETED) ? __("(exc. cap)") : '') }}--}}
                                                    {{--</div>--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="nk-plan-details">
                                <ul class="nk-wgacc-list">
                                    <li>
                                        <div class="sub-text">{{ __('Login') }}</div>
                                        <div class="lead-text">{{ data_get($invest, 'login') ?? 'N/A' }} </div>
                                    </li>
                                    <li>
                                        <div class="sub-text">{{ __('Leverage') }}</div>
                                        <div class="lead-text"><span class="currency">1 :</span> {{ data_get($invest, 'leverage') }}
                                        </div>
                                    </li>
                                    <li>
                                        <div class="sub-text">{{ __('Profit Share (%)') }}</div>
                                        <div class="lead-text"> {{ data_get($invest, 'profit_share_user') }}
                                            / {{ data_get($invest, 'profit_share_admin') }} </div>
                                    </li>
                                    <li>
                                        <div class="sub-text">{{ __('Term duration') }}</div>
                                        <div class="lead-text"> {{ ucfirst(data_get($invest, 'days_to_pass')) }} </div>
                                    </li>
    
                                </ul>
                                <ul class="nk-wgacc-list">
                                    <li>
                                        <div class="sub-text">{{ __('Payouts') }}</div>
                                        <div class="lead-text">{{ ucfirst(__(data_get($invest, 'payouts'))) }}</div>
                                    </li>
                                    <li>
                                        <div class="sub-text">{{ __('Daily Drawdown Limit') }}</div>
                                        <div class="lead-text">{{ amount_z(data_get($invest, 'daily_drawdown_limit'), $currency) }} {{ $currency  }}</div>
                                    </li>
                                    <li>
                                        <div class="sub-text">{{ __('Max Drawdown Limit') }}</div>
                                        <div class="lead-text">{{ amount_z(data_get($invest, 'max_drawdown_limit'), $currency) }} {{ $currency  }}</div>
                                    </li>
                                    <li>
                                        <div class="sub-text">{{ __('Account Type') }}</div>
                                        <div class="lead-text">{{ ucfirst(__(data_get($invest, 'account_type'))) }}</div>
                                    </li>
                                    {{--<li>--}}
                                    {{--<div class="sub-text">{{ __('Term duration') }}</div>--}}
                                    {{--<div class="lead-text">{{ data_get($invest, 'term') }}</div>--}}
                                    {{--</li>--}}
    
                                </ul>
                                <ul class="nk-wgacc-list">
                                    <li>
                                        <div class="sub-text">{{ __('Scheme Name') }}</div>
                                        <div class="lead-text">{{ ucfirst(data_get($invest->pricing_scheme, 'name')) ?? 'N/A' }}</div>
                                    </li>
                                    <li>
                                        {{--{{dd(data_get($invest, 'payment_source'))}}--}}
                                        <div class="sub-text">{{ __('Payment source') }}</div>
                                        <div
                                            class="lead-text">{{ (data_get($invest, 'payment_source')) ? (w2n(data_get($invest, 'payment_source')) ? w2n(data_get($invest, 'payment_source')) : data_get($invest, 'payment_source')) : __("N/A") }}</div>
                                    </li>
                                    <li>
                                        <div class="sub-text">{{ __('Term start at') }}</div>
                                        <div class="lead-text">{{ show_date(data_get($invest, 'term_start'), true) ?? 'N/A' }}</div>
                                    </li>
    
                                    <li>
                                        <div class="sub-text">{{ __('Ordered date') }}</div>
                                        <div class="lead-text">{{ show_date(data_get($invest, 'created_at'), true) }}</div>
                                    </li>
    
    
                                    {{--<li>--}}
                                    {{--<div class="sub-text">{{ __('Paid amount') }}</div>--}}
                                    {{--<div class="lead-text">--}}
                                    {{--@if($invest->status!=PricingInvestmentStatus::PENDING)--}}
                                    {{--<span class="currency">{{ $currency  }}</span> {{ amount_z(data_get($invest, 'paid_amount'), $currency) }}--}}
                                    {{--@else--}}
                                    {{--{{ __("N/A") }}--}}
                                    {{--@endif--}}
                                    {{--</div>--}}
                                    {{--</li>--}}
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card card-bordered card-full">
                            <div class="card-inner pb-2">
                                <h6 class="overline-title text-secondary fs-13px">{{ __("Plan Details") }}</h6>
                            </div>
                            <ul class="nk-wgacc-list w-100">
                                <li>
                                    <div class="sub-text">{{ __('Scheme / Plan') }}</div>
                                    <div class="lead-text">
                                        {{ data_get($invest->pricing_scheme, 'name') . ' (' . data_get($invest->pricing_scheme, 'short') . ')' }}
                                    </div>
                                </li>
                                <li>
                                    <div class="sub-text">{{ __('Type') }}</div>
                                    <div class="lead-text">
                                        {{ fst2n((data_get($invest->pricing_scheme, 'type'))) ?? 'N/A' }}
                                    </div>
                                </li>
    
                                <li>
                                    <div class="sub-text">{{ __('Sub Type') }}</div>
                                    <div class="lead-text">
                                        {{ fsst2n((data_get($invest->pricing_scheme, 'sub_type'))) ?? 'N/A' }}
                                    </div>
                                </li>
                                <li>
                                    <div class="sub-text">{{ __('Stage') }}</div>
                                    <div class="lead-text">
                                        {{ (data_get($invest->pricing_scheme, 'stage')) ?? 'N/A' }}
                                    </div>
                                </li>
                                {{--<li>--}}
                                {{--<div class="sub-text">{{ __('Interest rate') }} ({{ ucfirst(data_get($invest->pricing_scheme, 'calc_period')) }})</div>--}}
                                {{--<div class="lead-text">--}}
                                {{--{{ data_get($invest->pricing_scheme, 'rate') . ' (' . ucfirst(data_get($invest->pricing_scheme, 'rate_type')) . ')' }}--}}
                                {{--</div>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                {{--<div class="sub-text">{{ __('Term duration') }}</div>--}}
                                {{--<div class="lead-text">--}}
                                {{--{{ data_get($invest->pricing_scheme, 'term') . ' ' . ucfirst(data_get($invest->pricing_scheme, 'term_type')) }}--}}
                                {{--</div>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                {{--<div class="sub-text">{{ __('Capital return') }}</div>--}}
                                {{--<div class="lead-text">--}}
                                {{--{{ (data_get($invest->pricing_scheme, 'capital', 0)==1) ? __("End of term") : __("Each term") }}--}}
                                {{--</div>--}}
                                {{--</li>--}}
                                {{--<li>--}}
                                {{--<div class="sub-text">{{ __('Fixed investment') }}</div>--}}
                                {{--<div class="lead-text">--}}
                                {{--{{ (data_get($invest->pricing_scheme, 'is_fixed', 0)==1) ? __("Yes") : __("No") }}--}}
                                {{--</div>--}}
                                {{--</li>--}}
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card card-bordered card-full">
                            <div class="card-inner pb-2">
                                <h6 class="overline-title text-secondary fs-13px">{{ __("Action Details") }}</h6>
                            </div>
                            <ul class="nk-wgacc-list w-100">
                                <li>
                                    <div class="sub-text">{{ __('Ordered date') }}</div>
                                    <div class="lead-text">
                                        {{ show_date(data_get($invest, 'created_at'), true) }}
                                    </div>
                                </li>
                                <li>
                                    <div class="sub-text">{{ __('Ordered by') }}</div>
                                    <div class="lead-text">
                                        @if(data_get($invest, 'order_by'))
                                            <em class="icon ni ni-info text-soft small nk-tooltip"
                                                title="{{ str_protect(get_user(data_get($invest, 'order_by'))->email) }}"></em>
                                            {{ get_user(data_get($invest, 'order_by'))->name }}
                                        @else
                                            {{ __("Unknown") }}
                                        @endif
                                    </div>
                                </li>
                                <li>
                                    <div class="sub-text">{{ __('Approved date') }}</div>
                                    <div class="lead-text">
                                        @if(data_get($invest, 'approve_at'))
                                            {{ show_date(data_get($invest, 'approve_at'), true) }}
                                        @else
                                            <em class="text-soft">{{ __("Not yet") }}</em>
                                        @endif
                                    </div>
                                </li>
                                <li>
                                    <div class="sub-text">{{ __('Approved by') }}</div>
                                    <div class="lead-text">
                                        @if(data_get($invest, 'approve_by'))
                                            <em class="icon ni ni-info text-soft small nk-tooltip"
                                                title="{{ str_protect(get_user(data_get($invest, 'approve_by'))->email) }}"></em>
                                            {{ get_user(data_get($invest, 'approve_by'))->name }}
                                        @else
                                            {{ __("N/A") }}
                                        @endif
                                    </div>
                                </li>
                                <li>
                                    <div class="sub-text">{{ __('Completed date') }}</div>
                                    <div class="lead-text">
                                        @if(data_get($invest, 'completed_at'))
                                            {{ show_date(data_get($invest, 'completed_at'), true) }}
                                        @else
                                            @if($invest->status==PricingInvestmentStatus::PENDING)
                                                <em class="text-soft">{{ __("Not started") }}</em>
                                            @else
                                                <em class="text-soft">{{ __("Not completed") }}</em>
                                            @endif
                                        @endif
                                    </div>
                                </li>
                                @if($invest->status==PricingInvestmentStatus::CANCELLED)
                                    <li>
                                        <div class="sub-text">{{ __('Cancelation date') }}</div>
                                        <div class="lead-text">
                                            @if(data_get($invest, 'cancelled_by'))
                                                <em class="icon ni ni-info text-soft small nk-tooltip"
                                                    title="{{ __("Cancelled by :name", ['name' => get_user(data_get($invest, 'cancelled_by'))->name ]) }}"></em>
                                            @endif
                                            {{ show_date(data_get($invest, 'cancelled_at'), true) }}
                                        </div>
                                    </li>
                                @else
                                    <li>
                                        <div class="sub-text">{{ __('Last updated') }}</div>
                                        <div class="lead-text">
                                            {{ show_date(data_get($invest, 'updated_at'), true) }}
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card card-bordered card-full">
                            <div class="card-inner pb-2">
                                <h6 class="overline-title text-secondary fs-13px">{{ __("Client Detail") }}</h6>
                            </div>
                            <ul class="nk-wgacc-list w-100">
                                <li class="flex-column align-start w-100">
                                    <div class="sub-text">{{ __('Name') }}</div>
                                    <div class="lead-text">
                                        {{ (data_get($invest, 'funder_detail.name')) ? data_get($invest, 'funder_detail.name') : __("N/A") }}
                                    </div>
                                </li>
                                <li class="flex-column align-start w-100">
                                    <div class="sub-text">{{ __('Email') }}</div>
                                    <div class="lead-text">
                                        {{ (data_get($invest, 'funder_detail.email')) ? data_get($invest, 'funder_detail.email') : __("N/A") }}
                                    </div>
                                </li>
                                <li class="flex-column align-start w-100">
                                    <div class="sub-text">{{ __('Phone Number') }}</div>
                                    <div class="lead-text">
                                        {{ (data_get($invest, 'funder_detail.phone_number')) ? data_get($invest, 'funder_detail.phone_number') : __("N/A") }}
                                    </div>
                                </li>
                                <li class="flex-column align-start w-100">
                                    <div class="sub-text">{{ __('Whatsapp Number') }}</div>
                                    <div class="lead-text">
                                        {{ (data_get($invest, 'funder_detail.profile_whatsapp')) ? data_get($invest, 'funder_detail.profile_whatsapp') : __("N/A") }}
                                    </div>
                                </li>
    
                                {{--<li class="flex-column align-start w-100">--}}
                                {{--<div class="sub-text">{{ __('Remarks (Admin Only)') }}</div>--}}
                                {{--<div class="lead-text">--}}
                                {{--{!! (data_get($invest, 'remarks')) ? auto_p(data_get($invest, 'remarks')) : __("N/A") !!}--}}
                                {{--</div>--}}
                                {{--</li>--}}
                                @if(data_get($invest, 'cancelled_by')==$invest->user_id)
                                    <li class="flex-column align-start w-100">
                                        <div class="sub-text text-danger pt-2">
                                            {{ __('Caution:') }} {{ __("The investment cancelled by investor.") }}
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-8 ">
                        @if(!empty(data_get($invest, 'meta.receipt')) && !empty(preview_media(data_get($invest, 'meta.receipt'))))
                            <div class="form-group mb-0 logo-preview">
                                <div class="d-flex p-3 bg-dark align-center justify-center round-lg" data-height="70">
                                    <img class="img-fluid w-75" src="{{ preview_media(data_get($invest, 'meta.receipt')) }}">
                                </div>
                                {{--<a href="javascript:void(0);"  class="remove-logo mt-1 d-inline-block" >{{ __("Change") }}</a>--}}
                            </div>
                        @else
                            <label class="form-label overline-title">{{ __('No Deposit Slip Uploaded Yet') }}</label>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    @if($invest->status == PricingInvestmentStatus::PENDING)
        <div class="modal fade" tabindex="-1" role="dialog" id="approve-investment">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                    <div class="modal-body modal-body-md">
                        <h5 class="title nk-modal-title">{{ __("Approved the Investment") }}</h5>
                        <form
                            action="{{ route('admin.pricing.plan.approve', ['id' => the_hash($invest->id)]) }}"
                            method="POST" data-confirm="approved" class="form-validate is-alter">
                            <div class="form-group">
                                <label class="form-label" for="note">{{ __('Note') }}</label>
                                <div class="form-control-wrap">
                                    <input type="text" name="note" class="form-control form-control-lg" id="note"
                                           placeholder="{{ __('Enter a note for user') }}" maxlength="190">
                                </div>
                                <div
                                    class="form-note">{{ __('The note will display to user from transaction details.') }}</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="remarks">{{ __('Remarks') }}</label>
                                <div class="form-control-wrap">
                                    <input type="text" name="remarks" class="form-control form-control-lg" id="remarks"
                                           placeholder="{{ __('Enter a remarks note for admin') }}" maxlength="190">
                                </div>
                                <div
                                    class="form-note">{{ __('The remarks note help to reminder. Only administrator can read from transaction details.') }}</div>
                            </div>
                            <ul class="align-center flex-wrap flex-sm-nowrap gx-1 gy-2">
                                <li>
                                    <button type="button"
                                            class="btn btn-lg btn-primary m-ivs-approve">{{ __('Approve') }}</button>
                                </li>
                                <li>
                                    <button type="button" class="btn btn-lg btn-white" data-dismiss="modal"
                                            data-target="#approve-investment">{{ __('Dismiss') }}</button>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($invest->status == PricingInvestmentStatus::ACTIVE)
        <div class="modal fade" tabindex="-1" role="dialog" id="cancel-investment">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                    <div class="modal-body modal-body-md">
                        <h5 class="title nk-modal-title">{{ __("Cancelled the Investment") }}</h5>
                        <p class="text-danger">
                            <strong>{{ __("Please confirm that you want to cancel this ACTIVE plan.") }}</strong></p>
                        <form
                            action="{{ route('admin.pricing.plan.cancel', ['id' => the_hash($invest->id)]) }}"
                            method="POST" data-confirm="cancelled" class="form-validate is-alter">
                            <div class="form-group">
                                <label class="form-label" for="cancel-note">{{ __('Investment Return Method') }}</label>
                                <div class="form-control-wrap">
                                    <input type="hidden" name="reload" value="true">
                                    <select class="form-select" name="cancel-method">
                                        <option
                                            value="{{ RefundType::TOTAL }}">{{ __("Return :Type Amount", ['type' => RefundType::TOTAL]) }}</option>
                                        <option
                                            value="{{ RefundType::PARTIAL }}">{{ __("Return :Type Amount", ['type' => RefundType::PARTIAL]) }}</option>
                                    </select>
                                </div>
                                <div
                                    class="form-note">{{ __('How do you want to return the investment amount.') }}</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="cancel-note">{{ __('Cancelation Note') }}</label>
                                <div class="form-control-wrap">
                                    <input type="text" name="note" class="form-control" id="cancel-note"
                                           placeholder="{{ __('Enter a note for user') }}" maxlength="190">
                                </div>
                                <div
                                    class="form-note">{{ __('The note will display to user from transaction details.') }}</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="cancel-remarks">{{ __('Remarks') }}</label>
                                <div class="form-control-wrap">
                                    <input type="text" name="remarks" class="form-control" id="cancel-remarks"
                                           placeholder="{{ __('Enter a remarks note for admin') }}" maxlength="190">
                                </div>
                                <div
                                    class="form-note">{{ __('The remarks note help to reminder. Only administrator can read from transaction details.') }}</div>
                            </div>
                            <ul class="align-center flex-wrap flex-sm-nowrap gx-1 gy-2">
                                <li>
                                    <button type="button"
                                            class="btn btn-lg btn-danger m-ivs-cancel-plan">{{ __('Cancel the Plan') }}</button>
                                </li>
                                <li>
                                    <button type="button" class="btn btn-lg btn-white" data-dismiss="modal"
                                            data-target="#cancel-investment">{{ __('Dismiss') }}</button>
                                </li>
                            </ul>
                        </form>
                        <div class="divider stretched"></div>
                        <div class="notes">
                            <ul>
                                <li class="alert-note is-plain">
                                    <em class="icon ni ni-info"></em>
                                    <p>{{ __(":Type Amount: The total funded amount will return to investor account.", ['type' => RefundType::TOTAL]) }}</p>
                                </li>
                                <li class="alert-note is-plain">
                                    <em class="icon ni ni-info"></em>
                                    <p>{{ __(":Type Amount: The adjusted profit will deduct from the amount funded and return amount partially to investor account.", ['type' => RefundType::PARTIAL]) }}</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('investment.admin.pricing.invest.plan_migrate_model',['invest','plans'])
    @endif
@endpush

@push('scripts')
    <script type="text/javascript">
        const routes = {
                cancelled: "{{ route( 'admin.pricing.plan.cancel', ['id' => the_hash($invest->id)] ) }}"
            },
            msgs = {
                approved: {
                    title: "{{ __('Approved Investment?') }}",
                    btn: {cancel: "{{ __('Cancel') }}", confirm: "{{ __('Yes, Procced') }}"},
                    context: "{!! __("Please confirm that you want to procced the request and start the investment.") !!}",
                    custom: "success", type: "info"
                },
                cancelled: {
                    title: "{{ __('Cancel Investment?') }}",
                    btn: {cancel: "{{ __('No') }}", confirm: "{{ __('Yes, Cancel') }}"},
                    context: "{!! __("You cannot revert back this action, so please confirm that you want to cancel.") !!}",
                    custom: "danger", type: "warning"
                }
            };

        $("body").on("click", ".m-ivs-migrate-plan", function () {
            $('.m-ivs-migrate-plan').prop('disabled', true);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var invetsment_id = $('#invetsment_id').val();
            var plan_id = $('#plan_id').val();
            var SITEURL = "{{ url('/') }}";
            $.ajax({
                url: SITEURL + "/admin/pricing/investment/plan/migrate",
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
                                location.reload();
                            }, 900);
                        }
                    } else if (res.error) {
                        $('.m-ivs-migrate-plan').prop('disabled', false);
                        NioApp.Toast(res.error, 'warning');
                        // setTimeout(function(){ location.reload(); }, 900);
                    } else if (res.errors) {
                        $('.m-ivs-migrate-plan').prop('disabled', false);

                        NioApp.Form.errors(res, true);
                    }
                },
                error: function (data) {
                    NioApp.Toast("{{ __("Sorry, something went wrong! Please reload the page and try again.") }}", 'warning');
                }
            });
        });


    </script>
@endpush
