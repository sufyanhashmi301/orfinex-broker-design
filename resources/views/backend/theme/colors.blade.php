@extends('backend.setting.customization.index')
@section('title')
    {{ __('Theme Colors') }}
@endsection
@section('customization-content')
    <?php
        $type = request()->query('type');
        $section = $type;
        $fields = config("setting.$section");
    ?>

    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="card p-4 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
            <li class="nav-item">
                <a href="{{ route('admin.theme.colors', ['type' => 'light_colors']) }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->routeIs('admin.theme.colors') && request()->query('type') === 'light_colors' ? 'active' : '' }}">
                    {{ __('Light Theme') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.theme.colors', ['type' => 'dark_colors']) }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->routeIs('admin.theme.colors') && request()->query('type') === 'dark_colors' ? 'active' : '' }}">
                    {{ __('Dark Theme') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.theme.colors', ['type' => 'misc_colors']) }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->routeIs('admin.theme.colors') && request()->query('type') === 'misc_colors' ? 'active' : '' }}">
                    {{ __('Misc Colors') }}
                </a>
            </li>
        </ul>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <form action="{{ route('admin.settings.update') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="section" value="{{$section}}">
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
                                    <label for="{{ __($field['name']) }}" class="file-ok" style="background-image: url( {{asset(oldSetting($field['name'],$section)) }} )">
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
                                <label for="" class="form-label !flex items-center">
                                    {{ __($field['label']) }}
                                    <iconify-icon class="toolTip onTop ml-1" icon="lucide:info" data-tippy-content="{{ __($field['description']) }}"></iconify-icon>
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
                                <label for="" class="form-label !flex items-center">
                                    {{ __($field['label']) }}
                                    <iconify-icon class="toolTip onTop ml-1" icon="lucide:info" data-tippy-content="{{ __($field['description']) }}"></iconify-icon>
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
                <div class="mt-10">
                    <button
                        type="submit"
                        class="btn btn-dark inline-flex items-center justify-center">
                        {{ __('Save Changes') }}
                    </button>
                    <button
                        type="button"
                        id="defaultColorsBtn"
                        class="btn btn-outline-dark inline-flex items-center justify-center ml-1">
                        {{ __('Default Colors') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('customization-script')
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

        $(document).ready(function() {

            const $section = '{{ $section }}';

            $('#defaultColorsBtn').click(function() {
                let data = {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    section: $section
                };

                // Conditional data based on the value of section
                if ($section === 'light_colors') {
                    data = {
                        ...data,
                        body_bg_color: '#f1f5f9',
                        base_color: '#ffffff',
                        active_menu_bg: '#0f172a',
                        active_menu_color: '#ffffff',
                        secondary_btn_bg: '#f3f4f6',
                        secondary_btn_color: '#0f172a',
                        primary_btn_bg: '#0f172a',
                        primary_btn_color: '#ffffff',
                    };
                } else if ($section === 'dark_colors') {
                    data = {
                        ...data,
                        body_bg_color_dark: '#11171f',
                        base_color_dark: '#181e26',
                        primary_color_dark: '#0f172a',
                        active_menu_bg_dark: '#0f172a',
                        active_menu_color_dark: '#ffffff',
                        secondary_btn_bg_dark: '#f3f4f6',
                        secondary_btn_color_dark: '#0f172a',
                        primary_btn_bg_dark: '#f3f4f6',
                        primary_btn_color_dark: '#0f172a',
                    };
                } else if ($section === 'misc_colors') {
                    data = {
                        ...data,
                        primary_color: '#0f172a',
                        secondary_color: '#f1f5f9',
                        success_color: '#0fb60b',
                        warning_color: '#ffbb0d',
                        danger_color: '#dc0000',
                    };
                }
                $.ajax({
                    url: '{{ route('admin.settings.update') }}',
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        location.reload();
                    },
                    error: function() {
                        alert('There was an error processing your request');
                    }
                });
            });
        });
    </script>
@endsection
