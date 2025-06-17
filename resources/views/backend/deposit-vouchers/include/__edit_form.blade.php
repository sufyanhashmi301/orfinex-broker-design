<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">@lang('Edit Deposit Voucher')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" action="{{ route('admin.deposit-vouchers.update', $depositVoucher) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_title" class="form-label">@lang('Title')</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="edit_title" name="title" value="{{ $depositVoucher->title }}" required>
                                <div class="invalid-feedback" id="title_error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_amount" class="form-label">@lang('Amount')</label>
                                <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="edit_amount" name="amount" value="{{ $depositVoucher->amount }}" required>
                                <div class="invalid-feedback" id="amount_error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_expiry_date" class="form-label">@lang('Expiry Date')</label>
                                <input type="datetime-local" class="form-control @error('expiry_date') is-invalid @enderror" id="edit_expiry_date" name="expiry_date" value="{{ $depositVoucher->expiry_date->format('Y-m-d\TH:i') }}" required>
                                <div class="invalid-feedback" id="expiry_date_error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_status" class="form-label">@lang('Status')</label>
                                <select class="form-control @error('status') is-invalid @enderror" id="edit_status" name="status" required>
                                    <option value="active" {{ $depositVoucher->status === 'active' ? 'selected' : '' }}>@lang('Active')</option>
                                    <option value="used" {{ $depositVoucher->status === 'used' ? 'selected' : '' }}>@lang('Used')</option>
                                    <option value="expired" {{ $depositVoucher->status === 'expired' ? 'selected' : '' }}>@lang('Expired')</option>
                                </select>
                                <div class="invalid-feedback" id="status_error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_modal" class="form-label">@lang('Modal')</label>
                                <input type="text" class="form-control @error('modal') is-invalid @enderror" id="edit_modal" name="modal" value="{{ $depositVoucher->modal }}">
                                <div class="invalid-feedback" id="modal_error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">@lang('Description')</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="edit_description" name="description" rows="3">{{ $depositVoucher->description }}</textarea>
                        <div class="invalid-feedback" id="description_error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('Update')</button>
                </div>
            </form>
        </div>
    </div>
</div> 