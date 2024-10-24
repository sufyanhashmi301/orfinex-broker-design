@extends('backend.setting.data_management.index')
@section('title')
    {{ __('End-to-End Encryption') }}
@endsection
@section('data-management-content')
    <div class="card basicTable_wrapper items-center justify-center">
        <div class="card-body p-6">
            <div class="max-w-2xl mx-auto text-center">
                <div id="lottie-container" style="display: inline-flex; width: 350px; height: 350px;"></div>
                <p class="card-text">
                    {{ __('End-to-End Encryption ensures that all data transmitted within the CRM remains secure and confidential. Admins can enable or disable this option to control the encryption of sensitive information, protecting client data throughout its entire journey.') }}
                </p>
                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-danger inline-flex items-center justify-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="game-icons:shield-disabled"></iconify-icon>
                        {{ __('Disable End-to-End Encryption') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('data-management-script')
    <script>
        var animation = lottie.loadAnimation({
            container: document.getElementById('lottie-container'), // ID of the div where the animation will render
            renderer: 'svg',  // Render the animation in SVG format
            loop: true,       // Loop the animation
            autoplay: true,   // Autoplay the animation
            path: '{{ asset('global/json/secure-2.json') }}' // Path to your JSON file
        });
    </script>
@endsection
