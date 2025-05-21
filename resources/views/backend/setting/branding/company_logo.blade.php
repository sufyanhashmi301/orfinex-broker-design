@extends('backend.setting.website.index')
@section('title')
    {{ __('Company Logo Settings') }}
@endsection
@section('website-content')
    <?php
        $section = 'company_logo';
        $fields = config('setting.company_logo');
        $defaultLogo = 'backend/images/brokeret_logo.png';
        $currentLogo = setting('company_logo_image', 'company_logo') ?: $defaultLogo;
    ?>

    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>

    @include('backend.setting.branding.include.__tabs_nav')
    
    <div class="grid grid-cols-2 gap-5">
        <div class="lg:col-span-1 col-span-2">
            <div class="h-full rounded transition-all duration-100 shadow-none bg-slate-200 dark:bg-dark">
                <form action="{{ route('admin.settings.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="section" value="{{ $section }}">
                    
                    <div class="relative flex justify-between items-center bg-white dark:bg-slate-800 rounded shadow-base px-6 py-5">
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-[2px] bg-primary"></span>
                        <h3 class="text-lg text-slate-900 dark:text-white font-medium capitalize">
                            {{ __('Provider Logo') }}
                        </h3>
                        <div class="form-switch ps-0" style="line-height: 0;">
                            <input type="hidden" value="0" name="company_logo_status">
                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-switch">
                                <input type="checkbox" name="company_logo_status" value="1" class="sr-only peer" 
                                    @if(setting('company_logo_status', 'company_logo')) checked @endif>
                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="px-2 py-4 h-full space-y-4 rounded-bl rounded-br">
                        <div class="card rounded-md bg-white dark:bg-slate-800 shadow-base custom-class card-body space-y-5 p-6">
                            <div class="input-area">
                                <label for="" class="form-label flex items-center !w-auto">
                                    {{ __('Logo') }}
                                    <iconify-icon class="toolTip onTop relative top-[2px]" icon="lucide:info" data-tippy-content="Recommended format: PNG, JPG. Max size: 2MB"></iconify-icon>
                                </label>
                                <div class="wrap-custom-file">
                                    <input
                                        type="file"
                                        name="company_logo_image"
                                        id="company_logo-image"
                                        accept=".jpg,.jpeg,.png"
                                    />
                                    <label for="company_logo-image" class="@if(setting('company_logo_image', 'company_logo')) file-ok @endif" style="background-image: url('{{ asset($currentLogo) }}')">
                                        <img
                                            class="upload-icon"
                                            src="{{ asset('global/materials/upload.svg') }}"
                                            alt=""
                                        />
                                        <span>{{ __('Upload Logo') }}</span>
                                    </label>
                                </div>
                                <p class="text-xs text-slate-400 mt-2">
                                    {{ __('Current logo will be replaced with new upload') }}
                                </p>
                            </div>
                            
                            <div class="input-area">
                                <button type="submit" class="btn btn-dark block-btn">
                                    <span class="flex items-center">
                                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                        <span>{{ __('Save Changes') }}</span>
                                    </span>
                                </button>
                                <div class="mt-3 p-3 bg-slate-100 dark:bg-slate-700 rounded-md">
                                    <p class="text-sm text-slate-600 dark:text-slate-300">
                                        <strong>{{ __('Note:') }}</strong> 
                                        {{ __('When enabled, the logo will be visible in the admin login page footer. When disabled, no logo will be displayed.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Preview image before upload
            const logoInput = document.getElementById('company_logo-image');
            if (logoInput) {
                logoInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            const label = document.querySelector('label[for="company_logo-image"]');
                            label.classList.add('file-ok');
                            label.style.backgroundImage = `url(${event.target.result})`;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
    @endpush
@endsection