<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords" content="@yield('meta_keywords',setting('site_title','global'))">
    <meta name="description" content="@yield('meta_description',setting('site_title','global'))">
    <meta property="og:image" content="{{ getFilteredPath(setting('link_thumbnail','global'), 'fallback/branding/thumbnail.png') }}">
    <link rel="canonical" href="{{ url()->current() }}"/>
    <link rel="shortcut icon" href="{{ getFilteredPath(setting('site_favicon','global'), 'fallback/branding/favicon.png') }}" type="image/x-icon"/>
    <link rel="icon" href="{{ getFilteredPath(setting('site_favicon','global'), 'fallback/branding/favicon.png') }}" type="image/x-icon"/>
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
    <link rel="stylesheet" href="{{ asset('frontend/theme_base/prime_x/css/styles.css?var=1') }}"/>
    @include('global.__styles')
    <!-- End : Theme CSS-->
    <script src="{{ asset('frontend/js/settings.js') }}" sync></script>
    <script src="https://static.sumsub.com/idensic/static/sns-websdk-builder.js"></script>
    <style>
        {{ \App\Models\CustomCss::first()->css }}
    </style>
</head>
