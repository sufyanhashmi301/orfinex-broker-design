<div class="flex space-x-3 rtl:space-x-reverse">
    @can('deposit-voucher-edit')
        <a href="{{ route('admin.deposit-vouchers.edit', $id) }}" data-id="{{ $id }}" class="action-btn editVoucher">
            <iconify-icon icon="lucide:edit-3"></iconify-icon>
        </a>
    @endcan

    @can('deposit-voucher-delete')
        @if($status !== 'used')
            <a href="{{ route('admin.deposit-vouchers.destroy', $id) }}" class="action-btn deleteVoucher" data-id="{{ $id }}" type="button">
                <iconify-icon icon="heroicons:trash"></iconify-icon>
            </a>
        @endif
    @endcan
</div>