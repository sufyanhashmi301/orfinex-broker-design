@extends('frontend::layouts.user')
@section('title')
    {{ __('Send Money Logs') }}
@endsection
@php use App\Enums\TxnStatus; use App\Enums\TxnType; @endphp
@section('content')
    <div class="space-y-5">
    <div class="card desktop-screen-show md:block hidden">
        <div class="card-body p-6 pb-0">
            @if(count($sendMoneys) == 0)
                <div class="flex items-center justify-center flex-col">
                    <p class="text-lg text-slate-600 dark:text-slate-100 mb-3">
                        {{ __("You don't have any transaction yet.") }}
                    </p>
                    <a href="{{ route('user.send-money.view') }}" class="btn btn-dark loaderBtn inline-flex items-center justify-center min-w-[170px]">
                        {{ __('Send Now') }}
                    </a>
                </div>
            @else
                <div class="innerMenu grid xl:grid-cols-2 grid-cols-1 gap-5 mb-6">
                    <div class="filter">
                        <form action="{{ route('user.send-money.log') }}" method="get">
                            <div class="search flex gap-3 items-center">
                                <input type="text" class="form-control" id="search" placeholder="{{ __('Search') }}"
                                    value="{{ request('query') }}"
                                    name="query"/>
                                <input type="date" class="form-control flatpickr flatpickr-input active" data-mode="range" name="date" value="{{ request()->get('date') }}"/>
                                <button type="submit" class="btn btn-dark btn-sm">
                                    <i icon-name="search"></i>
                                    {{ __('Search') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="overflow-x-auto -mx-6">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden basicTable_wrapper">
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                <thead class="border-t border-slate-100 dark:border-slate-800">
                                    <tr>
                                        <th scope="col" class="table-th">{{ __('Description') }}</th>
                                        <th scope="col" class="table-th">{{ __('Transactions ID') }}</th>
                                        <th scope="col" class="table-th">{{ __('Amount') }}</th>
                                        <th scope="col" class="table-th">{{ __('Fee') }}</th>
                                        <th scope="col" class="table-th">{{ __('Status') }}</th>
                                        <th scope="col" class="table-th">{{ __('Method') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                    @foreach($sendMoneys as $raw)
                                    <tr>
                                        <td class="table-td">
                                            <div class="flex items-center">
                                                <div class="flex-none">
                                                    <div class="w-10 h-10 lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mr-2">
{{--                                                            <iconify-icon icon="fluent:arrow-reply-32-regular"></iconify-icon>--}}
                                                        @switch($raw->type->value)
                                                            @case('send_money')
                                                            <iconify-icon icon="ph:arrow-right-bold"></iconify-icon>
                                                            @break
                                                            @case('send_money_internal')
                                                            <iconify-icon icon="ph:arrow-right-bold"></iconify-icon>
                                                            @break
                                                            @case('receive_money')
                                                            <iconify-icon icon="ph:arrow-left-bold"></iconify-icon>
                                                            @break
                                                            @case('send_money_internal')
                                                            <iconify-icon icon="ph:arrow-left-bold"></iconify-icon>
                                                            @break
                                                            @case('deposit')
                                                            <iconify-icon icon="octicon:download-16"></iconify-icon>
                                                            @break
                                                            @case('manual_deposit')
                                                            <iconify-icon icon="octicon:download-16"></iconify-icon>
                                                            @break
                                                            @case('investment')
                                                            <iconify-icon icon="fluent:arrow-swap-24-regular"></iconify-icon>
                                                            @break
                                                            @case('withdraw')
                                                            <iconify-icon icon="akar-icons:arrow-back"></iconify-icon>
                                                            @break
                                                            @default()
                                                            <iconify-icon icon="lucide:backpack"></iconify-icon>
                                                        @endswitch
                                                    </div>
                                                </div>
                                                <div class="flex-1 text-start">
                                                    <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                        {{ $raw->description }} @if(!in_array($raw->approval_cause,['none',""]))
                                                            <span class="optional-msg" data-bs-toggle="tooltip" title="" data-bs-original-title="{{ $raw->approval_cause }}">
                                                                <i icon-name="mail"></i>
                                                            </span>
                                                        @endif
                                                    </h4>
                                                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                                        {{ $raw->created_at }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="table-td">
                                            {{ $raw->tnx }}
                                        </td>
                                        <td class="table-td">
                                            <span class="font-medium">
{{--                                                    -{{$raw->amount.' '.$currency }}--}}
                                      <strong class="{{in_array($raw->type,[TxnType::Subtract,TxnType::Investment,TxnType::SendMoney,TxnType::Withdraw,TxnType::WithdrawAuto,TxnType::SendMoneyInternal]) ?  'red-color' : 'green-color'}}">{{ (in_array($raw->type,[TxnType::Subtract,TxnType::Investment,TxnType::SendMoney,TxnType::Withdraw,TxnType::WithdrawAuto,TxnType::SendMoneyInternal]) ? '-': '+' ).$raw->amount.' '.$raw->currency }}</strong>

                                            </span>
                                        </td>
                                        <td class="table-td">
                                            <span class="font-medium">
                                      <strong class="{{in_array($raw->type,[TxnType::Subtract,TxnType::Investment,TxnType::SendMoney,TxnType::Withdraw,TxnType::WithdrawAuto,TxnType::SendMoneyInternal]) ?  'red-color' : 'green-color'}}">{{ $raw->charge.' '.$raw->currency }}</strong>
                                            </span>
                                        </td>
                                        <td class="table-td">
                                            <span class="block text-left">
                                                <span class="inline-block text-center mx-auto py-1">
                                                    <span class="flex items-center space-x-3 rtl:space-x-reverse">
                                                    @switch($raw->status->value)
                                                        @case('pending')
                                                            <span class="h-[6px] w-[6px] bg-warning-500 rounded-full inline-block ring-4 ring-opacity-30 ring-warning-500"></span>
                                                            <span>{{ __('Pending') }}</span>
                                                            @break
                                                        @case('success')
                                                            <span class="h-[6px] w-[6px] bg-success-500 rounded-full inline-block ring-4 ring-opacity-30 ring-success-500"></span>
                                                            <span>{{ __('Success') }}</span>
                                                            @break
                                                        @case('failed')
                                                            <span class="h-[6px] w-[6px] bg-danger-500 rounded-full inline-block ring-4 ring-opacity-30 ring-danger-500"></span>
                                                            <span>{{ __('canceled') }}</span>
                                                            @break
                                                    @endswitch
                                                    </span>
                                                </span>
                                            </span>
                                        </td>
                                        <td class="table-td">
                                            {{ ucfirst($raw->method) }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-3 mt-auto">
                                <div>
                                    @php
                                        $from = $sendMoneys->firstItem(); // The starting item number on the current page
                                        $to = $sendMoneys->lastItem(); // The ending item number on the current page
                                        $total = $sendMoneys->total(); // The total number of items
                                    @endphp

                                    <p class="text-sm text-gray-700 dark:text-slate-300 px-3">
                                        {{ __('Showing') }}
                                        <span class="font-medium">{{ $from }}</span>
                                        {{ __('to') }}
                                        <span class="font-medium">{{ $to }}</span>
                                        {{ __('of') }}
                                        <span class="font-medium">{{ $total }}</span>
                                        {{ __('results') }}
                                    </p>
                                </div>
                                {{  $sendMoneys->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<div class="mobile-screen-show  md:hidden block">
    <!-- Transactions -->
    <div class="card all-feature-mobile mobile-transactions mb-3">
        <div class="card-header">
            <h4 class="card-title">{{ __('All Send Money Log') }}</h4>
        </div>
        <div class="card-body p-3 mobile-transaction-filter">
            <div class="filter mb-3">
                <form action="{{ route('user.send-money.log') }}" method="get">
                    <div class="search flex items-center gap-2">
                        <input type="text" class="form-control" placeholder="{{ __('Search') }}" value="{{ request('query') }}" name="query"/>
                        <input type="date" class="form-control" name="date" value="{{ request()->get('date') }}"/>
                        <button type="submit" class="apply-btn h-10 btn btn-dark">
                            <iconify-icon icon="lucide:search"></iconify-icon>
                        </button>
                    </div>
                </form>
            </div>
            <div class="contents space-y-3">
                @foreach($sendMoneys as $raw )
                    <div class="single-transaction flex justify-between text-xs bg-slate-100 dark:bg-slate-900 rounded-md p-2 py-3">
                        <div class="transaction-left w-3/4">
                            <div class="transaction-des">
                                <div class="transaction-title mb-1 dark:text-white">{{ $raw->description }}</div>
                                <div class="transaction-id mb-1 dark:text-white">{{ $raw->tnx }}</div>
                                <div class="transaction-date mb-1 dark:text-white">{{ $raw->created_at }}</div>
                            </div>
                        </div>
                        <div class="transaction-right text-right">
                            <div class="transaction-amount sub mb-1 dark:text-white">- {{ $raw->amount .' '.$currency }}</div>
                            <div class="transaction-fee sub mb-1 dark:text-white">-{{  $raw->charge.' '. $currency .' '.__('Fee') }} </div>
                            <div class="transaction-gateway mb-1 dark:text-white">{{ $raw->method }}</div>

                            @if($raw->status->value == App\Enums\TxnStatus::Pending->value)
                                <div class="transaction-status text-warning-500">{{ __('Pending') }}</div>
                            @elseif($raw->status->value ==  App\Enums\TxnStatus::Success->value)
                                <div class="transaction-status text-success-500">{{ __('Success') }}</div>
                            @elseif($raw->status->value ==  App\Enums\TxnStatus::Failed->value)
                                <div class="transaction-status text-danger-500">{{ __('canceled') }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            {{  $sendMoneys->onEachSide(1)->links() }}
        </div>
    </div>
</div>
@endsection
