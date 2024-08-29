@extends('backend.layouts.app')
@section('title')
    {{ __('Update Staff') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ url()->previous() }}" class="btn btn-primary inline-flex items-center justify-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                {{ __('Back') }}
            </a>
        </div>
    </div>

    <form action="{{ route('admin.staff.update',$staff->id) }}" method="post" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('Basic Information') }}</h4>
            </div>
            <div class="card-body p-6">
                <div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
                    <div class="lg:col-span-2">
                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5">
                            <div class="input-area !mt-0">
                                <label for="" class="form-label">
                                    {{ __('First Name:') }}
                                    <span class="text-xs text-danger-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="first_name"
                                    class="form-control mb-0"
                                    value="{{ $staff->first_name }}"
                                    placeholder="First Name"
                                    required
                                />
                            </div>
                            <div class="input-area !mt-0">
                                <label for="" class="form-label">
                                    {{ __('Last Name:') }}
                                    <span class="text-xs text-danger-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="last_name"
                                    class="form-control mb-0"
                                    value="{{ $staff->last_name }}"
                                    placeholder="Last Name"
                                    required
                                />
                            </div>
                            <div class="input-area !mt-0">
                                <label for="" class="form-label">
                                    {{ __('Nick Name:') }}
                                    <span class="text-xs text-danger-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    class="form-control mb-0"
                                    value="{{ $staff->name }}"
                                    placeholder="Staff Name"
                                    required
                                />
                            </div>
                            <div class="input-area">
                                <label for="" class="form-label">
                                    {{ __('Employee ID:') }}
                                </label>
                                <input
                                    type="text"
                                    name="employee_id"
                                    class="form-control mb-0"
                                    value="{{ $staff->employee_id }}"
                                    placeholder="Employee ID"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">
                            {{ __('Profile Avatar:') }}
                        </label>
                        <div class="wrap-custom-file h-full">
                            <input
                                type="file"
                                name="avatar"
                                id="profile-avatar"
                                accept=".gif, .jpg, .png"
                            />
                            <label for="profile-avatar">
                                <img
                                    class="upload-icon"
                                    src="{{asset('global/materials/upload.svg')}}"
                                    alt=""
                                />
                                <span>{{ __('Upload Avatar') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('Work Information') }}</h4>
            </div>
            <div class="card-body p-6">
                <div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
                    <div class="input-area">
                        <label class="form-label" for="department">{{ __('Select Department:') }}</label>
                        <select name="department_id" class="select2 form-control w-100" id="department">
                            <option value="">Select</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" @selected($staff->departments->contains($department->id))>
                                    {{ $department->name }}
                                </option>
                                @foreach($department->children as $child)
                                    <option value="{{ $child->id }}" @selected($staff->departments->contains($child->id))>
                                        -- {{ $child->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div class="input-area">
                        <label class="form-label" for="designation">{{ __('Select Designation:') }}</label>
                        <select name="designation_id" class="select2 form-control w-100" id="designation">
                            <option value="">Select</option>
                            @foreach($designations as $designation)
                                <option value="{{ $designation->id }}" @selected($staff->designations->contains($designation->id))>
                                    {{ $designation->name }}
                                </option>
                                @foreach($designation->children as $child)
                                    <option value="{{ $child->id }}" @selected($staff->designations->contains($child->id))>
                                        -- {{ $child->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div class="input-area">
                        <label class="form-label" for="">
                            {{ __('Select Role:') }}
                            <span class="text-xs text-danger-500">*</span>
                        </label>
                        <select name="role" class="select2 form-control w-100" required>
                            @foreach($roles as $role)
                                <option @selected($role->name == $staff->roles[0]['name']) value="{{$role->name}}">
                                    {{ str_replace('-', ' ', $role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-area">
                        <label class="form-label" for="">
                            {{ __('Employment Type:') }}
                        </label>
                        <select name="employment_type" class="select2 form-control w-100">
                            <option value="permanent" @selected($staff->employment_type === 'permanent')>{{ __('Permanent') }}</option>
                            <option value="on contract" @selected($staff->employment_type === 'on contract')>{{ __('On Contract') }}</option>
                            <option value="temporary" @selected($staff->employment_type === 'temporary')>{{ __('Temporary') }}</option>
                            <option value="trainee" @selected($staff->employment_type === 'trainee')>{{ __('Trainee') }}</option>
                        </select>
                    </div>

                    <div class="input-area">
                        <label class="form-label" for="">
                            {{ __('Employment Status:') }}
                        </label>
                        <select name="employment_status" class="select2 form-control w-100">
                            <option value="active" @selected($staff->employment_status === 'active')>{{ __('Active') }}</option>
                            <option value="terminated" @selected($staff->employment_status === 'terminated')>{{ __('Terminated') }}</option>
                            <option value="deceased" @selected($staff->employment_status === 'deceased')>{{ __('Deceased') }}</option>
                            <option value="resigned" @selected($staff->employment_status === 'resigned')>{{ __('Resigned') }}</option>
                            <option value="probation" @selected($staff->employment_status === 'probation')>{{ __('Probation') }}</option>
                            <option value="notice period" @selected($staff->employment_status === 'notice period')>{{ __('Notice Period') }}</option>
                        </select>
                    </div>

                    <div class="input-area">
                        <label class="form-label" for="">
                            {{ __('Source Of Hire:') }}
                        </label>
                        <select name="source_of_hire" class="select2 form-control w-100">
                            <option value="direct" @selected($staff->source_of_hire === 'direct')>{{ __('Direct') }}</option>
                            <option value="referral" @selected($staff->source_of_hire === 'referral')>{{ __('Referral') }}</option>
                            <option value="web" @selected($staff->source_of_hire === 'web')>{{ __('Web') }}</option>
                            <option value="newspaper" @selected($staff->source_of_hire === 'newspaper')>{{ __('Newspaper') }}</option>
                        </select>
                    </div>

                    <div class="input-area">
                        <label class="form-label" for="">
                            {{ __('Location:') }}
                        </label>
                        <select name="location" class="select2 form-control w-100">
                            @foreach( getCountries() as $country)
                                <option @selected($country['name'] == $staff->location) value="{{$country['name']}}">
                                    {{ str_replace('-', ' ', $country['name']) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Date Of Joining:') }}</label>
                        <input
                            type="text"
                            name="date_of_joining"
                            class="form-control flatpickr"
                            value="{{ $staff->date_of_joining }}"
                        >
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('Personal Details') }}</h4>
            </div>
            <div class="card-body p-6">
                <div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Date Of Birth:') }}</label>
                        <input
                            type="text"
                            name="date_of_birth"
                            class="form-control dateOfBirth"
                            value="{{ $staff->date_of_birth }}"
                            placeholder="2006-12-19"
                        >
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Gender:') }}</label>
                        <select name="status" class="select2 form-control w-full">
                            <option value="male" @selected($staff->gender === 'male')>{{ __('Male') }}</option>
                            <option value="female" @selected($staff->gender === 'female')>{{ __('Female') }}</option>
                            <option value="other" @selected($staff->gender === 'other')>{{ __('Other') }}</option>
                        </select>
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Marital Status:') }}</label>
                        <div class="flex items-center space-x-7 flex-wrap pt-2">
                            <div class="basicRadio">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" class="hidden" name="marital_status" value="single" @if($staff->marital_status === 'single') checked @endif>
                                    <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                    <span class="text-secondary-500 text-sm leading-6 capitalize">{{ __('Single') }}</span>
                                </label>
                            </div>
                            <div class="basicRadio">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" class="hidden" name="marital_status" value="married" @if($staff->marital_status === 'married') checked @endif>
                                    <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                    <span class="text-secondary-500 text-sm leading-6 capitalize">{{ __('Married') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Work Phone Number:') }}</label>
                        <input
                            type="text"
                            name="work_phone"
                            class="form-control"
                            value="{{ $staff->work_phone }}"
                            placeholder=""
                        >
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Personal Phone Number:') }}</label>
                        <input
                            type="text"
                            name="phone"
                            class="form-control"
                            value="{{ $staff->phone }}"
                            placeholder=""
                        >
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('System Info') }}</h4>
            </div>
            <div class="card-body p-6">
                <div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
                    <div class="input-area">
                        <label for="" class="form-label">
                            {{ __('Email:') }}
                            <span class="text-xs text-danger-500">*</span>
                        </label>
                        <input
                            type="email"
                            name="email"
                            class="form-control mb-0"
                            placeholder="Staff Email"
                            value="{{ $staff->email }}"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">
                            {{ __('Password:') }}
                        </label>
                        <input
                            type="password"
                            name="password"
                            class="form-control mb-0"
                            placeholder="Password"
                        />
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">
                            {{ __('Confirm Password:') }}
                        </label>
                        <input
                            type="password"
                            name="confirm-password"
                            class="form-control mb-0"
                            placeholder="Confirm Password"
                        />
                    </div>
                    <div class="input-area lg:col-span-3">
                        <div class="flex items-center space-x-7 flex-wrap">
                            <label class="form-label !w-auto" for="">
                                {{ __('Status') }}
                                <span class="text-xs text-danger-500">*</span>
                            </label>
                            <div class="form-switch ps-0">
                                <input type="hidden" value="0" name="status">
                                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                    <input type="checkbox" name="status" value="1" class="sr-only peer" @checked($staff->status)>
                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="action-btns mt-10">
                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('script')
    <script !src="">
        $(".dateOfBirth").flatpickr({
            dateFormat: "Y-m-d",
            maxDate: "31.12.2006"
        });
    </script>
@endsection
