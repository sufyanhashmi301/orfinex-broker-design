@extends('backend.layouts.app')
@section('title')
    {{ __('Links Setting') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <h2 class="title">@yield('links-title')</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="site-tab-bars">
                        <ul>
                            <li class="{{ isActive('admin.links.document-links') }}">
                                <a href="{{ route('admin.links.document-links') }}">
                                    <i icon-name="file-text"></i>
                                    Document links
                                </a>
                            </li>
                            <li class="{{ isActive('admin.links.platform-links') }}">
                                <a href="{{ route('admin.links.platform-links') }}">
                                    <i icon-name="monitor-check"></i>
                                    Platform links
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="row">
                        @yield('links-content')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection