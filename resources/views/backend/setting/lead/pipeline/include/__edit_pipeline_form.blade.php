<form action="{{ route('admin.lead.pipeline.update', $pipeline->id) }}" method="post">
    @method('put')
    @csrf
    <div class="space-y-4">
        <div class="input-area">
            <label class="form-label" for="">
                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter a name for the pipeline (e.g., Sales, Onboarding)">
                    {{ __('Name') }}
                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                </span>
            </label>
            <input type="text" class="form-control" name="name" value="{{ old('name', $pipeline->name) }}" required>
        </div>
        <div class="input-area">
            <label for="" class="form-label">
                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Choose a color to visually represent this pipeline">
                    {{ __('Label Color') }}
                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                </span>
            </label>
            <div class="color-input-group relative">
                <input type="" name="" class="form-control text-input" value="{{ old('name', $pipeline->label_color) }}">
                <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full flex items-center justify-center">
                    <input type="color" name="label_color" class="color-input" value="{{ old('name', $pipeline->label_color) }}" required>
                </span>
            </div>
        </div>
    </div>
    <div class="action-btns text-right mt-10">
        <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
            {{ __('Update Pipeline') }}
        </button>
        <a href="#" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
            {{ __('Close') }}
        </a>
    </div>
</form>
