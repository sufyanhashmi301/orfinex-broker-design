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
    
    <!-- Brokeret Theme Assets with Tailwind v4 & Alpine.js -->
    @vite(['resources/css/style.css', 'resources/js/index.js'])
        
    <!-- BEGIN: Theme CSS-->
    @stack('style')
    @yield('style')
</head>
