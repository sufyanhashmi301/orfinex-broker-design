@extends('backend.symbol_groups.index')

@section('title')
    {{ __('All Symbols') }}
@endsection

@section('title-btns')
    <a href="{{route('admin.symbol-groups.index')}}"
       class="btn btn-sm btn-primary inline-flex items-center justify-center" type="button">
        {{ __('Symbol Groups') }}
    </a>
@endsection

@section('filters')
    <div class="flex flex-col sm:flex-row justify-between flex-wrap sm:items-center gap-3">
       
            <!-- Filter Form (GET request) -->
            <form id="filter-form" method="GET" action="{{ route('admin.symbols.index') }}" class="flex-1 w-full flex flex-col sm:flex-row sm:gap-3 gap-2">
                <div class="flex-1 input-area relative">
                    <input type="text" name="global_search" id="global_search" class="form-control h-full"
                           placeholder="Search by Symbol Name" value="{{ request('global_search') }}">
                </div>
                <div class="flex-1 input-area relative">
                    <input type="text" name="contact_size" id="contact_size" class="form-control h-full"
                           placeholder="Contact Size" value="{{ request('contact_size') }}">
                </div>
                <div class="flex-1 input-area relative">
                    <input type="text" name="path" id="path" class="form-control h-full" 
                           placeholder="Path" value="{{ request('path') }}">
                </div>
                
                <!-- Filter Button -->
                <div class="input-area relative">
                    <button type="submit" id="filter"
                            class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light"
                                    icon="lucide:filter"></iconify-icon>
                        {{ __('Filter') }}
                    </button>
                </div>
                @can('symbols-export')
                <div class="input-area relative">
                    <button id="export-button" 
                            class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" 
                                      icon="lets-icons:export-fill"></iconify-icon>
                        {{ __('Export') }}
                    </button>
                </div>
                @endcan
            </form>
        
        
        <!-- Export Form (POST request) - Placed at the end to maintain your layout -->
       
    </div>
@endsection

@section('symbol-groups-content')
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6 mt-4">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700"
                               id="symbolsTable">
                            <thead>
                            <tr>
                                <th scope="col" class="table-th">{{ __('Symbol ID') }}</th>
                                <th scope="col" class="table-th">{{ __('Symbol') }}</th>
                                <th scope="col" class="table-th">{{ __('Path') }}</th>
                                <th scope="col" class="table-th">{{ __('Description') }}</th>
                                <th scope="col" class="table-th">{{ __('Contract Size') }}</th>
                                <th scope="col" class="table-th">
                                    <div class="flex items-center">
                                        <span class="mr-2">{{ __('Enable') }}</span>
                                        <span class="toolTip onTop leading-none" data-tippy-content="Enable All">
                                            <button type="button" id="enableAll" class="action-btn">
                                                <iconify-icon icon="lucide:check"></iconify-icon>
                                            </button>
                                        </span>
                                    </div>
                                </th>
                            </tr>
                            </thead>
                            <tbody id="symbolTableBody">
                            @foreach ($mt5Symbols as $symbol)
                                <tr>
                                    <td class="table-td"><strong>{{ $symbol->Symbol_ID }}</strong></td>
                                    <td class="table-td">{{ $symbol->Symbol }}</td>
                                    <td class="table-td">{{ $symbol->Path }}</td>
                                    <td class="table-td">{{ $symbol->Description }}</td>
                                    <td class="table-td">{{ $symbol->ContractSize }}</td>
                                    <td class="table-td">
                                        <div class="form-switch">
                                            <label
                                                class="relative !inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-checkbox">
                                                <input
                                                    type="checkbox"
                                                    class="sr-only peer symbol-toggle"
                                                    data-id="{{ $symbol->Symbol_ID }}"
                                                    @if($symbol->status === 'Enabled') checked @endif>
                                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white
    after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all
    dark:border-gray-600 peer-checked:bg-black-500"></span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                        <div id="paginationLinks">
                            <div class="pagination-container mt-4 ml-4">
                                Showing <span id="paginationInfo">{{ $mt5Symbols->firstItem() }} to {{ $mt5Symbols->lastItem() }} of {{ $mt5Symbols->total() }}</span>
                                symbols

                                {{ $mt5Symbols->appends(request()->query())->links() }}
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
   <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
     id="disableSymbolModal"
     tabindex="-1"
     aria-labelledby="disableSymbolModal"
     aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body p-6 py-10 text-center">
                <div class="space-y-3">
                    <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-danger-500 text-danger-500 bg-opacity-30">
                        <iconify-icon class="text-4xl" icon="lucide:alert-triangle"></iconify-icon>
                    </div>
                    <div class="title">
                        <h4 class="text-2xl font-medium dark:text-white capitalize">
                            {{ __('Are you sure?') }}
                        </h4>
                    </div>
                    <p>
                        {{ __('You want to disable') }}
                        <strong class="symbol-name"></strong>
                    </p>
                    <div id="attached-groups" class="hidden">
                        <div class="text-left max-h-60 overflow-y-auto">
                            <h5 class="font-medium mb-2">{{ __('Attached Symbol Groups') }}:</h5>
                            <ul class="groups-list"></ul>
                        </div>
                        <div class="mt-4 text-red-500">
                            {{ __('Please detach these symbol groups first before disabling.') }}
                        </div>
                    </div>
                    <div id="no-groups" class="hidden">
                        <p class="text-green-500">{{ __('No symbol groups are attached.') }}</p>
                    </div>
                </div>
                <form method="POST" id="disableSymbolForm">
                    @csrf
                    <input type="hidden" name="symbol_id" id="symbol_id">
                    <input type="hidden" name="status" value="0">
                    <div class="action-btns mt-10">
                        <button type="submit" id="confirm-disable-btn" class="btn btn-dark inline-flex items-center justify-center mr-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                            {{ __('Confirm') }}
                        </button>
                        <button type="button" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
    <div class="card-body relative px-6 pt-3">
    <div id="processingIndicator" class="text-center" style="display: none;">
        <iconify-icon class="spining-icon text-5xl dark:text-slate-100" icon="lucide:loader"></iconify-icon>
    </div>
</div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
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

            $("#filter-form").submit(function (event) {
                event.preventDefault();
                fetchSymbols();
               
            });
            $('#export-button').click(function() {
        const btn = $(this);
        btn.prop('disabled', true);
        btn.html('<iconify-icon class="animate-spin" icon="lucide:loader"></iconify-icon> Exporting...');
        
        // Get current filter values
        const filters = {
            global_search: $('#global_search').val(),
            contact_size: $('#contact_size').val(),
            path: $('#path').val(),
            status: $('#status').val(),
            _token: '{{ csrf_token() }}'
        };
        
        // Submit via AJAX
        $.ajax({
            url: '{{ route("admin.symbols.export") }}',
            type: 'POST',
            data: filters,
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response) {
                // Create download link
                const url = window.URL.createObjectURL(response);
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', 'filtered_symbols.xlsx');
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                // Reset button
                btn.prop('disabled', false);
                btn.html('<iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lets-icons:export-fill"></iconify-icon> {{ __("Export") }}');
            },
            error: function(xhr) {
                console.error('Export failed:', xhr.responseText);
                tNotify('error', 'Export failed. Please try again.');
                btn.prop('disabled', false);
                btn.html('<iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lets-icons:export-fill"></iconify-icon> {{ __("Export") }}');
            }
        });
    });
      const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('global_search') || urlParams.has('contact_size') || urlParams.has('path') || urlParams.has('status')) {
        const $filtersDiv = $('#filters_div');
        if ($filtersDiv.hasClass('hidden')) {
            $filtersDiv.removeClass('hidden').show(); // Use .show() for instant visibility or .slideDown() for an animated effect
        }
    }
           function fetchSymbols(page = 1) {
    // Show the loading indicator
    $('#processingIndicator').show();

    var formData = $("#filter-form").serialize();
    $.ajax({
        url: "{{ route('admin.symbols.index') }}?page=" + page,
        type: "GET",
        data: formData,
        success: function(response) {
            $("#symbolTableBody").html(response.table);
            $("#paginationLinks").html(response.pagination);
        },
        error: function(xhr, status, error) {
            tNotify('warning', "Error fetching symbols.");
            console.error("Error:", error);
        },
        complete: function() {
            // Hide the loading indicator when the request is complete
            $('#processingIndicator').hide();
        }
    });
}
 $('#global_search, #contact_size, #path').on('keyup', function(e) {
                // Prevent form submission on 'Enter' keypress
                if (e.which === 13) {
                    e.preventDefault();
                    return false;
                }
                fetchSymbols();
            });
            // Handle pagination click event dynamically
            $('body').on('click', '.pagination a', function (event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1]; // Get the clicked page number
                fetchSymbols(page); // Load the filtered page via AJAX
            });


 // Handle Toggle Change for Single Symbol with confirmation
$('body').on('change', '.symbol-toggle', function () {
    var checkbox = $(this);
    var symbolId = $(this).data("id");
    var newState = $(this).is(":checked") ? 1 : 0;
    var previousState = !newState;

    // If enabling, proceed directly
    if (newState === 1) {
        updateSymbolStatus(symbolId, newState, checkbox);
        return;
    }

    // If disabling, show confirmation modal and prevent immediate toggle change
    checkbox.prop("checked", true); // Keep it checked (enable state) until confirmed
    showDisableConfirmation(symbolId, checkbox, previousState);
});

// Function to show disable confirmation modal
function showDisableConfirmation(symbolId, checkbox, previousState) {
    // Reset modal state
    resetDisableModal();
    
    // Store the previous state in the checkbox data
    checkbox.data('previous-state', previousState);
    
    // Show loading in modal
    $('#attached-groups').removeClass('hidden');
    $('.groups-list').html(`
        <div class="flex items-center justify-center py-4">
            <iconify-icon icon="svg-spinners:180-ring" class="text-lg mr-2"></iconify-icon>
            Checking for attached symbol groups...
        </div>
    `);
    
    // Find the symbol name from the table row
    var symbolName = $(checkbox).closest('tr').find('td:nth-child(2)').text().trim();
    $('.symbol-name').text(symbolName);
    $('#symbol_id').val(symbolId);
    
    // Show modal
    $('#disableSymbolModal').modal('show');
    
    // Check for attached symbol groups
    $.ajax({
        url: '{{ route("admin.symbols.check-groups", ":id") }}'.replace(':id', symbolId),
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success && response.group_count !== undefined) {
                if (response.group_count > 0) {
                    // Show groups list and disable confirm button
                    let groupList = '';
                    response.groups.forEach(group => {
                        groupList += `
                            <li class="flex items-center justify-between py-2 border-b border-slate-100 dark:border-slate-700">
                                <span>${group.title}</span>
                                <span class="text-slate-400 text-sm">ID: ${group.id}</span>
                            </li>`;
                    });
                    $('.groups-list').html(groupList);
                    $('#attached-groups').removeClass('hidden');
                    $('#no-groups').addClass('hidden');
                    $('#confirm-disable-btn').prop('disabled', true)
                        .addClass('opacity-50 cursor-not-allowed');
                } else {
                    // No groups attached - enable confirm button
                    $('.groups-list').html('');
                    $('#attached-groups').addClass('hidden');
                    $('#no-groups').removeClass('hidden');
                    $('#confirm-disable-btn').prop('disabled', false)
                        .removeClass('opacity-50 cursor-not-allowed');
                }
            }
        },
        error: function(xhr) {
            $('.groups-list').html(`
                <div class="text-red-500 py-4">
                    Error checking for attached symbol groups
                </div>
            `);
            console.error('Error checking symbol groups:', xhr.responseText);
        }
    });
}

// Function to reset modal state
function resetDisableModal() {
    $('.groups-list').html('');
    $('#attached-groups').addClass('hidden');
    $('#no-groups').addClass('hidden');
    $('#confirm-disable-btn').prop('disabled', false)
        .removeClass('opacity-50 cursor-not-allowed');
}

// Handle form submission for disabling
$('#disableSymbolForm').on('submit', function(e) {
    e.preventDefault();
    
    const form = $(this);
    const symbolId = $('#symbol_id').val();
    const submitBtn = form.find('#confirm-disable-btn');
    
    submitBtn.prop('disabled', true).html(`
        <iconify-icon class="spining-icon text-xl ltr:mr-2 rtl:ml-2" icon="svg-spinners:180-ring"></iconify-icon>
        Disabling...
    `);

    // Update symbol status
    updateSymbolStatus(symbolId, 0, null, function() {
        $('#disableSymbolModal').modal('hide');
        submitBtn.prop('disabled', false).html(`
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
            {{ __('Confirm') }}
        `);
    });
});

// Handle modal close/cancel event
$('#disableSymbolModal').on('hidden.bs.modal', function () {
    // Find the checkbox that triggered the modal and revert to previous state
    $('.symbol-toggle').each(function() {
        if ($(this).data('previous-state') !== undefined) {
            $(this).prop('checked', $(this).data('previous-state'));
            $(this).removeData('previous-state');
        }
    });
});

// Function to update symbol status
function updateSymbolStatus(symbolId, status, checkbox, callback = null) {
    $.ajax({
        url: "{{ route('admin.symbols.updateStatus') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            id: symbolId,
            status: status
        },
        success: function (response) {
            if (response.success) {
                tNotify('success', response.message);
                if (status === 0) {
                    // Refresh the table to show updated state
                    fetchSymbols();
                }
            } else {
                tNotify('warning', response.message);
                if (checkbox) {
                    checkbox.prop("checked", !status); // Revert toggle
                }
            }
            if (callback) callback();
        },
        error: function (xhr, status, error) {
            tNotify('warning', "Error updating symbol status.");
            console.error("Error:", error);
            if (checkbox) {
                checkbox.prop("checked", !status); // Revert toggle
            }
            if (callback) callback();
        }
    });
}
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

            // Handle Enable All Click
            $("#enableAll").click(function () {
                $.ajax({
                    url: "{{ route('admin.symbols.enableAll') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.success) {
                            tNotify('success', response.message);
                            fetchSymbols(); // Refresh table
                        } else {
                            tNotify('warning', response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        tNotify('warning', "Error enabling all symbols.");
                        console.error("Error:", error);
                    }
                });
            });
        });
    </script>
@endsection
