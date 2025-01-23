@extends('backend.layouts.dual-sidebar')
@section('title')
    {{ __('Promotion Setting') }}
@endsection
@section('content')

    @section('submenu')
      <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
          <li>
              <a href="{{ route('admin.banner.user_dashboard') }}" class="navItem {{ isActive('admin.banner.user_dashboard') }}">
                  {{ __('Banners') }}
              </a>
          </li>
          <li>
              <a href="{{ route('admin.slider.user_dashboard') }}" class="navItem {{ isActive('admin.slider.user_dashboard') }}">
                  {{ __('Sliders') }}
              </a>
          </li>
      
      </ul>
    @endsection


    @yield('content')
@endsection
@section('script')
    <script>
        new SimpleBar($("#sidebar_subMenus, #scrollModal")[0]);
    </script>

    @yield('script')
@endsection
