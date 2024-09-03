@extends('backend.theme.index')
@section('title')
    {{ __('Theme Colors') }}
@endsection
@section('theme-content')
    <?php
        $section = 'colors';
        $fields = config('setting.colors');
        //   dd($fields);
    ?>

    <div class="card">
        <div class="card-body p-6">
            <div class="space-y-5">
                @include('backend.setting.site_setting.include.form.__open_action')
                    <div class="grid lg:grid-cols-6 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-7">
                        @foreach( $fields['elements'] as $key => $field)
                            @if($field['type'] == 'file')
                                <div class="lg:col-span-2">
                                    <div class="input-area">
                                        <label class="form-label">
                                            <div class="flex items-center">
                                                {!! __($field['label']) !!}
                                            </div>
                                        </label>
                                        <div class="wrap-custom-file {{ $errors->has($field['name']) ? 'has-error' : '' }}">
                                            <input
                                                type="{{$field['type']}}"
                                                name="{{$field['name']}}"
                                                id="{{$field['name']}}"
                                                value="{{ oldSetting($field['name'],$section) }}"
                                                accept=".jpeg, .jpg, .png"
                                            />
                                            <label for="{{ __($field['name']) }}" class="file-ok"
                                                style="background-image: url( {{asset(oldSetting($field['name'],$section)) }} )">
                                                <img
                                                    class="upload-icon"
                                                    src="{{ asset('global/materials/upload.svg') }}"
                                                    alt=""
                                                />
                                                <span>
                                                    <div class="flex items-center">
                                                        Upload {!! __($field['label']) !!}
                                                    </div>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @elseif($field['type'] == 'color')
                                <div class="lg:col-span-3">
                                    <div class="input-area">
                                        <label for="" class="form-label">
                                            {{ __($field['label']) }}
                                        </label>
                                        <div class="color-input-group relative">
                                            <input type="" name="" class="form-control text-input" value="{{ oldSetting($field['name'],$section) }}">
                                            <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full flex items-center justify-center">
                                                <input
                                                    type="{{$field['type']}}"
                                                    name="{{$field['name']}}"
                                                    class="color-input"
                                                    value="{{ oldSetting($field['name'],$section) }}"
                                                />
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="lg:col-span-6">
                                    <div class="input-area">
                                        <label for="" class="form-label">
                                            {{ __($field['label']) }}
                                        </label>
                                        <input
                                            type="{{$field['type']}}"
                                            name="{{$field['name']}}"
                                            class=" form-control {{ $errors->has($field['name']) ? 'has-error' : '' }}"
                                            value="{{ oldSetting($field['name'],$section) }}"
                                        />
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @include('backend.setting.site_setting.include.form.__close_action')
            </div>
        </div>
    </div>
@endsection
@section('setting-script')
    <script>
        $(document).ready(function() {
            // Function to synchronize text and color inputs in the same group
            function syncGroupInputs(group) {
                var $textInput = $(group).find('.text-input');
                var $colorInput = $(group).find('.color-input');

                $textInput.on('input', function() {
                    var colorValue = $(this).val();
                    if (isValidColor(colorValue)) {
                        $colorInput.val(colorValue).css('background-color', colorValue);
                    }
                });

                $colorInput.on('input', function() {
                    var colorValue = $(this).val();
                    $textInput.val(colorValue);
                });
            }

            // Function to validate color input
            function isValidColor(value) {
                var s = new Option().style;
                s.color = value;
                return s.color !== '';
            }

            // Initialize synchronization for each input group
            $('.color-input-group').each(function() {
                syncGroupInputs(this);
            });
        });
    </script>
@endsection
