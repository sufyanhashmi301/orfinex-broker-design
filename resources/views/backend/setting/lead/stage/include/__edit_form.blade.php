<form action="{{ route('admin.lead.stage.update',$stage->id) }}" method="post">
    @method('put')
    @csrf
    <div class="space-y-4">
        <div class="input-area">
            <label class="form-label" for="">{{ __('Name:') }}</label>
            <input type="text" class="form-control" name="name" value="{{ old('name', $stage->name) }}" required>
        </div>
        <div class="input-area">
            <label for="" class="form-label">{{ __('Label Color') }}</label>
            <div class="color-input-group relative">
                <input type="" name="" class="form-control text-input" value="{{ old('name', $stage->label_color) }}">
                <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full flex items-center justify-center">
                    <input type="color" name="label_color" class="color-input" value="{{ old('name', $stage->label_color) }}" required>
                </span>
            </div>
        </div>
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
