 <form action="{{ route('admin.designations.update',$designation->id) }}" method="post" class="space-y-4">
                @method('put')    
                @csrf
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Name:') }}</label>
                        <input type="text" name="name" value="{{ old('name', $designation->name) }}" class="form-control" placeholder="Department Name" required/>
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Parent:') }}</label>
                        <select name="parent_id" class="form-control">
                            <option value="">This is Parent</option>
                            @foreach($designations as $dept)
                            <option value="{{$dept->id}}" {{ $designation->parent_id == $dept->id ? 'selected' : '' }}>{{$dept->name}}</option>
                            @endforeach
                        </select>
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