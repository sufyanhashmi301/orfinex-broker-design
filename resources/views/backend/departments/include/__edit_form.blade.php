 <form action="{{ route('admin.departments.update',$department->id) }}" method="post" class="space-y-4">
                @method('put')    
                @csrf
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Name:') }}</label>
                        <input type="text" name="name" value="{{ old('name', $department->name) }}" class="form-control" placeholder="Department Name" required/>
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Parent:') }}</label>
                        <select name="parent_id" class="form-control">
                            <option value="">This is Parent</option>
                            @foreach($departments as $dept)
                            <option value="{{$dept->id}}" {{ $department->parent_id == $dept->id ? 'selected' : '' }}>{{$dept->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Department Email:') }}</label>
                        <input type="email" name="department_email" value="{{ old('department_email', $department->department_email) }}" class="form-control" placeholder="Department Email" />
                    </div>
                    <div class="input-area">
                    <input type="hidden" name="hide_from_client" value="0">
                    <input type="checkbox" name="hide_from_client" value="1" {{ old('hide_from_client', $department->hide_from_client) == 1 ? 'checked' : '' }}>
                        <label class="form-label" for="">{{ __('Hide From Client:') }}</label>
                    </div>
                    

                    <div class="action-btns text-right">
        <button type="submit" href="" class="btn btn-dark inline-flex items-center justify-center mr-2">
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
            {{ __('Save Changes') }}
        </button>
        <a
            href="#"
            class="btn btn-danger inline-flex items-center justify-center"
            data-bs-dismiss="modal"
            aria-label="Close"
        >
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
            Close
        </a>
    </div>
                </form>