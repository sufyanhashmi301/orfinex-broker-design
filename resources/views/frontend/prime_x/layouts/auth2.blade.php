<!DOCTYPE html>
<html lang="en" class="light">
    @include('frontend::include.__head')
    <body class="font-inter dashcode-app" id="body_class">
        <x:notify-messages/>

        @yield('content')

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
        {!! setting('site_user_footer_code', 'defaults') !!}
    </body>
</html>

