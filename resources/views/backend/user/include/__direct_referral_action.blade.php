<div class="flex space-x-3 rtl:space-x-reverse">
    @canany(['customer-basic-manage','customer-balance-add-or-subtract','customer-change-password','all-type-status'])
        <a href="{{route('admin.user.edit',$id)}}" class="action-btn" data-bs-toggle="tooltip"
        title="Edit User" data-bs-original-title="Edit User">
            <iconify-icon icon="lucide:edit-3"></iconify-icon>
        </a>
    @endcanany
    @can('user-direct-referral-remove')
        <span type="button"
            data-id="{{$id}}"
            data-name="{{ $first_name.' '. $last_name }}" class="delete-direct-referral"
            data-bs-toggle="modal" data-bs-target="#deleteReferralConfirmationModal"
        >
            <button class="action-btn" data-bs-toggle="tooltip" title="Delete Referral" data-bs-original-title="Delete">
                <iconify-icon icon="lucide:trash-2"></iconify-icon>
            </button>
        </span>
    @endcan
</div>
<script>
    $(document).ajaxComplete(function () {
        "use strict";
        $('[data-bs-toggle="tooltip"]').tooltip({
            "html": true,
        });
    });
</script>
