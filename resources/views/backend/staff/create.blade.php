<form action="{{ route('admin.staff.store') }}" method="post" enctype="multipart/form-data" class="space-y-5">
    @csrf
    <div class="card !mt-0">
        <div class="card-header">
            <h4 class="card-title">{{ __('Basic Information') }}</h4>
        </div>
        <div class="card-body p-6">
            <div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
                <div class="lg:col-span-2">
                    <div class="grid lg:grid-cols-2 grid-cols-1 gap-5">
                        <div class="input-area !mt-0">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="The first name of the staff">
                                    {{ __('First Name') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                    <span class="text-xs text-danger">*</span>
                                </span>
                            </label>
                            <input type="text" name="first_name" class="form-control mb-0"
                                placeholder="First Name" />
                        </div>
                        <div class="input-area !mt-0">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="The last name of the staff">
                                    {{ __('Last Name') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                    <span class="text-xs text-danger">*</span>
                                </span>
                            </label>
                            <input type="text" name="last_name" class="form-control mb-0" placeholder="Last Name" />
                        </div>
                        <div class="input-area !mt-0">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="The nick name of the staff">
                                    {{ __('Nick Name') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                    <span class="text-xs text-danger">*</span>
                                </span>
                            </label>
                            <input type="text" name="name" class="form-control mb-0" placeholder="Staff Name" />
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="The employee id of the staff">
                                    {{ __('Employee ID') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="number" name="employee_id" class="form-control mb-0" placeholder="Employee ID"
                                min="1" step="1" />
                        </div>
                    </div>
                </div>
                <div class="input-area">
                    <label for="" class="form-label">
                        <span class="shift-Away inline-flex items-center gap-1"
                            data-tippy-content="The profile avatar of the staff">
                            {{ __('Profile Avatar') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                        </span>
                    </label>
                    <div class="wrap-custom-file h-full">
                        <input type="file" name="avatar" id="profile-avatar" accept=".gif, .jpg, .png" />
                        <label for="profile-avatar">
                            <img class="upload-icon" src="{{ asset('global/materials/upload.svg') }}" alt="" />
                            <span>{{ __('Upload Avatar') }}</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--    Personal Detail --}}
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('Personal Details') }}</h4>
        </div>
        <div class="card-body p-6">
            <div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
                <div class="input-area">
                    <label for="" class="form-label">
                        <span class="shift-Away inline-flex items-center gap-1"
                            data-tippy-content="The date of birth of the staff">
                            {{ __('Date Of Birth') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                        </span>
                    </label>
                    <input type="text" name="date_of_birth" class="form-control flatpickr dateOfBirth"
                        placeholder="2006-12-19">
                </div>
                <div class="input-area">
                    <label for="" class="form-label">
                        <span class="shift-Away inline-flex items-center gap-1"
                            data-tippy-content="The gender of the staff">
                            {{ __('Gender') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                        </span>
                    </label>
                    <select name="gender" class="select2 form-control w-full">
                        <option value="male">{{ __('Male') }}</option>
                        <option value="female">{{ __('Female') }}</option>
                        <option value="other">{{ __('Other') }}</option>
                    </select>
                </div>
                <div class="input-area">
                    <label for="" class="form-label">
                        <span class="shift-Away inline-flex items-center gap-1"
                            data-tippy-content="The marital status of the staff">
                            {{ __('Marital Status') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline"
                                class="text-[16px]"></iconify-icon>
                        </span>
                    </label>
                    <div class="flex items-center space-x-7 flex-wrap pt-2">
                        <div class="basicRadio">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" class="hidden" name="marital_status" value="single"
                                    checked="checked">
                                <span
                                    class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                <span
                                    class="text-secondary-500 text-sm leading-6 capitalize">{{ __('Single') }}</span>
                            </label>
                        </div>
                        <div class="basicRadio">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" class="hidden" name="marital_status" value="married">
                                <span
                                    class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                <span
                                    class="text-secondary-500 text-sm leading-6 capitalize">{{ __('Married') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="input-area">
                    <label for="" class="form-label">
                        <span class="shift-Away inline-flex items-center gap-1"
                            data-tippy-content="The work phone number of the staff">
                            {{ __('Work Phone Number') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline"
                                class="text-[16px]"></iconify-icon>
                        </span>
                    </label>
                    <input type="text" name="work_phone" class="form-control" placeholder="">
                </div>
                <div class="input-area">
                    <label for="" class="form-label">
                        <span class="shift-Away inline-flex items-center gap-1"
                            data-tippy-content="The personal phone number of the staff">
                            {{ __('Personal Phone Number') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline"
                                class="text-[16px]"></iconify-icon>
                        </span>
                    </label>
                    <input type="text" name="phone" class="form-control" placeholder="">
                </div>
            </div>
        </div>
    </div>

    {{--    Work Information --}}
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('Work Information') }}</h4>
        </div>
        <div class="card-body p-6">
            <div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
                <div class="input-area">
                    <label class="form-label" for="department">
                        <span class="shift-Away inline-flex items-center gap-1"
                            data-tippy-content="The department of the staff">
                            {{ __('Select Department') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline"
                                class="text-[16px]"></iconify-icon>
                        </span>
                    </label>
                    <select name="department_id" class="select2 form-control w-100" id="department">
                        <option value="">Select</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="input-area">
                    <label class="form-label" for="designation">
                        <span class="shift-Away inline-flex items-center gap-1"
                            data-tippy-content="The designation of the staff">
                            {{ __('Select Designation') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline"
                                class="text-[16px]"></iconify-icon>
                        </span>
                    </label>
                    <select name="designation_id" class="select2 form-control w-100" id="designation">
                        <option value="">Select</option>
                        @foreach ($designations as $designation)
                            <option value="{{ $designation->id }}">
                                {{ $designation->name }}
                            </option>
                        @endforeach
                    </select>
                </div>



                <div class="input-area">
                    <label class="form-label" for="">
                        <span class="shift-Away inline-flex items-center gap-1"
                            data-tippy-content="The employment type of the staff">
                            {{ __('Employment Type') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline"
                                class="text-[16px]"></iconify-icon>
                        </span>
                    </label>
                    <select name="employment_type" class="select2 form-control w-100">
                        <option value="permanent">{{ __('Permanent') }}</option>
                        <option value="on contract">{{ __('On Contract') }}</option>
                        <option value="temporary">{{ __('Temporary') }}</option>
                        <option value="trainee">{{ __('Trainee') }}</option>
                    </select>
                </div>

                <div class="input-area">
                    <label class="form-label" for="">
                        <span class="shift-Away inline-flex items-center gap-1"
                            data-tippy-content="The employment status of the staff">
                            {{ __('Employment Status') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline"
                                class="text-[16px]"></iconify-icon>
                        </span>
                    </label>
                    <select name="employment_status" class="select2 form-control w-100">
                        <option value="active">{{ __('Active') }}</option>
                        <option value="terminated">{{ __('Terminated') }}</option>
                        <option value="deceased">{{ __('Deceased') }}</option>
                        <option value="resigned">{{ __('Resigned') }}</option>
                        <option value="probation">{{ __('Probation') }}</option>
                        <option value="notice period">{{ __('Notice Period') }}</option>
                    </select>
                </div>

                <div class="input-area">
                    <label class="form-label" for="">
                        <span class="shift-Away inline-flex items-center gap-1"
                            data-tippy-content="The source of hire of the staff">
                            {{ __('Source Of Hire') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline"
                                class="text-[16px]"></iconify-icon>
                        </span>
                    </label>
                    <select name="source_of_hire" class="select2 form-control w-100">
                        <option value="direct">{{ __('Direct') }}</option>
                        <option value="referral">{{ __('Referral') }}</option>
                        <option value="web">{{ __('Web') }}</option>
                        <option value="newspaper">{{ __('Newspaper') }}</option>
                    </select>
                </div>

                <div class="input-area">
                    <label class="form-label" for="">
                        <span class="shift-Away inline-flex items-center gap-1"
                            data-tippy-content="The location of the staff">
                            {{ __('Location') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline"
                                class="text-[16px]"></iconify-icon>
                        </span>
                    </label>
                    <select name="location" class="select2 form-control w-100">
                        @foreach (getCountries() as $country)
                            <option value="{{ $country['name'] }}">{{ $country['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="input-area">
                    <label for="" class="form-label">
                        <span class="shift-Away inline-flex items-center gap-1"
                            data-tippy-content="The date of joining of the staff">
                            {{ __('Date Of Joining') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline"
                                class="text-[16px]"></iconify-icon>
                        </span>
                    </label>
                    <input type="text" name="date_of_joining" class="form-control flatpickr flatpickr-input">
                </div>
            </div>
        </div>
    </div>

    {{--    Role Management --}}
    <div class="card-header">
        <h4 class="card-title">{{ __('Role Management') }}</h4>
    </div>
    <div class="card-body p-6">
        <div class="grid lg:grid-cols-3 grid-cols-1 gap-5">

            <div class="input-area">
                <label class="form-label" for="">
                    <span class="shift-Away inline-flex items-center gap-1"
                        data-tippy-content="The role of the staff">
                        {{ __('Select Role') }}
                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                        <span class="text-xs text-danger">*</span>
                    </span>
                </label>
                <select name="role" class="select2 form-control w-100" id="staff-role-select">
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">{{ str_replace('-', ' ', $role->name) }}</option>
                    @endforeach
                </select>
            </div>

            @can('staff-branch-assign')
                <div class="input-area" id="branch-assignment-section" style="display: none;">
                    <label class="form-label" for="">
                        <span class="shift-Away inline-flex items-center gap-1"
                            data-tippy-content="Assign a branch to this staff member">
                            {{ __('Assign Branch') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                        </span>
                    </label>
                    <select name="branch_id" class="select2 form-control w-100">
                        <option value="">{{ __('Select Branch') }}</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }} ({{ $branch->code }})</option>
                        @endforeach
                    </select>
                    <small
                        class="text-slate-500">{{ __('Select a branch for this staff member. Leave empty for no branch restrictions.') }}</small>
                </div>
            @endcan

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
                        <span class="shift-Away inline-flex items-center gap-1"
                            data-tippy-content="The email of the staff">
                            {{ __('Email') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline"
                                class="text-[16px]"></iconify-icon>
                            <span class="text-xs text-danger">*</span>
                        </span>
                    </label>
                    <input type="email" name="email" class="form-control mb-0" placeholder="Staff Email" />
                </div>
                <div class="input-area">
                    <label for="" class="form-label">
                        <span class="shift-Away inline-flex items-center gap-1"
                            data-tippy-content="The password of the staff">
                            {{ __('Password') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline"
                                class="text-[16px]"></iconify-icon>
                            <span class="text-xs text-danger">*</span>
                        </span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" class="form-control mb-0" placeholder="Password" />
                        <button type="button" class="toggle-password absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center" data-toggle="password">
                            <iconify-icon class="text-lg" icon="heroicons:eye"></iconify-icon>
                        </button>
                    </div>
                </div>
                <div class="input-area">
                    <label for="" class="form-label">
                        <span class="shift-Away inline-flex items-center gap-1"
                            data-tippy-content="The confirm password of the staff">
                            {{ __('Confirm Password') }}
                            <iconify-icon icon="mdi:information-slab-circle-outline"
                                class="text-[16px]"></iconify-icon>
                            <span class="text-xs text-danger">*</span>
                        </span>
                    </label>
                    <div class="relative">
                        <input type="password" name="confirm-password" id="confirm-password" class="form-control mb-0" placeholder="Confirm Password" />
                        <button type="button" class="toggle-password absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center" data-toggle="confirm-password">
                            <iconify-icon class="text-lg" icon="heroicons:eye"></iconify-icon>
                        </button>
                    </div>
                </div>
                <div class="input-area lg:col-span-3">
                    <div class="flex items-center space-x-7 flex-wrap">
                        <label class="form-label !w-auto" for="">
                            <span class="shift-Away inline-flex items-center gap-1"
                                data-tippy-content="The status of the staff">
                                {{ __('Status') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline"
                                    class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <div class="form-switch ps-0">
                            <input type="hidden" value="0" name="status">
                            <label
                                class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                <input type="checkbox" name="status" value="1" class="sr-only peer">
                                <span
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="action-btns text-right mt-10">
                <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                    {{ __('Add Staff') }}
                </button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        $(document).ready(function() {
            // Handle role selection change
            $('#staff-role-select').on('change', function() {
                const selectedRole = $(this).val();
                const branchSection = $('#branch-assignment-section');

                if (selectedRole === 'Super-Admin') {
                    branchSection.hide();
                } else {
                    branchSection.show();
                }
            });

            // Initial check on page load
            $('#staff-role-select').trigger('change');
        });
    </script>
@endpush
