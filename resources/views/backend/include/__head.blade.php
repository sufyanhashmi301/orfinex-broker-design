<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }} ">
    <meta property="og:image" content="{{ getFilteredPath(setting('link_thumbnail','global'), 'fallback/branding/thumbnail.png') }}">
    <link
        rel="shortcut icon"
        href="{{ getFilteredPath(setting('site_favicon','global'), 'fallback/branding/favicon.png') }}"
        type="image/x-icon"
    />
    <link rel="icon" href="{{ getFilteredPath(setting('site_favicon','global'), 'fallback/branding/favicon.png') }}" type="image/x-icon"/>
    <link rel="stylesheet" href="{{ asset('global/css/simple-notify.min.css') }}"/>
    @notifyCss
    <link rel="stylesheet" href="{{ asset('global/css/custom.css?var=2.2') }}"/>
    <link rel="stylesheet" href="{{ asset('global/css/rt-plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('global/css/intlTelInput.css') }}">
    <link rel="stylesheet" href="{{ asset('global/summernote/summernote-lite.min.css') }}">
    <link rel="stylesheet" href="{{ asset('global/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    @include('global.__styles')

    @yield('style')

    <title>{{ setting('site_title', 'global') }} - @yield('title')</title>
</head>
