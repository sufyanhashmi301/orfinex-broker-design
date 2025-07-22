 <form action="{{ route('admin.departments.update',$department->id) }}" method="post">
    @method('put')
    @csrf
    <div class="space-y-5">
        <div class="input-area">
            <label class="form-label" for="">
                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the department name">
                    {{ __('Name') }}
                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                </span>
            </label>
            <input type="text" name="name" value="{{ old('name', $department->name) }}" class="form-control" placeholder="Department Name" required/>
        </div>
        <div class="input-area">
            <label class="form-label" for="">
                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Select a parent department, if applicable">
                    {{ __('Parent') }}
                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                </span>
            </label>
            <select name="parent_id" class="form-control">
                <option value="">{{ __('This is Parent') }}</option>
                @foreach($departments as $dept)
                <option value="{{$dept->id}}" {{ $department->parent_id == $dept->id ? 'selected' : '' }}>{{$dept->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="input-area">
            <label class="form-label" for="">
                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Email used for department communication">
                    {{ __('Department Email') }}
                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                </span>
            </label>
            <input type="email" name="department_email" value="{{ old('department_email', $department->department_email) }}" class="form-control" placeholder="Department Email" />
        </div>
        <div class="input-area">
            <div class="flex items-center space-x-7 flex-wrap">
                <label class="form-label !w-auto mb-0">
                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Toggle to make this department invisible to clients">
                        {{ __('Hide From Client') }}
                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                    </span>
                </label>
                <div class="form-switch ps-0" style="line-height: 0">
                    <input type="hidden" value="0" name="hide_from_client">
                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                        <input type="checkbox" name="hide_from_client" value="1" class="sr-only peer" {{ old('hide_from_client', $department->hide_from_client) == 1 ? 'checked' : '' }}>
                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                    </label>
                </div>
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
