<div
    class="tab-pane fade"
    id="ib-info"
    role="tabpanel"
    aria-labelledby="ib-info-tab"
>

    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h4 class="title">{{ __('IB Account') }}</h4>

                    <div class="content">
                                <span data-bs-toggle="modal" data-bs-target="#addIBModal">
                                <a href="javascript:void(0)" class="btn btn-dark btn-sm">
                                    <i icon-name="plus"></i>
                                    Add New IB
                                </a>
                                </span>
                                <span data-bs-toggle="modal" data-bs-target="#updateIBModal">
                                <a href="javascript:void(0)" class="btn btn-dark btn-sm">
                                    <i icon-name="plus"></i>
                                    Update IB
                                </a>
                                </span>
                    </div>

                </div>

                <div class="site-card-body table-responsive">
                    <div class="site-datatable">
                        <table id="ib-info-dataTable" class="display data-table">
                            <thead>
                            <tr>
{{--                                <th>{{ __('Icon') }}</th>--}}
                                <th>{{ __('Login') }}</th>
                                <th>{{ __('Balance') }}</th>
                                <th>{{ __('status') }}</th>
                                <th></th>
{{--                                <th>{{ __('Group') }}</th>--}}
{{--                                <th>{{ __('Balance') }}</th>--}}
{{--                                <th>{{ __('Equity') }}</th>--}}
{{--                                <th>{{ __('Credit') }}</th>--}}

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
            var table = $('#ib-info-dataTable').DataTable();
            table.destroy();
            var table = $('#ib-info-dataTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('admin.user.ib-info',$user->id) }}",
                columns: [
                    {data: 'ib_login', name: 'ib_login'},
                    {data: 'ib_balance', name: 'ib_balance'},
                    {data: 'ib_status', name: 'ib_status'},
                    // {data: 'action', name: 'action'},

                ]
            });
        })(jQuery);
    </script>
@endpush
