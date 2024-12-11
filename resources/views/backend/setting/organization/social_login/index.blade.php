@extends('backend.setting.organization.index')
@section('title')
    {{ __('Social Logins') }}
@endsection
@section('organization-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8 hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="socialLogin-dataTable">
                            <thead>
                            <tr>
                                <th scope="col" class="table-th">{{ __('Title') }}</th>
                                <th scope="col" class="table-th">{{ __('Client-ID') }}</th>
                                <th scope="col" class="table-th">{{ __('Seceret-ID') }}</th>
                                <th scope="col" class="table-th">{{ __('Status') }}</th>
                                <th scope="col" class="table-th">{{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--Modal for update social login--}}
    @can('social-login-edit')
        @include('backend.setting.organization.social_login.__edit_modal')
    @endcan

@endsection
@section('organization-script')
    <script !src="">
        (function ($) {
            "use strict";
            var table = $('#socialLogin-dataTable').DataTable();
            table.destroy();
            var table = $('#socialLogin-dataTable').DataTable({
                dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
                searching: false,
                lengthChange: false,
                info: true,
                language: {
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        previous: "<iconify-icon icon=\"ic:round-keyboard-arrow-left\"></iconify-icon>",
                        next: "<iconify-icon icon=\"ic:round-keyboard-arrow-right\"></iconify-icon>"
                    },
                    search: "Search:"
                },
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('admin.social.index') }}",
                columns: [
                    {data: 'title', name: 'title'},
                    {data: 'client_id', name: 'client_id'},
                    {data: 'client_secret', name: 'client_secret'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'},
                ]
            });
        })(jQuery);

        $('body').on('click', '.editBtn', function (event){
            "use strict";
            event.preventDefault();
            $('#edit-social-login-body').empty();
            var recordId = $(this).data('id');
            var url = "{{ route('admin.social.edit', ':id') }}".replace(':id', recordId);

            $.get(url, function (response) {
                $('#editSocialLoginModal').modal('show');
                $('#edit-social-login-body').append(response);
            });
        });
    </script>
@endsection
