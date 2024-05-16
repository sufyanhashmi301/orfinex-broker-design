@extends('backend.layouts.app')
@section('title', __('Investment - Manage Schemes'))

@php
    use App\Enums\SchemeStatus;
    use App\Enums\InterestRateType;
@endphp

@section('has-content-sidebar', '')

@section('content')
    @if (request()->method() == 'GET')
        {{ session(['ivlistStatus' => request('status')]) }}
    @endif
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <div class="">
                                <h2 class="title">{{ __('Manage Schemes') }}</h2>
                                <p>
                                    {{ __('Manage your funded plan that you want to offer.') }}
                                </p>
                            </div>
                            <div class="">
                                <a href="{{ route('admin.pricing.list') }}" class="btn title-btn d-none d-sm-inline-flex">
                                    <em class="icon ni ni-invest"></em>
                                    <span>{{ __("Funded Plans") }}</span>
                                </a>
                                <a href="{{ route('admin.pricing.list') }}" class="btn title-btn d-inline-flex d-sm-none">
                                    <em class="icon ni ni-invest"></em>
                                </a>
                                @if(has_route('admin.pricing.scheme.save'))
                                <a href="javascript:void(0)" class="btn title-btn d-none d-sm-inline-flex m-ivs-scheme" data-action="scheme" data-view="modal">
                                    <em class="icon ni ni-plus"></em>
                                    <span>{{ __('Add Scheme') }}</span>
                                </a>
                                <a href="javascript:void(0)" class="btn title-btn d-inline-flex d-sm-none m-ivs-scheme" data-action="scheme" data-view="modal">
                                    <em class="icon ni ni-plus"></em>
                                </a>
                                @endif
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
                        <ul id="scheme-list-nav">
                            <li class="{{ ((empty($status)) ? " active" : '') }}">
                                <a href="{{ route('admin.pricing.schemes') }}" data-tab_status="" id="iv-scheme">
                                    {{ __('Scheme / Plan') }}
                                </a>
                            </li>
                            <li class="{{ (($status == 'inactive') ? " active" : '') }}">
                                <a href="{{ route('admin.pricing.schemes', SchemeStatus::INACTIVE) }}">
                                    {{ __('Inactive') }}
                                </a>
                            </li>
                            <li class="{{ (($status == 'archived') ? " active" : '') }}">
                                <a href="{{ route('admin.pricing.schemes', SchemeStatus::ARCHIVED) }}">
                                    {{ __('Archived') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="nk-block" id="scheme-list-container">
                @include('backend.pricing.schemes.cards', $schemes)
            </div>
        </div>
    </div>
@endsection

@push('modal')
<div class="modal fade" tabindex="-1" role="dialog" id="ajax-modal"></div>
@endpush

@push('scripts')
<script type="text/javascript">
    const quick_update = "{{ route('admin.pricing.scheme.status') }}",
        routes = {
            update: "{{ route('admin.pricing.scheme.update') }}",
            scheme: "{{ route('admin.pricing.scheme.action', 'new') }}",
            edit: "{{ route('admin.pricing.scheme.action', 'edit') }}"
        },
        msgs = {
            update: {
                title: "{{ __('Are you sure?') }}",
                btn: {cancel: "{{ __('Cancel') }}", confirm: "{{ __('Yes, Save Changes') }}"},
                context: "{!! __("Please confirm that you want to update the scheme as your changes will affect to new subscription.") !!}",
                custom: "success", type: "info"
            }
        },
        qmsg = { title: "{{ __('Are you sure?') }}", btn: {cancel: "{{ __('Cancel') }}", confirm: "{{ __('Confirm') }}"}, context: "{!! __("Do you want to perform this action?") !!}", action: {active: "{!! __("Do you want to active the funded scheme so users can purchase and invest on this plan?") !!}", inactive: "{!! __("Do you want to inactive the funded scheme? Once you confirmed, users cannot purchase the plan anymore.") !!}", archived: "{!! __("Do you want to archived the scheme? Once you confirmed, the plan will be deleted and cannot purchase the plan anymore.") !!}"} };
</script>
@endpush
