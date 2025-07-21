 <form action="{{ route('admin.designations.update',$designation->id) }}" method="post">
    @method('put')    
    @csrf
    <div class="space-y-5">
        <div class="input-area">
            <label class="form-label" for="">
                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the designation name">
                    {{ __('Name') }}
                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                </span>
            </label>
            <input type="text" name="name" value="{{ old('name', $designation->name) }}" class="form-control" placeholder="Department Name" required/>
        </div>
        <div class="input-area">
            <label class="form-label" for="">
                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Select a parent designation, if applicable">
                    {{ __('Parent') }}
                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                </span>
            </label>
            <select name="parent_id" class="form-control">
                <option value="">This is Parent</option>
                @foreach($designations as $dept)
                <option value="{{$dept->id}}" {{ $designation->parent_id == $dept->id ? 'selected' : '' }}>{{$dept->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
        
    <div class="action-btns text-right mt-10">
        <button type="submit" href="" class="btn btn-dark inline-flex items-center justify-center mr-2">
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
            {{ __('Save Changes') }}
        </button>
        <a
            href="#"
            class="btn btn-danger inline-flex items-center justify-center"
            data-bs-dismiss="modal"
            aria-label="Close">
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
            {{ __('Close') }}
        </a>
    </div>
</form>