@extends('frontend::layouts.user')
@section('title')
    {{ __(':full_name\'s Dashboard', ['full_name' => auth()->user()->full_name]) }}
@endsection
@section('content')

        <div class="grid grid-cols-{{ $banners->count() }} gap-5 mb-5">
            @foreach($banners as $banner)
                <div class="card flex flex-wrap items-center justify-between md:nowrap  px-4 py-5 gap-5">
                    <div class="">
                        <p class="text-base text-slate-900 dark:text-white text-opacity-80 mb-2">
                            {{ $banner->subtitle }}
                        </p>
                        <h4 class="text-lg font-medium text-slate-900 dark:text-white">
                            {{ $banner->title }}
                        </h4>
                    </div>
                    @if($banners->count() <= 2)
                        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                            <a href="{{ $banner->button_link }}" class="btn btn-dark inline-flex items-center justify-center">
                                {{ $banner->button_text }}
                            </a>
                            <a href="{{ $banner->primary_link }}" class="btn inline-flex items-center justify-center">
                                <span class="flex items-center">
                                    <span>{{ __('Learn More') }}</span>
                                    <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon>
                                </span>
                            </a>
                        </div>
                    @else
                        <a href="{{ $banner->primary_link }}" class="btn inline-flex items-center justify-center">
                            <span>{{ __('Learn More') }}</span>
                            <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon>
                        </a>
                    @endif
                </div>
            @endforeach
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
