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
