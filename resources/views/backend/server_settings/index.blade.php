@extends('backend.layouts.app')
@section('title')
    {{ __('server setting') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <h2 class="title">@yield('server-setting-title')</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="site-tab-bars">
                        <ul>
                            <li class="{{ isActive('admin.settings.server') }}">
                                <a href="{{ route('admin.settings.server') }}">
                                    <i icon-name="server"></i>
                                    {{ __('Server Settings') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="row">
                        @yield('server-setting-content')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection