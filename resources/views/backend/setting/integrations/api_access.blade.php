@extends('backend.setting.integrations.index')
@section('title')
    {{ __('API Access') }}
@endsection
@section('integrations-content')
    <div class="card basicTable_wrapper items-center justify-center">
        <div class="card-body p-6">
            <div class="max-w-2xl mx-auto text-center">
                <div id="lottie-container" style="display: inline-flex; width: 350px; height: 350px;"></div>
                <p class="card-text">
                    {{ __('API Access allows seamless integration with external systems and is available as an add-on upon request. Please request access only if your system requires external connectivity.') }}
                </p>
                <div class="text-center mt-5">
                    <a href="{{ route('admin.clear-cache') }}" type="submit" class="btn btn-dark inline-flex items-center justify-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="lineicons:cloud-network"></iconify-icon>
                        {{ __('Request Access') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('integrations-script')
    <script>
        var animation = lottie.loadAnimation({
            container: document.getElementById('lottie-container'), // ID of the div where the animation will render
            renderer: 'svg',  // Render the animation in SVG format
            loop: true,       // Loop the animation
            autoplay: true,   // Autoplay the animation
            path: '{{ asset('global/json/api.json') }}' // Path to your JSON file
        });
    </script>
@endsection
