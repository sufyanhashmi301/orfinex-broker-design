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
                    <input type="hidden" name="hide_from_client" value="0">
                    <input type="checkbox" name="hide_from_client" value="1" {{ old('hide_from_client', $department->hide_from_client) == 1 ? 'checked' : '' }}>
                        <label class="form-label" for="">{{ __('Hide From Client:') }}</label>
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


