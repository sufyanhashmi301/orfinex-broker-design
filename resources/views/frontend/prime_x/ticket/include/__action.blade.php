<div class="flex space-x-3 rtl:space-x-reverse">
    @if($status == 'open')
        <a href="{{ route('user.ticket.close.now', $uuid) }}" class="action-btn cancel"
           data-bs-toggle="tooltip" title="{{ __('Complete Ticket') }}"
           data-bs-original-title="{{ __('Complete Ticket') }}">
            <iconify-icon icon="heroicons:check-16-solid"></iconify-icon>
        </a>
        <a href="{{ route('user.ticket.show', $uuid) }}" class="action-btn loaderBtn"
           data-bs-toggle="tooltip" title="{{ __('Show Ticket') }}"
           data-bs-original-title="{{ __('Show Ticket') }}">
            <iconify-icon icon="heroicons:eye"></iconify-icon>
        </a>
    @elseif($status == 'closed')
        <a href="#" class="action-btn cancel disabled">
            <iconify-icon icon="heroicons:check-16-solid"></iconify-icon>
        </a>
        <a href="{{ route('user.ticket.show', $uuid) }}" class="action-btn loaderBtn"
           data-bs-toggle="tooltip" data-bs-placement="top"
           title="{{ __('Re-open the Ticket') }}">
            <iconify-icon icon="heroicons:book-open"></iconify-icon>
        </a>
    @endif
</div>
