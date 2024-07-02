@extends('frontend::layouts.user')
@section('title')
    {{ __('Dashboard') }}
@endsection
@section('content')

        <div class="grid sm:grid-cols-2 grid-cols-1 gap-5 mb-5">
            <div class="flex items-center justify-between flex-wrap md:nowrap gap-5 bg-no-repeat bg-cover bg-center p-4 rounded-[6px]" style="background-image: url({{ asset('frontend/images/yellow-gradient.png') }});">
                <div class="">
                    <p class="text-base text-slate-600 text-opacity-80 mb-2">
                        You don't have an Active Account yet.
                    </p>
                    <p class="text-lg font-medium text-slate-900">
                        Unlock Exclusive Bonus to Kickstart Your Success.
                    </p>
                </div>
                <div class="ltr:right-6 rtl:left-6">
                    <a href="" class="btn btn-sm btn-dark inline-flex items-center">
                        {{ __('Open New Account') }}
                    </a>
                </div>
            </div>
            <div class="bg-slate-900 dark:bg-slate-800 p-4 rounded-[6px] relative">
                <div class="">
                    <h4 class="text-lg font-medium text-white mb-2">
                        Enjoy Exclusive Benefits & Boost Your Earnings!
                    </h4>
                    <a href="" class="flex items-center text-slate-100">
                        <span>Become IB Partner Now</span>
                        <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon>
                    </a>
                </div>
            </div>
        </div>

        <div class="md:block hidden desktop-screen-show">
            {{-- User Card--}}
            <h4 class="text-lg mb-3">
                {{ __('Account Details') }}
            </h4>
            @include('frontend::user.include.__user_card')

            {{-- Recent Transactions--}}
            <h4 class="text-lg mb-3">
                {{ __('Recent Transactions') }}
            </h4>
            @include('frontend::user.include.__recent_transaction')
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
