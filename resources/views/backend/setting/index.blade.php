@extends('backend.layouts.dual-sidebar')
@section('title')
    {{ __('setting') }}
@endsection
@section('submenu')
    <ul class="sidebar-submenu menu-open divide-y divide-slate-100 dark:divide-slate-700">
        <li>
            <a href="{{ route('admin.settings.company') }}" class="navItem {{ isActive('admin.settings.company') }}">
                {{ __('Company') }}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings.site') }}" class="navItem {{ isActive('admin.settings.site') }}">
                {{ __('Site Settings') }}
            </a>
        </li>
        @canany(['role-list','role-create','role-edit'])
            <li class="nav-item">
                <a href="{{route('admin.roles.index')}}" class="navItem {{ isActive('admin.roles*') }}">
                    {{__('Roles & Permissions') }}
                </a>
            </li>
        @endcanany
        <li>
            <a href="{{ route('admin.risk-profile-tag.index') }}" class="navItem {{ isActive('admin.risk-profile-tag*') }}">
                {{ __('Risk Profile Tag Form') }}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.theme.site') }}" class="navItem {{ isActive('admin.theme.site') }}">
                {{ __('Theme')}}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings.mail') }}" class="navItem {{ isActive('admin.settings.mail') }}">
                {{ __('Email') }}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings.platform-api') }}" class="navItem {{ isActive('admin.settings.platform-api') }}">
                {{ __('Platform API') }}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.security.all-sections') }}" class="navItem {{ isActive('admin.security*') }}">
                {{ __('Security')}}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings.plugin','system') }}" class="navItem {{ isActive('admin.settings.plugin') }}">
                {{ __('Notification') }}
            </a>
        </li>
        @can('language-setting')
            <li>
                <a href="{{ route('admin.language.index') }}" class="navItem {{ isActive('admin.language*') }}">
                    {{ __('Language') }}
                </a>
            </li>
        @endcan
        <li>
            <a href="{{ route('admin.settings.currency') }}" class="navItem {{ isActive('admin.settings.currency') }}">
                {{ __('Currency')}}
            </a>
        </li>
        <li>
            <a href="" class="navItem">
                {{ __('Social Logins')}}
            </a>
        </li>
        <li>
            <a href="{{route('admin.links.document-links')}}" class="navItem {{ isActive('admin.links*') }}">
                {{ __('Doc & Links')}}
            </a>
        </li>
        <li>
            <a href="{{route('admin.settings.slack')}}" class="navItem {{isActive('admin.settings.slack')}}">
                {{ __('Collab Tools')}}
            </a>
        </li>
        <li>
            <a href="" class="navItem">
                {{ __('Multi-Factor Auth')}}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings.transfers') }}" class="navItem {{ isActive('admin.settings.transfers') }}">
                {{ __('Transfers')}}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings.gdpr') }}" class="navItem {{ isActive('admin.settings.gdpr') }}">
                {{ __('GDPR')}}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings.site-maintenance') }}" class="navItem {{ isActive('admin.settings.site-maintenance') }}">
                {{ __('Maintenance')}}
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings.forex-api') }}" class="navItem {{ isActive('admin.settings.forex-api') }}">
                {{ __('Platform API') }}
            </a>
        </li>
    </ul>
@endsection
@section('content')
<div class="transition-all duration-150 ltr:ml-[200px] rtl:mr-[200px] p-6">
    @yield('setting-content')
</div>
@endsection
@section('script')
    <script>
        new SimpleBar($("#sidebar_subMenus, #scrollModal")[0]);
    </script>
@endsection
