@extends('errors.layout')
@section('title')
    {{ __('Server Error') }}
@endsection
@section('content')
    <div class="">
        <a href="{{route('admin.dashboard')}}" class="items-center">
            <img src="{{ asset(setting('site_logo','global')) }}" class="black_logo max-w-xs" alt="Logo"/>
            <img src="{{ asset(setting('site_logo_light','global')) }}" class="white_logo max-w-xs" alt="Logo"/>
        </a>
    </div>
    <div class="max-w-3xl my-8 mx-auto">
        <div id="lottie-container" style="display: inline-flex; width: 350px; height: 350px;"></div>
        <p class="text-xl text-center font-semibold mb-3">
            {{ __('Oops! Something went wrong on our end.') }}
        </p>
        <p class="text-center mb-3">
            {{ __('We’re working to resolve the issue as quickly as possible. You can try refreshing the page or come back later. If the issue persists, please contact our support team for assistance.') }}
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
            path: '{{ asset('global/json/server-error.json') }}' // Path to your JSON file
        });
    </script>
@endsection
