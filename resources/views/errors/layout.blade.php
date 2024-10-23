<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" class="light">
    <head>
        <meta charset="UTF-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <meta name="csrf-token" content="{{ csrf_token() }} ">
        <link
            rel="shortcut icon"
            href="{{ asset(setting('site_favicon','global')) }}"
            type="image/x-icon"
        />
        <link rel="icon" href="{{ asset(setting('site_favicon','global')) }}" type="image/x-icon"/>
        <link rel="stylesheet" href="{{ asset('global/css/rt-plugins.css') }}">
        <link rel="stylesheet" href="{{ asset('global/css/app.css') }}">
        <title>{{ setting('site_title', 'global') }} - @yield('title')</title>
        <style>
            .notfound {
                position: relative;
                height: 240px;
            }

            .notfound h1 {
                position: absolute;
                left: 50%;
                top: 50%;
                -webkit-transform: translate(-50%, -50%);
                -ms-transform: translate(-50%, -50%);
                transform: translate(-50%, -50%);
                font-size: 220px;
                font-weight: 900;
                margin: 0;
                text-transform: uppercase;
                letter-spacing: -40px;
                margin-left: -20px;
            }

            .notfound h1>span {
                text-shadow: -8px 0 0 #fff;
            }

            .notfound h3 {
                position: relative;
                font-size: 16px;
                font-weight: 700;
                text-transform: uppercase;
                margin: 0;
                letter-spacing: 3px;
                padding-left: 6px;
            }

            @media only screen and (max-width:767px) {
                .notfound {
                    height: 200px;
                }

                .notfound h1 {
                    font-size: 180px;
                }
            }

            @media only screen and (max-width:480px) {
                .notfound {
                    height: 162px;
                }

                .notfound h1 {
                    font-size: 118px;
                    height: 150px;
                    line-height: 162px;
                    letter-spacing: -20px;
                }
            }
        </style>
    </head>
    <body class=" font-inter skin-default">
        <div class="min-h-screen flex flex-col justify-center items-center text-center py-20">
            @yield('content')
        </div>
        <script src="{{ asset('global/js/jquery-3.6.0.min.js') }}"></script>
        <script src="{{ asset('global/js/rt-plugins.js') }}"></script>
        <script src="{{ asset('global/js/app.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.14/lottie.min.js"></script>
        @yield('script')
    </body>
</html>


