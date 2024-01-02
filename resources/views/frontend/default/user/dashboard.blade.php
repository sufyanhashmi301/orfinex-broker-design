@extends('frontend::layouts.user')
@section('title')
    {{ __('Dashboard') }}
@endsection
@section('content')

        <div class="md:block hidden desktop-screen-show">
            {{-- User Card--}}
            @include('frontend::user.include.__user_card')
            <div class="grid grid-cols-12 gap-5">
                <div class="col-span-12 lg:col-span-8">
                    <div class="space-y-5">

                        @if(count($realForexAccounts) == 0 && count($demoForexAccounts) == 0)
                        <div class="bg-no-repeat h-[140px] bg-cover bg-center px-10 rounded-[6px] relative flex items-center" style="background-image: url(https://cloud.orfinex.com/crm/no-account.png)">
                            <div class="flex-1">
                                <p class="font-normal text-white text-lg mb-1">You don't have an Active Account yet.</p>
                                <h4 class="font-bold text-white">
                                    Unlock Exclusive Bonus to <br>Kickstart Your Success.
                                </h4>
                            </div>
                            <div class="flex-none">
                                <a href="{{route('user.schema')}}" class="btn btn-light btn-white">Open new account</a>
                            </div>
                        </div>
                       @else
                        <div class="card">
                            <div class="card-header flex-wrap noborder">
                                <div class="mb-4 sm:mb-0">
                                    <ul class="nav nav-tabs flex flex-row flex-wrap list-none border-b-0 pl-0" id="tabs-tab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a href="#tabs-realAccounts"
                                                class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-2 pb-2 my-2 hover:border-transparent focus:border-transparent active dark:text-slate-300"
                                                id="tabs-realAccounts-tab" data-bs-toggle="pill" data-bs-target="#tabs-realAccounts" role="tab"
                                                aria-controls="tabs-realAccounts" aria-selected="true">Live Accounts</a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a href="#tabs-demoAccounts"
                                                class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-2 pb-2 my-2 hover:border-transparent focus:border-transparent dark:text-slate-300"
                                                id="tabs-demoAccounts-tab" data-bs-toggle="pill" data-bs-target="#tabs-demoAccounts" role="tab"
                                                aria-controls="tabs-demoAccounts" aria-selected="false">Demo Accounts</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="sm:space-x-4 space-x-2 rtl:space-x-reverse">
                                    <a href="{{route('user.schema')}}" class="btn btn-dark">Open New Account</a>
                                </div>
                            </div>
                            <div class="card-body px-6 pb-6">
                                <div class="tab-content" id="trading-accounts">
                                    <div class="tab-pane fade show active" id="tabs-realAccounts" role="tabpanel" aria-labelledby="tabs-realAccounts-tab">
                                        {{--Live Accounts--}}
                                        @include('frontend::user.include.__live_accounts')
                                    </div>
                                    <div class="tab-pane fade" id="tabs-demoAccounts" role="tabpanel" aria-labelledby="tabs-demoAccounts-tab">
                                        {{--Demo Accounts--}}
                                        @include('frontend::user.include.__demo_accounts')
                                    </div>
                                </div>
                            </div>
                        </div>
                            @endif
                    </div>
                </div>

                <div class="col-span-12 lg:col-span-4">
                    <div class="space-y-5">

                        @if(auth()->user()->ib_status == \App\Enums\IBStatus::APPROVED)
                        {{--Referral and Ranking --}}
                        @include('frontend::user.include.__referral_ranking')
                        @else
                            <div class="bg-no-repeat h-[140px] bg-cover bg-center px-6 py-4 rounded-[6px] relative flex items-center" style="background-image: url(https://cloud.orfinex.com/crm/no-partner.png)">
                                <div class="flex-1 text-center">
                                    <h6 class="font-bold text-white mb-5">
                                        Enjoy Exclusive Benefits & Boost Your Earnings!
                                    </h6>
                                    <a href="{{ route('user.referral') }}" class="btn btn-light btn-white">Become IB Partner</a>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                {{--Recent Transactions--}}
                @include('frontend::user.include.__recent_transaction')
            </div>
        </div>

         {{--for mobile--}}
        <div class="md:hidden block mobile-screen-show">
            @include('frontend::user.mobile_screen_include.dashboard.__index')
        </div>


@endsection
@section('script')
    <script>
        function copyRef() {
            /* Get the text field */
            var textToCopy = $('#refLink').val();
            // Create a temporary input element
            var tempInput = $('<input>');
            $('body').append(tempInput);
            tempInput.val(textToCopy).select();
            // Copy the text from the temporary input
            document.execCommand('copy');
            // Remove the temporary input element
            tempInput.remove();
            $('#copy').text('Copied'); var copyApi = document.getElementById("refLink");
            /* Select the text field */
            copyApi.select();
            copyApi.setSelectionRange(0, 999999999); /* For mobile devices */
            /* Copy the text inside the text field */
            document.execCommand('copy');
            $('#copy').text('Copied')

        }

        // Load More
        $('.moreless-button').click(function () {
            $('.moretext').slideToggle();
            if ($('.moreless-button').text() == "Load more") {
                $(this).text("Load less")
            } else {
                $(this).text("Load more")
            }
        });

        $('.moreless-button-2').click(function () {
            $('.moretext-2').slideToggle();
            if ($('.moreless-button-2').text() == "Load more") {
                $(this).text("Load less")
            } else {
                $(this).text("Load more")
            }
        });

    </script>
@endsection
