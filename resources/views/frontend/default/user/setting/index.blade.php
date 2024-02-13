@extends('frontend::layouts.user')
@section('title')
    {{ __('Settings') }}
@endsection
@section('content')
    <div class="mb-5">
        <ul class="m-0 p-0 list-none">
            <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter ">
                <a href="{{route('user.dashboard')}}">
                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                </a>
            </li>
            <li class="inline-block relative text-sm text-primary-500 font-Inter ">
                {{ __('Settings') }}
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ __('Settings') }}
            </li>
        </ul>
    </div>
    <div class="space-y-5 profile-page">
        <div class="profiel-wrap px-[35px] pb-10 md:pt-[84px] pt-10 rounded-lg bg-white dark:bg-slate-800 lg:flex lg:space-y-0 space-y-6 justify-between items-end relative z-[1]">
            <div class="bg-slate-900 dark:bg-slate-700 absolute left-0 top-0 md:h-1/2 h-[150px] w-full z-[-1] rounded-t-lg">
            </div>
            <div class="profile-box flex-none md:text-start text-center">
                <div class="md:flex items-end md:space-x-6 rtl:space-x-reverse">
                    <div class="flex-none">
                        <div class="md:h-[186px] md:w-[186px] h-[140px] w-[140px] md:ml-0 md:mr-0 ml-auto mr-auto md:mb-0 mb-4 rounded-full ring-4 ring-slate-100 relative">
                            <img src="@if(auth()->user()->avatar && file_exists('assets/'.auth()->user()->avatar)) {{asset($user->avatar)}} @else {{ asset('frontend/images/all-img/user.png') }}@endif" alt="" class="w-full h-full object-cover rounded-full">
                            <a href="{{route('user.setting.profile')}}" class="absolute right-2 h-8 w-8 bg-slate-50 text-slate-600 rounded-full shadow-sm flex flex-col items-center justify-center md:top-[140px] top-[100px]">
                                <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                            </a>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="text-2xl font-medium text-slate-900 dark:text-slate-200 mb-[3px]">
                            {{auth()->user()->full_name}}
                        </div>
                        <div class="text-sm font-light text-slate-600 dark:text-slate-400">
                            {{ $user->rank->ranking }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- end profile box -->
            <div class="profile-info-500 md:flex md:text-start text-center flex-1 max-w-[516px] md:space-y-0 space-y-4">
                <div class="flex-1">
                    <div class="text-base text-slate-900 dark:text-slate-300 font-medium mb-1">
                        ${{auth()->user()->totalForexBalance()}}
                    </div>
                    <div class="text-sm text-slate-600 font-light dark:text-slate-300">
                        Total Balance
                    </div>
                </div>
                <!-- end single -->
                <div class="flex-1">
                    <div class="text-base text-slate-900 dark:text-slate-300 font-medium mb-1">
                        ${{auth()->user()->totalForexEquity()}}
                    </div>
                    <div class="text-sm text-slate-600 font-light dark:text-slate-300">
                        Equity
                    </div>
                </div>
                <!-- end single -->
                <div class="flex-1">
                    <div class="text-base text-slate-900 dark:text-slate-300 font-medium mb-1">
                        0
                    </div>
                    <div class="text-sm text-slate-600 font-light dark:text-slate-300">
                        Success Point
                    </div>
                </div>
                <!-- end single -->
            </div>
            <!-- profile info-500 -->
        </div>
        <div class="grid grid-cols-12 gap-6">
            <div class="lg:col-span-4 col-span-12">
                <div class="card h-full">
                    <header class="card-header">
                        <h4 class="card-title">Info</h4>
                    </header>
                    <div class="card-body p-6">
                        <ul class="list space-y-8">
                            <li class="flex space-x-3 rtl:space-x-reverse">
                                <div class="flex-none text-2xl text-slate-600 dark:text-slate-300">
                                    <iconify-icon icon="heroicons:envelope"></iconify-icon>
                                </div>
                                <div class="flex-1">
                                    <div class="uppercase text-xs text-slate-500 dark:text-slate-300 mb-1 leading-[12px]">
                                        EMAIL
                                    </div>
                                    <a href="mailto:{{ $user->email }}" class="text-sm text-slate-600 dark:text-slate-50">
                                        {{ $user->email }}
                                    </a>
                                </div>
                                <button class="h-8 w-8 btn-dark inline-flex items-center justify-center rounded-full"
                                type="button"
                                data-bs-toggle="modal"
                                data-bs-target="#emailEditModal">
                                    <iconify-icon icon="heroicons:pencil-square" class="text-lg"></iconify-icon>
                                </button>
                            </li>
                            <!-- end single list -->
                            <li class="flex space-x-3 rtl:space-x-reverse">
                                <div class="flex-none text-2xl text-slate-600 dark:text-slate-300">
                                    <iconify-icon icon="heroicons:phone-arrow-up-right"></iconify-icon>
                                </div>
                                <div class="flex-1">
                                    <div class="uppercase text-xs text-slate-500 dark:text-slate-300 mb-1 leading-[12px]">
                                        PHONE
                                    </div>
                                    <a href="tel:{{ $user->phone }}" class="text-sm text-slate-600 dark:text-slate-50">
                                        {{ $user->phone }}
                                    </a>
                                </div>
                                <button class="h-8 w-8 btn-dark inline-flex items-center justify-center rounded-full
                                type="button"
                                data-bs-toggle="modal"
                                data-bs-target="#phoneEditModal">
                                    <iconify-icon icon="heroicons:pencil-square" class="text-lg"></iconify-icon>
                                </button>
                            </li>
                            <!-- end single list -->
                            <li class="flex space-x-3 rtl:space-x-reverse">
                                <div class="flex-none text-2xl text-slate-600 dark:text-slate-300">
                                    <iconify-icon icon="heroicons:map"></iconify-icon>
                                </div>
                                <div class="flex-1">
                                    <div class="uppercase text-xs text-slate-500 dark:text-slate-300 mb-1 leading-[12px]">
                                        LOCATION
                                    </div>
                                    <div class="text-sm text-slate-600 dark:text-slate-50">
                                        {{ $user->address }}
                                    </div>
                                </div>
                                <button class="h-8 w-8 btn-dark inline-flex items-center justify-center rounded-full"
                                type="button"
                                data-bs-toggle="modal"
                                data-bs-target="#addressEditModal">
                                    <iconify-icon icon="heroicons:pencil-square" class="text-lg"></iconify-icon>
                                </button>
                            </li>
                            <!-- end single list -->
                        </ul>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-8 col-span-12">
                <div class="grid lg:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-3">
                    <a href="{{ route('user.setting.profile') }}" class="card">
                        <div class="card-body p-5">
                            <div class="space-y-6">
                                <div class="flex space-x-3 items-center rtl:space-x-reverse">
                                    <div class="flex-none h-8 w-8 rounded-full bg-slate-800 dark:bg-slate-700 text-slate-300 flex flex-col items-center
                                    justify-center text-lg">
                                        <iconify-icon icon="heroicons:user"></iconify-icon>
                                    </div>
                                    <div class="flex-1 text-base text-slate-900 dark:text-white font-medium">
                                        Profile Setting
                                    </div>
                                </div>
                                <div class="text-slate-600 dark:text-slate-300 text-sm">
                                    Shape Your Financial Identity: Your profile is your professional reflection.
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('user.setting.security') }}" class="card">
                        <div class="card-body p-5">
                            <div class="space-y-6">
                                <div class="flex space-x-3 items-center rtl:space-x-reverse">
                                    <div class="flex-none h-8 w-8 rounded-full bg-primary-500 text-slate-300 flex flex-col items-center justify-center text-lg">
                                        <iconify-icon icon="heroicons:lock-closed"></iconify-icon>
                                    </div>
                                    <div class="flex-1 text-base text-slate-900 dark:text-white font-medium">
                                        Security Setting
                                    </div>
                                </div>
                                <div class="text-slate-600 dark:text-slate-300 text-sm">
                                    Strengthen Your Online Security: It's your primary defense.
                                </div>
                            </div>
                        </div>
                    </a>
                    @if(setting('kyc_verification','permission'))
                    <a href="{{ route('user.kyc') }}" class="card">
                        <div class="card-body p-5">
                            <div class="space-y-6">
                                <div class="flex space-x-3 rtl:space-x-reverse items-center">
                                    <div class="flex-none h-8 w-8 rounded-full bg-success-500 text-white flex flex-col items-center justify-center text-lg">
                                        <iconify-icon icon="heroicons:clipboard-document-list"></iconify-icon>
                                    </div>
                                    <div class="flex-1 text-base text-slate-900 dark:text-white font-medium">
                                        KYC Setting
                                    </div>
                                </div>
                                <div class="text-slate-600 dark:text-slate-300 text-sm">
                                    Ensure Trust and Security: Complete your KYC to access all features.
                                </div>
                            </div>
                        </div>
                    </a>
                    @endif
                    <a href="{{ route('user.agreements') }}" class="card">
                        <div class="card-body p-5">
                            <div class="space-y-6">
                                <div class="flex space-x-3 rtl:space-x-reverse items-center">
                                    <div class="flex-none h-8 w-8 rounded-full bg-slate-800 dark:bg-slate-700 text-slate-300 flex flex-col items-center
                                    justify-center text-lg">
                                        <iconify-icon icon="fa6-regular:handshake"></iconify-icon>
                                    </div>
                                    <div class="flex-1 text-base text-slate-900 dark:text-white font-medium">
                                        Legal Agreements
                                    </div>
                                </div>
                                <div class="text-slate-600 dark:text-slate-300 text-sm">
                                    Stay informed and compliant; review all legal agreements linked to your profile.
                                </div>
                            </div>
                        </div>
                    </a>
        
                    <a href="{{ route('user.margin-account') }}" class="card">
                        <div class="card-body p-5">
                            <div class="space-y-6">
                                <div class="flex space-x-3 rtl:space-x-reverse items-center">
                                    <div class="flex-none h-8 w-8 rounded-full bg-success-500 text-white flex flex-col items-center justify-center text-lg">
                                        <iconify-icon icon="fa6-solid:sack-dollar"></iconify-icon>
                                    </div>
                                    <div class="flex-1 text-base text-slate-900 dark:text-white font-medium">
                                        Margin 4x360
                                    </div>
                                </div>
                                <div class="text-slate-600 dark:text-slate-300 text-sm">
                                    Application of Account Opening of Margin 4x360
                                </div>
                            </div>
                        </div>
                    </a>
        
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Edit Phone -->
    @include('frontend.default.user.setting.profile.modal.__edit_phone')
    
    <!-- Modal for Edit Email -->
    @include('frontend.default.user.setting.profile.modal.__edit_email')
    
    <!-- Modal for Edit Email -->
    @include('frontend.default.user.setting.profile.modal.__edit_address')

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