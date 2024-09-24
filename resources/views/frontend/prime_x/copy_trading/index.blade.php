@extends('frontend::layouts.user')
@section('style')
    <style>
        .page-content {
            padding: 0 !important;
        }
        .copy-trading__menu {
            top: 22px;
        }
        .nav-tabs .nav-link.active {
            color: #FED000 !important;
            border-color: #FED000 !important;
        }

        @media (min-width: 960px) {
            .copy-trading__menu {
                left: 15px;
            }
        }
        @media (min-width: 1200px) {
            .copy-trading__menu {
                left: 237px;
            }
        }
        @media (min-width: 1600px) {
            .copy-trading__menu {
                left: 118px;
            }
        }
    </style>
@endsection
@section('content')
    <div class="relative">
        <div class="absolute copy-trading__menu">
            <ul class="nav nav-tabs flex flex-col md:flex-row flex-wrap list-none border-b-0 pl-0">
                <li class="nav-item">
                    <a href="{{ route('user.follower_access') }}" class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 hover:border-transparent focus:border-transparent dark:text-slate-300 {{ isActive('user.follower_access') }}">
                        {{ __('Follower Access') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.provider_access') }}" class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 hover:border-transparent focus:border-transparent dark:text-slate-300 {{ isActive('user.provider_access') }}">
                        {{ __('Provider Access') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.ratings') }}" class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 hover:border-transparent focus:border-transparent dark:text-slate-300 {{ isActive('user.ratings') }}">
                        {{ __('Ratings') }}
                    </a>
                </li>
            </ul>
        </div>
        @yield('copy-trading-content')
    </div>
@endsection
