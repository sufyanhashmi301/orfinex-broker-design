<form id="update-staff__form" class="space-y-5">
    @csrf
    @method('PUT')
    {{--    {{dd($staff->avatar)}}--}}
    <input type="hidden" id="staff-id" value="{{ $staff->id }}">
    <div class="card">
        <div class="card-header flex flex-col sm:flex-row">
            <div class="flex-none">
                <div class="w-20 h-20 rounded-[100%] ltr:mr-3 rtl:ml-3 ring-2 ring-slate-100 dark:ring-slate-100">
                    <img src="{{ getFilteredPath($staff->avatar, 'fallback/staff.png') }}" alt=""
                         class="w-full h-full rounded-[100%] object-cover">
                </div>
            </div>
            <div class="flex-1 text-start">
                <h4 class="text-lg font-medium text-slate-600 whitespace-nowrap">
                    {{$staff->first_name}} {{$staff->last_name}}
                    <span class="badge-primary text-xs capitalize rounded-lg px-2 py-0.5 ml-1">
                        {{ $staff->getRoleNames()->first() }}
                    </span>
                </h4>
                <div class="text-sm font-normal text-slate-500 dark:text-slate-400 my-1">
                    @if(isset($staff->designation))
                        {{ $staff->designation->name }}
                    @else
                        <span>-</span>
                    @endif
                </div>
                <div class="flex flex-wrap items-center gap-2 sm:gap-5">
                    @if($staff->email)
                        <div class="inline-flex items-center text-sm font-normal text-slate-800 dark:text-slate-400">
                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2 font-light"
                                          icon="heroicons-outline:mail"></iconify-icon>
                            {{ $staff->email }}
                        </div>
                    @endif
                    @if($staff->phone)
                        <div class="inline-flex items-center text-sm font-normal text-slate-800 dark:text-slate-400">
                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2 font-light"
                                          icon="heroicons-outline:phone"></iconify-icon>
                            {{ $staff->phone }}
                        </div>
                    @endif
                    @if(!$staff->hasRole('Super-Admin'))
                        @can('staff-login')
                            <a href="{{ route('admin.staff.login', $staff->id) }}"
                               class="inline-flex items-center text-sm font-normal text-slate-800 dark:text-slate-400 hover:underline">
                                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2 font-light"
                                              icon="mdi:user-add-outline"></iconify-icon>
                                {{ __('Login As Staff') }}
                            </a>
                        @endcan
                        @canany(['staff-attach-users-create','staff-attach-users-list'])
                            <a href="{{ route('admin.staff.attachUser.index', $staff->id) }}"
                               class="inline-flex items-center text-sm font-normal text-slate-800 dark:text-slate-400 hover:underline">
                                <iconify-icon class="ltr:mr-2 rtl:ml-2 font-light" icon="icomoon-free:attachment"></iconify-icon>
                                {{ __('Attached Users') }}
                            </a>
                        @endcanany

                        @canany(['staff-team-create','staff-team-list'])
                        <a href="{{ route('admin.team.manage', $staff->id) }}" class="inline-flex items-center text-sm font-normal text-slate-800 hover:underline">
                            <iconify-icon class="ltr:mr-2 rtl:ml-2" icon="heroicons:user-group"></iconify-icon>
                            {{ __('Manage Team') }}
                        </a>
                        @endcanany
                    @endif
                </div>
            </div>
        </div>
        @can('staff-edit')
        <div class="card-body p-6">
            <div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
                <div class="lg:col-span-2">
                    <div class="grid lg:grid-cols-2 grid-cols-1 gap-5">
                        <div class="input-area !mt-0">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The first name of the staff">
                                    {{ __('First Name') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    <span class="text-xs text-danger">*</span>
                                </span>
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
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The last name of the staff">
                                    {{ __('Last Name') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    <span class="text-xs text-danger">*</span>
                                </span>
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
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The nick name of the staff">
                                    {{ __('Nick Name') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    <span class="text-xs text-danger">*</span>
                                </span>
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
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The employee id of the staff">
                                    {{ __('Employee ID') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input
                                type="number"
                                name="employee_id"
                                class="form-control mb-0"
                                value="{{ $staff->employee_id }}"
                                placeholder="Employee ID"
                            />
                        </div>
                        @if(auth()->user()->hasRole('Super-Admin'))
                            <div class="input-area">
                                <label for="key" class="form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The key of the staff">
                                        {{ __('Key') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <input
                                    type="text"
                                    id="key"
                                    name="key"
                                    class="form-control mb-0"
                                    value="{{ $staff->key ?? '' }}"
                                    placeholder="Enter unique key"
                                />
                            </div>
                        @endif
                    </div>
                </div>
                <div class="input-area">
                    <label for="" class="form-label">
                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The profile avatar of the staff">
                            {{ __('Profile Avatar') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                        </span>
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
                                src="{{ getFilteredPath($staff->avatar, 'fallback/staff.png') }}"
                                alt=""
                            />
                            <span>{{ __('Upload Avatar') }}</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        @if(!$staff->hasRole('Super-Admin'))
            <div class="card-header">
                <h4 class="card-title">{{ __('Work Information') }}</h4>
            </div>
            <div class="card-body p-6">
                <div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
                    <div class="input-area">
                        <label class="form-label" for="department">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The department of the staff">
                                {{ __('Select Department') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <select name="department_id" class="select2 form-control w-100" id="department">
                            <option value="">Select</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}"
                                        @if($staff->department && $staff->department->id == $department->id) selected @endif>
                                    {{ $department->name }}
                                </option>
                                @foreach($department->children as $child)
                                    <option value="{{ $child->id }}"
                                            @if($staff->department && $staff->department->id == $child->id) selected @endif>
                                        -- {{ $child->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div class="input-area">
                        <label class="form-label" for="designation">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The designation of the staff">
                                {{ __('Select Designation') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <select name="designation_id" class="select2 form-control w-100" id="designation">
                            <option value="">Select</option>
                            @foreach($designations as $designation)
                                <option value="{{ $designation->id }}"
                                        @if($staff->designation && $staff->designation->id == $designation->id) selected @endif>
                                    {{ $designation->name }}
                                </option>
                                @foreach($designation->children as $child)
                                    <option value="{{ $child->id }}"
                                            @if($staff->designation && $staff->designation->id == $child->id) selected @endif>
                                        -- {{ $child->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>

                    <div class="input-area">
                        <label class="form-label" for="">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The employment type of the staff">
                                {{ __('Employment Type') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <select name="employment_type" class="select2 form-control w-100">
                            <option value="permanent" @selected($staff->employment_type ===
                                'permanent')>{{ __('Permanent') }}</option>
                            <option value="on contract" @selected($staff->employment_type === 'on
                                contract')>{{ __('On Contract') }}</option>
                            <option value="temporary" @selected($staff->employment_type ===
                                'temporary')>{{ __('Temporary') }}</option>
                            <option value="trainee" @selected($staff->employment_type ===
                                'trainee')>{{ __('Trainee') }}</option>
                        </select>
                    </div>

                    <div class="input-area">
                        <label class="form-label" for="">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The employment status of the staff">
                                {{ __('Employment Status') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <select name="employment_status" class="select2 form-control w-100">
                            <option value="active" @selected($staff->employment_status ===
                                'active')>{{ __('Active') }}</option>
                            <option value="terminated" @selected($staff->employment_status ===
                                'terminated')>{{ __('Terminated') }}</option>
                            <option value="deceased" @selected($staff->employment_status ===
                                'deceased')>{{ __('Deceased') }}</option>
                            <option value="resigned" @selected($staff->employment_status ===
                                'resigned')>{{ __('Resigned') }}</option>
                            <option value="probation" @selected($staff->employment_status ===
                                'probation')>{{ __('Probation') }}</option>
                            <option value="notice period" @selected($staff->employment_status === 'notice
                                period')>{{ __('Notice Period') }}</option>
                        </select>
                    </div>

                    <div class="input-area">
                        <label class="form-label" for="">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The source of hire of the staff">
                                {{ __('Source Of Hire') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <select name="source_of_hire" class="select2 form-control w-100">
                            <option value="direct" @selected($staff->source_of_hire ===
                                'direct')>{{ __('Direct') }}</option>
                            <option value="referral" @selected($staff->source_of_hire ===
                                'referral')>{{ __('Referral') }}</option>
                            <option value="web" @selected($staff->source_of_hire === 'web')>{{ __('Web') }}</option>
                            <option value="newspaper" @selected($staff->source_of_hire ===
                                'newspaper')>{{ __('Newspaper') }}</option>
                        </select>
                    </div>

                    <div class="input-area">
                        <label class="form-label" for="">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The location of the staff">
                                {{ __('Location') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
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
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The date of joining of the staff">
                                {{ __('Date Of Joining') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <input
                            type="text"
                            name="date_of_joining"
                            class="form-control flatpickr"
                            value="{{ $staff->date_of_joining }}"
                        >
                    </div>
                </div>
            </div>
        @endif
            @can('staff-edit-role')
                <div class="card-header">
                    <h4 class="card-title">{{ __('Role Management') }}</h4>
                </div>
                <div class="card-body p-6">
                    <div class="grid lg:grid-cols-3 grid-cols-1 gap-5">


                        <div class="input-area">
                            <label class="form-label" for="">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The role of the staff">
                                    {{ __('Select Role') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    <span class="text-xs text-danger">*</span>
                                </span>
                            </label>
                            <select name="role" class="select2 form-control w-100" required>
                                @foreach($roles as $role)
                                    <option @selected($role->name == $staff->roles[0]['name']) value="{{$role->name}}">
                                        {{ str_replace('-', ' ', $role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            @endcan
        <div class="card-header">
            <h4 class="card-title">{{ __('Personal Details') }}</h4>
        </div>
        <div class="card-body p-6">
            <div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
                <div class="input-area">
                    <label for="" class="form-label">
                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The date of birth of the staff">
                            {{ __('Date Of Birth') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                        </span>
                    </label>
                    <input
                        type="text"
                        name="date_of_birth"
                        class="form-control flatpickr dateOfBirth"
                        value="{{ $staff->date_of_birth }}"
                        placeholder="2006-12-19"
                    >
                </div>
                <div class="input-area">
                    <label for="" class="form-label">
                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The gender of the staff">
                            {{ __('Gender') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                        </span>
                    </label>
                    <select name="gender" class="select2 form-control w-full">
                        <option value="male" @selected($staff->gender === 'male')>{{ __('Male') }}</option>
                        <option value="female" @selected($staff->gender === 'female')>{{ __('Female') }}</option>
                        <option value="other" @selected($staff->gender === 'other')>{{ __('Other') }}</option>
                    </select>
                </div>
                <div class="input-area">
                    <label for="" class="form-label">
                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The marital status of the staff">
                            {{ __('Marital Status') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                        </span>
                    </label>
                    <div class="flex items-center space-x-7 flex-wrap pt-2">
                        <div class="basicRadio">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" class="hidden" name="marital_status" value="single"
                                       @if($staff->marital_status === 'single') checked @endif>
                                <span
                                    class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                <span class="text-secondary-500 text-sm leading-6 capitalize">{{ __('Single') }}</span>
                            </label>
                        </div>
                        <div class="basicRadio">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" class="hidden" name="marital_status" value="married"
                                       @if($staff->marital_status === 'married') checked @endif>
                                <span
                                    class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                <span class="text-secondary-500 text-sm leading-6 capitalize">{{ __('Married') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="input-area">
                    <label for="" class="form-label">
                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The work phone number of the staff">
                            {{ __('Work Phone Number') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                        </span>
                    </label>
                    <input
                        type="text"
                        name="work_phone"
                        class="form-control"
                        value="{{ $staff->work_phone }}"
                        placeholder=""
                    >
                </div>
                <div class="input-area">
                    <label for="" class="form-label">
                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The personal phone number of the staff">
                            {{ __('Personal Phone Number') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                        </span>
                    </label>
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

        <div class="card-header">
            <h4 class="card-title">{{ __('System Info') }}</h4>
        </div>
        <div class="card-body p-6">
            <div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
                <div class="input-area">
                    <label for="" class="form-label">
                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The email of the staff">
                            {{ __('Email') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            <span class="text-xs text-danger">*</span>
                        </span>
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
                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The password of the staff">
                            {{ __('Password') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                        </span>
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
                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The confirm password of the staff">
                            {{ __('Confirm Password') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                        </span>
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
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The status of the staff">
                                {{ __('Status') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                            <span class="text-xs text-danger">*</span>
                        </label>
                        <div class="form-switch ps-0">
                            <input type="hidden" value="0" name="status">
                            <label
                                class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                <input type="checkbox" name="status" value="1" class="sr-only peer" @checked($staff->status)>
                                <span
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endcan
        @if(!$staff->hasRole('Super-Admin'))

            <div class="card-header">
                <h4 class="card-title">{{ __('Invite') }}</h4>
            </div>
            <div class="card-body p-6">
                <div class="input-area">
                    <div class="relative">
                        <input type="text" class="form-control !pr-32" id="referral-input" value="{{ $staff->link }}"
                               readonly>
                        <span
                            class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                        <a href="javascript:;" class="copy-button" type="button" data-target="#referral-input">
                            {{ __('Copy Link') }}
                        </a>
                    </span>
                    </div>
                    {{--                    <p class="referral-joined text-sm dark:text-white mb-4 sm:mb-0">--}}
                    {{--                        {{ $getReferral->relationships()->count() }} {{ __('peoples are joined by using this URL') }}--}}
                    {{--                    </p>--}}
                </div>
            </div>
        @endif
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center p-6">
            @can('staff-edit')
            <button type="submit" class="btn btn-dark inline-flex items-center justify-center" id="update-staff__btn">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                {{ __('Save Changes') }}
            </button>
            @endcan
            @can('staff-delete')
                <button type="button" class="btn btn-danger inline-flex items-center justify-center delete-staff-btn"
                        data-id="{{ $staff->id }}">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="heroicons:trash"></iconify-icon>
                    {{ __('Delete') }}
                </button>
            @endcan
        </div>
    </div>

</form>
