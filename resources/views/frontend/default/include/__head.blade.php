<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords" content="@yield('meta_keywords',setting('site_title','global'))">
    <meta name="description" content="@yield('meta_description',setting('site_title','global'))">
    <meta property="og:image" content="{{env('APP_URL')}}.'/assets/images/ZH8Dw583JGGOFKg3x85z.png">
    <link rel="canonical" href="{{ url()->current() }}"/>
    <link rel="shortcut icon" href="{{ asset(setting('site_favicon','global')) }}" type="image/x-icon"/>
    <link rel="icon" href="{{ asset(setting('site_favicon','global')) }}" type="image/x-icon"/>
    <title>{{ setting('site_title', 'global') }} - @yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- BEGIN: Theme CSS-->
    @stack('style')
    @notifyCss
    @yield('style')
    <link rel="stylesheet" href="{{ asset('global/css/simple-notify.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('global/css/custom.css') }}"/>
    <link rel="stylesheet" href="{{ asset('frontend/css/rt-plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/intlTelInput.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/app.css') }}">
    <!-- End : Theme CSS-->
    <script src="{{ asset('frontend/js/settings.js') }}" sync></script>
    <style>
        {{ \App\Models\CustomCss::first()->css }}
    </style>
</head>
