@can('support-ticket-action')
    <a href="{{ route('admin.ticket.show',$uuid) }}" class="hover:underline">
        {{ $uuid }}
    </a>
@endcan
