<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }} ">
    <link
        rel="shortcut icon"
        href="{{ asset(setting('site_favicon','global')) }}"
        type="image/x-icon"
    />
    <link rel="icon" href="{{ asset(setting('site_favicon','global')) }}" type="image/x-icon"/>
    <link rel="stylesheet" href="{{ asset('global/css/simple-notify.min.css') }}"/>
    @notifyCss
    <link rel="stylesheet" href="{{ asset('backend/css/summernote-lite.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('global/css/custom.css?var=2.2') }}"/>
    <link rel="stylesheet" href="{{ asset('global/css/rt-plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('global/css/intlTelInput.css') }}">
    <link rel="stylesheet" href="{{ asset('global/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    @include('global.__styles')

    @yield('style')

    <title>{{ setting('site_title', 'global') }} - @yield('title')</title>
    
    {!!setting('site_admin_header_code', 'defaults')!!}
</head>
