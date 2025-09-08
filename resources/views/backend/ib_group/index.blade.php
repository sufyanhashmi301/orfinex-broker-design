@extends('backend.setting.customer.index')

@section('title')
    {{ __('IB Groups') }}
@endsection
@can('ib-group-create')
@section('title-btns')
    <a href="javascript:;" class="btn btn-sm btn-primary inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#ibGroupModal">
        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
        {{ __('Add New') }}
    </a>
@endsection
@endcan

@section('customer-content')
    <!-- Filters Section -->
    <div class="card p-6 mb-5">
        <form id="filter-form" method="POST" action="{{ route('admin.ib-group.export')}}">
            @csrf
            @if(request('filter_group'))
                <input type="hidden" name="filter_group" value="{{ request('filter_group') }}">
            @endif
            <div class="flex flex-col sm:flex-row justify-between flex-wrap sm:items-center gap-3">
                <div class="flex-1 w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    <div class="input-area relative">
                        <input type="text" name="global_search" id="global_search" class="form-control h-full" placeholder="Search Group Name, Rebate Rule, Account Type..." value="{{ request('global_search') }}">
                    </div>
                    <div class="input-area relative">
                        <select name="status" id="status" class="form-control h-full w-full">
                            <option value="">{{ __('All Status') }}</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ __('Active') }}</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>{{ __('Disabled') }}</option>
                        </select>
                    </div>
                    <div class="input-area relative">
                        <select name="global_account" id="global_account" class="form-control h-full w-full">
                            <option value="">{{ __('All Global Account Types') }}</option>
                            <option value="1" {{ request('global_account') == '1' ? 'selected' : '' }}>{{ __('Active') }}</option>
                            <option value="0" {{ request('global_account') == '0' ? 'selected' : '' }}>{{ __('Disabled') }}</option>
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
                    @can('ib-group-export')
                    <div class="input-area relative">
                        <button type="submit" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                            <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lets-icons:export-fill"></iconify-icon>
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
                                <th scope="col" class="table-th">{{ __('Group Name') }}</th>
                                <th scope="col" class="table-th">{{ __('Rebate Rules') }}</th>
                                <th scope="col" class="table-th">{{ __('Account Types') }}</th>
                                <th scope="col" class="table-th">{{ __('Global Account Type') }}</th>
                                <th scope="col" class="table-th">{{ __('Status') }}</th>
                                <th scope="col" class="table-th">{{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody id="ib-groups-tbody" class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                            @foreach($ibGroups as $ibGroup)
                                <tr>
                                    <td class="table-td">
                                        <strong>{{ $ibGroup->name }}</strong>
                                    </td>
                                    <td class="table-td">
                                        @if($ibGroup->rebateRules->isNotEmpty())
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($ibGroup->rebateRules as $rule)
                                                    <a href="{{ route('admin.rebate-rules.index') }}?filter_rule={{ $rule->id }}&global_search={{ urlencode($rule->title) }}" 
                                                       class="badge badge-secondary uppercase text-primary hover:text-primary-dark cursor-pointer">
                                                        {{ $rule->title }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-slate-400">{{ __('N/A') }}</span>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        @php
                                            $accountTypes = collect();
                                            foreach($ibGroup->rebateRules as $rule) {
                                                $accountTypes = $accountTypes->merge($rule->forexSchemas);
                                            }
                                            $accountTypes = $accountTypes->unique('id');
                                        @endphp
                                        @if($accountTypes->isNotEmpty())
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($accountTypes as $schema)
                                                                <a href="{{ route('admin.accountType.index') }}?filter_schema={{ $schema['id'] }}&title={{ urlencode($schema['title']) }}" 
                                                       class="badge badge-secondary uppercase text-primary hover:text-primary-dark cursor-pointer">
                                                        {{ $schema->title }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-slate-400">{{ __('N/A') }}</span>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        @if($ibGroup->is_global_account)
                                            <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">{{ __('Active') }}</div>
                                        @else
                                            <div class="badge bg-warning-500 text-warning-500 bg-opacity-30 capitalize">{{ __('Disabled') }}</div>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        @if($ibGroup->status)
                                            <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">{{ __('Active') }}</div>
                                        @else
                                            <div class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">{{ __('Disabled') }}</div>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <button type="button" class="action-btn edit-ib-group" data-id="{{ $ibGroup->id }}">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </button>
                                            <button type="button" data-id="{{ $ibGroup->id }}" data-name="{{ $ibGroup->name }}" class="action-btn deleteIbGroup">
                                                <iconify-icon icon="lucide:trash-2"></iconify-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div id="pagination-container" class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto">
                            <div>
                                @php
                                    $from = $ibGroups->firstItem();
                                    $to = $ibGroups->lastItem();
                                    $total = $ibGroups->total();
                                @endphp
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium">{{ $from }}</span> to <span class="font-medium">{{ $to }}</span> of <span class="font-medium">{{ $total }}</span> results
                                </p>
                            </div>
                            {{ $ibGroups->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Create IB Group -->
    @include('backend.ib_group.modal.__create_group')

    <!-- Modal for Edit IB Group -->
    @include('backend.ib_group.modal.__edit_group')

    <!-- Modal for Delete IB Group -->
    @include('backend.ib_group.modal.__delete_group')

@endsection

@section('user-management-script')
<script>
$(document).ready(function() {
     if (!window.location.search.includes('filter_group')) {
        $('#global_search').val('');
        $('#status').val('');
        $('#global_account').val('');
        $('input[name="filter_group"]').remove();
    }
    // Debounce function
    let filterTimeout;
    function debouncedFilter() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(function() {
            loadIBGroups();
        }, 500);
    }

    // AJAX function to load IB Groups
    function loadIBGroups() {
        const globalSearch = $('#global_search').val();
        const status = $('#status').val();
        const globalAccount = $('#global_account').val();
        const filterGroup = $('input[name="filter_group"]').val();

        // Show loading indicator
        $('#ib-groups-tbody').html('<tr><td colspan="6" class="text-center py-4"><iconify-icon class="spining-icon text-2xl" icon="lucide:loader"></iconify-icon> Loading...</td></tr>');

        const data = {
            global_search: globalSearch,
            status: status,
            global_account: globalAccount,
            ajax: true
        };

        // Add filter_group if present
        if (filterGroup) {
            data.filter_group = filterGroup;
        }

        $.ajax({
            url: '{{ route("admin.ib-group.index") }}',
            method: 'GET',
            data: data,
            success: function(response) {
                // Extract table body and pagination from the full view response
                const tempDiv = $('<div>').html(response.html);
                const tableBody = tempDiv.find('#ib-groups-tbody').html();
                const pagination = tempDiv.find('#pagination-container').html();
                
                $('#ib-groups-tbody').html(tableBody);
                $('#pagination-container').html(pagination);
                
                // Update export form with current filters
                updateExportForm();
            },
            error: function() {
                $('#ib-groups-tbody').html('<tr><td colspan="6" class="text-center py-4 text-red-500">Error loading data</td></tr>');
            }
        });
    }

    // Function to update export form with current filters
    function updateExportForm() {
        const globalSearch = $('#global_search').val();
        const status = $('#status').val();
        const globalAccount = $('#global_account').val();
        const filterGroup = $('input[name="filter_group"]').val();

        // Only remove hidden inputs, not the visible ones!
        $('input[type="hidden"][name="global_search"], input[type="hidden"][name="status"], input[type="hidden"][name="global_account"]').remove();

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
        if (globalAccount) {
            $('<input>').attr({
                type: 'hidden',
                name: 'global_account',
                value: globalAccount
            }).appendTo('#filter-form');
        }
        if (filterGroup) {
            // Update existing filter_group input or create new one
            if ($('input[name="filter_group"]').length) {
                $('input[name="filter_group"]').val(filterGroup);
            } else {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'filter_group',
                    value: filterGroup
                }).appendTo('#filter-form');
            }
        }
    }

    // Remove filter_group if any other filter is used
    function clearFilterGroupIfOtherFilters() {
        const globalSearch = $('#global_search').val();
        const status = $('#status').val();
        const globalAccount = $('#global_account').val();
        if (globalSearch || status || globalAccount) {
            $('input[name="filter_group"]').remove();
        }
    }

    // Event listeners for filters
    $('#global_search').on('keyup', function() {
        if (!$(this).val()) {
            // If global_search is empty, remove filter_group and reload all
            $('input[name="filter_group"]').remove();
            debouncedFilter();
        } else {
            clearFilterGroupIfOtherFilters();
            debouncedFilter();
        }
    });

    $('#status, #global_account').on('change', function() {
        clearFilterGroupIfOtherFilters();
        debouncedFilter();
    });

    $('#filter').on('click', function() {
        clearFilterGroupIfOtherFilters();
        loadIBGroups();
    });

    // Pre-populate filters from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    $('#global_search').val(urlParams.get('global_search') || '');
    $('#status').val(urlParams.get('status') || '');
    $('#global_account').val(urlParams.get('global_account') || '');
    
    // Initial export form update
    updateExportForm();

    // Edit IB Group (existing functionality)
    $('body').on('click', '.edit-ib-group', function (event) {
        event.preventDefault();
        $('#edit-group-body').empty();
        var id = $(this).data('id'); // Get the ID from the clicked button

        var url = '{{ route("admin.ib-group.edit", ":id") }}'; // Use the correct route name
        url = url.replace(':id', id);

        $.get(url, function (data) {
            $('#edit-group-body').append(data);
            $('#editIbGroupModal').modal('show'); // Correct modal ID
            $('.summernote').summernote({
                height: 150,
                minHeight: null,
                maxHeight: null,
                focus: true,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                callbacks: {
                    onChange: function(contents, $editable) {
                        var markupStr = contents;
                        markupStr = markupStr.replace(/</g, '{').replace(/>/g, '}');
                        var html_container = $(this).closest('.input-area').find('input[type="hidden"]');
                        html_container.val(markupStr);
                    }
                }
            });
            $('#rebate_rule_id_edit').select2();
            tippy(".shift-Away", {
                placement: "top",
                animation: "shift-away"
            });
        }).fail(function () {
            alert('Error loading the edit form.');
        });
    });
$(document).on('click', '.deleteIbGroup', function(event) {
    event.preventDefault();
    const id = $(this).data('id');
    const name = $(this).data('name');
    const deleteUrl = '{{ route("admin.ib-group.destroy", ":id") }}'.replace(':id', id);

    // Reset modal state
    resetDeleteModal(name, deleteUrl);
    
    // Show modal
    $('#deleteIbGroup').modal('show');
    
    // Check for attached users
    checkAttachedUsers(deleteUrl);
});

$('#ibGroupDeleteForm').on('submit', function(e) {
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
                $('#deleteIbGroup').modal('hide');
                
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
                tNotify('error', response.message || 'Error deleting IB Group');
            }
        },
        error: function(xhr) {
            submitBtn.prop('disabled', false).html(`
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                Confirm
            `);

            let errorMessage = 'Error deleting IB Group';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            tNotify('error', errorMessage);
        }
    });
});
// Helper function to reset modal state
function resetDeleteModal(name = '', deleteUrl = '') {
    $('#ibGroupDeleteForm').attr('action', deleteUrl);
    $('.name').text(name);
    $('#attached-users').addClass('hidden');
    $('#no-users').addClass('hidden');
    $('.users-list').html('');
    $('#confirm-delete-btn').prop('disabled', false).html(`
        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
        Confirm
    `);
}

// Helper function to check attached users
function checkAttachedUsers(deleteUrl) {
    // Show loading state
    $('#attached-users').removeClass('hidden');
    $('.users-list').html(`
        <div class="flex items-center justify-center py-4">
            <iconify-icon icon="svg-spinners:180-ring" class="text-lg mr-2"></iconify-icon>
            Checking for attached users...
        </div>
    `);

    // Check for attached users
    $.ajax({
        url: deleteUrl,
        method: 'POST',
        data: {
            _method: 'DELETE',
            _token: '{{ csrf_token() }}',
            check_users: true
        },
        success: function(response) {
            if (response.user_count > 0) {
                // Show users list
                let userList = '';
                response.users.forEach(user => {
                    userList += `
                        <li class="flex items-center justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                            <span>${user.first_name} ${user.last_name} (${user.username})</span>
                            <span class="text-slate-400 text-sm">${user.email}</span>
                        </li>
                    `;
                });
                $('.users-list').html(userList);
                $('#attached-users').removeClass('hidden');
                $('#no-users').addClass('hidden');
                $('#confirm-delete-btn').prop('disabled', true)
                    .addClass('opacity-50 cursor-not-allowed');
            } else {
                // No users attached
                $('.users-list').html('');
                $('#attached-users').addClass('hidden');
                $('#no-users').removeClass('hidden');
                $('#confirm-delete-btn').prop('disabled', false)
                    .removeClass('opacity-50 cursor-not-allowed');
            }
        },
        error: function(xhr) {
            $('.users-list').html(`
                <div class="text-red-500 py-4">
                    Error checking for attached users
                </div>
            `);
            $('#confirm-delete-btn').prop('disabled', true);
        }
    });
}
});
</script>
@endsection