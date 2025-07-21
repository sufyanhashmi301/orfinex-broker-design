@extends('backend.setting.customization.index')
@section('title')
    {{ __('Theme Fonts') }}
@endsection
@section('customization-content')
    <?php
        $section = 'fonts_settings';
        $fields = config('setting.fonts_settings');
    ?>
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between flex-wrap items-center mb-6">
            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                @yield('title')
            </h4>
        </div>
        <div class="card">
            <div class="card-body p-6">
                <div class="mb-5" id="textSample">
                    <p class="text-4xl font-medium leading-relaxed mb-5">
                        {{ __('The quick brown fox jumps over the lazy dog. Pack my box with five dozen liquor jugs. Jaded zombies quickly faxed over mixed quaint gym equipment. Bright vixens jump; dozy fowl quack.') }}
                    </p>
                    <ul class="flex gap-3">
                        <li class="font-normal">
                            {{ __('Regular') }}
                        </li>
                        <li class="font-medium">
                            {{ __('Medium') }}
                        </li>
                        <li class="font-semibold">
                            {{ __('Semibold') }}
                        </li>
                        <li class="font-bold">
                            {{ __('Bold') }}
                        </li>
                    </ul>
                </div>
                <form action="{{ route('admin.settings.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="section" value="{{$section}}">
                    <div class="grid lg:grid-cols-6 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-7">
                    @foreach( $fields['elements'] as $key => $field)
                        @if($field['type'] == 'file')
                            <div class="lg:col-span-2">
                                <div class="input-area">
                                    <label class="form-label">
                                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="{{ __($field['description']) }}">
                                            {{ __($field['label']) }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                        </span>
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
                                    <label for="" class="form-label">
                                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="{{ __($field['description']) }}">
                                            {{ __($field['label']) }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                        </span>
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
                        @elseif($field['name'] == 'font_family')
                            <div class="lg:col-span-6 md:col-span-3 sm:col-span-2">
                                <label for="" class="form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="{{ __($field['description']) }}">
                                        {{ __($field['label']) }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                {{--<hr class="dark:border-slate-700 my-6">--}}
                                <ul class="space-y-3">
                                    @foreach ($field['options'] as $value => $label)
                                        <li class="flex items-center justify-between rounded p-3 border dark:border-slate-700">
                                            <div class="success-radio flex-1">
                                                <label class="flex items-center cursor-pointer">
                                                    <input
                                                        type="radio"
                                                        class="hidden"
                                                        name="{{$field['name']}}"
                                                        value="{{ $value }}"
                                                        {{ oldSetting($field['name'],$section) === $value ? 'checked' : '' }}>
                                                    <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                                    <span class="text-sm capitalize dark:text-white ml-2">
                                                        <span class="block text-base font-medium">{{ $label }}</span>
                                                        <span>{{ __('Modern & Clean') }}</span>
                                                    </span>
                                                </label>
                                            </div>
                                            <p class="text-2xl font-medium" style="font-family: {{ $value }}">{{ __('Aa') }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <div class="lg:col-span-6">
                                <div class="input-area">
                                    <label for="" class="form-label !flex items-center">
                                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="{{ __($field['description']) }}">
                                            {{ __($field['label']) }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                        </span>
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
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('style')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');
    </style>
@endsection
@section('script')
    <script !src="">
        $(document).ready(function() {
            $('input[name="font_family"]').change(function() {
                const selectedFont = $(this).val();
                $('#textSample').css('font-family', selectedFont);
            });
        });
    </script>
@endsection
