@extends('backend.layouts.app')
@section('title')
    {{ __('Update Department') }}
@endsection
@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between flex-wrap items-center mb-6">
            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                {{ __('Update Department') }}
            </h4>
            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                <a href="{{ route('admin.departments.index') }}" class="btn btn-primary inline-flex items-center justify-center">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                    {{ __('Back') }}
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-6">
                <form action="{{ route('admin.departments.update',$department->id) }}" method="post" class="space-y-4">
                @method('put')
                @csrf
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Name:') }}</label>
                        <input type="text" name="name" value="{{ old('name', $department->name) }}" class="form-control" placeholder="Department Name" required/>
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Parent:') }}</label>
                        <select name="parent_id" class="form-control">
                            <option value="">This is Parent</option>
                            @foreach($departments as $dept)
                            <option value="{{$dept->id}}" {{ $department->parent_id == $dept->id ? 'selected' : '' }}>{{$dept->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Department Email:') }}</label>
                        <input type="text" name="department_email" value="{{ old('department_email', $department->department_email) }}" class="form-control" placeholder="Department Email" required/>
                    </div>
                    <div class="input-area">
                        <div class="flex items-center space-x-7 flex-wrap">
                            <label class="form-label !w-auto !mb-0">
                                {{ __('Hide From Client:') }}
                            </label>
                            <div class="form-switch ps-0" style="line-height: 0">
                                <input type="hidden" value="0" name="hide_from_client">
                                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                    <input type="checkbox" name="hide_from_client" value="1" class="sr-only peer" {{ old('hide_from_client', $department->hide_from_client) == 1 ? 'checked' : '' }}>
                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                </label>
                            </div>
                        </div>
                    </div>


                    <div class="input-area text-right">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


