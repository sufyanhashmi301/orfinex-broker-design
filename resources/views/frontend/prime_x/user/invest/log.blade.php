@extends('frontend::layouts.user')
@section('title')
    {{ __('Schema Logs') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">{{ __('All Invested Account Type') }}</h3>
                </div>
                <div class="site-card-body">
                    <div class="site-datatable">
                        <div class="row table-responsive">
                            <div class="col-xl-12">
                                <table id="dataTable" class="display data-table">
                                    <thead>
                                    <tr>
                                        <th>{{ __('Icon') }}</th>
                                        <th>{{ __('Schema') }}</th>
                                        <th>{{ __('ROI') }}</th>
                                        <th>{{ __('Profit') }}</th>
                                        <th>{{ __('Period Remaining') }}</th>
                                        <th>{{ __('Capital Back') }}</th>
                                        <th>{{ __('Timeline') }}</th>
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
                processing: false,
                serverSide: true,
                ajax: "{{ route('user.invest-logs') }}",
                columns: [
                    {data: '{{ __("icon") }}', name: '{{ __("icon") }}'},
                    {data: '{{ __("schema") }}', name: '{{ __("schema") }}'},
                    {data: '{{ __("rio") }}', name: '{{ __("rio") }}'},
                    {data: '{{ __("profit") }}', name: '{{ __("profit") }}'},
                    {data: '{{ __("period_remaining") }}', name: '{{ __("period_remaining") }}'},
                    {data: '{{ __("capital_back") }}', name: '{{ __("capital_back") }}'},
                    {data: '{{ __("next_profit_time") }}', name: '{{ __("next_profit_time") }}'},
                ]
            });
        })(jQuery);
    </script>
@endsection
