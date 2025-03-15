@extends('backend.setting.user_management.index')
@section('title')
    {{ __('Create Group') }}
@endsection
@section('user-management-content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Add New Group') }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary inline-flex items-center justify-center">
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
                    <div class="flex items-center space-x-7 flex-wrap">
                        <label class="form-label !w-auto pt-0">
                            {{ __('Status:') }}
                        </label>
                        <div class="form-switch ps-0">
                            <input type="hidden" value="0" name="status">
                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                <input type="checkbox" name="status" value="1" class="sr-only peer">
                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="text-right py-5">
                    <button class="btn btn-dark inline-flex items-center justify-center" type="submit">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                        {{ __('Add New Group') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
