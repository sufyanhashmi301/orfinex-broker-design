@extends('backend.setting.platform.index')
@section('title')
    {{ __('All Risk Book') }}
@endsection
@section('platform-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>

    <div class="innerMenu card p-6 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
            <li class="nav-item">
                <a href="javascript:;" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 active">
                    {{ __('Meta Trader 5') }}
                </a>
            </li>
        </ul>
    </div>
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Risk Book') }}</th>
                                    <th scope="col" class="table-th">{{ __('Groups') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach ($riskBooks as $riskBook)
                                    <tr>
                                        <td class="table-td">{{ $riskBook->name }}</td>
                                        <td class="table-td">
                                            <ul class="flex flex-wrap items-center gap-3">
                                                @foreach ($riskBook->groups as $group)
                                                    <li class="badge bg-secondary uppercase">
                                                        {{ $group->group }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="table-td">
                                            <a href="javascript:;" data-id="{{ $riskBook->id }}" class="action-btn editRiskBook">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="table-td">{{ __('Un-Assigned') }}</td>
                                    <td class="table-td">
                                        <ul class="flex flex-wrap items-center gap-3">
                                            @foreach ($unAssignedGroups as $unAssignedGroup)
                                                <li class="badge bg-secondary uppercase">
                                                    {{ $unAssignedGroup->group }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="table-td"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Edit Assign Risk Book -->
    @include('backend.platform_group.modal.__edit')

@endsection
@section('platform-script')
    <script>

        $(document).on('click', '.editRiskBook', function() {
            var riskBookId = $(this).data('id');

            var url = '{{ route('admin.riskBook.show', ':id') }}'.replace(':id', riskBookId);

            $.ajax({
                url: url,
                method: 'GET',
                success: function(data) {
                    $('#id').val(data.risk_book.id);
                    $('#riskBookId').val(data.risk_book.name);
                    $('#groupSelect').empty();

                    data.all_groups.forEach(function(group) {
                        const selected = data.selected_group_ids.includes(group.id) ? 'selected' : '';
                        $('#groupSelect').append(`<option value="${group.id}" ${selected}>${group.group}</option>`);
                    });

                    $('#updateRiskBookForm').attr('action', '{{ route('admin.riskBook.update', ':id') }}'.replace(':id', riskBookId));
                    $('#editRiskBookModal').modal('show');
                },
                error: function(err) {
                    console.error('Error fetching risk book data', err);
                }
            });
        });

    </script>
@endsection
