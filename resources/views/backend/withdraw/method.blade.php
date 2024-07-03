@extends('backend.withdraw.index')
@section('title')
    {{ __('Withdraw Methods') }}
@endsection
@section('withdraw_content')
    <div class="card">
        <div class="card-header">
            <h2 class="card-title"> @yield('title')</h2>
            @isset($button)
                <a href="{{$button['route']}}" class="btn btn-dark btn-sm inline-flex items-center justify-center" type="button">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:{{$button['icon']}}"></iconify-icon>
                    {{$button['name']}}
                </a>
            @endisset
        </div>
        <div class="card-body p-6 space-y-4">
            <p class="paragraph text-xs">
                {{ __(' All the ') }} <strong>{{ __('Withdraw Methods') }}</strong> {{ __('setup for user') }}
            </p>
            @foreach( $withdrawMethods as $method)
                @php
                    $icon = $method->icon;
                    if (null != $method->gateway_id && $method->icon == ''){
                        $icon = $method->gateway->logo;
                    }
                @endphp

                <div class="single-gateway flex items-center justify-between border rounded py-3 px-4">
                    <div class="gateway-name flex items-center gap-2">
                        <div class="gateway-icon relative mr-4">
                            <img
                                class="h-7"
                                src="{{ asset($icon) }}"
                                alt=""
                            />
                            <span class="icon-currency-type bg-slate-900 text-white rounded-full font-Inter text-xs py-0.5 px-2 absolute -top-[14px] -right-[15px]">
                                {{ $method->currency }}
                            </span>
                        </div>
                        <div class="gateway-title">
                            <h4 class="text-sm">{{ $method->name }}</h4>
                            <p class="text-xs">{{ __('Minimum Withdraw: ').$method->min_withdraw .' '.$currency }}</p>
                        </div>
                    </div>
                    <div class="gateway-right flex items-center gap-2">
                        <div class="gateway-status">
                            @if( $method->status)
                                <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
                                    {{ __('Activated') }}
                                </div>
                            @else
                                <div class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">
                                    {{ __('DeActivated') }}
                                </div>
                            @endif
                        </div>
                        <div class="gateway-edit">
                            <a href="{{ route('admin.withdraw.method.edit',['type' => strtolower($type),'id' => $method->id]) }}" class="action-btn">
                                <iconify-icon icon="lucide:settings-2"></iconify-icon>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
