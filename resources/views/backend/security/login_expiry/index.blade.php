@extends('backend.security.index')
@section('security-title')
    {{ __('Login Expiry') }}
@endsection
@section('title')
    {{ __('Login Expiry') }}
@endsection
@section('security-content')
    <div class="col-xl-12">
        <div class="site-card">
            <div class="site-card-body table-responsive">
                <div class="site-datatable">
                    <table id="dataTable" class="display data-table">
                        <thead>
                        <tr>
                            <th>{{ __('#') }}</th>
                            <th>{{ __('User') }}</th>
                            <th>{{ __('Expiry Date') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    <div class="flex align-items-center">
                                        <span class="avatar-text">NA</span>
                                        <div class="ml-2">
                                            <p class="fw-bold lh-1 mb-1">Naeem Ali</p>
                                            <p class="small lh-1 mb-0">Junior</p>
                                        </div>
                                    </div>
                                </td>
                                <td>10-1-2024</td>
                                <td>
                                    <a href="" class="round-icon-btn primary-btn">
                                        <i icon-name="edit-3"></i>
                                    </a>
                                    <a href="" class="round-icon-btn red-btn">
                                        <i icon-name="trash-2"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    <div class="flex align-items-center">
                                        <span class="avatar-text">NA</span>
                                        <div class="ml-2">
                                            <p class="fw-bold lh-1 mb-1">Naeem Ali</p>
                                            <p class="small lh-1 mb-0">Junior</p>
                                        </div>
                                    </div>
                                </td>
                                <td>15-1-2-24</td>
                                <td>
                                    <a href="" class="round-icon-btn primary-btn">
                                        <i icon-name="edit-3"></i>
                                    </a>
                                    <a href="" class="round-icon-btn red-btn">
                                        <i icon-name="trash-2"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection