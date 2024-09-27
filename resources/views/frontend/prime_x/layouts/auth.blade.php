<!DOCTYPE html>
<html lang="en">
@include('frontend::include.__head')
<body class="font-inter skin-default">
    <div class="loginwrapper">
        <div class="lg-inner-column">
            <div class="right-column relative">
                <div class="inner-content h-full flex flex-col bg-white dark:bg-slate-800">
                    <div class="auth-box h-full flex flex-col justify-center">
                        <div class="mobile-logo text-center mb-6 lg:hidden block">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset(setting('site_logo','global')) }}" alt="" class="h-12 mx-auto"/>
                            </a>
                        </div>
                        <x:notify-messages/>
                        <div class="text-center 2xl:mb-10 mb-4">
                            <h4 class="font-medium">
                                @yield('title')
                            </h4>
                            <div class="text-slate-500 dark:text-slate-400 text-base">
                                @yield('description')
                            </div>
                        </div>

                        @yield('content')

                    </div>
                    <div class="auth-footer text-center">
                        {{ setting('footer_content', 'global') }}
                    </div>
                </div>
            </div>
            <div class="left-column bg-cover bg-no-repeat bg-center" style="background-image: url({{ asset(setting('login_bg','global')) }})">
                <div class="flex flex-col h-full justify-center">
                    <div class="flex-1 flex flex-col justify-center items-center">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset(setting('site_logo','global')) }}" alt="" class="h-14"/>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('frontend::include.__script')
    <script>
        $(document).ready(function() {
            $('.toggle-password').click(function () {
                const input = $($(this).attr('toggle'));
                const type = input.attr('type') === 'password' ? 'text' : 'password';
                input.attr('type', type);

                const icon = type === 'password' ? 'heroicons:eye-slash' : 'heroicons:eye';
                $(this).find('iconify-icon').attr('icon', icon);
            });
        });
    </script>
</body>
</html>

