@extends('backend.deposit.index')
@section('title')
    {{ __(ucwords($type).' Deposit Method') }}
@endsection
@section('page-title')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            @isset($button)
                <a href="{{$button['route']}}" class="inline-flex items-center justify-center text-success-600" type="button">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:{{$button['icon']}}"></iconify-icon>
                    {{$button['name']}}
                </a>
            @endisset
        </div>
    </div>
@endsection
@section('deposit_content')
    <div class="card">
        <div class="card-body p-6 space-y-4">
            <p class="paragraph text-xs">
                {{ __(' All the ') }}
                <strong>{{ __('Deposit Payment Methods') }}</strong> {{ __('Setup for user') }}
            </p>
            @foreach($depositMethods as $method)
                <div class="single-gateway flex items-center justify-between border rounded py-3 px-4">
                    <div class="gateway-name flex items-center gap-2">
                        <div class="gateway-icon relative mr-4">
                            <img class="h-7" src="{{ asset($method->logo ?? $method->gateway->logo) }}"
                                alt=""
                            />
                            <span class="icon-currency-type bg-slate-900 text-white rounded-full font-Inter text-xs py-0.5 px-2 absolute -top-[14px] -right-[15px]">
                                {{ $method->currency }}
                            </span>
                        </div>
                        <div class="gateway-title">
                            <h4 class="text-sm">{{$method->name}}</h4>
                            <p class="text-xs">{{ __('Minimum Deposit: ').$method->minimum_deposit .' '. $currency }}</p>
                        </div>
                    </div>
                    <div class="gateway-right flex items-center gap-2">
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
                        <div class="gateway-edit">
                            <a href="{{ route('admin.deposit.method.edit',['type' => strtolower($type),'id' => $method->id]) }}" class="action-btn">
                                <iconify-icon icon="lucide:settings-2"></iconify-icon>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

    </div>
@endsection
