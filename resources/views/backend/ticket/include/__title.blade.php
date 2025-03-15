@can('support-ticket-action')
    <a href="{{ route('admin.ticket.show',$uuid) }}" class="font-semibold hover:underline">
        {{ $title }}
    </a>
@endcan
