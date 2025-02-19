<form action="{{ route('admin.lead.source.update',$source->id) }}" method="post">
    @method('put')
    @csrf
    <div class="input-area">
        <label class="form-label" for="">{{ __('Name:') }}</label>
        <input type="text" name="name" value="{{ old('name', $source->name) }}" class="form-control" required/>
    </div>
    <div class="action-btns text-right mt-10">
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
