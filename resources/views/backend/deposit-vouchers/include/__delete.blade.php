<!-- Modal for Delete -->
<div class="modal fade" id="deleteVoucher" tabindex="-1" aria-labelledby="deleteVoucherLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteVoucherLabel">{{ __('Delete Deposit Voucher') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="voucherDeleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>{{ __('Are you sure you want to delete this voucher?') }}</p>
                    <p class="text-danger">{{ __('Title') }}: <span class="title"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                </div>
            </form>
        </div>
    </div>
</div> 