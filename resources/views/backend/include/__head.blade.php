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
    <style>
        .btn-primary {
            --tw-bg-opacity: 1;
            background-color: rgba({{ implode(' ', getColorFromSettings('primary_color')) }} / var(--tw-bg-opacity));
            --tw-ring-opacity: 1;
            --tw-ring-color: rgba({{ implode(' ', getColorFromSettings('primary_color')) }} / var(--tw-ring-opacity));
        }
        .bg-primary {
            --tw-bg-opacity: 1;
            background-color: rgba({{ implode(' ', getColorFromSettings('primary_color')) }} / var(--tw-bg-opacity));
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
        #page-loader .dot {
            --tw-bg-opacity: 1;
            background-color: rgba({{ implode(' ', getColorFromSettings('primary_color')) }} / var(--tw-bg-opacity));
        }
    </style>

    @yield('style')

    <title>{{ setting('site_title', 'global') }} - @yield('title')</title>
</head>
