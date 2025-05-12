@extends('errors.layout')
@section('title')
    {{ __('Not Found') }}
@endsection
@section('content')
    <div class="">
        <a href="{{route('admin.dashboard')}}" class="items-center">
            <img src="{{ getFilteredPath(setting('site_logo','theme'), 'fallback/branding/desktop-logo.png') }}" class="black_logo max-w-xs" alt="Logo"/>
            <img src="{{ getFilteredPath(setting('site_logo_light','theme'), 'fallback/branding/desktop-logo.png') }}" class="white_logo max-w-xs" alt="Logo"/>
        </a>
    </div>
    <div class="max-w-3xl my-8 mx-auto">
        <div id="lottie-container" style="display: inline-flex; width: 350px; height: 350px;"></div>
        <p class="text-center mb-3">
            {{ __('Oops! The page you’re looking for cannot be found. It might have been moved, deleted, or the URL may be incorrect. ') }}
        </p>
        @php
            $adminPrefix = trim(setting('site_admin_prefix', 'global'), '/');
            $prefix = request()->segment(1);
        @endphp
        <div class="max-w-[300px] mx-auto w-full">
            <a href="{{ $prefix === $adminPrefix ? route('admin.dashboard') : route('home') }}" class="btn btn-dark dark:bg-slate-800 block text-center">
                {{ __('Return to Home') }}
            </a>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var animation = lottie.loadAnimation({
            container: document.getElementById('lottie-container'), // ID of the div where the animation will render
            renderer: 'svg',  // Render the animation in SVG format
            loop: true,       // Loop the animation
            autoplay: true,   // Autoplay the animation
            path: '{{ asset('global/json/page-not-found.json') }}' // Path to your JSON file
        });
    </script>
@endsection
