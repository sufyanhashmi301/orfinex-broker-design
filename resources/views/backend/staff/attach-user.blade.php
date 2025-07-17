@extends('backend.layouts.app')
@section('title')
    {{ __('Attach User') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Attach Users') }}
        </h4>
    </div>
    <div class="grid grid-cols-12 gap-5">
        @can('staff-attach-users-create')
        <div class="lg:col-span-5 col-span-12">
            <div class="card">
                <div class="card-header noborder">
                    <h4 class="card-title">{{ __('Attach User') }}</h4>
                </div>
                <div class="card-body p-6 pt-3">
                    <form action="{{ route('admin.staff.attachUser', $staff->id) }}" method="post">
                        @csrf
                        <div class="space-y-5">
                            <div class="input-area">
                                <label class="form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Select an IB Group to link selected users with">
                                        {{ __('IB Groups') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <select name="ib_groups[]" class="select2 form-control w-full" data-placeholder="Select Options" multiple>
                                    <option value="all" @if(in_array('all', $staff->ib_groups ?? [])) selected @endif>
                                        {{ __('All') }}
                                    </option>
                                    @foreach($ibGroups as $ibGroup)
                                        <option value="{{ $ibGroup->id }}"
                                            {{ in_array($ibGroup->id, $staff->ib_groups ?? []) ? 'selected' : '' }}>
                                            {{ $ibGroup->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-area">
                                <label class="form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Select the account type to assign to the users">
                                        {{ __('Account Types') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <select name="account_types[]" class="select2 form-control w-full" data-placeholder="Select Options" multiple>
                                    <option value="all" @if(in_array('all', $staff->account_types ?? [])) selected @endif>
                                        {{ __('All') }}
                                    </option>
                                    @foreach($schemas as $schema)
                                        <option value="{{ $schema->id }}"
                                            {{ in_array($schema->id, $staff->account_types ?? []) ? 'selected' : '' }}>
                                            {{ $schema->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-area">
                                <label class="form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Choose one or more users to attach">
                                        {{ __('Attach Users') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <select name="user_ids[]" id="users_input" class="form-control w-full" data-placeholder="Select Options" multiple></select>
                            </div>
                        </div>
                        <div class="action-btns text-right mt-10">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center" id="update-staff__btn">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endcan
        <div class="lg:col-span-7 col-span-12">
            <div class="card h-full">
                <div class="card-header noborder">
                    <h4 class="card-title">{{ __('Attached Users') }}</h4>
                    @can('staff-attach-users-delete')
                    <div class="flex items-stretch gap-3">
                        <div class="flex items-center justify-center border rounded px-3">
                            <label class="flex items-center text-sm font-medium mb-0">
                                <input type="checkbox" id="select_all_users" class="mr-2">
                                {{ __('All Users') }}
                            </label>
                        </div>
                        <button class="btn btn-danger btn-sm inline-flex items-center justify-center" id="bulkDetachBtn" type="button" disabled>
                            <iconify-icon class="text-base ltr:mr-2 rtl:ml-2" icon="lucide:trash-2"></iconify-icon>
                            {{ __('Detach Selected') }}
                        </button>
                    </div>
                    @endcan
                </div>
                <div class="card-body relative px-6">
                    <div class="overflow-x-auto -mx-6 dashcode-data-table">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700" id="attachedUsers">
                                    <thead class="bg-slate-200 dark:bg-slate-700">
                                        <tr>
                                            <th scope="col" class="w-10">
                                                <input type="checkbox" id="select_all_table">
                                            </th>
                                            <th scope="col" class="table-th">{{ __('User') }}</th>
                                            <th scope="col" class="table-th">{{ __('Email') }}</th>
                                            <th scope="col" class="table-th">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="processingIndicator" class="text-center">
                        <iconify-icon class="spining-icon text-5xl dark:text-slate-100" icon="lucide:loader"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Single Detach User Modal --}}
    @can('staff-attach-users-delete')
        @include('backend.staff.modal.__detach_user')
        @include('backend.staff.modal.__selected_detach_users')
    @endcan
@endsection

@section('script')
<script>
    (function ($) {
        "use strict";

        var table = $('#attachedUsers')
        .on('processing.dt', function (e, settings, processing) {
            $('#processingIndicator').css('display', processing ? 'block' : 'none');
        }).DataTable({
            dom: "<'grid grid-cols-12 gap-5 px-6 mt-6'<'col-span-4'l><'col-span-8 flex justify-end'f><'#pagination.flex items-center'>><'min-w-full't><'flex justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'ip>",
            paging: true,
            ordering: true,
            info: true,
            searching: true,
            lengthChange: true,
            lengthMenu: [10, 25, 50, 100],
            language: {
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    previous: "<iconify-icon icon=\"ic:round-keyboard-arrow-left\"></iconify-icon>",
                    next: "<iconify-icon icon=\"ic:round-keyboard-arrow-right\"></iconify-icon>"
                },
                search: "Search:"
            },
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: "{{ route('admin.staff.attachedUsers', $staff->id) }}",
                data: function (d) {
                    d.global_search = $('#global_search').val();
                }
            },
            columns: [
                { 
                    data: 'id', 
                    render: function(data, type, row) {
                        return '<input type="checkbox" class="user-checkbox" value="'+data+'">';
                    }, 
                    orderable: false, 
                    searchable: false,
                    className: 'text-center'
                },
                { data: 'full_name', name: 'full_name' },
                { data: 'email', name: 'email' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });

        // Handle select all checkboxes
        $('#select_all_table').on('click', function() {
            var isChecked = $(this).prop('checked');
            $('.user-checkbox').prop('checked', isChecked);
            $('#select_all_users').prop('checked', isChecked);
            updateBulkDetachButton();
        });

        $('#select_all_users').on('click', function() {
            var isChecked = $(this).prop('checked');
            $('.user-checkbox').prop('checked', isChecked);
            $('#select_all_table').prop('checked', isChecked);
            updateBulkDetachButton();
        });

        // Handle individual checkbox changes
        $(document).on('change', '.user-checkbox', function() {
            var allChecked = $('.user-checkbox:checked').length === $('.user-checkbox').length;
            $('#select_all_users').prop('checked', allChecked);
            $('#select_all_table').prop('checked', allChecked);
            updateBulkDetachButton();
        });

        // Update bulk detach button state based on selected checkboxes
        function updateBulkDetachButton() {
            var hasChecked = $('.user-checkbox:checked').length > 0;
            $('#bulkDetachBtn').prop('disabled', !hasChecked);
        }

        // Bulk detach button click handler
        $('#bulkDetachBtn').on('click', function() {
            var selectedIds = $('.user-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedIds.length === 0) {
                notify().error('Please select at least one user to detach');
                return;
            }

            $('#bulkUserIdsInput').val(selectedIds.join(','));
            $('#bulkDetachModal').modal('show');
        });

        // Bulk detach form submission
        // Bulk detach form submission
        $('#bulkDetachForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);

            // Submit the form normally (not via AJAX)
            form.off('submit').submit();
        });

        $('#users_input').select2({
            ajax: {
                url: '{{ route("admin.user.search") }}',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data.results.map(function(item) {
                            return {
                                id: item.id,
                                text: item.text + ' (' + item.email + ')',
                                email: item.email
                            };
                        })
                    };
                },
                cache: true
            },
            templateResult: function(data) {
                return $('<span>' + data.text + '</span>');
            },
            templateSelection: function(data) {
                return data.text;
            }
        });

        // Fix single detach button click handler
        $('body').on('click', '.userDetachBtn', function(e) {
            e.preventDefault();
            let userId = $(this).data('user-id');
            let staffId = $(this).data('staff-id');
            var name = $(this).data('name');

            $('#detachUserForm').attr('action', '{{ route("admin.staff.detachUser", $staff->id) }}');
            $('#userIdInput').val(userId);
            $('.name').html(name);
            $('#detachUserModal').modal('show');
        });

    })(jQuery);
</script>
@endsection