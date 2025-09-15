<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @foreach(json_decode($fields, true) as $key => $field)

        @if($field['type'] == 'file')
            <div class="">
                <x-frontend::forms.label
                    fieldLabel="{{ __($field['name']) }}"
                    fieldRequired="{{ $field['validation'] == 'required' }}"
                />
                <div class="relative inline-block w-full h-40 text-center border border-dashed border-gray-300 rounded-lg"
                    x-data="{
                        preview: '{{ $existingImageUrl ?? '' }}',
                        fileName: '{{ $existingFileName ?? '' }}',
                        updatePreview(event) {
                            const file = event.target.files[0];
                            if (!file) return;

                            this.fileName = file.name;
                            const reader = new FileReader();
                            reader.onload = e => {
                                this.preview = e.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    }">
                    <label
                        class="relative flex items-center justify-center w-full h-40 border-2 border-dashed rounded-lg cursor-pointer overflow-hidden"
                        :style="preview ? `background-image: url('${preview}'); background-size: cover; background-position: center;` : ''">
                        <input
                            type="file"
                            name="kyc_credential[{{$field['name']}}]"
                            id="{{ $key }}"
                            class="hidden"
                            accept=".gif, .jpg, .png"
                            @if($field['validation'] == 'required') required @endif
                            @change="updatePreview"
                        />
                        <template x-if="!preview">
                            <div class="flex flex-col items-center">
                                <img src="{{ asset('global/materials/upload.svg') }}" alt="Upload Icon" class="w-8 h-8 mb-2" />
                                <span class="text-gray-500">Click to upload</span>
                            </div>
                        </template>
                    </label>
                    
                    <!-- File name -->
                    <template x-if="fileName">
                        <p class="w-full absolute bottom-0 bg-black/50 text-white text-xs px-2 py-1 truncate" x-text="fileName"></p>
                    </template>
                </div>
                <p class="text-xs dark:text-white mt-1">
                    {{ __('Provide files in ') }} <span class="font-medium">{{ __('JPG') }}</span> {{ __('format, ') }} <span class="font-medium">{{ __('10 MB') }}</span> {{ __('maximum') }}
                </p>
            </div>
        @elseif($field['type'] == 'textarea')
            <div class="md:col-span-2">
                <x-frontend::forms.label
                    fieldLabel="{{ __($field['name']) }}"
                    fieldId="{{ $field['name'] }}"
                    fieldRequired="{{ $field['validation'] == 'required' }}"
                />
                <x-frontend::forms.textarea
                    fieldId="{{ $field['name'] }}"
                    fieldName="kyc_credential[{{$field['name']}}]"
                    fieldRequired="{{ $field['validation'] == 'required' }}"
                />
            </div>
        @else
            <div class="md:col-span-2">
                <x-frontend::forms.label
                    fieldLabel="{{ __($field['name']) }}"
                    fieldId="{{ $field['name'] }}"
                    fieldRequired="{{ $field['validation'] == 'required' }}"
                />
                <x-frontend::forms.input
                    fieldId="{{ $field['name'] }}"
                    fieldName="kyc_credential[{{$field['name']}}]"
                    fieldRequired="{{ $field['validation'] == 'required' }}"
                />
            </div>
        @endif

    @endforeach
</div>
