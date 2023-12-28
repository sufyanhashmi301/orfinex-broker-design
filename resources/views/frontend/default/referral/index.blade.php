@extends('frontend::layouts.user')
@section('title')
    {{ __('Dashboard') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap flex-col md:flex-row items-start md:items-center mb-6">
        <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            IB Dashboard
        </h4>
        <div>
            <ul class="nav nav-tabs flex flex-wrap list-none border-b-0 pl-0">
                <li class="nav-item">
                    <a href="{{ route('user.referral') }}" class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 md:px-4 pb-2 my-2 hover:border-transparent focus:border-transparent dark:text-slate-300 {{ isActive('user.referral') }}">
                        IB Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.referral.network') }}" class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 md:px-4 pb-2 my-2 hover:border-transparent focus:border-transparent dark:text-slate-300 {{ isActive('user.referral.network') }}">
                        Network
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.referral.advertisement.material') }}" class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 md:px-4 pb-2 my-2 hover:border-transparent focus:border-transparent dark:text-slate-300 {{ isActive('user.referral.advertisement.material') }}">
                        Resources
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.referral.reports') }}" class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 md:px-4 pb-2 my-2 hover:border-transparent focus:border-transparent dark:text-slate-300 {{ isActive('user.referral.reports') }}">
                        Reports
                    </a>
                </li>
            </ul>
        </div>
    </div>

    @if(request()->routeIs('user.referral'))
        @include('frontend.default.referral.include.__dashboard')
        @include('frontend.default.referral.modal.__qr_code')
    @endif
    @if(request()->routeIs('user.referral.advertisement.material'))
        @include('frontend.default.referral.include.__advertisement_material')
    @endif
    @if(request()->routeIs('user.referral.network'))
        @include('frontend.default.referral.include.__network')
    @endif
    @if(request()->routeIs('user.referral.reports'))
        @include('frontend.default.referral.include.__reports')
    @endif
    {{--    @if(!isset(auth()->user()->ib_status))--}}
        {{-- IB account modal --}}
        @include('frontend.default.referral.modal.__ib_form')
    {{--    @endif--}}

@endsection
@section('script')
    <script>
        function copyRef() {
            /* Get the text field */
            var copyApi = document.getElementById("refLink");
            /* Select the text field */
            copyApi.select();
            copyApi.setSelectionRange(0, 999999999); /* For mobile devices */
            /* Copy the text inside the text field */
            document.execCommand('copy');
            $('#copy').text($('#copied').val())
        }
    </script>
@endsection
