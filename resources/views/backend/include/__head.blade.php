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

    @php
        // Retrieve and decode the color settings
        $primaryColorSetting = setting('primary_color', 'global');
        $secondaryColorSetting = setting('secondary_color', 'global');
        $activeMenuBgColorSetting = setting('active_menu_bg', 'global');
        $activeMenuTextColorSetting = setting('active_menu_color', 'global');

        $primaryColorArray = json_decode($primaryColorSetting, true);
        $secondaryColorArray = json_decode($secondaryColorSetting, true);
        $activeMenuBgColorArray = json_decode($activeMenuBgColorSetting, true);
        $activeMenuTextColorArray = json_decode($activeMenuTextColorSetting, true);

        // Convert arrays to comma-separated strings
        $primaryColor = "{$primaryColorArray['r']} {$primaryColorArray['g']} {$primaryColorArray['b']}";
        $secondaryColor = "{$secondaryColorArray['r']} {$secondaryColorArray['g']} {$secondaryColorArray['b']}";
        $activeMenuBgColor = "{$activeMenuBgColorArray['r']} {$activeMenuBgColorArray['g']} {$activeMenuBgColorArray['b']}";
        $activeMenuTextColor = "{$activeMenuTextColorArray['r']} {$activeMenuTextColorArray['g']} {$activeMenuTextColorArray['b']}";

    @endphp

    <style>
        .btn-primary {
            --tw-bg-opacity: 1;
            background-color: rgb({{ $primaryColor  }} / var(--tw-bg-opacity));
            --tw-ring-opacity: 1;
            --tw-ring-color: rgb({{ $primaryColor  }} / var(--tw-ring-opacity));
        }

        .sidebar-menu .navItem.active, .dark .sidebar-menu .navItem.active {
            --tw-bg-opacity: 1;
            background-color: rgb({{ $activeMenuBgColor }} / var(--tw-bg-opacity));
            border-left-color: rgb({{ $activeMenuTextColor }});
            --tw-text-opacity: 1;
            color: rgb({{ $activeMenuTextColor }} / var(--tw-text-opacity));
        }
    </style>

    @yield('style')

    <title>{{ setting('site_title', 'global') }} - @yield('title')</title>
</head>
