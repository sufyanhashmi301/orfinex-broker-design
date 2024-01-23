@extends('backend.setting.index')
@section('setting-title')
    {{ __('User Permissions') }}
@endsection
@section('title')
    {{ __('User Permissions') }}
@endsection
@section('setting-content')
    <div class="col-xl-6 col-12">
        <div class="site-card">
            <div class="site-card-header">
                <h3 class="title">{{ __('Finance') }}</h3>
            </div>
            <div class="site-card-body">
                @include('backend.setting.user_permissions.include.__finance')
            </div>
        </div>
        <div class="site-card">
            <div class="site-card-header">
                <h3 class="title">{{ __('Trading Accounts') }}</h3>
            </div>
            <div class="site-card-body">
                @include('backend.setting.user_permissions.include.__trading_accounts')
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-12">
        <div class="site-card">
            <div class="site-card-header">
                <h3 class="title">{{ __('Partner Area') }}</h3>
            </div>
            <div class="site-card-body">
                @include('backend.setting.user_permissions.include.__partner_area')
            </div>
        </div>
        <div class="site-card">
            <div class="site-card-header">
                <h3 class="title">{{ __('Client Area') }}</h3>
            </div>
            <div class="site-card-body">
                @include('backend.setting.user_permissions.include.__client_area')
            </div>
        </div>
    </div>

@endsection