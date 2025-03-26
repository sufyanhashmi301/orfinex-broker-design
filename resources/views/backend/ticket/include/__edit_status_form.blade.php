<form action="{{ route('admin.ticket.statuses.update',$status->id) }}" method="post" class="space-y-4">
    @method('put')
    @csrf
    <div class="input-area">
        <label class="form-label" for="">{{ __('Status Name') }}</label>
        <input type="text" name="name" value="{{ old('name', $status->name) }}" class="form-control" required/>
    </div>
    <div class="input-area">
        <label class="form-label" for="">{{ __('Status type') }}</label>
        <select name="status_type" class="form-control">
            <option value="open" {{ $status->status_type == 'open' ? 'selected' : '' }}>{{ __('Open') }}</option>
            <option value="closed" {{ $status->status_type == 'closed' ? 'selected' : '' }}>{{ __('Closed') }}</option>
        </select>
    </div>
    <div class="action-btns text-right">
        <button type="submit" href="" class="btn btn-dark inline-flex items-center justify-center mr-2">
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
            {{ __('Save Changes') }}
        </button>
        <a href="#" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
            {{ __('Close') }}
        </a>
    </div>
</form>
