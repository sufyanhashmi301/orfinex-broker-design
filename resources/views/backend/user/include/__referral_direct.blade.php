<div
    class="tab-pane fade"
    id="pills-direct-referral"
    role="tabpanel"
    aria-labelledby="pills-direct-referral-tab"
>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h4 class="title">{{ __('Direct referrals of :name',['name'=>$user->full_name]) }}</h4>
                    <div class="content">
                                <span data-bs-toggle="modal" data-bs-target="#addReferralModal">
                                <a href="javascript:void(0)" class="btn btn-dark btn-sm">
                                    <i icon-name="plus"></i>
                                    Add Referral
                                </a>
                                </span>
                    </div>
                </div>
                <div class="site-card-body table-responsive">
                    <div class="site-datatable">
                        <table id="user-referral-dataTable" class="display data-table">
                            <thead>
                            <tr>
                                <th>{{ __('Avatar') }}</th>
                                <th>{{ __('User') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Balance') }}</th>
                                <th>{{ __('KYC') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
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
            $('#user-referral-dataTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('admin.referral.direct.list',$user->id) }}",
                columns: [
                    {data: 'avatar', name: 'avatar'},
                    {data: 'full_name', name: 'full_name'},
                    {data: 'email', name: 'email'},
                    {data: 'balance', name: 'balance'},
                    {data: 'kyc', name: 'kyc'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        })(jQuery);
    </script>
@endpush
