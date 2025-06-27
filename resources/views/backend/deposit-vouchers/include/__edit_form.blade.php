<form id="editForm" action="{{ route('admin.deposit-vouchers.update', $depositVoucher) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="space-y-5">
        <div class="input-area">
            <label for="edit_title" class="form-label">
                @lang('Title')
            </label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="edit_title" name="title" value="{{ $depositVoucher->title }}" required>
            <div class="invalid-feedback" id="title_error"></div>
        </div>
        <div class="input-area">
            <label for="edit_amount" class="form-label">
                @lang('Amount')
            </label>
            <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="edit_amount" name="amount" value="{{ $depositVoucher->amount }}" required>
            <div class="invalid-feedback" id="amount_error"></div>
        </div>
        <div class="input-area">
            <label for="edit_expiry_date" class="form-label">
                @lang('Expiry Date')
            </label>
            <input type="datetime-local" class="form-control flatpickr flatpickr-input @error('expiry_date') is-invalid @enderror" id="edit_expiry_date" data-enable-time="true" name="expiry_date" value="{{ $depositVoucher->expiry_date->format('Y-m-d\TH:i') }}" required>
            <div class="invalid-feedback" id="expiry_date_error"></div>
        </div>
        <div class="input-area">
            <label for="edit_description" class="form-label">
                @lang('Description')
            </label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="edit_description" name="description" rows="3">{{ $depositVoucher->description }}</textarea>
            <div class="invalid-feedback" id="description_error"></div>
        </div>
    </div>
    <div class="text-right mt-10">
        <button type="submit" href="" class="btn btn-dark inline-flex items-center justify-center mr-2">
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
            {{ __('Save Changes') }}
        </button>
        <a href="#" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
            {{ __('Cancel') }}
        </a>
    </div>
</form>
