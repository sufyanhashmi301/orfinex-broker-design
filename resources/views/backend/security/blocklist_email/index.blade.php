@extends('backend.security.index')
@section('security-title')
    {{ __('Blocklist Email') }}
@endsection
@section('title')
    {{ __('Blocklist Email') }}
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
                            <th>{{ __('Blacklist Email') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Naeemali@gmail..com</td>
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
                                <td>naeemali2020@gmail.com</td>
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