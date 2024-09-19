<script src="{{ asset('frontend/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('frontend/js/rt-plugins.js') }}"></script>
<script src="{{ asset('global/js/simple-notify.min.js') }}"></script>
<script src="{{ asset('frontend/js/app.js') }}"></script>
<script src="{{ asset('global/js/custom.js?var=6') }}"></script>
@include('global.__t_notify')
@if(auth()->check())
    <script src="{{ asset('global/js/pusher.min.js') }}"></script>
    @include('global.__notification_script',['for'=>'user','userId' => auth()->user()->id])
@endif
{{--@if(setting('site_animation','permission'))--}}
{{--    <script>--}}
{{--        (function ($) {--}}
{{--            'use strict';--}}
{{--            // AOS initialization--}}
{{--            AOS.init();--}}
{{--        })(jQuery);--}}
{{--    </script>--}}
{{--@endif--}}
@if(setting('back_to_top','permission'))
    <script>
        (function ($) {
            'use strict';
            // To top
            $.scrollUp({
                scrollText: '<i class="fas fa-caret-up"></i>',
                easingType: 'linear',
                scrollSpeed: 500,
                animation: 'fade'
            });
        })(jQuery);
    </script>
@endif

@notifyJs

@yield('script')
@stack('script')
<script>
    $(document).ready(function () {
        function calculateHeights() {
            // Store heights in variables, checking if elements exist
            var headerHeight = $('#app_header').length ? $('#app_header').outerHeight() : 0;
            var footerHeight = $('#footer').length ? $('#footer').outerHeight() : 0;
            var titleHeight = $('.pageTitle').length ? $('.pageTitle').outerHeight() + 24 : 0;
            var tabsHeight = $('.innerMenu').length ? $('.innerMenu').outerHeight() + 20 : 0;

            // Calculate the available height for content
            var totalHeight = headerHeight + footerHeight + titleHeight + tabsHeight + 73;
            var minHeight = 'calc(100vh - ' + totalHeight + 'px)';

            $('.dataTables_wrapper, .basicTable_wrapper').css('min-height', minHeight);
        }

        // Run the function on page load and window resize
        calculateHeights();
        // $(window).resize(calculateHeights);
    });
</script>
@php
    $googleAnalytics = plugin_active('Google Analytics');
    $tawkChat = plugin_active('Tawk Chat');
    $fb = plugin_active('Facebook Messenger');
    $customChat = plugin_active('Custom Chat');
@endphp

@if($googleAnalytics)
    @include('frontend::plugin.google_analytics',['GoogleAnalyticsId' => json_decode($googleAnalytics?->data,true)['app_id']])
@endif
@if($tawkChat)
    @include('frontend::plugin.tawk',['data' => json_decode($tawkChat->data, true)])
@endif
@if($fb)
    @include('frontend::plugin.fb',['data' => json_decode($fb->data, true)])
@endif
@if($customChat)
    @include('frontend::plugin.custom_chat',['data' => json_decode($customChat->data, true)])
@endif


