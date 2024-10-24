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
                <span class="col-span-8 hidden"></span>
                <span class="col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
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
                                        <td>{{ $update->forexAccount->balance }}</td>
                                        <td>{{ $update->forexAccount->equity }}</td>
                                        <td>{{ $update->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <div class="badge bg-warning text-warning bg-opacity-30 capitalize">Pending</div>
                                        </td>
                                        <td>
                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                <form method="POST" action="{{ route('admin.pending-leverage') }}">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $update->id }}">
                                                    <input type="hidden" name="action" value="approve">
                                                    <button type="submit" class="btn btn-sm btn-light inline-flex items-center">
                                                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                                        <span>{{ __('Approve') }}</span>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.pending-leverage') }}">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $update->id }}">
                                                    <input type="hidden" name="action" value="reject">
                                                    <button type="submit" class="btn btn-sm btn-danger inline-flex items-center">
                                                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="mdi:close"></iconify-icon>
                                                        <span>{{ __('Reject') }}</span>
                                                    </button>
                                                </form>
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
@endsection

@section('script')
    <script>
        $('#dataTable').DataTable({
            dom: "<'min-w-full't><'flex justify-between items-center border-t border-slate-100 dark:border-slate-700 px-4 py-5'lip>",
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
