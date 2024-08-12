@extends('backend.layouts.app')
@section('title')
    {{ __('Create New Staff') }}
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
            <form action="{{ route('admin.staff.store') }}" method="post" id="modalForm">
                @csrf
                <div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
                    <div class="input-area !mt-0">
                        <label for="" class="form-label">{{ __('Name:') }}</label>
                        <input
                            type="text"
                            name="name"
                            class="form-control mb-0"
                            placeholder="Staff Name"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Email:') }}</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control mb-0"
                            placeholder="Staff Email"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Password:') }}</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control mb-0"
                            placeholder="Password"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Confirm Password:') }}</label>
                        <input
                            type="password"
                            name="confirm-password"
                            class="form-control mb-0"
                            placeholder="Confirm Password"
                            required
                        />
                    </div>

                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Select Role:') }}</label>
                        <select name="role" class="select2 form-control w-100">
                            @foreach($roles as $role)
                                <option value="{{$role->name}}">{{ str_replace('-', ' ', $role->name) }}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="department">{{ __('Select Department:') }}</label>
                        <select name="department" class="select2 form-control w-100" id="department">
                            <option value="">Select</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-area">
                        <label class="form-label" for="designation">{{ __('Select Designation:') }}</label>
                        <select name="designation" class="select2 form-control w-100" id="designation">
                            <option value="">Select</option>
                            @foreach($designations as $designation)
                                <option value="{{ $designation->id }}">
                                    {{ $designation->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Status') }}</label>
                        <select name="status" class="select2 form-control w-full">
                            <option value="1">{{ __('Active') }}</option>
                            <option value="0">{{ __('Inactive') }}</option>
                        </select>
                    </div>
                </div>

                <div class="action-btns mt-10">
                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                        {{ __('Add Staff') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
