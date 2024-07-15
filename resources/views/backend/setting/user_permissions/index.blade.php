@extends('backend.setting.index')
@section('title')
    {{ __('User Permissions') }}
@endsection
@section('setting-content')
    <div class="lg:col-span-6 col-span-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('Finance') }}</h4>
            </div>
            <div class="card-body p-6">
                @include('backend.setting.user_permissions.include.__finance')
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('Trading Accounts') }}</h4>
            </div>
            <div class="card-body p-6">
                @include('backend.setting.user_permissions.include.__trading_accounts')
            </div>
        </div>
    </div>

    <div class="lg:col-span-6 col-span-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('Partner Area') }}</h4>
            </div>
            <div class="card-body p-6">
                @include('backend.setting.user_permissions.include.__partner_area')
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('Client Area') }}</h4>
            </div>
            <div class="card-body p-6">
                @include('backend.setting.user_permissions.include.__client_area')
            </div>
        </div>
    </div>

@endsection