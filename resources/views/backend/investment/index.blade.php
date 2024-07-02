@extends('backend.layouts.app')
@section('title')
    {{ __('Forex Accounts') }}
@endsection
@section('style')
    <style>
        .data-card {
            flex-direction: column !important;
        }
    </style>
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('All :type Forex Accounts',['type'=>ucfirst($type)]) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="site-tab-bars py-4">
                <div class="row row-cols-1 row-cols-md-3 row-cols-lg-6 g-3 gx-2">
                    <div class="col position-relative">
                        <div class="d-flex flex-col text-center">
                            <div class="icon-square d-flex align-items-center justify-content-center bg-light mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                            </div>
                            <div class="content">
                                <p class="small my-2">Total Accounts</p>
                                <h4 class="count lead fw-bold mb-0">{{$data['TotalAccounts']}}</h4>
                            </div>
                        </div>
                        <div class="position-absolute h-100 end-0 top-0 bg-light d-none d-md-block" style="width: 2px;"></div>
                    </div>
                    <div class="col position-relative">
                        <div class="d-flex flex-col text-center">
                            <div class="icon-square d-flex align-items-center justify-content-center bg-light mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                            </div>
                            <div class="content">
                                <p class="small my-2">With Balance</p>
                                <h4 class="count lead fw-bold mb-0">{{$data['withBalance']}}</h4>
                            </div>
                        </div>
                        <div class="position-absolute h-100 end-0 top-0 bg-light d-none d-md-block" style="width: 2px;"></div>
                    </div>
                    <div class="col position-relative">
                        <div class="d-flex flex-col text-center">
                            <div class="icon-square d-flex align-items-center justify-content-center bg-light mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                            </div>
                            <div class="content">
                                <p class="small my-2">With Bonus</p>
                                <h4 class="count lead fw-bold mb-0">0</h4>
                            </div>
                        </div>
                        <div class="position-absolute h-100 end-0 top-0 bg-light d-none d-lg-block" style="width: 2px;"></div>
                    </div>
                    <div class="col position-relative">
                        <div class="d-flex flex-col text-center">
                            <div class="icon-square d-flex align-items-center justify-content-center bg-light mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                            </div>
                            <div class="content">
                                <p class="small my-2">Without Balance</p>
                                <h4 class="count lead fw-bold mb-0">{{$data['withoutBalance']}}</h4>
                            </div>
                        </div>
                        <div class="position-absolute h-100 end-0 top-0 bg-light d-none d-md-block" style="width: 2px;"></div>
                    </div>
                    <div class="col position-relative">
                        <div class="d-flex flex-col text-center">
                            <div class="icon-square d-flex align-items-center justify-content-center bg-light mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                            </div>
                            <div class="content">
                                <p class="small my-2">Without Bonus</p>
                                <h4 class="count lead fw-bold mb-0">0</h4>
                            </div>
                        </div>
                        <div class="position-absolute h-100 end-0 top-0 bg-light d-none d-md-block" style="width: 2px;"></div>
                    </div>
                    <div class="col">
                        <div class="d-flex flex-col text-center">
                            <div class="icon-square d-flex align-items-center justify-content-center bg-light mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-check"><path d="M18 6 7 17l-5-5"/><path d="m22 10-7.5 7.5L13 16"/></svg>
                            </div>
                            <div class="content">
                                <p class="small my-2">Inactive Accounts</p>
                                <h4 class="count lead fw-bold mb-0">{{$data['unActiveAccounts']}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="site-card">
                        <div class="site-card-body table-responsive">
                            <div class="site-datatable">
                                <table id="dataTable" class="display data-table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Account Number') }}</th>
                                            <th>{{ __('User') }}</th>
                                            <th>{{ __('Account Type') }}</th>
                                            <th>{{ __('Group') }}</th>
{{--                                            <th>{{ __('Currency') }}</th>--}}
                                            <th>{{ __('Leverage') }}</th>
                                            <th>{{ __('Balance') }}</th>
                                            <th>{{ __('Agent/IB Number') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Created At') }}</th>
                                            <th>{{ __('Actions') }}</th>
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
            var type = "{{ $type }}"; // Retrieve the 'type' variable from PHP and assign it to a JavaScript variable
            var url = '{{ route("admin.forex-accounts", ["type" => ":type"]) }}';
            url = url.replace(':type', type);
            var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: url,
                columns: [
                    {data: 'login', name: 'login'},
                    {data: 'username', name: 'username'},
                    {data: 'schema', name: 'schema'},
                    // {data: 'login', name: 'login'},
                    {data: 'group', name: 'group'},
                    // {data: 'currency', name: 'currency'},
                    {data: 'leverage', name: 'leverage'},
                    {data: 'balance', name: 'balance'},
                    {data: 'ib_number', name: 'ib_number'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action'},
                ]
            });


        })(jQuery);
    </script>
@endsection
