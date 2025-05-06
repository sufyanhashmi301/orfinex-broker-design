<div class="flex space-x-3 rtl:space-x-reverse">
    <button type="button"
        class="action-btn grace-status-btn"
        data-id="{{ $id }}"
        data-in_grace="{{ $in_grace_period ? '1' : '0' }}"
        data-bs-toggle="modal"
        data-bs-target="#gracePeriodModal"
        data-tippy-theme="dark"
        data-tippy-content="Grace Period Status">
        <iconify-icon icon="lucide:edit-3"></iconify-icon>
    </button>
</div>
