<!DOCTYPE html>
<html lang="en" class="light">
    @include('frontend::include.__head')
    <body class=" font-inter skin-default">
        <x:notify-messages/>
        <div class="loginwrapper">
            <div class="lg-inner-column">
                <div class="left-column relative">
 <div class="w-full h-full flex items-center justify-around bg-cover bg-no-repeat bg-center" style="background-image:url('{{ setting('login_bg', 'theme') ? asset(setting('login_bg', 'theme')) : asset('default/auth-bg.jpg') }}')">        
                 <div class="mx-auto max-w-xs text-center">
                            <a href="{{ route('home')}}" class="">
                                <img src="{{ getFilteredPath(setting('site_logo', 'global'), 'fallback/branding/desktop-logo.png') }}" class="h-[56px]" alt="{{ __('Logo') }}">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="right-column relative">
                    <div class="inner-content h-full flex flex-col bg-white dark:bg-slate-800">
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

