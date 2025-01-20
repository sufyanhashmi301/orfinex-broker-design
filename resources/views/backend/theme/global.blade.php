@extends('backend.setting.website.index')
@section('title')
    {{ __('Global Settings') }}
@endsection
@section('website-content')
    <?php
        $section = 'theme';
        $fields = config('setting.theme');
        //   dd($fields);
    ?>
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <div class="space-y-5">
                @include('backend.setting.site_setting.include.form.__open_action')
                    <div class="grid lg:grid-cols-6 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-7">
                        @foreach( $fields['elements'] as $key => $field)
                            @if($field['type'] == 'file')
                                <div class="lg:col-span-2">
                                    <div class="flex flex-col items-start h-full rounded-lg border dark:border-slate-700 p-3 gap-3">
                                        <div>
                                            <label class="form-label !mb-0">{{  __($field['label']) }}</label>
                                            <p class="text-sm text-slate-500 dark:text-slate--300">{{ __($field['description']) }}</p>
                                        </div>
                                        @php
                                            $imageSrc = oldSetting($field['name'], $section)
                                                ? asset(oldSetting($field['name'], $section))
                                                : asset('backend/images/' . __($field['example_logo']));
                                        @endphp
                                        <img src="{{ $imageSrc }}" class="{{ $field['name'] }}_preview_img inline-flex h-10">
                                        <div class="w-full border-t dark:border-slate-700 mt-auto pt-3">
                                            <input
                                                type="{{$field['type']}}"
                                                name="{{$field['name']}}"
                                                id="{{$field['name']}}"
                                                class="w-full"
                                                value="{{ oldSetting($field['name'],$section) }}"
                                                accept=".jpeg, .jpg, .png"
                                                data-preview="{{$field['name']}}_preview_img "
                                            />
                                        </div>
                                    </div>
                                </div>
                            @elseif($field['type'] == 'color')
                                <div class="lg:col-span-3">
                                    <div class="input-area">
                                        <label for="" class="form-label">
                                            {{ __($field['label']) }}
                                        </label>
                                        <div class="relative">
                                            <input type="" name="" class="form-control" value="{{ oldSetting($field['name'],$section) }}">
                                            <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full flex items-center justify-center">
                                                    <input
                                                        type="{{$field['type']}}"
                                                        name="{{$field['name']}}"
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
@section('website-script')
    <script !src="">
        $(document).ready(function() {
            function handleImagePreviews() {
                $('input[type="file"]').change(function() {
                    var previewClass = $(this).data('preview');
                    var preview = $('.' + previewClass);

                    preview.fadeOut();

                    var oFReader = new FileReader();
                    oFReader.readAsDataURL(this.files[0]);

                    oFReader.onload = function(oFREvent) {
                        preview.attr('src', oFREvent.target.result).fadeIn();
                    };
                });
            }
            handleImagePreviews();
        });
    </script>
@endsection
