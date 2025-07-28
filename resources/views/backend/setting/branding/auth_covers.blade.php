@extends('backend.setting.website.index')
@section('title')
    {{ __('Auth Covers Management') }}
@endsection
@section('website-content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    @include('backend.setting.branding.include.__tabs_nav')
    <div class="card">
        <div class="card-body p-6">
            <div class="space-y-5">
                <form action="{{ route('admin.theme.update-auth-covers') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid lg:grid-cols-2 gap-6">
                        <!-- Default login/signup cover Option -->
                        <div class="border rounded-lg p-4 {{ ($currentLoginBg === 'https://cdn.brokeret.com/crm-assets/login-image/c19.png' || $currentLoginBg === $defaultLoginBg || $currentLoginBg === 'default/auth-bg.jpg' || empty($currentLoginBg)) ? 'border-primary bg-primary/5' : 'border-slate-200 dark:border-slate-700' }}">
                            <div class="flex flex-col h-full">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        {{ __('Default login/signup cover') }}
                                    </label>
                                    <div class="mb-3">
                                        <img src="https://cdn.brokeret.com/crm-assets/login-image/c19.png"
                                             alt="Default login/signup cover"
                                             class="w-full h-48 object-cover rounded-lg">
                                    </div>
                                </div>
                                <!-- Radio button at bottom -->
                                <div class="mt-4 pt-3 border-t border-slate-200 dark:border-slate-700">
                                    <div class="flex items-center space-x-3">
                                        <input type="radio"
                                               name="login_bg_choice"
                                               value="default"
                                               id="default_bg"
                                               checked
                                               class="w-5 h-5 text-primary border-slate-300 focus:ring-primary">
                                        <label for="default_bg" class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                            {{ __('Select Default login/signup cover') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Uploaded login/signup cover Option -->
                        <div class="border rounded-lg p-4 {{ ($currentLoginBg !== 'https://cdn.brokeret.com/crm-assets/login-image/c19.png' && $currentLoginBg !== $defaultLoginBg && $currentLoginBg !== 'default/auth-bg.jpg' && !empty($currentLoginBg)) ? 'border-primary bg-primary/5' : 'border-slate-200 dark:border-slate-700' }}">
                            <div class="flex flex-col h-full">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        {{ __('Custom login/signup cover') }}
                                    </label>
                                    
                                    <!-- Current Uploaded Image Preview -->
                                    @if($currentLoginBg !== 'https://cdn.brokeret.com/crm-assets/login-image/c19.png' && $currentLoginBg !== $defaultLoginBg && $currentLoginBg !== 'default/auth-bg.jpg' && !empty($currentLoginBg))
                                        <div class="mb-3">
                                            <img src="{{ getFilteredPath($currentLoginBg, 'default/auth-bg.jpg') }}" 
                                                 alt="Current Uploaded login/signup cover" 
                                                 class="w-full h-48 object-cover rounded-lg">
                                        </div>
                                    @endif
                                    
                                    <!-- File Upload Input -->
                                    <div class="mb-3">
                                        <input type="file" 
                                               name="login_bg" 
                                               accept=".jpeg,.jpg,.png"
                                               class="form-control"
                                               id="login_bg_file">
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                            {{ __('Recommended Size: 935 x 920 pixels. Max size: 2MB') }}
                                        </p>
                                    </div>
                                </div>
                                <!-- Radio button at bottom -->
                                <div class="mt-4 pt-3 border-t border-slate-200 dark:border-slate-700">
                                    <div class="flex items-center space-x-3">
                                        <input type="radio" 
                                               name="login_bg_choice" 
                                               value="uploaded" 
                                               id="uploaded_bg"
                                               {{ ($currentLoginBg !== 'https://cdn.brokeret.com/crm-assets/login-image/c19.png' && $currentLoginBg !== $defaultLoginBg && $currentLoginBg !== 'default/auth-bg.jpg' && !empty($currentLoginBg)) ? 'checked' : '' }}
                                               class="w-5 h-5 text-primary border-slate-300 focus:ring-primary">
                                        <label for="uploaded_bg" class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                            {{ __('Select Custom login/signup cover') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Site Logo Toggle -->
                    <div class="flex items-center space-x-7 flex-wrap mt-6">
                        <label class="form-label !w-auto pt-0">
                            {{ __('Show site logo on login/signup cover') }}
                        </label>
                        <div class="form-switch ps-0">
                            <input class="form-check-input" type="hidden" value="0" name="show_login_logo">
                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                <input type="checkbox" name="show_login_logo" value="1" class="sr-only peer" {{ old('show_login_logo', setting('show_login_logo', 'theme', 1)) ? 'checked' : '' }}>
                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                            </label>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 ml-7">
                        {{ __('If enabled, the site logo will appear in the middle of the login/signup cover. If disabled, no logo will be shown.') }}
                    </p>

                    <!-- Submit Button -->
                    <div class="flex justify-end mt-6">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('website-script')
    <script>
        $(document).ready(function() {
            // Handle radio button changes
            $('input[name="login_bg_choice"]').change(function() {
                // Remove active styling from all containers
                $('.border').removeClass('border-primary bg-primary/5').addClass('border-slate-200 dark:border-slate-700');
                
                // Add active styling to selected container
                $(this).closest('.border').removeClass('border-slate-200 dark:border-slate-700').addClass('border-primary bg-primary/5');
                
                // Show/hide file input based on selection
                if ($(this).val() === 'uploaded') {
                    $('#login_bg_file').prop('required', true);
                } else {
                    $('#login_bg_file').prop('required', false);
                }
            });

            // Handle file input change for preview
            $('#login_bg_file').change(function() {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        // Find the closest container and update the image
                        var container = $('#login_bg_file').closest('.border');
                        var img = container.find('img');
                        if (img.length === 0) {
                            // Create new image if it doesn't exist
                            img = $('<img class="w-full h-48 object-cover rounded-lg mb-3">');
                            container.find('.mb-3').first().after(img);
                        }
                        img.attr('src', e.target.result);
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
    </script>
@endsection 