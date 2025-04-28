@can('staff-attach-users-delete')
<button class="action-btn userDetachBtn" data-user-id="{{ $user->id }}" data-staff-id="{{ $staff->id }}" data-name="{{ $user->full_name }}" type="button">
    <iconify-icon icon="heroicons:trash"></iconify-icon>
</button>
@endcan