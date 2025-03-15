<form id="update-staff__form" class="space-y-5">
    @csrf
    @method('PUT')
{{--    {{dd($staff->avatar)}}--}}
    <input type="hidden" id="staff-id" value="{{ $staff->id }}">
    <div class="card">
        <div class="card-header noborder flex-col sm:flex-row">
            <div class="flex-none">
                <div class="w-20 h-20 rounded-[100%] ltr:mr-3 rtl:ml-3">
                    <img src="{{ getFilteredPath($staff->avatar, 'frontend/images/avatar/av-4.svg') }}" alt="" class="w-full h-full rounded-[100%] object-cover">
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
                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2 font-light" icon="heroicons-outline:mail"></iconify-icon>
                            {{ $staff->email }}
                        </div>
                    @endif
                    @if($staff->phone)
                        <div class="inline-flex items-center text-sm font-normal text-slate-800 dark:text-slate-400">
                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2 font-light" icon="heroicons-outline:phone"></iconify-icon>
                            {{ $staff->phone }}
                        </div>
                    @endif
                    @if(Auth::user() && Auth::user()->getRoleNames()->contains('Super-Admin') && $staff->getRoleNames()->first() != 'Super-Admin')
                        <a href="{{ route('admin.staff.login', $staff->id) }}" class="inline-flex items-center text-sm font-normal text-slate-800 dark:text-slate-400 hover:underline">
                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2 font-light" icon="mdi:user-add-outline"></iconify-icon>
                            {{ __('Login As Staff') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body p-6">
            <div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
                <div class="lg:col-span-2">
                    <div class="grid lg:grid-cols-2 grid-cols-1 gap-5">
                        <div class="input-area !mt-0">
                            <label for="" class="form-label">
                                {{ __('First Name:') }}
                                <span class="text-xs text-danger">*</span>
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
                                <span class="text-xs text-danger">*</span>
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
                                <span class="text-xs text-danger">*</span>
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
                        @if(auth()->user()->hasRole('Super-Admin'))
                            <div class="input-area">
                                <label for="key" class="form-label">
                                    {{ __('Key:') }}
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
                                src="{{asset($staff->avatar ?? 'frontend/images/avatar/av-4.svg')}}"
                                alt=""
                            />
                            <span>{{ __('Upload Avatar') }}</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(!$staff->hasRole('Super-Admin'))
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
                            <option value="{{ $department->id }}" @if($staff->department && $staff->department->id == $department->id) selected @endif>
                                {{ $department->name }}
                            </option>
                            @foreach($department->children as $child)
                                <option value="{{ $child->id }}" @if($staff->department && $staff->department->id == $child->id) selected @endif>
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
                            <option value="{{ $designation->id }}" @if($staff->designation && $staff->designation->id == $designation->id) selected @endif>
                                {{ $designation->name }}
                            </option>
                            @foreach($designation->children as $child)
                                <option value="{{ $child->id }}" @if($staff->designation && $staff->designation->id == $child->id) selected @endif>
                                    -- {{ $child->name }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                </div>

                    <div class="input-area">
                    <label class="form-label" for="">
                        {{ __('Select Role:') }}
                        <span class="text-xs text-danger">*</span>
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
    @endif

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
                        class="form-control flatpicket dateOfBirth"
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
                        <span class="text-xs text-danger">*</span>
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
                            <span class="text-xs text-danger">*</span>
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
        </div>
    </div>

    <div class="card">
        @if(auth()->user()->hasRole('Super-Admin') && !$staff->hasRole('Super-Admin'))
            <div class="card-header">
                <h4 class="card-title">{{ __('Attach Users') }}</h4>
            </div>
        @endif
        <div class="card-body p-6">
            @if(auth()->user()->hasRole('Super-Admin') && !$staff->hasRole('Super-Admin'))
                <div class="grid lg:grid-cols-2 grid-cols-1 gap-5">
                    <div class="input-area">
                        <label class="form-label">{{ __('IB Groups:') }}</label>
                        <select name="ib_groups[]" class="select2 form-control w-full" data-placeholder="Select Options" multiple>
                            <option value="all" @if(in_array('all', $staff->ib_groups ?? [])) selected @endif>
                                {{ __('All') }}
                            </option>
                            @foreach($ibGroups as $ibGroup)
                                <option value="{{ $ibGroup->id }}"
                                    {{ in_array($ibGroup->id, $staff->ib_groups ?? []) ? 'selected' : '' }}>
                                    {{ $ibGroup->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-area">
                        <label class="form-label">{{ __('Account Types:') }}</label>
                        <select name="account_types[]" class="select2 form-control w-full" data-placeholder="Select Options" multiple>
                            <option value="all" @if(in_array('all', $staff->account_types ?? [])) selected @endif>
                                {{ __('All') }}
                            </option>
                            @foreach($schemas as $schema)
                                <option value="{{ $schema->id }}"
                                    {{ in_array($schema->id, $staff->account_types ?? []) ? 'selected' : '' }}>
                                    {{ $schema->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-area">
                        <label class="form-label">{{ __('Attach Users:') }}</label>
                        <select name="user_ids[]" class="select2 form-control w-full" data-placeholder="Select Options" multiple>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @if($attachedUsers->contains($user->id)) selected @endif>
                                    {{ $user->full_name }}({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto -mx-6 dashcode-data-table mt-6">
                    <span class=" col-span-8  hidden"></span>
                    <span class="  col-span-4 hidden"></span>
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 data-table">
                                <thead class="bg-slate-200 dark:bg-slate-700">
                                    <tr>
                                        <th scope="col" class="table-th">{{ __('User') }}</th>
                                        <th scope="col" class="table-th">{{ __('Email') }}</th>
                                        <th scope="col" class="table-th">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                    @foreach($attachedUsers as $user)
                                        <tr>
                                            <td class="table-td">
                                                <strong>{{ $user->full_name }}</strong>
                                            </td>
                                            <td class="table-td">
                                                <strong class="lowercase">{{$user->email }}</strong>
                                            </td>
                                            <td class="table-td">
                                                <button class="action-btn userDetachBtn" data-user-id="{{ $user->id }}" data-staff-id="{{ $staff->id }}" data-name="{{ $user->full_name }}" type="button">
                                                    <iconify-icon icon="heroicons:trash"></iconify-icon>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            {{--            </div>--}}

            <div class="action-btns text-right mt-10">
                <button type="submit" class="btn btn-dark inline-flex items-center justify-center" id="update-staff__btn">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                    {{ __('Save Changes') }}
                </button>
            </div>

        </div>
    </div>

</form>
