<form action="{{ route('admin.ticket.priorities.update',$priority->id) }}" method="post" class="space-y-4">
    @method('put')
    @csrf
    <div class="input-area">
        <label class="form-label" for="">{{ __('Priority Title:') }}</label>
        <input type="text" name="name" value="{{ old('name', $priority->name) }}" class="form-control" required/>
    </div>
    <div class="input-area">
        <label for="" class="form-label">{{ __('Pick Color') }}</label>
        <div class="color-input-group relative">
            <input type="" name="" class="form-control text-input" value="{{ old('name', $priority->color) }}">
            <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full flex items-center justify-center">
                <input type="color" name="color" class="color-input" value="{{ old('name', $priority->color) }}">
            </span>
        </div>
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
