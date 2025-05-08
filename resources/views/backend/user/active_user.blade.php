@extends('backend.user.index')
@section('title')
    {{ __('Active Customers') }}
@endsection
@php
    $riskProfileTags = getRiskProfileTag();
@endphp
@section('filters')
    <form id="filter-form" method="POST" action="{{ route('admin.user.export', ['type' => 'active']) }}">
        @csrf
        <div class="flex flex-col sm:flex-row justify-between flex-wrap sm:items-center gap-3">
            <div class="flex-1 w-full flex flex-col sm:flex-row sm:gap-3 gap-2">
                <div class="flex-1 input-area relative">
                    <input type="text" name="global_search" id="global_search" class="form-control h-full" placeholder="Search by Name, Username, Email, Staff">
                </div>
                <div class="flex-1 input-area relative">
                    <input type="text" name="staff_name" id="staff_name" class="form-control h-full" placeholder="Staff Name">
                </div>
                <div class="flex-1 input-area relative">
                    <input type="text" name="phone" id="phone" class="form-control h-full" placeholder="Phone">
                </div>
                <div class="flex-1 input-area relative">
                    <select name="country" id="country" class="select2 form-control h-full w-full" data-placeholder="{{ __('Select a country') }}">
                        <option value="" selected>
                            {{ __('country') }}
                        </option>
                        @foreach( getCountries() as $country)
                            <option value="{{ $country['name'] }}">
                                {{ $country['name']  }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 input-area relative">
                    <input type="date" name="created_at" id="created_at" class="form-control h-full" placeholder="Created At">
                </div>
                <div class="flex-1 input-area relative">
                    <select name="tag" id="tag" class="select2 form-control w-full h-full" data-placeholder="{{ __('Select a tag') }}">
                        <option value="" selected>
                            {{ __('tags') }}
                        </option>
                        @foreach($riskProfileTags as $tag)
                            <option value="{{ $tag->name }}">
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex sm:space-x-3 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                <div class="input-area relative">
                    <button type="button" id="filter" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                        {{ __('Filter') }}
                    </button>
                </div>
                @can('customer-export')
                <div class="input-area relative">
                    <button type="submit" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lets-icons:export-fill"></iconify-icon>
                        {{ __('Export') }}
                    </button>
                </div>
                @endcan
                <div class="input-area relative">
                    <button type="button" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white" data-bs-toggle="modal" data-bs-target="#configureModal">
                        <iconify-icon class="text-base font-light" icon="lucide:wrench"></iconify-icon>
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('customers-content')
    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class="col-span-8 hidden"></span>
                <span class="col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('User') }}</th>
                                    <th scope="col" class="table-th">{{ __('Balance') }}</th>
                                    <th scope="col" class="table-th">{{ __('Equity') }}</th>
                                    <th scope="col" class="table-th">{{ __('Credit') }}</th>
                                    <th scope="col" class="table-th">{{ __('Country') }}</th>
                                    <th scope="col" class="table-th">{{ __('Staff') }}</th>
                                    <th scope="col" class="table-th">{{ __('KYC') }}</th>
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
            <div id="processingIndicator" class="text-center">
                {{-- <img src="{{ asset('global/images/loading.gif') }}" class="inline-block h-20" alt="Loader"> --}}
                <iconify-icon class="spining-icon text-5xl dark:text-slate-100" icon="lucide:loader"></iconify-icon>
            </div>
        </div>
    </div>

    <!-- Modal for Send Email -->
    @can('customer-mail-send')
        @include('backend.user.include.__mail_send')
    @endcan
    @include('backend.user.include.__configure_modal')
    @include('backend.user.include.__reset_password')
@endsection

@section('customers-script')

    <script>
        (function ($) {
            "use strict";

            var table = $('#dataTable')
            .on('processing.dt', function (e, settings, processing) {
                $('#processingIndicator').css('display', processing ? 'block' : 'none');
            }).DataTable({
                dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
                processing: true,
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
                    search: "Search:",
                    processing: '<iconify-icon icon="lucide:loader"></iconify-icon>'
                },
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.user.active') }}",
                    data: function (d) {
                        d.global_search = $('#global_search').val();
                        d.phone = $('#phone').val();
                        d.country = $('#country').val();
                        d.staff_name = $('#staff_name').val();
                        d.status = $('#status').val();
                        d.created_at = $('#created_at').val();
                        d.tag = $('#tag').val();
                    }
                },
                columns: [
                    {data: 'username', name: 'username'},
                    {data: 'balance', name: 'balance'},
                    {data: 'equity', name: 'equity'},
                    {data: 'credit', name: 'credit'},
                    {data: 'country', name: 'country'},
                    {data: 'staff_name', name: 'staff_name'},
                    {data: 'kyc', name: 'kyc'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
              // Function to generate a random password
              function generateRandomPassword(length = 12) {
                const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
                let password = "";
                for (let i = 0; i < length; i++) {
                    password += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                return password;
            }

            // When the reset password button is clicked
            $('body').on('click', '.reset-password-btn', function () {
                const userId = $(this).data('id');
                const userEmail = $(this).data('email');
                const userName = $(this).data('name');
                const newPassword = generateRandomPassword();

                $('#resetUserId').val(userId);
                $('#resetUserEmail').val(userEmail);
                $('#generatedPassword').val(newPassword);

                $('#resetPasswordModal').modal('show');
            });
            $('#filter').click(function () {
                table.draw();
            });
            $('#filter-form').on('keypress', function(e) {
                if (e.which === 13) { // 13 is the Enter key code
                    e.preventDefault(); // Prevent form submission
                    table.draw(); // Trigger filtering only
                    return false;
                }
            });
            $('#staff_name').keyup(function() {
    table.draw();
});
            $('#country').select2({
                placeholder: $('#country').data('placeholder'), // Retrieve the placeholder text from the data attribute

            });
            $('#tag').select2({
                placeholder: $('#tag').data('placeholder'), // Retrieve the placeholder text from the data attribute

            });
            
            //send mail modal form open
            $('body').on('click', '.send-mail', function () {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#name').html(name);
                var url = '{{ route("admin.user.mail-send", ":id") }}';
                url = url.replace(':id', id);
                $('#send-mail-form').attr('action', url);
                $('#sendEmail').modal('toggle')

            })

        })(jQuery);
    </script>
@endsection
