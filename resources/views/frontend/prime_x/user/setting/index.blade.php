@extends('frontend::layouts.user')
@section('title')
    {{ __('Settings') }}
@endsection
@section('content')
    <div class="pageTitle overflow-x-auto mb-5">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden">
                <ul class="nav nav-tabs custom-tabs inline-flex items-center overflow-hidden rounded list-none border-0 pl-0">
                    <li class="nav-item">
                        <a href="{{ route('user.setting.profile') }}" class="btn btn-sm inline-flex justify-center btn-outline-primary loaderBtn {{ isActive('user.setting.profile') }}">
                            {{ __('Profile') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('user.withdraw.account.index') }}" class="btn btn-sm inline-flex justify-center btn-outline-primary loaderBtn {{ isActive('user.withdraw.account*') }}">
                            {{ __('Withdraw Accounts') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('user.setting.security') }}" class="btn btn-sm inline-flex justify-center btn-outline-primary loaderBtn {{ isActive('user.setting.security') }}">
                            {{ __('Security') }}
                        </a>
                    </li>
                    @if(setting('kyc_verification','permission'))
                    <li class="nav-item">
                        <a href="{{ route('user.kyc') }}" class="btn btn-sm inline-flex justify-center btn-outline-primary loaderBtn {{ isActive('user.kyc') }}">
                            {{ __('KYC') }}
                        </a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('user.setting.preference') }}" class="btn btn-sm inline-flex justify-center btn-outline-primary loaderBtn {{ isActive('user.setting.preference') }}">
                            {{ __('Preference') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('user.agreements') }}" class="btn btn-sm inline-flex justify-center btn-outline-primary loaderBtn {{ isActive('user.agreements') }}">
                            {{ __('Agreements') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('user.setting.tools') }}" class="btn btn-sm inline-flex justify-center btn-outline-primary loaderBtn {{ isActive('user.setting.tools') }}">
                            {{ __('Tools') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @yield('settings-content')

    <!-- Modal for Edit Phone -->
    @include('frontend::.user.setting.profile.modal.__edit_phone')

    <!-- Modal for Edit Email -->
    @include('frontend::.user.setting.profile.modal.__edit_address')

@endsection
@section('script')
    <script src="{{ asset('frontend/js/intlTelInput.min.js') }}"></script>
    <script>
        const input = document.querySelector("#phone");
        window.intlTelInput(input, {
            showSelectedDialCode: true,
            utilsScript: "{{ asset('frontend/js/utils.js') }}",
        });
    </script>
    @yield('settings-script')
@endsection
