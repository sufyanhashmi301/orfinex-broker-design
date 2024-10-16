@extends('backend.setting.system.index')
@section('title')
    {{ __('Clear Cache') }}
@endsection
@section('system-content')
    <div class="card basicTable_wrapper items-center justify-center">
        <div class="card-body p-6">
            <div class="max-w-2xl mx-auto text-center">
                <div id="lottie-container" style="display: inline-flex; width: 350px; height: 350px;"></div>
                <p class="card-text">
                    {{ __('Clearing the cache can help resolve issues with outdated data and improve system performance. Only use this option when necessary, as it may temporarily affect loading times.') }}
                </p>
                <div class="text-center mt-5">
                    <a href="{{ route('admin.clear-cache') }}"
                        type="submit"
                        class="btn btn-danger inline-flex items-center justify-center">
                        {{ __('Clear Cache') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('system-script')
    <script>
        var animation = lottie.loadAnimation({
            container: document.getElementById('lottie-container'), // ID of the div where the animation will render
            renderer: 'svg',  // Render the animation in SVG format
            loop: true,       // Loop the animation
            autoplay: true,   // Autoplay the animation
            path: '{{ asset('global/json/delete.json') }}' // Path to your JSON file
        });
    </script>
@endsection
