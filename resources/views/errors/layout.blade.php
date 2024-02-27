<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Jost', sans-serif;
        }

        .dark-bg {
            background: #032836;
            color: #fff;
        }

        .light-bg {
            background: #fff;
            color: #001219b3;
        }

        .unusual-page {
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .unusual-page .unusual-page-img {
            height: 250px;
            margin-bottom: 40px;
        }

        .back-to-home-btn {
            --tw-bg-opacity: 1;
            background-color: rgb(15 23 42 / var(--tw-bg-opacity));
            --tw-text-opacity: 1;
            color: rgb(255 255 255 / var(--tw-text-opacity));
            --tw-ring-opacity: 1;
            --tw-ring-color: rgb(15 23 42 / var(--tw-ring-opacity));
            display: inline-block;
            padding: 13px 27px;
            border-radius: 50px;
            color: #ffffff;
            /* background: #e73667; */
            font-weight: 500;
            font-size: 13px;
            text-transform: uppercase;
            box-shadow: 0px 0px 2px #00304966;
            text-decoration: none;
            margin-top: 20px;
        }

        .back-to-home-btn:hover {
            --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
            --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);
            box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
            --tw-ring-opacity: 0.8;
            --tw-ring-offset-width: 1px;
        }

        @media (max-width: 991px) {
            .unusual-page .unusual-page-img {
                height: 150px;
            }
        }

        .unusual-page .title {
            font-size: 62px;
            font-weight: 700;
            margin-bottom: 30px;
        }

        @media (max-width: 991px) {
            .unusual-page .title {
                font-size: 42px;
            }
        }

        .unusual-page .description {
            font-size: 22px;
            font-weight: 300;
        }

        @media (max-width: 991px) {
            .unusual-page .description {
                font-size: 18px;
            }
        }
    </style>
</head>

<body class="light-bg">

<div class="unusual-page">
    @yield('content')
</div>

</body>
</html>


