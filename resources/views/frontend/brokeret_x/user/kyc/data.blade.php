<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @foreach( json_decode($fields, true) as $key => $field)

        @if($field['type'] == 'file')
            <div class="">
                <label for="" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    {{ __('' . $field['name']) }}
                </label>
                <div 
                    class="relative inline-block w-full h-40 text-center border border-dashed border-gray-300 rounded-lg"
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
                        :style="preview ? `background-image: url('${preview}'); background-size: cover; background-position: center;` : ''"
                    >
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
                <div class="progress-steps-form">
                    <label for="" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        {{ __('' . $field['name']) }}
                    </label>
                    <textarea 
                        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" 
                        @if($field['validation'] == 'required') required @endif 
                        placeholder="{{ __('Send Money Note') }}" 
                        name="kyc_credential[{{$field['name']}}]" 
                        rows="3"></textarea>
                </div>
            </div>

        @else
            <div class="md:col-span-2">
                <div class="progress-steps-form">
                    <label for="" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        {{ __('' . $field['name']) }}
                    </label>
                    <input 
                        type="text" 
                        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" 
                        name="kyc_credential[{{$field['name']}}]"
                        @if($field['validation'] == 'required') required @endif 
                        aria-label="{{ __('Amount') }}" id="amount" aria-describedby="basic-addon1">
                </div>
            </div>
        @endif

    @endforeach
    </div>
