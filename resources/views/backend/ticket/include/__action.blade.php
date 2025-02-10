<div class="flex space-x-3 rtl:space-x-reverse">
    @can('support-ticket-action')
        <a href="{{ route('admin.ticket.show',$uuid) }}" class="action-btn" data-bs-toggle="tooltip" title="" data-bs-original-title="Ticket Details">
           <iconify-icon icon="solar:chat-dots-linear"></iconify-icon>
        </a>
    @endcan
    <button type="button" id="assignTicket" data-id="{{ $id }}" class="action-btn">
        <iconify-icon icon="lucide:user-round-plus"></iconify-icon>
    </button>
</div>
