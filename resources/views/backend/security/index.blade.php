@extends('backend.layouts.app')
@section('title')
    {{ __('Security Setting') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <h2 class="title">@yield('security-title')</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="site-tab-bars">
                        <ul>
                            <li class="{{ isActive('admin.security.all-sections') }}">
                                <a href="{{ route('admin.security.all-sections') }}">
                                    <i icon-name="layout-list"></i>
                                    {{ __('All Sections') }}
                                </a>
                            </li>
                            <li class="{{ isActive('admin.security.blocklist-ip') }}">
                                <a href="{{ route('admin.security.blocklist-ip') }}">
                                    <i icon-name="screen-share-off"></i>
                                    {{ __('Blocklist IP') }}
                                </a>
                            </li>
                            <li class="{{ isActive('admin.security.single-session') }}">
                                <a href="{{ route('admin.security.single-session') }}">
                                    <i icon-name="clock"></i>
                                    {{ __('Single Session') }}
                                </a>
                            </li>
                            <li class="{{ isActive('admin.security.blocklist-email') }}">
                                <a href="{{ route('admin.security.blocklist-email') }}">
                                    <i icon-name="mail-x"></i>
                                    {{ __('Blocklist Email') }}
                                </a>
                            </li>
                            <li class="{{ isActive('admin.security.login-expiry') }}">
                                <a href="{{ route('admin.security.login-expiry') }}">
                                    <i icon-name="alarm-clock-off"></i>
                                    {{ __('Login Expiry') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="row">
                        @yield('security-content')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
