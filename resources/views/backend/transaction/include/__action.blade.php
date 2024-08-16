@can('transaction-action')
  <span data-bs-toggle="tooltip" title="" data-bs-placement="top" data-bs-original-title="View Transaction">
  <a href="{{ route('admin.transactions.view',$id) }}" data-id="{{ $id }}" class="action-btn viewTransaction">
    <iconify-icon icon="lucide:eye"></iconify-icon>
    </a>

  </span>
@endcan