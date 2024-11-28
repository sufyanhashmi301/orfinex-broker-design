@extends('backend.layouts.app')

@section('title', __('Pending Leverage Updates'))

@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            @yield('title')
        </h4>
    </div>

    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Account Number') }}</th>
                                    <th scope="col" class="table-th">{{ __('User') }}</th>
                                    <th scope="col" class="table-th">{{ __('Account Type') }}</th>
                                    <th scope="col" class="table-th">{{ __('Old Leverage') }}</th>
                                    <th scope="col" class="table-th">{{ __('New Leverage') }}</th>
                                    <th scope="col" class="table-th">{{ __('Currency') }}</th>
                                    <th scope="col" class="table-th">{{ __('Balance') }}</th>
                                    <th scope="col" class="table-th">{{ __('Equity') }}</th>
                                    <th scope="col" class="table-th">{{ __('Time') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach($leverageUpdates as $update)
                                    <tr>
                                        <td>{{ $update->forexAccount->login }}</td>
                                        <td>
                                            <a href="{{ route('admin.user.edit', $update->user->id) }}" class="flex">
                                                <span class="w-8 h-8 rounded-full bg-slate-100 text-slate-900 dark:bg-slate-600 dark:text-slate-200 flex items-center justify-center">
                                                    {{ strtoupper(substr($update->user->username ?? 'NA', 0, 1)) }}
                                                </span>
                                                <div class="ml-3">
                                                    <span class="text-sm text-slate-900 dark:text-white block capitalize">{{ $update->user->username ?? 'NA' }}</span>
                                                    <span class="text-xs text-slate-500 dark:text-slate-300">{{ $update->user->email ?? 'NA' }}</span>
                                                </div>
                                            </a>
                                        </td>
                                        <td><strong>{{ $update->forexAccount->account_type }}</strong></td>
                                        <td>{{ $update->last_leverage }}</td>
                                        <td>{{ $update->updated_leverage }}</td>
                                        <td>{{ $update->forexAccount->currency }}</td>
                                        <td><strong>{{ $update->forexAccount->balance }}</strong></td>
                                        <td><strong>{{ $update->forexAccount->equity }}</strong></td>
                                        <td>{{ $update->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <div class="badge badge-warning capitalize">Pending</div>
                                        </td>
                                        <td>
                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                <button type="button" data-id="{{ $update->id }}" data-action="approve" class="btn btn-sm btn-light inline-flex items-center leverageAction">
                                                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                                    <span>{{ __('Approve') }}</span>
                                                </button>
                                                <button type="button" data-id="{{ $update->id }}" data-action="reject" class="btn btn-sm btn-danger inline-flex items-center leverageAction">
                                                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="mdi:close"></iconify-icon>
                                                    <span>{{ __('Reject') }}</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('backend.investment.leverage.include.__leverage_confirm')
@endsection

@section('script')
    <script>
        $(document).on('click', '.leverageAction', function() {
            var actionType = $(this).data('action');
            var leverageId = $(this).data('id');

            // Set values in the modal form
            $('#action_type').val(actionType);
            $('#leverage_id').val(leverageId);
            $('#actionMessage').text(actionType === 'approve' ? 'approve' : 'reject');

            // Show modal
            $('#confirmLeverageAction').modal('show');
        });
        $('#leverageActionForm').on('submit', function(e) {
    e.preventDefault();
    var form = $(this);
    var url = '{{ route("admin.pending-leverage.action") }}';

    $.ajax({
        type: 'POST',
        url: url,
        data: form.serialize(),
        success: function(response) {
            console.log(response.message); // Debug log to see the full message
            $('#confirmLeverageAction').modal('hide');
            tNotify('success', response.message);  // Display the success message in notify
            location.reload(); // Redirect back to the pending leverage page
        },
        error: function(xhr) {
            $('#confirmLeverageAction').modal('hide');
            var errorMessage = xhr.responseJSON?.message || 'Action failed. Please try again.';
            tNotify('error', errorMessage);
        }
    });
});
    $('#dataTable').DataTable({
            dom: "<'min-w-full't><'flex justify-between items-center border-t border-slate-100 dark:border-slate-700 px-4 py-5 mt-auto'lip>",
            processing: true,
            searching: false,
            lengthChange: false,
            info: true,
            language: {
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    previous: "<iconify-icon icon='ic:round-keyboard-arrow-left'></iconify-icon>",
                    next: "<iconify-icon icon='ic:round-keyboard-arrow-right'></iconify-icon>"
                },
                search: "Search:",
                processing: '<iconify-icon icon="lucide:loader"></iconify-icon>'
            },
            serverSide: false,
            autoWidth: false,
        });
    </script>
@endsection
