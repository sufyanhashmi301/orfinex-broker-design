<!-- Frontend Notification System -->
<script src="{{ asset('js/notify.js') }}"></script>
<x-notify-user />

<!-- Third-party libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.14/lottie.min.js"></script>

@if(setting('back_to_top','permission'))
    <script>
        // Back to top functionality using Alpine.js
        document.addEventListener('alpine:init', () => {
            Alpine.data('backToTop', () => ({
                show: false,
                init() {
                    window.addEventListener('scroll', () => {
                        this.show = window.pageYOffset > 100;
                    });
                },
                scrollToTop() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            }));
        });
    </script>
@endif

@yield('script')
@stack('script')
