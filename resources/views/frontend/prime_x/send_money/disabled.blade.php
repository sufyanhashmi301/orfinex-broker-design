@extends('errors.layout')
@section('title')
    {{ __('Forbidden') }}
@endsection
@section('content')
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-left md:text-center">
            <a href="{{route('admin.dashboard')}}" class="inline-block mb-6">
                <img src="{{ getFilteredPath(setting('site_logo','theme'), 'fallback/branding/desktop-logo.png') }}" class="black_logo max-w-xs" alt="Logo"/>
                <img src="{{ getFilteredPath(setting('site_logo_light','theme'), 'fallback/branding/desktop-logo.png') }}" class="white_logo max-w-xs" alt="Logo"/>
            </a>

            <h2 class="text-5xl font-bold text-slate-800 dark:text-slate-200 mb-3">
                {{ $title ?? __('Transfer Money Disabled') }}
            </h2>
            <p class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-6">
                {{ $description ?? __('Transfer money functionality is currently disabled. Please contact our support team for assistance.') }}
            </p>

            <p class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-6">
                {{ $message ?? __('Thank you for your understanding') }}<br>
                &#9866; {{ setting('site_title', 'global') }}
            </p>

            <a href="{{ $button_link ?? route('home') }}" class="btn btn-{{ $button_type ?? 'dark' }} inline-flex items-center justify-center min-w-full md:min-w-[310px]">
                {{ $button_text ?? __('Back to Home') }}
            </a>
        </div>
    </div>
@endsection