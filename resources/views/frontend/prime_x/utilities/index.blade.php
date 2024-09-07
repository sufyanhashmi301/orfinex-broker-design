@extends('frontend::layouts.user')
@section('title')
    {{ __('Utilities') }}
@endsection
@section('content')
    <div class="card mb-6">
        <div class="card-body p-6">
            <h4 class="card-title mb-2">
                {{ __('Welcome to :siteTitle Utilities', ['siteTitle' => setting('site_title', 'global')]) }}
            </h4>
            <p class="card-text">
                {{ __('Download free tools, books, and videos while enjoying upto 50% discounts on premium tools.') }}
            </p>
        </div>
    </div>

    <div class="flex justify-between flex-wrap items-center mb-3">
        <ul class="nav nav-tabs custom-tabs inline-flex items-center overflow-hidden rounded list-none border-0 pl-0">
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary active">
                    All
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary">
                    Tools
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary">
                    Partnership
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary">
                    E-Books
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary">
                    Vidoes
                </a>
            </li>
        </ul>
    </div>

    <div class="grid md:grid-cols-2 grid-cols-1 gap-5 mb-6">
        <div class="card h-full border border-slate-100 dark:border-slate-700 p-3 rounded">
            <div class="flex items-center justify-between mb-3">
                <div class="flex-none">
                    <div class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900 rounded-md p-2 ltr:mr-3 rtl:ml-3">
                        <iconify-icon class="text-2xl" icon="solar:chart-linear"></iconify-icon>
                    </div>
                </div>
                <span class="badge bg-warning-500 text-white capitalize">
                        {{ __('New') }}
                    </span>
            </div>
            <h4 class="text-lg font-medium mb-[6px] dark:text-white text-slate-900 mb-1">
                {{ __('Get a free 2-month trial of TraderSync!') }}
            </h4>
            <p class="card-text">
                {{ __('Note: Once requested you will be notified via email within 48 Hrs') }}
            </p>
            <button class="btn btn-dark inline-flex items-center justify-center mt-5">
                {{ __('Not Eligible') }}
            </button>
        </div>
        <div class="card h-full border border-slate-100 dark:border-slate-700 p-3 rounded">
            <div class="flex items-center justify-between mb-3">
                <div class="flex-none">
                    <div class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900 rounded-md p-2 ltr:mr-3 rtl:ml-3">
                        <iconify-icon class="text-2xl" icon="solar:chart-linear"></iconify-icon>
                    </div>
                </div>
                <span class="badge bg-warning-500 text-white capitalize">
                        {{ __('New') }}
                    </span>
            </div>
            <h4 class="text-lg font-medium mb-[6px] dark:text-white text-slate-900 mb-1">
                {{ __('Trade Smart with PrimeMarket Terminal') }}
            </h4>
            <p class="card-text">
                {{ __('Immediate Insights, High-Powered Algorithms, Competitive Advantage Data, and More - 50% off Exclusively for You!') }}
            </p>
        </div>
    </div>

    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4 class="font-medium text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ __('Tools') }}
        </h4>
    </div>
    <div class="card p-6 mb-6">
        <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
            <div class="card h-full border border-slate-100 dark:border-slate-700 p-3 rounded">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex-none">
                        <div class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900 rounded-md p-2 ltr:mr-3 rtl:ml-3">
                            <iconify-icon class="text-2xl" icon="solar:chart-linear"></iconify-icon>
                        </div>
                    </div>
                    <span class="badge bg-warning-500 text-white capitalize">
                        {{ __('New') }}
                    </span>
                </div>
                <h4 class="text-lg font-medium mb-[6px] dark:text-white text-slate-900 mb-1">
                    {{ __('Session Bar Indicator') }}
                </h4>
                <p class="card-text">
                    {{ __("Transform your trading strategy with :siteTitle's 'Session Bar Indicator'. Gain precise control and clarity over global trading sessions, tailoring candle and box colors to your unique preferences.", ['siteTitle' => setting('site_title', 'global')]) }}
                </p>
                <button class="btn btn-dark inline-flex items-center justify-center mt-5">
                    {{ __('Download') }}
                </button>
            </div>
            <div class="card h-full border border-slate-100 dark:border-slate-700 p-3 rounded">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex-none">
                        <div class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900 rounded-md p-2 ltr:mr-3 rtl:ml-3">
                            <iconify-icon class="text-2xl" icon="solar:chart-linear"></iconify-icon>
                        </div>
                    </div>
                    <span class="badge bg-warning-500 text-white capitalize">
                        {{ __('New') }}
                    </span>
                </div>
                <h4 class="text-lg font-medium mb-[6px] dark:text-white text-slate-900 mb-1">
                    {{ __('Session Bar Indicator') }}
                </h4>
                <p class="card-text">
                    {{ __("Transform your trading strategy with :siteTitle's 'Session Bar Indicator'. Gain precise control and clarity over global trading sessions, tailoring candle and box colors to your unique preferences.", ['siteTitle' => setting('site_title', 'global')]) }}
                </p>
                <button class="btn btn-dark inline-flex items-center justify-center mt-5">
                    {{ __('Download') }}
                </button>
            </div>
            <div class="card h-full border border-slate-100 dark:border-slate-700 p-3 rounded">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex-none">
                        <div class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900 rounded-md p-2 ltr:mr-3 rtl:ml-3">
                            <iconify-icon class="text-2xl" icon="solar:chart-linear"></iconify-icon>
                        </div>
                    </div>
                    <span class="badge bg-warning-500 text-white capitalize">
                        {{ __('New') }}
                    </span>
                </div>
                <h4 class="text-lg font-medium mb-[6px] dark:text-white text-slate-900 mb-1">
                    {{ __('Session Bar Indicator') }}
                </h4>
                <p class="card-text">
                    {{ __("Transform your trading strategy with :siteTitle's 'Session Bar Indicator'. Gain precise control and clarity over global trading sessions, tailoring candle and box colors to your unique preferences.", ['siteTitle' => setting('site_title', 'global')]) }}
                </p>
                <button class="btn btn-dark inline-flex items-center justify-center mt-5">
                    {{ __('Download') }}
                </button>
            </div>
            <div class="card h-full border border-slate-100 dark:border-slate-700 p-3 rounded">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex-none">
                        <div class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900 rounded-md p-2 ltr:mr-3 rtl:ml-3">
                            <iconify-icon class="text-2xl" icon="solar:chart-linear"></iconify-icon>
                        </div>
                    </div>
                    <span class="badge bg-warning-500 text-white capitalize">
                        {{ __('New') }}
                    </span>
                </div>
                <h4 class="text-lg font-medium mb-[6px] dark:text-white text-slate-900 mb-1">
                    {{ __('Session Bar Indicator') }}
                </h4>
                <p class="card-text">
                    {{ __("Transform your trading strategy with :siteTitle's 'Session Bar Indicator'. Gain precise control and clarity over global trading sessions, tailoring candle and box colors to your unique preferences.", ['siteTitle' => setting('site_title', 'global')]) }}
                </p>
                <button class="btn btn-dark inline-flex items-center justify-center mt-5">
                    {{ __('Download') }}
                </button>
            </div>
        </div>
    </div>

    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4 class="font-medium text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ __('E-Books') }}
        </h4>
    </div>
    <div class="card p-6 mb-6">
        <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
            <div class="card h-full border border-slate-100 dark:border-slate-700 p-3 rounded">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex-none">
                        <div class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900 rounded-md p-2 ltr:mr-3 rtl:ml-3">
                            <iconify-icon class="text-2xl" icon="solar:chart-linear"></iconify-icon>
                        </div>
                    </div>
                    <span class="badge bg-warning-500 text-white capitalize">
                        {{ __('New') }}
                    </span>
                </div>
                <h4 class="text-lg font-medium mb-[6px] dark:text-white text-slate-900 mb-1">
                    {{ __('Prop Trader Secrets') }}
                </h4>
                <button class="btn btn-dark inline-flex items-center justify-center mt-5">
                    {{ __('Download') }}
                </button>
            </div>
            <div class="card h-full border border-slate-100 dark:border-slate-700 p-3 rounded">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex-none">
                        <div class="w-12 h-12 flex items-center justify-center bg-slate-100 dark:bg-slate-900 rounded-md p-2 ltr:mr-3 rtl:ml-3">
                            <iconify-icon class="text-2xl" icon="solar:chart-linear"></iconify-icon>
                        </div>
                    </div>
                    <span class="badge bg-warning-500 text-white capitalize">
                        {{ __('New') }}
                    </span>
                </div>
                <h4 class="text-lg font-medium mb-[6px] dark:text-white text-slate-900 mb-1">
                    {{ __('Prop Trader Secrets') }}
                </h4>
                <button class="btn btn-dark inline-flex items-center justify-center mt-5">
                    {{ __('Download') }}
                </button>
            </div>
        </div>
    </div>

    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4 class="font-medium text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ __('Videos') }}
        </h4>
    </div>
    <div class="card p-6 mb-6">
        <div class="grid md:grid-cols-3 grid-cols-1 gap-5">
            <div class="card border border-slate-100 dark:border-slate-700">
                <div class="card-body flex flex-col p-4">
                    <div class="h-[260px] bg-no-repeat bg-cover bg-center flex items-center justify-center" style="background-image: url('{{ asset('frontend/images/videos-thumbnail.jpg') }}')">
                        <a href="" class="inline-flex">
                            <svg width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M30.6075 16.8L9.8 29.3475C8.575 30.0825 7 29.2075 7 27.7725V13.7725C7 7.66499 13.5975 3.84999 18.9 6.89499L26.9325 11.515L30.59 13.615C31.7975 14.3325 31.815 16.0825 30.6075 16.8Z" fill="#FED000"/>
                                <path d="M31.6574 27.055L24.5699 31.15L17.4999 35.2275C14.9624 36.68 12.0924 36.3825 10.0099 34.9125C8.99491 34.2125 9.11741 32.655 10.1849 32.025L32.4274 18.69C33.4774 18.06 34.8599 18.655 35.0524 19.8625C35.4899 22.575 34.3699 25.4975 31.6574 27.055Z" fill="#FED000"/>
                            </svg>
                        </a>
                    </div>
                    <div class="card-text h-full mt-3">
                        <h5 class="card-title text-slate-900 dark:text-white">
                            {{ __('How to Grow Your :siteTitle Account to $4 Million', ['siteTitle' => setting('site_title', 'global')]) }}
                        </h5>
                    </div>
                </div>
            </div>
            <div class="card border border-slate-100 dark:border-slate-700">
                <div class="card-body flex flex-col p-4">
                    <div class="h-[260px] bg-no-repeat bg-cover bg-center flex items-center justify-center" style="background-image: url('{{ asset('frontend/images/videos-thumbnail.jpg') }}')">
                        <a href="" class="inline-flex">
                            <svg width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M30.6075 16.8L9.8 29.3475C8.575 30.0825 7 29.2075 7 27.7725V13.7725C7 7.66499 13.5975 3.84999 18.9 6.89499L26.9325 11.515L30.59 13.615C31.7975 14.3325 31.815 16.0825 30.6075 16.8Z" fill="#FED000"/>
                                <path d="M31.6574 27.055L24.5699 31.15L17.4999 35.2275C14.9624 36.68 12.0924 36.3825 10.0099 34.9125C8.99491 34.2125 9.11741 32.655 10.1849 32.025L32.4274 18.69C33.4774 18.06 34.8599 18.655 35.0524 19.8625C35.4899 22.575 34.3699 25.4975 31.6574 27.055Z" fill="#FED000"/>
                            </svg>
                        </a>
                    </div>
                    <div class="card-text h-full mt-3">
                        <h5 class="card-title text-slate-900 dark:text-white">
                            {{ __('How to Grow Your :siteTitle Account to $4 Million', ['siteTitle' => setting('site_title', 'global')]) }}
                        </h5>
                    </div>
                </div>
            </div>
            <div class="card border border-slate-100 dark:border-slate-700">
                <div class="card-body flex flex-col p-4">
                    <div class="h-[260px] bg-no-repeat bg-cover bg-center flex items-center justify-center" style="background-image: url('{{ asset('frontend/images/videos-thumbnail.jpg') }}')">
                        <a href="" class="inline-flex">
                            <svg width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M30.6075 16.8L9.8 29.3475C8.575 30.0825 7 29.2075 7 27.7725V13.7725C7 7.66499 13.5975 3.84999 18.9 6.89499L26.9325 11.515L30.59 13.615C31.7975 14.3325 31.815 16.0825 30.6075 16.8Z" fill="#FED000"/>
                                <path d="M31.6574 27.055L24.5699 31.15L17.4999 35.2275C14.9624 36.68 12.0924 36.3825 10.0099 34.9125C8.99491 34.2125 9.11741 32.655 10.1849 32.025L32.4274 18.69C33.4774 18.06 34.8599 18.655 35.0524 19.8625C35.4899 22.575 34.3699 25.4975 31.6574 27.055Z" fill="#FED000"/>
                            </svg>
                        </a>
                    </div>
                    <div class="card-text h-full mt-3">
                        <h5 class="card-title text-slate-900 dark:text-white">
                            {{ __('How to Grow Your :siteTitle Account to $4 Million', ['siteTitle' => setting('site_title', 'global')]) }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
