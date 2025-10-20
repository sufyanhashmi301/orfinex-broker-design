<!DOCTYPE html>
<html lang="en" class="light">
    @include('frontend::include.__head')
    <body class=" font-inter skin-default">
        <x:notify-messages/>
        <div class="loginwrapper">
            <div class="lg-inner-column">
                <div class="left-column relative">
                    <div class="w-full h-full flex items-center justify-around bg-cover bg-no-repeat bg-center"
                         style="background-image:url('{{ setting('login_bg_choice', 'theme', 'default') === 'uploaded' ? getFilteredPath(setting('login_bg', 'theme'), 'https://cdn.brokeret.com/crm-assets/login-image/c19.png') : 'https://cdn.brokeret.com/crm-assets/login-image/c19.png' }}')">
                        <div class="mx-auto max-w-xs text-center">
                            @if(setting('show_login_logo', 'theme', 1))
                                <a href="{{ route('home')}}" class="">
                                    <img src="{{ getFilteredPath(setting('site_logo', 'global'), 'fallback/branding/desktop-logo.png') }}" class="h-[56px]" alt="{{ __('Logo') }}">
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="right-column relative">
                    <div class="inner-content h-full flex flex-col bg-white dark:bg-slate-800">
                        @if(setting('company_website', 'common_settings') && setting('company_website', 'common_settings') !== '')
                            <div class="auth-header py-5">
                                <div class="w-full max-w-lg mx-auto">
                                    <a href="{{ setting('company_website', 'common_settings') }}" class="inline-flex items-center text-sm text-gray-500 transition-colors hover:text-slate-900 dark:text-gray-400">
                                        <svg class="stroke-current" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M12.7083 5L7.5 10.2083L12.7083 15.4167" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                        {{ __('Back to website') }}
                                    </a>
                                </div>
                            </div>
                        @endif
                        <div class="h-full flex flex-col justify-center">
                            <div class="w-full max-w-lg mx-auto">
                                <div class="mobile-logo text-center mb-6 lg:hidden block">
                                    <a href="{{ route('home')}}" class="inline-flex">
                                        <img src="{{ getFilteredPath(setting('site_logo', 'global'), 'fallback/branding/desktop-logo.png') }}" alt="{{ __('Logo') }}" class="h-[56px]">
                                    </a>
                                </div>
                                @yield('content')
                            </div>
                        </div>
                        @if(setting('copyright_text', 'common_settings'))
                            <div class="auth-footer text-center mt-5">
                                {{ setting('copyright_text', 'common_settings') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @include('frontend::include.__script')


        <script !src="">
            $(document).ready(function() {
                $('.toggle-password').click(function () {
                    const input = $($(this).data('toggle'));
                    const type = input.attr('type') === 'password' ? 'text' : 'password';
                    input.attr('type', type);

                    const icon = type === 'password' ? 'heroicons:eye-slash' : 'heroicons:eye';
                    $(this).find('iconify-icon').attr('icon', icon);
                });
            });
        </script>
    </body>
</html>

