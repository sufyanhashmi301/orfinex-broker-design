@extends('backend.setting.payment.withdraw.index')
@section('title')
    {{ __('Withdraw Methods') }}
@endsection
@section('page-title')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            @isset($button)
                <a href="{{$button['route']}}" class="btn btn-primary inline-flex items-center justify-center" type="button">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:{{$button['icon']}}"></iconify-icon>
                    {{$button['name']}}
                </a>
            @endisset
        </div>
    </div>
@endsection
@section('withdraw-content')
    <div class="grid xl:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
        @foreach( $withdrawMethods as $method)
            @php
                $icon = $method->icon;
                if (null != $method->gateway_id && $method->icon == ''){
                    $icon = $method->gateway->logo;
                }
            @endphp
            <div class="card lg:h-full border dark:border-slate-700 trading-account-card">
                <div class="card-body rounded-md bg-white dark:bg-dark p-6">
                    <div class="grid-view-layout">
                        <div class="flex justify-between items-center mb-4">
                            <img class="inline-block h-10" src="{{ isset($method->gateway_id) ? $method->gateway->logo : asset($icon) }}" alt=""/>
                            <div class="dropdown relative">
                                <button class="text-xl text-center block w-full " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="text-lg inline-flex h-6 w-6 flex-col items-center justify-center border border-slate-200 dark:border-slate-700 rounded dark:text-slate-400">
                                        <iconify-icon icon="heroicons-outline:dots-vertical"></iconify-icon>
                                    </span>
                                </button>
                                <ul class="dropdown-menu min-w-[120px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none lrt:origin-top-right ">
                                    <li>
                                        <a href="{{ route('admin.withdraw.method.edit',['type' => strtolower($type),'id' => $method->id]) }}" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                            <iconify-icon icon="lucide:edit" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                            {{ __('Upadte') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                            <iconify-icon icon="lucide:trash" class="relative top-[2px] text-lg ltr:mr-1 rtl:ml-1"></iconify-icon>
                                            {{ __('Delete') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <h4 class="text-base font-medium dark:text-white">{{$method->name}}</h4>
                        <ul class="divide-y divide-slate-100 dark:divide-slate-700 h-full">
                            <li class="flex items-center py-3">
                                <span class="flex-1 text-sm text-slate-600 dark:text-slate-300">{{ __('Minimum Withdraw:') }}</span>
                                <span class="flex-1 text-right text-slate-600 dark:text-slate-300">{{ $method->min_withdraw .' '.$currency }}</span>
                            </li>
                        </ul>
                        <div class="gateway-status">
                            @if($method->status)
                                <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
                                    {{ __('Activated') }}
                                </div>
                            @else
                                <div class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">
                                    {{ __('Deactivated') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
