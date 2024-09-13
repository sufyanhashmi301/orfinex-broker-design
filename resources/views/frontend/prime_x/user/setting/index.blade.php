@extends('frontend::layouts.user')
@section('title')
    {{ __('Settings') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-5">
        <ul class="nav nav-tabs custom-tabs inline-flex items-center overflow-hidden rounded list-none border-0 pl-0">
            <li class="nav-item">
                <a href="{{ route('user.setting.profile') }}" class="btn btn-sm inline-flex justify-center btn-outline-primary loaderBtn {{ isActive('user.setting.profile') }}">
                    Profile
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.setting.security') }}" class="btn btn-sm inline-flex justify-center btn-outline-primary loaderBtn {{ isActive('user.setting.security') }}">
                    Security
                </a>
            </li>
            @if(setting('kyc_verification','permission'))
            <li class="nav-item">
                <a href="{{ route('user.kyc') }}" class="btn btn-sm inline-flex justify-center btn-outline-primary loaderBtn {{ isActive('user.kyc') }}">
                    KYC
                </a>
            </li>
            @endif
            <li class="nav-item">
                <a href="{{ route('user.setting.communication') }}" class="btn btn-sm inline-flex justify-center btn-outline-primary loaderBtn {{ isActive('user.setting.communication') }}">
                    Communication
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.agreements') }}" class="btn btn-sm inline-flex justify-center btn-outline-primary loaderBtn {{ isActive('user.agreements') }}">
                    Agreements
                </a>
            </li>
        </ul>
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
@endsection
