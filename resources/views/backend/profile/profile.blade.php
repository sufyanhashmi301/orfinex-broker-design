@extends('backend.layouts.app')
@section('title')
    {{ __('Details of Admin') }}
@endsection
@section('content')
    <?php
        $staff = Auth::user();
    ?>
    <div class="grid grid-cols-12 gap-6">
        <div class="2xl:col-span-3 lg:col-span-4 col-span-12">
            <div class="profiel-wrap px-[35px] pb-10 pt-10 rounded-lg bg-white dark:bg-slate-800 lg:space-y-0 space-y-6 relative z-[1]">
                <div class="customer-profile-cover absolute left-0 top-0 h-[115px] w-full z-[-1] rounded-t-lg" style="background-image: url('https://cdn.brokeret.com/crm-assets/staff-image/h1.png')">
                </div>
                <div class="profile-box">
                    <div class="h-[140px] w-[140px] ml-auto mr-auto mb-4 rounded-full ring-4 ring-slate-100 relative bg-slate-300 dark:bg-slate-900 dark:text-white text-slate-900 flex flex-col items-center justify-center">
                        <span class="text-4xl">{{ $staff->first_name  . $staff->last_name }}</span>

                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-medium text-slate-900 dark:text-slate-200 mb-[3px]">
                            {{$staff->first_name .' '. $staff->last_name}}
                        </div>
                        <div class="text-sm font-light text-slate-600 dark:text-slate-400">
                            <span class="font-medium">{{ __('Employee ID: ') }}</span>
                            <span>{{ $staff->employee_id }}</span>
                        </div>
                        <div class="text-sm font-light text-slate-600 dark:text-slate-400 mt-3">
                            <span class="font-medium">
                                {{ __('Member since: ') }}
                            </span>
                            {{ $staff->date_of_joining }}
                        </div>
                    </div>
                    <ul class="space-y-3 my-5">
                        <li class="flex justify-between text-xs text-slate-600 dark:text-slate-300">
                            <span>{{ __('Department: ') }}</span>
                            @if(isset($staff->department))
                                <span>{{ $staff->department->name }}</span>
                            @else
                                <span>-</span>
                            @endif
                        </li>
                        <li class="flex justify-between text-xs text-slate-600 dark:text-slate-300">
                            <span>{{ __('Designation: ') }}</span>
                            @if(isset($staff->designation))
                                <span>{{ $staff->designation->name }}</span>
                            @else
                                <span>-</span>
                            @endif
                        </li>
                        <li class="flex justify-between text-xs text-slate-600 dark:text-slate-300">
                            <span>{{ __('Location: ') }}</span>
                            <span>{{ $staff->location }}</span>
                        </li>
                    </ul>
                    <ul class="nav nav-pills border-t border-slate-100 dark:border-slate-700 pt-4 space-y-3">
                        <li class="nav-item">
                            <a href="javascript:;" class="nav-link block flex items-center font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 active">
                                <iconify-icon class="mr-2" icon="heroicons-outline:home"></iconify-icon>
                                {{ __('Overview') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.password-change') }}" class="nav-link block flex items-center font-medium font-Inter text-sm text-center leading-tight capitalize rounded-md px-4 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300">
                                <iconify-icon class="mr-2" icon="lucide:lock"></iconify-icon>
                                {{ __('Change Password') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="2xl:col-span-9 lg:col-span-8 col-span-12">
            <form action="{{ route('admin.profile-update') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card mb-5">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('Basic Information') }}</h4>
                    </div>
                    <div class="card-body p-6">
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
                                    readonly
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
                                    readonly
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
                                    readonly
                                />
                            </div>
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
                                    readonly
                                />
                            </div>
                            
                        </div>
                    </div>
                </div>

                <div class="card mb-5">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('Work Information') }}</h4>
                    </div>
                    <div class="card-body p-6">
                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5">
                            <div class="input-area">
                                <label class="form-label" for="">
                                    {{ __('Employment Type:') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $staff->employment_type }}"
                                    placeholder=""
                                    readonly
                                >
                            </div>

                            <div class="input-area">
                                <label class="form-label" for="">
                                    {{ __('Employment Status:') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $staff->employment_status }}"
                                    placeholder=""
                                    readonly
                                >
                            </div>

                            <div class="input-area">
                                <label class="form-label" for="">
                                    {{ __('Source Of Hire:') }}
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $staff->source_of_hire }}"
                                    placeholder=""
                                    readonly
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-5">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('Personal Details') }}</h4>
                    </div>
                    <div class="card-body p-6">
                        <div class="grid lg:grid-cols-2 grid-cols-1 gap-5">
                            <div class="input-area">
                                <label for="" class="form-label">{{ __('Date Of Birth:') }}</label>
                                <input
                                    type="text"
                                    name="date_of_birth"
                                    class="form-control dateOfBirth"
                                    value="{{ $staff->date_of_birth }}"
                                    placeholder="2006-12-19"
                                    readonly
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
    </div>
@endsection

