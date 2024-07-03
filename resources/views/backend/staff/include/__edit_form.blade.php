<form action="{{ route('admin.staff.update',$staff->id) }}" method="post" class="space-y-4">
    @csrf
    @method('PUT')

    <div class="input-area !mt-0">
        <label for="" class="form-label">{{ __('Name:') }}</label>
        <input
            type="text"
            name="name"
            class="form-control mb-0"
            value="{{ $staff->name }}"
            required=""
            id="name"
        />
    </div>
    <div class="input-area">
        <label for="" class="form-label">{{ __('Email:') }}</label>
        <input
            type="email"
            name="email"
            class="form-control mb-0"
            value="{{ $staff->email }}"
            required=""
            id="email"
        />
    </div>
    <div class="input-area">
        <label for="" class="form-label">{{ __('Password:') }}</label>
        <input
            type="password"
            name="password"
            class="form-control mb-0"
        />
    </div>
    <div class="input-area">
        <label for="" class="form-label">{{ __('Confirm Password:') }}</label>
        <input
            type="password"
            name="confirm-password"
            class="form-control mb-0"
        />
    </div>

    <div class="input-area">
        <label class="form-label" for="">{{ __('Status:') }}</label>
        <div class="max-w-xs">
            <div class="switch-field flex mb-3 overflow-hidden">
                <input type="radio" id="radio-seven" name="status" value="1" @checked($staff->status)>
                <label for="radio-seven">{{ __('Active') }}</label>
                <input type="radio" id="radio-eight" name="status" value="0" @checked(!$staff->status)>
                <label for="radio-eight">{{ __('Disabled') }}</label>
            </div>
        </div>
    </div>


    <div class="input-area">
        <label class="form-label" for="">{{ __('Select Role:') }}</label>
        <select name="role" class="form-control w-100" id="role">
            @foreach($roles as $role)
                <option
                    @selected($role->name == $staff->roles[0]['name']) value="{{$role->name}}">{{ str_replace('-', ' ', $role->name) }}</option>
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
