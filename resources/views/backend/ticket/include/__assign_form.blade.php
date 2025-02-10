<form action="{{ route('admin.ticket.assign', $ticket) }}" method="POST">
    @csrf
    <div class="space-y-5">
        <div class="input-area !mt-0">
            <label for="" class="form-label">{{ __('Assign To:') }}</label>
            <select name="assign_to" class="select2 form-control">
                @foreach($staff as $staff)
                    <option value="{{ $staff->id }}">{{ $staff->first_name.' '.$staff->last_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="action-btns text-right mt-10">
        <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
            {{ __('Assign Ticket') }}
        </button>
        <a href="#" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
            {{ __('Close') }}
        </a>
    </div>
</form>
