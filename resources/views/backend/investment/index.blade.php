@extends('backend.layouts.app')
@section('title')
    {{ __('Forex Accounts') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('All Forex Accounts') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="site-card">
                        <div class="site-card-body table-responsive">
                            <div class="site-datatable">
                                <table id="dataTable" class="display data-table">
                                    <thead>
                                    <tr>
                                        <th>{{ __('Icon') }}</th>
                                        <th>{{ __('Schema') }}</th>
                                        <th>{{ __('User') }}</th>
                                        <th>{{ __('Login') }}</th>
                                        <th>{{ __('Group') }}</th>
                                        <th>{{ __('Balance') }}</th>
                                        <th>{{ __('Equity') }}</th>
                                        <th>{{ __('Credit') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        (function ($) {
            "use strict";
            var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.forex-accounts-real') }}",
                columns: [
                    {data: 'icon', name: 'icon'},
                    {data: 'schema', name: 'schema'},
                    {data: 'username', name: 'username'},
                    {data: 'login', name: 'login'},
                    {data: 'group', name: 'group'},
                    {data: 'balance', name: 'balance'},
                    {data: 'equity', name: 'equity'},
                    {data: 'credit', name: 'credit'},
                ]
            });


        })(jQuery);
    </script>
@endsection
