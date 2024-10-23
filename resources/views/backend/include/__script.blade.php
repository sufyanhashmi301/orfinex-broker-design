<script src="{{ asset('frontend/js/settings.js') }}" sync></script>
<script src="{{ asset('global/js/jquery.min.js') }}"></script>
<script src="{{ asset('global/js/jquery-migrate.js') }}"></script>
<script src="{{ asset('backend/js/jquery-ui.js') }}"></script>

<script src="{{ asset('global/js/waypoints.min.js') }}"></script>
<script src="{{asset('global/js/jquery.counterup.min.js')}}"></script>
{{-- <script src="{{ asset('backend/js/chart.js') }}"></script> --}}

<script src="{{ asset('global/js/simple-notify.min.js') }}"></script>
<script src="{{ asset('backend/js/main.js?var=5') }}"></script>
<script src="{{ asset('global/js/pusher.min.js') }}"></script>
<script src="{{ asset('global/js/rt-plugins.js') }}"></script>
<script src="{{ asset('global/js/app.js') }}"></script>
<script src="{{ asset('global/js/custom.js?var=6') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.14/lottie.min.js"></script>

@include('global.__notification_script',['for'=>'admin','userId' => ''])
@notifyJs
@yield('script')
@stack('single-script')
<script>

    function removeLoader(loader) {
        if (loader) {
            loader.parentElement.removeChild(loader);
        }
    }

    window.addEventListener('beforeunload', (event) => {
        const loader = document.querySelector('.page-loader');
        if (loader) {
            loader.classList.remove('loader-hidden');
        }
    });

    // Hide loader when the page is fully loaded
    window.addEventListener('load', () => {
        const loader = document.querySelector('.page-loader');
        if (loader) {
            loader.classList.add('loader-hidden');

            const transitionDuration = parseFloat(getComputedStyle(loader).transitionDuration) * 1000;
            const removalTimeout = transitionDuration || 300;

            const transitionEndHandler = () => {
                loader.removeEventListener('transitionend', transitionEndHandler);
                removeLoader(loader);
            };

            loader.addEventListener('transitionend', transitionEndHandler);

            setTimeout(() => {
                removeLoader(loader);
            }, removalTimeout);
        }
    });

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

    var animation = lottie.loadAnimation({
        container: document.getElementById('secure-data'), // ID of the div where the animation will render
        renderer: 'svg',  // Render the animation in SVG format
        loop: true,       // Loop the animation
        autoplay: true,   // Autoplay the animation
        path: '{{ asset('global/json/secure.json') }}' // Path to your JSON file
    });

</script>
<script type="text/javascript" id="zsiqchat">var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || {widgetcode: "siq0d84b4a936b62d007a8b69171ad0bda856c528f3a2459c49b38109540292025a", values:{},ready:function(){}};var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;s.src="https://salesiq.zohopublic.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);</script>
