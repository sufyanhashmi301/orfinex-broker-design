@extends('backend.setting.system.index')
@section('title')
    {{ __('Dev Mode') }}
@endsection
@section('system-content')
    <?php
        $section = 'dev_mode';
        $fields = config('setting.dev_mode');
    ?>
    <div class="card basicTable_wrapper items-center justify-center">
        <div class="card-body p-6">
            <div class="max-w-2xl mx-auto text-center">
                <div id="lottie-container" style="display: inline-flex; width: 350px; height: 350px;"></div>
                <p class="card-text">
                    {{ __('Development Mode is strictly for troubleshooting and should only be activated by the Development or Technology Team in critical situations under their supervision.') }}
                </p>
                <form action="{{ route('admin.settings.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="section" value="{{$section}}">
                    @if(oldSetting('debug_mode', $section))
                        <input type="hidden" value="0" name="debug_mode"/>

                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="lucide:power"></iconify-icon>
                                {{ __('Turn Development Mode Off') }}
                            </button>
                        </div>
                    @else
                        <input type="hidden" value="1" name="debug_mode"/>

                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-danger inline-flex items-center justify-center">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="lucide:power"></iconify-icon>
                                {{ __('Turn Development Mode On') }}
                            </button>
                        </div>
                    @endif
                </form>
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
            path: '{{ asset('global/json/dev-mode.json') }}' // Path to your JSON file
        });
    </script>
@endsection
