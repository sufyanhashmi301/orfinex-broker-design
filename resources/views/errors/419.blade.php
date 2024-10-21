@extends('errors.layout')
@section('title')
    {{ __('Session Expired') }}
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
        <p class="text-center mb-3">
            {{ __('Your session has timed out due to inactivity. Please log in again to continue where you left off. We apologize for any inconvenience.') }}
        </p>
        <div class="max-w-[300px] mx-auto w-full">
            <a href="{{route('login')}}" class="btn btn-dark dark:bg-slate-800 block text-center">
                {{ __('Back to Login') }}
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
            path: '{{ asset('global/json/session-expire.json') }}' // Path to your JSON file
        });
    </script>
@endsection
