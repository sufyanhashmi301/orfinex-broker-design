<div
    class="tab-pane fade"
    id="pills-transfer"
    role="tabpanel"
    aria-labelledby="pills-transfer-tab"
>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h4 class="title">{{ __('Forex Account') }}</h4>
                </div>
                <div class="site-card-body table-responsive">
                    <div class="site-datatable">
                        <table id="user-forex-account-dataTable" class="display data-table">
                            <thead>
                            <tr>
{{--                                <th>{{ __('Icon') }}</th>--}}
                                <th>{{ __('Schema') }}</th>
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
@push('single-script')
    <script>
        (function ($) {
            "use strict";
            var table = $('#user-forex-account-dataTable').DataTable();
            table.destroy();
            var table = $('#user-forex-account-dataTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('admin.forex-accounts',['type'=>'real',$user->id]) }}",
                columns: [
                    // {data: 'icon', name: 'icon'},
                    {data: 'schema', name: 'schema'},
                    {data: 'login', name: 'login'},
                    {data: 'group', name: 'group'},
                    {data: 'balance', name: 'balance'},
                    {data: 'equity', name: 'equity'},
                    {data: 'credit', name: 'credit'},
                ]
            });
        })(jQuery);
    </script>
@endpush
