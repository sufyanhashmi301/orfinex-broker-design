@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Staff') }}
@endsection
@section('style')
    <style>
        @keyframes pulse{
            50%{
                opacity: .5;
            }
        }
    </style>
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Manage Staffs') }}
        </h4>
    </div>
    <div class="card border dark:border-slate-700">
        <div class="grid grid-cols-12">
            <div class="lg:col-span-4 col-span-12">
                <div class="h-full border-r dark:border-slate-700">
                    <div class="card-header pl-0" style="padding-bottom: 11px;">
                        <div class="input-area relative">
                            <select id="staffStatusFilter" class="form-control">
                                <option value="active">{{ __('Active Staff') }} ({{ $activeStaffCount }})</option>
                                <option value="inactive">{{ __('Inactive Staff') }} ({{ $inactiveStaffCount }})</option>
                            </select>
                        </div>
                        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                            @can('staff-create')
                                <a href="javascript:;" class="btn btn-sm btn-primary inline-flex items-center justify-center" id="create-staff">
                                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                                    {{ __('Add New Staff') }}
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div id="staff-list" class="p-6 pr-0">
                        @include('backend.staff.include.__staff_list', ['staff' => $staffs])
                    </div>
                </div>
            </div>
            <div class="lg:col-span-8 col-span-12">
                <div id="edit-staff-body">
                    <form action="{{ route('admin.staff.update',$loggedInUser->id) }}" method="post" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        @method('PUT')
                        <div class="card">
                            <div class="card-header noborder">
                                <div class="flex-none">
                                    <div class="w-20 h-20 rounded-[100%] ltr:mr-3 rtl:ml-3">
                                        <img src="{{ asset('frontend/images/avatar/av-4.svg') }}" alt="" class="w-full h-full rounded-[100%] object-cover">
                                    </div>
                                </div>
                                <div class="flex-1 text-start">
                                    <h4 class="text-lg font-medium text-slate-600 whitespace-nowrap">
                                        {{$loggedInUser->first_name}} {{$loggedInUser->last_name}}
                                        <span class="badge-primary text-xs capitalize rounded-lg px-2 py-0.5 ml-1">
                                            {{ $loggedInUser->getRoleNames()->first() }}
                                        </span>
                                    </h4>
                                    <div class="text-sm font-normal text-slate-500 dark:text-slate-400 my-1">
                                        @if(isset($loggedInUser->designation))
                                            {{ $loggedInUser->designation->name }}
                                        @else
                                            <span>-</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-5">
                                        <div class="inline-flex items-center text-sm font-normal text-slate-800 dark:text-slate-400">
                                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2 font-light" icon="heroicons-outline:mail"></iconify-icon>
                                            {{ $loggedInUser->email }}
                                        </div>
                                        <div class="inline-flex items-center text-sm font-normal text-slate-800 dark:text-slate-400">
                                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2 font-light" icon="heroicons-outline:phone"></iconify-icon>
                                            {{ $loggedInUser->phone }}
                                        </div>
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
                                                    value="{{ $loggedInUser->first_name }}"
                                                    placeholder="First Name"

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
                                                    value="{{ $loggedInUser->last_name }}"
                                                    placeholder="Last Name"

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
                                                    value="{{ $loggedInUser->name }}"
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
                                                    value="{{ $loggedInUser->employee_id }}"
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
                                                value="{{ $superAdmin->key ?? '' }}"
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
                                                <option value="{{ $department->id }}"
                                                        @if($loggedInUser->departments && $staff->departments->contains($department->id)) selected @endif>
                                                    {{ $department->name }}
                                                </option>
                                                @foreach($department->children as $child)
                                                    <option value="{{ $child->id }}"
                                                            @if($loggedInUser->departments && $staff->departments->contains($child->id)) selected @endif>
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
                                                <option value="{{ $designation->id }}"
                                                        @if($loggedInUser->designations && $staff->designations->contains($designation->id)) selected @endif>
                                                    {{ $designation->name }}
                                                </option>
                                                @foreach($designation->children as $child)
                                                    <option value="{{ $child->id }}"
                                                            @if($loggedInUser->designations && $staff->designations->contains($child->id)) selected @endif>
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
                                                <option @selected($role->name == $loggedInUser->roles[0]['name']) value="{{$role->name}}">
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
                                            <option value="permanent" @selected($loggedInUser->employment_type === 'permanent')>{{ __('Permanent') }}</option>
                                            <option value="on contract" @selected($loggedInUser->employment_type === 'on contract')>{{ __('On Contract') }}</option>
                                            <option value="temporary" @selected($loggedInUser->employment_type === 'temporary')>{{ __('Temporary') }}</option>
                                            <option value="trainee" @selected($loggedInUser->employment_type === 'trainee')>{{ __('Trainee') }}</option>
                                        </select>
                                    </div>

                                    <div class="input-area">
                                        <label class="form-label" for="">
                                            {{ __('Employment Status:') }}
                                        </label>
                                        <select name="employment_status" class="select2 form-control w-100">
                                            <option value="active" @selected($loggedInUser->employment_status === 'active')>{{ __('Active') }}</option>
                                            <option value="terminated" @selected($loggedInUser->employment_status === 'terminated')>{{ __('Terminated') }}</option>
                                            <option value="deceased" @selected($loggedInUser->employment_status === 'deceased')>{{ __('Deceased') }}</option>
                                            <option value="resigned" @selected($loggedInUser->employment_status === 'resigned')>{{ __('Resigned') }}</option>
                                            <option value="probation" @selected($loggedInUser->employment_status === 'probation')>{{ __('Probation') }}</option>
                                            <option value="notice period" @selected($loggedInUser->employment_status === 'notice period')>{{ __('Notice Period') }}</option>
                                        </select>
                                    </div>

                                    <div class="input-area">
                                        <label class="form-label" for="">
                                            {{ __('Source Of Hire:') }}
                                        </label>
                                        <select name="source_of_hire" class="select2 form-control w-100">
                                            <option value="direct" @selected($loggedInUser->source_of_hire === 'direct')>{{ __('Direct') }}</option>
                                            <option value="referral" @selected($loggedInUser->source_of_hire === 'referral')>{{ __('Referral') }}</option>
                                            <option value="web" @selected($loggedInUser->source_of_hire === 'web')>{{ __('Web') }}</option>
                                            <option value="newspaper" @selected($loggedInUser->source_of_hire === 'newspaper')>{{ __('Newspaper') }}</option>
                                        </select>
                                    </div>

                                    <div class="input-area">
                                        <label class="form-label" for="">
                                            {{ __('Location:') }}
                                        </label>
                                        <select name="location" class="select2 form-control w-100">
                                            @foreach( getCountries() as $country)
                                                <option @selected($country['name'] == $loggedInUser->location) value="{{$country['name']}}">
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
                                            value="{{ $loggedInUser->date_of_joining }}"
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
                                            value="{{ $loggedInUser->date_of_birth }}"
                                            placeholder="2006-12-19"
                                        >
                                    </div>
                                    <div class="input-area">
                                        <label for="" class="form-label">{{ __('Gender:') }}</label>
                                        <select name="status" class="select2 form-control w-full">
                                            <option value="male" @selected($loggedInUser->gender === 'male')>{{ __('Male') }}</option>
                                            <option value="female" @selected($loggedInUser->gender === 'female')>{{ __('Female') }}</option>
                                            <option value="other" @selected($loggedInUser->gender === 'other')>{{ __('Other') }}</option>
                                        </select>
                                    </div>
                                    <div class="input-area">
                                        <label for="" class="form-label">{{ __('Marital Status:') }}</label>
                                        <div class="flex items-center space-x-7 flex-wrap pt-2">
                                            <div class="basicRadio">
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="radio" class="hidden" name="marital_status" value="single" @if($loggedInUser->marital_status === 'single') checked @endif>
                                                    <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                                    <span class="text-secondary-500 text-sm leading-6 capitalize">{{ __('Single') }}</span>
                                                </label>
                                            </div>
                                            <div class="basicRadio">
                                                <label class="flex items-center cursor-pointer">
                                                    <input type="radio" class="hidden" name="marital_status" value="married" @if($loggedInUser->marital_status === 'married') checked @endif>
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
                                            value="{{ $loggedInUser->work_phone }}"
                                            placeholder=""
                                        >
                                    </div>
                                    <div class="input-area">
                                        <label for="" class="form-label">{{ __('Personal Phone Number:') }}</label>
                                        <input
                                            type="text"
                                            name="phone"
                                            class="form-control"
                                            value="{{ $loggedInUser->phone }}"
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
                                            value="{{ $loggedInUser->email }}"
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
                                                    <input type="checkbox" name="status" value="1" class="sr-only peer" @checked($loggedInUser->status)>
                                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="action-btns text-right mt-10">
                                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                        {{ __('Save Changes') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card hidden p-6" id="loader_placeholder">
                    @include('backend.staff.include.placeholder')
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Edit Staff -->
    @can('staff-edit')
        @include('backend.staff.modal.__edit_staff')
    @endcan
    <!-- Modal for Edit Staff-->

    <!-- Delete Confirmation Modal -->
    @include('backend.staff.include.__delete')



@endsection

@section('script')
    <script>

        $('body').on('click', '#create-staff', function (event) {
            "use strict";
            event.preventDefault();
            const createStaffRoute = "{{ route('admin.staff.create') }}";
            $('#edit-staff-body').empty();
            $('#loader_placeholder').removeClass('hidden');

            $.get(createStaffRoute, function (data) {
                $('#edit-staff-body').append(data);
                $('#loader_placeholder').addClass('hidden');
            });

        })

        $('#staffStatusFilter').change(function() {
            var status = $(this).val();

            $.ajax({
                url: "{{ route('admin.staff.index') }}",
                type: 'GET',
                data: { status: status },
                success: function(response) {
                    // Update the staff list
                    $('#staff-list').html(response.staffs);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching staff:', error);
                }
            });
        });

        $('body').on('click', '.edit-staff', function (event) {
            "use strict";
            event.preventDefault();
            $('#edit-staff-body').empty();
            $('#loader_placeholder').removeClass('hidden');
            var id = $(this).data('id');

            $.get('staff/' + id + '/edit', function (data) {
                $('#edit-staff-body').append(data);
                $('#loader_placeholder').addClass('hidden');
            });
        })

        $(document).ready(function () {
            let deleteSchemaId = null;

            // Event listener for delete buttons
            $('.delete-schema-btn').on('click', function (e) {
                e.preventDefault();
                deleteSchemaId = $(this).data('id');
                $('#deleteConfirmationModal').modal('show');
            });

            // Event listener for the confirm delete button in the modal
            $('#confirmDeleteButton').on('click', function () {
                const input = $('#deleteConfirmationInput').val();
                if (input.toLowerCase() === 'delete') {
                    // Create a form and submit it
                    const form = $('<form>', {
                        'method': 'POST',
                        'action': '{{ route('admin.staff.delete', ':id') }}'.replace(':id', deleteSchemaId)
                    });

                    // Add the CSRF token and method fields
                    const csrfToken = $('meta[name="csrf-token"]').attr('content');
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_token',
                        'value': csrfToken
                    }));

                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_method',
                        'value': 'DELETE'
                    }));
                    $('body').append(form);
                    form.submit();
                } else {
                    alert('You must type "delete" to confirm.');
                }
            });
        });

        $(".dateOfBirth").flatpickr({
            dateFormat: "d.m.Y",
            maxDate: "15.12.2017"
        });

    </script>
@endsection
