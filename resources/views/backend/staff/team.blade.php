@extends('backend.layouts.app')
@section('title')
    {{ __('Team Management') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Team Management for') }}: {{ $staff->name }}
        </h4>
    </div>
    <div class="grid grid-cols-12 gap-5">
        @can('staff-team-create')
        <div class="lg:col-span-5 col-span-12">
            <div class="card">
                <div class="card-header noborder">
                    <h4 class="card-title">{{ __('Attach Staff') }}</h4>
                </div>
                <div class="card-body p-6 pt-3">
                    <form action="{{ route('admin.team.attach', $staff->id) }}" method="post">
                        @csrf
                        <div class="space-y-5">
                            <div class="input-area">
                                <label class="form-label">{{ __('Select Staff:') }}</label>
                                <select name="staff_ids[]" id="staff_input" class="select2 form-control w-full" data-placeholder="Select Staff" multiple>
                                    @foreach($availableStaff as $staffMember)
                                        <option value="{{ $staffMember->id }}">
                                            {{ $staffMember->name }} ({{ $staffMember->roles->first()->name ?? 'No Role' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="action-btns text-right mt-10">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
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
                <div class="card-body relative px-6">
                    <div class="mb-4 flex justify-between items-center">
                        <div>
                            <input type="checkbox" id="select_all_staff" class="mr-2">
                            <label for="select_all_staff">{{ __('Select All') }}</label>
                        </div>
                        @can('staff-team-delete')
                        <button class="btn btn-danger inline-flex items-center justify-center" id="bulkDetachBtn" type="button" disabled>
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:trash-2"></iconify-icon>
                            {{ __('Detach Selected') }}
                        </button>
                        @endcan
                    </div>
                    <div class="overflow-x-auto -mx-6 dashcode-data-table">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700" id="attachedStaff">
                                    <thead class="bg-slate-200 dark:bg-slate-700">
                                        <tr>
                                            <th scope="col" class="w-10">
                                                <input type="checkbox" id="select_all_table">
                                            </th>
                                            <th scope="col" class="table-th">{{ __('Staff') }}</th>
                                            <th scope="col" class="table-th">{{ __('Email') }}</th>
                                            <th scope="col" class="table-th">{{ __('Role') }}</th>
                                            <th scope="col" class="table-th">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                        @foreach($staff->teamMembers as $member)
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" class="staff-checkbox" value="{{ $member->id }}">
                                            </td>
                                            <td class="table-td">{{ $member->name }}</td>
                                            <td class="table-td">{{ $member->email }}</td>
                                            <td class="table-td">
                                                @foreach($member->roles as $role)
                                                    <span class="badge bg-primary-500 text-white">{{ $role->name }}</span>
                                                @endforeach
                                            </td>
                                            <td class="table-td">
                                                @can('staff-team-delete')
                                        
                                                 <button class="staffDetachBtn" data-staff-id="{{ $staff->id }}" data-member-id="{{ $member->id }}">
                                                     <iconify-icon icon="lucide:trash-2" class="text-danger-500"></iconify-icon>
                                                 </button>
                                                @endcan
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
        </div>
    </div>

    {{-- Single Detach Modal --}}
    @can('staff-team-delete')
    @include('backend.staff.modal.__detach_staff')
    @include('backend.staff.modal.__bulk_detach_staff')
    @endcan
@endsection

@section('script')
<script>
    (function ($) {
        "use strict";

        // Initialize select2 for staff selection
        $('#staff_input').select2({
            placeholder: "Select Staff Members",
            allowClear: true
        });

        // Handle select all checkboxes
        $('#select_all_table, #select_all_staff').on('click', function() {
            var isChecked = $(this).prop('checked');
            $('.staff-checkbox').prop('checked', isChecked);
            updateBulkDetachButton();
        });

        // Handle individual checkbox changes
        $(document).on('change', '.staff-checkbox', function() {
            var allChecked = $('.staff-checkbox:checked').length === $('.staff-checkbox').length;
            $('#select_all_staff').prop('checked', allChecked);
            $('#select_all_table').prop('checked', allChecked);
            updateBulkDetachButton();
        });

        // Update bulk detach button state
        function updateBulkDetachButton() {
            var hasChecked = $('.staff-checkbox:checked').length > 0;
            $('#bulkDetachBtn').prop('disabled', !hasChecked);
        }

        // Bulk detach button click
        $('#bulkDetachBtn').on('click', function() {
            var selectedIds = $('.staff-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedIds.length === 0) {
                notify().error('Please select at least one staff member to detach');
                return;
            }

            $('#bulkStaffIdsInput').val(selectedIds.join(','));
            $('#bulkDetachModal').modal('show');
        });

     // Single detach button click
$('body').on('click', '.staffDetachBtn', function(e) {
    e.preventDefault();
    let staffId = $(this).data('staff-id');
    let memberId = $(this).data('member-id');
    
    $('#detachStaffForm').attr('action', '{{ route("admin.team.detach", ["staff" => ":staffId", "member" => ":memberId"]) }}'
        .replace(':staffId', staffId)
        .replace(':memberId', memberId)
    );
    $('#detachStaffModal').modal('show');
});

// Bulk detach
$('#bulkDetachBtn').on('click', function() {
    var selectedIds = $('.staff-checkbox:checked').map(function() {
        return $(this).val();
    }).get();

    if (selectedIds.length === 0) {
        notify().error('Please select at least one staff member to detach');
        return;
    }

    $('#bulkStaffIdsInput').val(selectedIds.join(','));
    $('#bulkDetachModal').modal('show');
});

// Form submission
$('#bulkDetachForm').on('submit', function(e) {
    e.preventDefault();
    var form = $(this);
    form.off('submit').submit();
});

$('#detachStaffForm').on('submit', function(e) {
    e.preventDefault();
    var form = $(this);
    form.off('submit').submit();
});


    })(jQuery);
</script>
@endsection