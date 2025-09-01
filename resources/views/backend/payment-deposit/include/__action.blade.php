<div class="flex space-x-3 rtl:space-x-reverse">
    <!-- View Details Button -->
    <button type="button" class="action-btn detail-btn" data-toggle="modal" data-target="#detailModal"
        data-request-id="{{ $request->id }}">
        <iconify-icon icon="lucide:eye"></iconify-icon>
    </button>

    @if ($request->status === 'pending')
        <!-- Approve Button -->
        <button type="button" class="toolTip onTop action-btn approve-btn" data-tippy-theme="dark"
            data-tippy-content="Approve" title="Approve">
            <iconify-icon icon="lucide:check"></iconify-icon>
        </button>

        <!-- Reject Button -->
        <button type="button" class="toolTip onTop action-btn reject-btn" data-tippy-theme="dark"
            data-tippy-content="Reject" title="Reject">
            <iconify-icon icon="lucide:x"></iconify-icon>
        </button>
    @elseif($request->status === 'approved')
        <!-- Update Bank Details Button -->
        <button type="button" class="toolTip onTop action-btn update-bank-btn" data-tippy-theme="dark"
            data-tippy-content="Update Bank Details" data-request-id="{{ $request->id }}" title="Update Bank Details">
            <iconify-icon icon="lucide:edit"></iconify-icon>
        </button>

        <!-- Reject Approved Request Button -->
        <button type="button" class="toolTip onTop action-btn reject-approved-btn" data-tippy-theme="dark"
            data-tippy-content="Reject Request" data-request-id="{{ $request->id }}" title="Reject Request">
            <iconify-icon icon="lucide:x"></iconify-icon>
        </button>
    @elseif($request->status === 'rejected')
        <!-- Reset Status Button -->
        <button type="button" class="toolTip onTop action-btn reset-status-btn" data-tippy-theme="dark"
            data-tippy-content="Reset to Pending" data-request-id="{{ $request->id }}" title="Reset to Pending">
            <iconify-icon icon="lucide:refresh-cw"></iconify-icon>
        </button>

        <!-- Re-approve Button -->
        <button type="button" class="toolTip onTop action-btn re-approve-btn" data-tippy-theme="dark"
            data-tippy-content="Re-approve Request" data-request-id="{{ $request->id }}" title="Re-approve Request">
            <iconify-icon icon="lucide:check-circle"></iconify-icon>
        </button>
    @endif
</div>
