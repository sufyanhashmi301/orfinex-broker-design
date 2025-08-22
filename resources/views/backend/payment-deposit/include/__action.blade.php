<div class="flex space-x-3 rtl:space-x-reverse">
    <!-- View Details Button -->
    <button type="button" class="action-btn detail-btn" data-toggle="modal" data-target="#detailModal" data-request-id="{{ $request->id }}">
        <iconify-icon icon="lucide:eye"></iconify-icon>
    </button>

    @if($request->status === 'pending')
        <!-- Approve Button -->
        <button type="button" class="toolTip onTop action-btn approve-btn" 
            data-tippy-theme="dark" 
            data-tippy-content="Approve" 
            title="Approve">
            <iconify-icon icon="lucide:check"></iconify-icon>
        </button>

        <!-- Reject Button -->
        <button type="button" class="toolTip onTop action-btn reject-btn" 
            data-tippy-theme="dark" 
            data-tippy-content="Reject" 
            title="Reject">
            <iconify-icon icon="lucide:x"></iconify-icon>
        </button>
    @else
        <!-- Status Icon -->
        @if($request->status === 'approved')
            <span class="toolTip onTop action-btn" data-tippy-theme="dark" data-tippy-content="Approved">
                <iconify-icon icon="lucide:check-circle" class="text-success-500"></iconify-icon>
            </span>
        @elseif($request->status === 'rejected')
            <span class="toolTip onTop action-btn" data-tippy-theme="dark" data-tippy-content="Rejected">
                <iconify-icon icon="lucide:x-circle" class="text-danger-500"></iconify-icon>
            </span>
        @endif
    @endif
</div>
