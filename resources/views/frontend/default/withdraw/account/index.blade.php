@extends('frontend::layouts.user')
@section('title')
    {{ __('Withdraw Account') }}
@endsection
@section('content')
    <div class="mb-5">
        <ul class="m-0 p-0 list-none">
            <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter ">
                <a href="{{route('user.dashboard')}}">
                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                </a>
            </li>
            <li class="inline-block relative text-sm text-primary-500 font-Inter ">
                {{ __('Dashboard') }}
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ __('Withdraw Accounts') }}
            </li>
        </ul>
    </div>
    <div class="grid grid-cols-12 gap-5">
        <div class="col-span-12">
            @if(count($accounts) == 0)
            <div class="max-w-xl text-center py-10 mx-auto space-y-5">
                <div class="w-20 h-20 bg-danger-500 text-white rounded-full inline-flex items-center justify-center">
                    <iconify-icon icon="fa6-solid:box-open" class="text-5xl"></iconify-icon>
                </div>
                <h4 class="text-3xl text-slate-900 dark:text-white">
                    {{ __("You're almost ready to withdraw!") }}
                </h4>
                <p class="text-slate-600 dark:text-slate-100">{{ __('To make a withdraw, please add a withdraw account from your profile (withdraw accounts).') }}</p>
                <a href="{{ route('user.withdraw.account.create') }}" class="btn btn-dark inline-flex items-center justify-center">
                    Add Withdraw Account
                </a>
            </div>
            @else
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('Withdraw Accounts') }}</h4>
                    <div>
                        <a href="{{ route('user.withdraw.account.create') }}" class="btn btn-dark">
                            {{ __('Add New') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="divide-y divide-slate-100 dark:divide-slate-700 -mb-6 h-full todo-list">
                        @foreach($accounts as $account)
                        <li class="flex items-center px-6 space-x-4 py-6 hover:-translate-y-1 hover:shadow-todo transition-all duration-200 rtl:space-x-reverse">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <div class="flex-none">
                                        <div class="w-10 h-10 lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mr-2">
                                            <iconify-icon icon="lucide:backpack"></iconify-icon>
                                        </div>
                                    </div>
                                    <div class="flex-1 text-start">
                                        <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                            {{$account->method_name}}
                                        </h4>
                                        <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                            {{ $account->method->currency .' '. __('Account') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex">
                                <span class="flex-none space-x-2 text-base text-secondary-500 flex rtl:space-x-reverse">
                                    <a href="{{ route('user.withdraw.account.edit',the_hash($account->id)) }}" class="action-btn">
                                        <iconify-icon icon="heroicons-outline:pencil-alt"></iconify-icon>
                                    </a>
                                </span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
