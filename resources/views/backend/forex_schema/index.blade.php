@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Schema') }}
@endsection
@section('filters')
    <form id="filter-form">
        <div class="flex flex-col sm:flex-row justify-between flex-wrap sm:items-center gap-3">
            <div class="flex-1 w-full flex flex-col sm:flex-row sm:gap-3 gap-2">
                <div class="flex-[2] input-area relative">
                    <input type="text" name="q" id="q" class="form-control h-full filter-input"
                        placeholder="Search by Title, Trader Type, Badge">
                </div>
                <div class="flex-1 input-area relative">
                    <input type="text" name="leverage" id="leverage" class="form-control h-full filter-input"
                        placeholder="Search by Leverage">
                </div>
                 <div class="flex-1 input-area relative">
                    <select name="branch_id" id="branch_id" class="form-control h-full filter-select">
                        <option value="">{{ __('Select Branch') }}</option>
                     
                        <option value="any">{{ __('Assigned Branches') }}</option>
                        <option value="none">{{ __('Unassigned Branches') }}</option>
                        <option disabled>──────────</option>
                        @isset($branches)
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <div class="flex-1 input-area relative">
                    <select name="status" id="status" class="form-control h-full filter-select">
                        <option value="">{{ __('Filter by Status') }}</option>
                        <option value="1">{{ __('Active') }}</option>
                        <option value="0">{{ __('Deactivated') }}</option>
                    </select>
                </div>
               
            </div>

            <div class="flex sm:space-x-3 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                <div class="input-area relative">
                    <button type="button" id="filter-button"
                        class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                        {{ __('Filter') }}
                    </button>
                </div>
                <div class="input-area relative">
                    <button type="button" id="clear-filters"
                        class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:x"></iconify-icon>
                        {{ __('Clear') }}
                    </button>
                </div>
                @can('account-type-export')
                    <div class="input-area relative">
                        <button type="button" id="export-button"
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
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4
            class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('All Account Type') }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            @can('account-type-create')
                <a href="{{ route('admin.accountType.create') }}"
                    class="btn btn-sm btn-primary inline-flex items-center justify-center">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                    {{ __('Add New') }}
                </a>
            @endcan
        </div>
    </div>

    @include('backend.forex_schema.include.__menu')

    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper" style="min-height: calc(100vh - 385px);">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Icon') }}</th>
                                    <th scope="col" class="table-th">{{ __('Trader Type') }}</th>
                                    <th scope="col" class="table-th">{{ __('Priority') }}</th>
                                    <th scope="col" class="table-th">{{ __('Title') }}</th>
                                    <th scope="col" class="table-th">{{ __('Leverage') }}</th>
                                    <th scope="col" class="table-th">{{ __('Branches') }}</th>
                                    <th scope="col" class="table-th">{{ __('Account Category') }}</th>
                                    <th scope="col" class="table-th">{{ __('Badge') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach ($schemas as $schema)
                                    <tr>
                                        <td class="table-td">
                                            <img class="h-7" src="{{ asset($schema->icon) }}" alt="" />
                                        </td>
                                        <td class="table-td">
                                            {{ $schema->trader_type }}
                                        </td>
                                        <td class="table-td">
                                            {{ $schema->priority }}
                                        </td>
                                        <td class="table-td">
                                            {{ $schema->title }}
                                        </td>
                                        <td class="table-td">
                                            {{ $schema->leverage }}
                                        </td>
                                        <td class="table-td">
                                            @if ($schema->branches && $schema->branches->count() > 0)
                                                @foreach ($schema->branches as $branch)
                                                    <span
                                                        class="badge badge-secondary mr-1 mb-1">{{ $branch->name }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-slate-500">{{ __('N/A') }}</span>
                                            @endif
                                        </td>
                                        <td class="table-td">
                                            @php
                                                $countries = $schema->country
                                                    ? json_decode($schema->country, true)
                                                    : [];
                                                $tags = $schema->tags ? json_decode($schema->tags, true) : [];
                                                $categorySlug = optional($schema->accountCategories)->slug;
                                            @endphp

                                            @if ($categorySlug === 'country_and_tags' && !empty($countries))
                                                <div>
                                                    <span class="font-medium">{{ __('Countries: ') }}</span>
                                                    {{ implode(', ', $countries) }}
                                                </div>
                                            @endif

                                            @if ($categorySlug === 'country_and_tags' && !empty($tags))
                                                <div>
                                                    <span class="font-medium">{{ __('Tags: ') }}</span>
                                                    {{ implode(', ', $tags) }}
                                                </div>
                                            @endif

                                            @if ($categorySlug === 'global_account')
                                                <div>
                                                    <span
                                                        class="font-medium">{{ $schema->is_global ? __('Universal Global') : __('Global') }}</span>
                                                </div>
                                            @endif

                                            @if ($categorySlug === 'ib_rebate_rules' && $schema->rebateRules->isNotEmpty())
                                                <div>
                                                    <span class="font-medium">{{ __('IB Rebate Rules: ') }}</span>
                                                    {{ implode(', ', $schema->rebateRules->pluck('title')->toArray()) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="table-td">
                                            <div @class([
                                                'badge bg-opacity-30 capitalize', // common classes
                                                'bg-success text-success' => $schema->badge,
                                                'bg-warning text-warning' => !$schema->badge,
                                            ])>
                                                {{ $schema->badge ? $schema->badge : 'No Feature Badge' }}</div>
                                        </td>
                                        <td class="table-td">
                                            <div @class([
                                                'badge bg-opacity-30 capitalize', // common classes
                                                'bg-success text-success' => $schema->status,
                                                'bg-danger text-danger' => !$schema->status,
                                            ])>
                                                {{ $schema->status ? 'Active' : 'Deactivated' }}</div>
                                        </td>
                                        <td class="table-td">
                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                @can('account-type-edit')
                                                    <a href="{{ route('admin.multi-level.view', $schema->id) }}"
                                                        class="action-btn">
                                                        <iconify-icon icon="lucide:eye"></iconify-icon>
                                                    </a>
                                                @endcan
                                                @can('account-type-edit')
                                                    <a href="{{ route('admin.accountType.edit', $schema->id) }}"
                                                        class="action-btn">
                                                        <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                                    </a>
                                                @endcan
                                                @can('account-type-delete')
                                                    <a href="#" class="action-btn delete-schema-btn"
                                                        data-id="{{ $schema->id }}">
                                                        <iconify-icon icon="lucide:trash"></iconify-icon>
                                                    </a>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div
                            class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto">
                            <div>
                                @php
                                    $from = $schemas->firstItem(); // The starting item number on the current page
                                    $to = $schemas->lastItem(); // The ending item number on the current page
                                    $total = $schemas->total(); // The total number of items
                                @endphp

                                <p class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-medium">{{ $from }}</span>
                                    to
                                    <span class="font-medium">{{ $to }}</span>
                                    of
                                    <span class="font-medium">{{ $total }}</span>
                                    results
                                </p>
                            </div>
                            {{ $schemas->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="processingIndicator" class="text-center" style="display: none;">
        <iconify-icon class="spining-icon text-5xl dark:text-slate-100" icon="lucide:loader"></iconify-icon>
    </div>
    <!-- Delete Confirmation Modal -->
    @include('backend.forex_schema.include.__delete')
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            let deleteSchemaId = null;
            const urlParams = new URLSearchParams(window.location.search);
            const query = urlParams.get('q');
            const branchId = urlParams.get('branch_id');

            // Initialize filters from URL
            if (query) {
                $('#q').val(query);
                $('#filters_div').removeClass('hidden'); // kept if present in layout
            }
            if (branchId) {
                $('#branch_id').val(branchId);
            }

            // Debounce function
            function debounce(func, wait, immediate) {
                let timeout;
                return function() {
                    const context = this,
                        args = arguments;
                    const later = function() {
                        timeout = null;
                        if (!immediate) func.apply(context, args);
                    };
                    const callNow = immediate && !timeout;
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                    if (callNow) func.apply(context, args);
                };
            }
            // REFINED fetchRecords function
            function fetchRecords(resetPage = true) {
                const formData = $('#filter-form').serialize();
                let url = '{{ route('admin.accountType.index') }}';

                if (resetPage) {
                    url += '?page=1&' + formData;
                } else {
                    // This part is not in your original code, but is good practice for pagination clicks
                    const currentPage = new URLSearchParams(window.location.search).get('page') || 1;
                    url += '?page=' + currentPage + '&' + formData;
                }

                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    beforeSend: function() {
                        $('#processingIndicator').show();
                    },
                    complete: function() {
                        $('#processingIndicator').hide();
                    },
                    success: function(response) {
                        // Note: Your controller returns JSON {html: '...'}, so access response.html
                        const $responseHtml = $(response.html);
                        const tableContent = $responseHtml.find('tbody').html();
                        // Assuming your pagination links are inside the main rendered view
                        const paginationContent = $responseHtml.find(
                            '.flex.flex-wrap.justify-between.items-center.border-t').last().html();

                        $('tbody').html(tableContent);
                        // Replace the entire pagination footer for simplicity and correctness
                        $('.flex.flex-wrap.justify-between.items-center.border-t').last().html(
                            paginationContent);

                        // Update URL
                        updateUrlWithFilters();
                    },
                    error: function(xhr) {
                        console.error('An error occurred during filtering.');
                    }
                });
            }


            // Update URL with current filters
            function updateUrlWithFilters() {
                const formData = $('#filter-form').serialize();
                const newUrl = '{{ route('admin.accountType.index') }}?' + formData;
                window.history.pushState({
                    path: newUrl
                }, '', newUrl);
            }

            // Event listeners for filters
            $('.filter-input').on('keyup', debounce(function() {
                fetchRecords(true); // Reset to page 1 when filtering
            }, 500));

            $('.filter-select').on('change', function() {
                fetchRecords(true); // Reset to page 1 when filtering
            });

            $('#filter-button').on('click', function() {
                fetchRecords(true); // Reset to page 1 when filtering
            });

            $('#clear-filters').on('click', function() {
                // Reset all filter inputs
                $('#filter-form')[0].reset();

                // Force a fresh load of page 1 with no filters
                loadFreshPage();
            });

            function loadFreshPage() {
                const freshUrl = '{{ route('admin.accountType.index') }}';

                $.ajax({
                    url: freshUrl,
                    type: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    beforeSend: function() {
                        $('#processingIndicator').show();
                    },
                    complete: function() {
                        $('#processingIndicator').hide();
                    },
                    success: function(response) {
                        // Note: Your controller returns JSON {html: '...'}, so access response.html
                        const $responseHtml = $(response.html);
                        const tableContent = $responseHtml.find('tbody').html();
                        const paginationContent = $responseHtml.find(
                            '.flex.flex-wrap.justify-between.items-center.border-t').last().html();

                        $('tbody').html(tableContent);
                        $('.flex.flex-wrap.justify-between.items-center.border-t').last().html(
                            paginationContent);

                        // Reset URL completely
                        window.history.pushState({
                            path: freshUrl
                        }, '', freshUrl);
                    },
                    error: function(xhr) {
                        console.error('An error occurred while clearing filters.');
                    }
                });
            }

            // Pagination links - don't reset page
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                const pageUrl = $(this).attr('href');
                $.ajax({
                    url: pageUrl,
                    type: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    beforeSend: function() {
                        $('#processingIndicator').show();
                    },
                    complete: function() {
                        $('#processingIndicator').hide();
                    },
                    success: function(response) {
                        const $response = $(response.html);
                        const tableContent = $response.find('tbody').html();
                        const pagination = $response.find('.pagination-container').html();

                        $('tbody').html(tableContent);
                        $('.pagination-container').html(pagination);
                        updateUrlWithFilters();
                    },
                    error: function(xhr) {
                        console.error('An error occurred during pagination.');
                    }
                });
            });
            // Update export link with current filters
            // Add this to your $(document).ready function
            $('#export-button').on('click', function() {
                // Get all filter values
                const formData = $('#filter-form').serialize();

                // Create a temporary form
                const form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route('admin.accountType.export') }}';

                // Add all filter parameters as hidden inputs
                const params = new URLSearchParams(formData);
                for (const [key, value] of params) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    form.appendChild(input);
                }

                // Submit the form
                document.body.appendChild(form);
                form.submit();
            });

            // Delete functionality
            $('.delete-schema-btn').on('click', function(e) {
                e.preventDefault();
                deleteSchemaId = $(this).data('id');
                $('#deleteConfirmationModal').modal('show');
            });

            $('#confirmDeleteButton').on('click', function() {
                const input = $('#deleteConfirmationInput').val();
                if (input.toLowerCase() === 'delete') {
                    const form = $('<form>', {
                        'method': 'POST',
                        'action': '{{ route('admin.accountType.delete', ':id') }}'.replace(':id',
                            deleteSchemaId)
                    });
                    const csrfToken = $('meta[name="csrf-token"]').attr('content');
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_token',
                        'value': csrfToken
                    }));
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_method',
                        'value': 'DELETE'
                    }));
                    $('body').append(form);
                    form.submit();
                } else {
                    alert('You must type "delete" to confirm.');
                }
            });
        });
        $(document).ready(function() {
            $('.filter-toggle-btn').click(function() {
                const $content = $('#filters_div');

                if ($content.hasClass('hidden')) {
                    $content.removeClass('hidden').slideDown();
                } else {
                    $content.slideUp(function() {
                        $content.addClass('hidden');
                    });
                }
            });
        });
    </script>
@endsection
