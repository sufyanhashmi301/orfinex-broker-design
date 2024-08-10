@can('support-ticket-action')
    <a href="{{ route('admin.ticket.show',$uuid) }}" class="action-btn" data-bs-toggle="tooltip"
       title="" data-bs-original-title="Ticket Details">
       <iconify-icon icon="lucide:eye"></iconify-icon>
    </a>
@endcan