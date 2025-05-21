<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords" content="@yield('meta_keywords',setting('site_title','global'))">
    <meta name="description" content="@yield('meta_description',setting('site_title','global'))">
    <link rel="canonical" href="{{ url()->current() }}"/>
    <link rel="shortcut icon" href="{{ asset(setting('site_favicon','global')) }}" type="image/x-icon"/>
    <link rel="icon" href="{{ asset(setting('site_favicon','global')) }}" type="image/x-icon"/>
    <title>{{ setting('site_title', 'global') }} - @yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" href="{{ asset('global/css/simple-notify.min.css') }}"/>
    @notifyCss
    <link rel="stylesheet" href="{{ asset('global/css/custom.css') }}"/>
    <link rel="stylesheet" href="{{ asset('frontend/css/rt-plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/intlTelInput.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/theme_base/prime_x/css/styles.css?var=1') }}"/>
    <style>
        .btn-primary {
            --tw-bg-opacity: 1;
            background-color: rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }} / var(--tw-bg-opacity));
            --tw-ring-opacity: 1;
            --tw-ring-color: rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }} / var(--tw-ring-opacity));
            --tw-text-opacity: 1;
            color: rgba({{ implode(' ', getColorFromSettings('primary_btn_color')) }} / var(--tw-text-opacity));
        }
        .bg-primary, .progress-steps .single-step.current .progress_bar, .after\:bg-primary:after, .before\:bg-primary:before {
            --tw-bg-opacity: 1;
            background-color: rgba({{ implode(' ', getColorFromSettings('primary_color')) }} / var(--tw-bg-opacity));
            --tw-text-opacity: 1;
            color: rgba({{ implode(' ', getColorFromSettings('primary_color')) }} / var(--tw-text-opacity));
        }
        .text-primary {
            --tw-text-opacity: 1;
            color: rgba({{ implode(' ', getColorFromSettings('primary_color')) }} / var(--tw-text-opacity));
        }
        .sidebar-menu .navItem.active, .dark .sidebar-menu .navItem.active, .sidebar-menu>li.active>a {
            --tw-bg-opacity: 1;
            background-color: rgba({{ implode(' ', getColorFromSettings('active_menu_bg')) }} / var(--tw-bg-opacity));
            border-left-color: rgb({{ implode(' ', getColorFromSettings('active_menu_color')) }});
            --tw-text-opacity: 1;
            color: rgba({{ implode(' ', getColorFromSettings('active_menu_color')) }} / var(--tw-text-opacity));
        }
        .grid-view-btn.active, .list-view-btn.active {
            --tw-bg-opacity: 1;
            background-color: rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }} / var(--tw-bg-opacity));
            border-color:  rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }});
            --tw-text-opacity: 1;
            color: rgba({{ implode(' ', getColorFromSettings('primary_btn_color')) }} / var(--tw-text-opacity));
        }
        .outline-buttons .btn.active {
            --tw-bg-opacity: 1;
            background-color: rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }} / var(--tw-bg-opacity));
        }
        .custom-tabs .btn-outline-primary:hover {
            --tw-bg-opacity: 0.35;
            background-color: rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }} / var(--tw-bg-opacity));
        }
        .custom-tabs .btn.active {
            --tw-bg-opacity: 1;
            background-color: rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }} / var(--tw-bg-opacity));
            border-color:  rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }});
            --tw-text-opacity: 1;
            color: rgba({{ implode(' ', getColorFromSettings('primary_btn_color')) }} / var(--tw-text-opacity));
        }
        .border-primary {
            border-color:  rgba({{ implode(' ', getColorFromSettings('primary_color')) }});
        }
        #page-loader .dot {
            --tw-bg-opacity: 1;
            background-color: rgba({{ implode(' ', getColorFromSettings('primary_color')) }} / var(--tw-bg-opacity));
        }
        .badge.badge-primary{
            --tw-bg-opacity: 1;
            background-color: rgba({{ implode(' ', getColorFromSettings('primary_btn_bg')) }} / var(--tw-bg-opacity));
            --tw-text-opacity: 1;
            color: rgba({{ implode(' ', getColorFromSettings('primary_btn_color')) }} / var(--tw-text-opacity));
        }
    </style>
    @stack('style')
    @yield('style')
    <!-- End : Theme CSS-->

    <script src="{{ asset('frontend/js/settings.js') }}" sync></script>
    <script src="https://static.sumsub.com/idensic/static/sns-websdk-builder.js"></script>
    
    {!! setting('site_user_header_code', 'defaults') !!}

</head>
