@extends('backend.setting.index')
@section('title')
    {{ __('Create Group') }}
@endsection
@section('setting-content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Add New Group') }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ url()->previous() }}" class="inline-flex items-center justify-center text-success-500">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                {{ __('Back') }}
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <form action="{{route('admin.customer-groups.store')}}" method="post" class="space-y-4">
                @csrf
                <div class="input-area">
                    <label for="" class="form-label">{{ __('Group Name') }}</label>
                    <input type="text" class="form-control" required="" name="name" placeholder="Group Name"/>
                </div>
                <div class="input-area">
                    <label class="form-label" for="">{{ __('Status:') }}</label>
                    <div class="switch-field flex mb-3 overflow-hidden">
                        <input
                            type="radio"
                            id="active-status-2"
                            name="status"
                            checked
                            value="1"
                        />
                        <label for="active-status-2">{{ __('Active') }}</label>
                        <input
                            type="radio"
                            id="deactivate-status-2"
                            name="status"
                            value="0"
                           
                        />
                        <label for="deactivate-status-2">{{ __('Deactivate') }}</label>
                    </div>
                </div>
                <div class="text-right p-5">
                    <button class="btn btn-dark inline-flex items-center justify-center" type="submit">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                        Add New Group
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
