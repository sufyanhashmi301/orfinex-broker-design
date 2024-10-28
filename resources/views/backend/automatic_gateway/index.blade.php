@extends('backend.setting.integrations.index')
@section('title')
    {{ __('Automatic Payment Gateway') }}
@endsection
@section('integrations-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Automatic Payment Gateway') }}
        </h4>
    </div>
    <div class="grid xl:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
        @foreach($gateways as $gateway)
            <div class="card border hover:shadow-lg">
                <div class="card-header items-center noborder !p-4">
                    <img class="inline-block h-10" src="{{ $gateway->logo }}" alt="{{ $gateway->name }}"/>
                    <button class="action-btn" type="button" data-bs-toggle="modal" data-bs-target="#manage-{{$gateway->id}}">
                        <iconify-icon icon="lucide:settings-2"></iconify-icon>
                    </button>
                </div>
                <div class="card-body p-4 pt-2">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-base font-medium dark:text-white mr-1">{{ $gateway->name }}</h4>
                        @if($gateway->status == 1)
                            <span class="badge-success text-xs text-success capitalize rounded bg-opacity-30 px-2 py-1">
                                {{ __('Active') }}
                            </span>
                        @else
                            <span class="badge-danger text-xs text-danger capitalize rounded bg-opacity-30 px-2 py-1">
                                {{ __('Deactive') }}
                            </span>
                        @endif
                    </div>
                    <ul class="space-y-3">
                        <li class="flex items-center justify-between text-sm">
                            <span class="text-slate-400 mr-1">{{ __('Withdraw Available') }}</span>
                            <span class="capitalize">
                                @if($gateway->is_withdraw != 0)
                                    {{ __('Yes') }}
                                @else
                                    {{ __('No') }}
                                @endif
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm">
                            <span class="text-slate-400 mr-1">{{ __('Supported Currency') }}</span>
                            <span>{{ count(json_decode($gateway->supported_currencies,true)) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <!--  Manage Modal -->
            @include('backend.automatic_gateway.include.__manage')
            <!-- Manage Modal End-->
        @endforeach
    </div>
@endsection
