<!DOCTYPE html>
<html lang="en">
@include('frontend::include.__head')
<body class="font-inter dashcode-app" id="body_class">
<x:notify-messages/>

@yield('content')

@include('frontend::include.__script')

</body>
</html>

