@extends('backend.setting.company.index')

@section('title')
    {{ __('Branches') }}
@endsection

@can('branch-create')
    @section('title-btns')
        <a href="javascript:;" class="btn btn-sm btn-primary inline-flex items-center justify-center" type="button"
            data-bs-toggle="modal" data-bs-target="#branchModal">
            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
            {{ __('Add New') }}
        </a>
    @endsection
@endcan

@section('company-content')
    <!-- Filters Section -->
    <div class="card p-6 mb-5">
        <form id="filter-form" method="POST" action="{{ route('admin.branches.export') }}">
            @csrf
            <div class="flex flex-col sm:flex-row justify-between flex-wrap sm:items-center gap-3">
                <div class="flex-1 w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    <div class="input-area relative">
                        <input type="text" name="global_search" id="global_search" class="form-control h-full"
                            placeholder="Search Branch Name, Code..." value="{{ request('global_search') }}">
                    </div>
                    <div class="input-area relative">
                        <select name="status" id="status" class="form-control h-full w-full">
                            <option value="">{{ __('All Status') }}</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ __('Active') }}
                            </option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>{{ __('Disabled') }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="flex sm:space-x-3 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                    <div class="input-area relative">
                        <button type="button" id="filter"
                            class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                            <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light"
                                icon="lucide:filter"></iconify-icon>
                            {{ __('Filter') }}
                        </button>
                    </div>
                    @can('branch-export')
                        <div class="input-area relative">
                            <button type="submit"
                                class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                                <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light"
                                    icon="lets-icons:export-fill"></iconify-icon>
                                {{ __('Export') }}
                            </button>
                        </div>
                    @endcan
                </div>
            </div>
        </form>
    </div>

    <!-- Main Content -->
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Branch Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Code') }}</th>
                                    <th scope="col" class="table-th">{{ __('Users') }}</th>
                                    <th scope="col" class="table-th">{{ __('Staff') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="branches-tbody"
                                class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach ($branches as $branch)
                                    <tr>
                                        <td class="table-td">
                                            <strong>{{ $branch->name }}</strong>
                                        </td>
                                        <td class="table-td">
                                            <span class="badge badge-secondary uppercase">{{ $branch->code }}</span>
                                        </td>
                                        <td class="table-td">
                                            <span class="text-slate-600">{{ $branch->users_count ?? 0 }}</span>
                                        </td>
                                        <td class="table-td">
                                            <span class="text-slate-600">{{ $branch->admins_count ?? 0 }}</span>
                                        </td>
                                        <td class="table-td">
                                            @if ($branch->status)
                                                <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
                                                    {{ __('Active') }}</div>
                                            @else
                                                <div class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">
                                                    {{ __('Disabled') }}</div>
                                            @endif
                                        </td>
                                        <td class="table-td">
                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                @can('branch-edit')
                                                    <button type="button" class="action-btn edit-branch"
                                                        data-id="{{ $branch->id }}">
                                                        <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                                    </button>
                                                @endcan
                                                @can('branch-delete')
                                                    <button type="button" data-id="{{ $branch->id }}"
                                                        data-name="{{ $branch->name }}" class="action-btn deleteBranch">
                                                        <iconify-icon icon="lucide:trash-2"></iconify-icon>
                                                    </button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div id="pagination-container"
                            class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto">
                            <div>
                                @php
                                    $from = $branches->firstItem();
                                    $to = $branches->lastItem();
                                    $total = $branches->total();
                                @endphp
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium">{{ $from }}</span> to <span
                                        class="font-medium">{{ $to }}</span> of <span
                                        class="font-medium">{{ $total }}</span> results
                                </p>
                            </div>
                            {{ $branches->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Create Branch -->
    @include('backend.branch.modal.__create_branch')

    <!-- Modal for Edit Branch -->
    @include('backend.branch.modal.__edit_branch')

    <!-- Modal for Delete Branch -->
    @include('backend.branch.modal.__delete_branch')
@endsection

@section('organization-script')
    <script>
        $(document).ready(function() {
            // Debounce function
            let filterTimeout;

            function debouncedFilter() {
                clearTimeout(filterTimeout);
                filterTimeout = setTimeout(function() {
                    loadBranches();
                }, 500);
            }

            // AJAX function to load Branches
            function loadBranches() {
                const globalSearch = $('#global_search').val();
                const status = $('#status').val();

                // Show loading indicator
                $('#branches-tbody').html(
                    '<tr><td colspan="6" class="text-center py-4"><iconify-icon class="spining-icon text-2xl" icon="lucide:loader"></iconify-icon> Loading...</td></tr>'
                );

                const data = {
                    global_search: globalSearch,
                    status: status,
                    ajax: true
                };

                $.ajax({
                    url: '{{ route('admin.branches.index') }}',
                    method: 'GET',
                    data: data,
                    success: function(response) {
                        // Extract table body and pagination from the full view response
                        const tempDiv = $('<div>').html(response.html);
                        const tableBody = tempDiv.find('#branches-tbody').html();
                        const pagination = tempDiv.find('#pagination-container').html();

                        $('#branches-tbody').html(tableBody);
                        $('#pagination-container').html(pagination);

                        // Update export form with current filters
                        updateExportForm();
                    },
                    error: function() {
                        $('#branches-tbody').html(
                            '<tr><td colspan="6" class="text-center py-4 text-red-500">Error loading data</td></tr>'
                        );
                    }
                });
            }

            // Function to update export form with current filters
            function updateExportForm() {
                const globalSearch = $('#global_search').val();
                const status = $('#status').val();

                // Remove existing hidden inputs
                $('input[type="hidden"][name="global_search"], input[type="hidden"][name="status"]').remove();

                // Add current filter values as hidden inputs
                if (globalSearch) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'global_search',
                        value: globalSearch
                    }).appendTo('#filter-form');
                }
                if (status) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'status',
                        value: status
                    }).appendTo('#filter-form');
                }
            }

            // Event listeners for filters
            $('#global_search').on('keyup', function() {
                debouncedFilter();
            });

            $('#status').on('change', function() {
                debouncedFilter();
            });

            $('#filter').on('click', function() {
                loadBranches();
            });

            // Initial export form update
            updateExportForm();

            // Edit Branch
            $('body').on('click', '.edit-branch', function(event) {
                event.preventDefault();
                $('#edit-branch-body').empty();
                var id = $(this).data('id');

                var url = '{{ route('admin.branches.edit', ':id') }}';
                url = url.replace(':id', id);

                $.get(url, function(data) {
                    $('#edit-branch-body').append(data);
                    $('#editBranchModal').modal('show');
                }).fail(function() {
                    alert('Error loading the edit form.');
                });
            });

            // Delete Branch
            $(document).on('click', '.deleteBranch', function(event) {
                event.preventDefault();
                const id = $(this).data('id');
                const name = $(this).data('name');
                const deleteUrl = '{{ route('admin.branches.destroy', ':id') }}'.replace(':id', id);

                // Reset modal state
                resetDeleteModal(name, deleteUrl);

                // Show modal
                $('#deleteBranch').modal('show');

                // Check for attached users/staff
                checkAttachedUsage(deleteUrl);
            });

            $('#branchDeleteForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const deleteUrl = form.attr('action');

                // Show loading state on button
                const submitBtn = form.find('#confirm-delete-btn');
                submitBtn.prop('disabled', true).html(`
            <iconify-icon class="spining-icon text-xl ltr:mr-2 rtl:ml-2" icon="svg-spinners:180-ring"></iconify-icon>
            Deleting...
        `);

                // Submit deletion request
                $.ajax({
                    url: deleteUrl,
                    method: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Hide modal
                            $('#deleteBranch').modal('hide');

                            // Show success notification using tNotify
                            tNotify('success', response.message);

                            // Reload the page after a short delay
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            // Handle error case
                            submitBtn.prop('disabled', false).html(`
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                        Confirm
                    `);
                            tNotify('error', response.message || 'Error deleting Branch');
                        }
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).html(`
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                    Confirm
                `);

                        let errorMessage = 'Error deleting Branch';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        tNotify('error', errorMessage);
                    }
                });
            });

            // Helper function to reset modal state
            function resetDeleteModal(name = '', deleteUrl = '') {
                $('#branchDeleteForm').attr('action', deleteUrl);
                $('.branch-name').text(name);
                $('#attached-usage').addClass('hidden');
                $('#no-usage').addClass('hidden');
                $('.usage-info').html('');
                $('#confirm-delete-btn').prop('disabled', false).html(`
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
            Confirm
        `);
            }

            // Helper function to check attached usage
            function checkAttachedUsage(deleteUrl) {
                // Show loading state
                $('#attached-usage').removeClass('hidden');
                $('.usage-info').html(`
            <div class="flex items-center justify-center py-4">
                <iconify-icon icon="svg-spinners:180-ring" class="text-lg mr-2"></iconify-icon>
                Checking for attached users and staff...
            </div>
        `);

                // Check for attached usage
                $.ajax({
                    url: deleteUrl,
                    method: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}',
                        check_usage: true
                    },
                    success: function(response) {
                        if (response.total_count > 0) {
                            // Show usage info
                            let usageInfo = `
                        <div class="bg-red-50 border border-red-200 rounded p-3">
                            <p class="text-red-800 font-medium mb-2">This branch cannot be deleted because:</p>
                            <ul class="text-red-700 text-sm space-y-1">
                    `;

                            if (response.user_count > 0) {
                                usageInfo +=
                                    `<li>• ${response.user_count} user(s) are assigned to this branch</li>`;
                            }
                            if (response.admin_count > 0) {
                                usageInfo +=
                                    `<li>• ${response.admin_count} staff member(s) have access to this branch</li>`;
                            }

                            usageInfo += `
                            </ul>
                            <p class="text-red-700 text-sm mt-2">Please reassign users and staff before deleting this branch.</p>
                        </div>
                    `;

                            $('.usage-info').html(usageInfo);
                            $('#attached-usage').removeClass('hidden');
                            $('#no-usage').addClass('hidden');
                            $('#confirm-delete-btn').prop('disabled', true)
                                .addClass('opacity-50 cursor-not-allowed');
                        } else {
                            // No usage found
                            $('.usage-info').html('');
                            $('#attached-usage').addClass('hidden');
                            $('#no-usage').removeClass('hidden');
                            $('#confirm-delete-btn').prop('disabled', false)
                                .removeClass('opacity-50 cursor-not-allowed');
                        }
                    },
                    error: function(xhr) {
                        $('.usage-info').html(`
                    <div class="text-red-500 py-4">
                        Error checking for attached usage
                    </div>
                `);
                        $('#confirm-delete-btn').prop('disabled', true);
                    }
                });
            }

        });
    </script>
@endsection
