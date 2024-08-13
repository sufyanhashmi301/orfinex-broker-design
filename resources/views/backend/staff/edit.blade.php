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

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('Account Details') }}</h4>
        </div>
        <div class="card-body p-6">
            <form action="{{ route('admin.staff.update',$staff->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
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
                        <label class="form-label" for="">{{ __('Select Role:') }}</label>
                        <select name="role" class="form-control w-100" id="role">
                            @foreach($roles as $role)
                                <option
                                    @selected($role->name == $staff->roles[0]['name']) value="{{$role->name}}">{{ str_replace('-', ' ', $role->name) }}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="department">{{ __('Select Department:') }}</label>
                        <select name="department" class="form-control w-100" id="department">
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
                        <select name="designation" class="form-control w-100" id="designation">
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
                        <label for="" class="form-label invisible">{{ __('Status') }}</label>
                        <div class="flex items-center space-x-7 flex-wrap">
                            <label class="form-label !w-auto pt-0 !mb-0">
                                {{ __('Status:') }}
                            </label>
                            <div class="form-switch ps-0" style="line-height:0;">
                                <input class="form-check-input" type="hidden" value="0" name="status">
                                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                    <input type="checkbox" name="status" value="1" class="sr-only peer" @checked($staff->status)>
                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="action-btns mt-10">
                    <button type="submit" href="" class="btn btn-dark inline-flex items-center justify-center mr-2">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
