<script src="{{ asset('frontend/js/settings.js') }}" sync></script>
<script src="{{ asset('global/js/jquery.min.js') }}"></script>
<script src="{{ asset('global/js/jquery-migrate.js') }}"></script>
<script src="{{ asset('backend/js/jquery-ui.js') }}"></script>

<script src="{{ asset('global/js/waypoints.min.js') }}"></script>
<script src="{{asset('global/js/jquery.counterup.min.js')}}"></script>
{{-- <script src="{{ asset('backend/js/chart.js') }}"></script> --}}

<script src="{{ asset('global/js/simple-notify.min.js') }}"></script>
<script src="{{ asset('backend/js/summernote-lite.min.js') }}"></script>
<script src="{{ asset('backend/js/main.js?var=5') }}"></script>
<script src="{{ asset('global/js/pusher.min.js') }}"></script>
<script src="{{ asset('global/js/rt-plugins.js') }}"></script>
<script src="{{ asset('global/js/app.js') }}"></script>
<script src="{{ asset('global/js/custom.js?var=6') }}"></script>

@include('global.__notification_script',['for'=>'admin','userId' => ''])
@notifyJs
@yield('script')
@stack('single-script')
<script>
    document.onreadystatechange = function () {
        var state = document.readyState;
        if (state === 'interactive') {
            document.getElementById('content-wrapper').style.display = 'none';
        } else if (state === 'complete') {
            setTimeout(function() {
                document.getElementById('page-loader').style.display = 'none';
                var contents = document.getElementById('content-wrapper');
                contents.style.display = 'block';
                setTimeout(function() {
                    contents.classList.add('show');
                    document.body.style.overflow = 'auto'; // Re-enable scrolling
                }, 50); // Small delay to ensure display block is applied
            }, 1000); // Adjust delay as needed
        }
    }

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
