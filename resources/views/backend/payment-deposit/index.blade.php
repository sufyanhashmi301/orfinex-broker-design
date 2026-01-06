@extends('backend.layouts.app')
@section('title')
    {{ __('Custom Payment Account Forms') }}
@endsection

@section('filters')
    <form id="filter-form" method="GET">
        <div class="flex flex-col sm:flex-row justify-between flex-wrap sm:items-center gap-3">
            <div class="flex-1 w-full flex flex-col sm:flex-row sm:gap-3 gap-2">
                <div class="flex-1 input-area relative">
                    <input type="text" name="global_search" id="global_search" class="form-control h-full"
                        placeholder="Search by Form Name">
                </div>
                <div class="flex-1 input-area relative">
                    <select id="status-filter" name="status_filter" class="form-control h-full">
                        <option value="">{{ __('All Status') }}</option>
                        <option value="1">{{ __('Active') }}</option>
                        <option value="0">{{ __('Inactive') }}</option>
                    </select>
                </div>
                <div class="flex-1 input-area relative">
                    <input type="text" name="created_at" id="created-at"
                        class="form-control flatpickr-created-at h-full w-full" placeholder="Created At Range" readonly>
                </div>
            </div>
            <div class="flex sm:space-x-3 space-x-2 sm:justify-end items-center">
                <div class="input-area relative">
                    <button type="button" id="filter"
                        class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                        {{ __('Filter') }}
                    </button>
                </div>
                <div class="input-area relative">
                    <a href="{{ route('admin.payment-deposit-form.create') }}"
                        class="btn btn-sm btn-primary inline-flex items-center justify-center min-w-max">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:plus"></iconify-icon>
                        {{ __('Add New') }}
                    </a>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4
            class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Custom Payment Account Forms') }}
        </h4>
    </div>

    @include('backend.payment-deposit.include.__menu')

    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Form Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Created At') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @forelse($questions as $question)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700">
                                        <td class="table-td">
                                            <div class="flex items-center">
                                                <div class="flex-none">
                                                    <div
                                                        class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                                                        <iconify-icon icon="lucide:file-text"
                                                            class="text-slate-500 dark:text-slate-400"></iconify-icon>
                                                    </div>
                                                </div>
                                                <div class="ltr:ml-3 rtl:mr-3">
                                                    <span
                                                        class="text-slate-600 dark:text-slate-300 text-sm font-medium">{{ $question->name }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="table-td">
                                            @if ($question->status)
                                                <span class="badge bg-success-500 text-white">{{ __('Active') }}</span>
                                            @else
                                                <span class="badge bg-danger-500 text-white">{{ __('Inactive') }}</span>
                                            @endif
                                        </td>

                                        <td class="table-td">
                                            <span
                                                class="text-slate-500 dark:text-slate-400">{{ toSiteTimezone($question->created_at, 'M d, Y') }}</span>
                                            <br>
                                            <span
                                                class="text-xs text-slate-400">{{ toSiteTimezone($question->created_at, 'h:i A') }}</span>
                                        </td>
                                        <td class="table-td">
                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                <a href="{{ route('admin.payment-deposit-form.edit', $question->id) }}"
                                                    class="action-btn toolTip onTop" data-tippy-theme="dark"
                                                    data-tippy-content="Edit" title="Edit">
                                                    <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                                </a>
                                                <form
                                                    action="{{ route('admin.payment-deposit-form.destroy', $question->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn text-danger-500 toolTip onTop"
                                                        data-tippy-theme="dark" data-tippy-content="Delete" title="Delete"
                                                        onclick="return confirm('Are you sure you want to delete this form?')">
                                                        <iconify-icon icon="lucide:trash-2"></iconify-icon>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-12">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center mb-4">
                                                    <iconify-icon icon="lucide:file-plus" class="text-3xl text-slate-400"></iconify-icon>
                                                </div>
                                                <h4 class="text-2xl text-slate-500 dark:text-slate-400 font-medium mb-2">
                                                    {{ __('No Forms Found') }}
                                                </h4>
                                                <p class="text-slate-400 text-sm mb-4">
                                                    {{ __('Create your first payment deposit form to get started.') }}</p>
                                                <a href="{{ route('admin.payment-deposit-form.create') }}"
                                                    class="btn btn-primary inline-flex items-center justify-center">
                                                    <iconify-icon class="text-base ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                                                    {{ __('Create Form') }}
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- Pagination -->
            <div
                class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto">
                {{ $questions->links() }}
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        (function($) {
            "use strict";

            flatpickr(".flatpickr-created-at", {
                mode: "range",
                dateFormat: "Y-m-d",
                allowInput: true
            });

            $(document).ready(function() {
                // Hide processing indicator on page load
                $('#processingIndicator').hide();

                // Filter toggle functionality
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

                // Filter functionality
                $('#filter').click(function() {
                    filterTable();
                });

                $('#global_search, #status-filter').on('change keyup', function() {
                    filterTable();
                });

                function filterTable() {
                    const searchText = $('#global_search').val().toLowerCase();
                    const statusFilter = $('#status-filter').val();

                    $('tbody tr').each(function() {
                        const row = $(this);
                        const name = row.find('td:first').text().toLowerCase();
                        const status = row.find('td:nth-child(2)').text().toLowerCase();

                        let showRow = true;

                        // Text search
                        if (searchText && !name.includes(searchText)) {
                            showRow = false;
                        }

                        // Status filter
                        if (statusFilter !== '' &&
                            ((statusFilter === '1' && !status.includes('active')) ||
                                (statusFilter === '0' && !status.includes('inactive')))) {
                            showRow = false;
                        }

                        if (showRow) {
                            row.show();
                        } else {
                            row.hide();
                        }
                    });
                }

                // Initialize tooltips if available
                if (typeof tippy !== 'undefined') {
                    tippy('.toolTip', {
                        theme: 'dark',
                        placement: 'top'
                    });
                }
            });
        })(jQuery);
    </script>
@endsection
