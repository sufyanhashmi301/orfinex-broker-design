@extends('backend.layouts.app')
@section('title', __('Pricing Funded Plan'))

@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <div class="">
                                <h2 class="title">{{ __('Pricing Funded Plans') }}</h2>
                                <p>
                                    {!! __('Total :count entries.', ['count' => '<span class="text-base">'.$investments->total().'</span>' ]) !!}
                                </p>
                            </div>
                            <div class="">
                                <a href="{{ route('admin.pricing.schemes') }}" class="btn title-btn d-none d-sm-inline-flex">
                                    <em class="icon ni ni-package-fill"></em>
                                    <span>{{ __("Manage Scheme") }}</span>
                                </a>
                                <a href="{{ route('admin.pricing.schemes') }}" class="btn btn-icon title-btn d-inline-flex d-sm-none">
                                    <em class="icon ni ni-package-fill"></em>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="site-tab-bars">
                        <ul>
                            <li class="{{ ($listing=='active') ? ' active' : '' }}">
                                <a href="{{ route('admin.pricing.list', ['status' => 'active']) }}">
                                    <span>{{ __('Actived') }}</span>
                                </a>
                            </li>
                            <li class="{{ ($listing=='pending') ? ' active' : '' }}">
                                <a href="{{ route('admin.pricing.list', ['status' => 'pending']) }}">
                                    <span>{{ __('Pending') }} @if($pendingCount > 0) <span class="site-badge danger px-2">{{ $pendingCount }}</span> @endif </span>
                                </a>
                            </li>
                            <li class="{{ ($listing=='completed') ? ' active' : '' }}">
                                <a href="{{ route('admin.pricing.list', ['status' => 'completed']) }}">
                                    <span>{{ __('Completed') }}</span>
                                </a>
                            </li>
                            <li class="{{ ($listing=='all') ? ' active' : '' }}">
                                <a href="{{ route('admin.pricing.list') }}">
                                    <span>{{ __('All Plans') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-12">
                    <div class="site-card">
                        <div class="site-card-header">
                            <h3 class="title">
                                {{ __(':status Funded Plans', ['status' => ucfirst((($listing=='active') ? 'Actived' : $listing)) ?? 'Actived']) }}
                            </h3>
                        </div>
                        <div class="site-card-body">
                            @if(blank($investments))
                                <div class="alert alert-primary">
                                    <div class="alert-cta flex-wrap flex-md-nowrap">
                                        <div class="alert-text">
                                            <p>{{ __('No investment plan found.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                            <div class="site-table table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('Plan') }}</th>
                                            <th scope="col">{{ __('Invest By') }}</th>
                                            <th scope="col">{{ __('Start Date') }}</th>
                                            <th scope="col">{{ __('Payment Source') }}</th>
                                            <th scope="col">{{ __('Investment ID') }}</th>
                                            <th scope="col">{{ __('Amount') }}</th>
                                            <th scope="col">{{ __('Status') }}</th>
                                            <th scope="col">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($investments as $plan)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="avatar-text">
                                                        {{ strtoupper(substr(data_get($plan, 'scheme.short'), 0, 2)) }}
                                                    </span>
                                                    <div class="ms-2">
                                                        <span class="d-block lh-1 mb-1 fw-bold">
                                                            {{ data_get($plan, 'scheme.name') }}
                                                            <span class="d-none d-md-inline">- {{ data_get($plan, 'calc_details') }}</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="avatar-text">
                                                        {{-- {!! user_avatar($plan->user, 'xs') !!} --}}
                                                    </span>
                                                    <div class="ms-2">
                                                        <span class="d-block lh-1 mb-1 fw-bold">
                                                            {{ data_get($plan, 'user.name') }}
                                                        </span>
                                                        <span class="d-block lh-1 small">
                                                            {{-- {{get_ref_code(data_get($plan, 'user_id'))}} --}}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ show_date(data_get($plan, 'term_start'), true) }}
                                            </td>
                                            <td>
                                                {{ (data_get($plan, 'meta.payment_type')) ? data_get($plan, 'meta.payment_type') : __("N/A") }}    
                                            </td>
                                            <td>
                                                {{ data_get($plan, 'pvx') }}    
                                            </td>
                                            <td>
                                                {{ money(data_get($plan, 'total'), base_currency()) }}
                                            </td>
                                            <td>
                                                <span class="site-badge {{ the_state(data_get($plan, 'status'), ['prefix' => 'badge']) }}">
                                                    {{ __(ucfirst(data_get($plan, 'status'))) }}
                                                </span>    
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.pricing.details', ['id' => the_hash($plan->id)]) }}" class="round-icon-btn primary-btn">
                                                    <i icon-name="chevron-right"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
<div class="modal fade" tabindex="-1" role="dialog" id="ajax-modal">
@endpush

