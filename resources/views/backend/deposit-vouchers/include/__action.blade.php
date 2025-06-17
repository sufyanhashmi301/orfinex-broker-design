@can('deposit-voucher-edit')
    <button type="button" class="btn btn-primary btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-url="{{ route('admin.deposit-vouchers.edit', $id) }}">
        <i class="fas fa-edit"></i>
    </button>
@endcan

@can('deposit-voucher-delete')
    @if($status !== 'used')
        <a href="{{ route('admin.deposit-vouchers.destroy', $id) }}" class="btn btn-danger btn-sm delete-btn">
            <i class="fas fa-trash"></i>
        </a>
    @endif
@endcan 