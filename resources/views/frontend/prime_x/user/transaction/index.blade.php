@php use App\Enums\TxnStatus; use App\Enums\TxnType; @endphp

@extends('frontend::layouts.user')
@section('title')
    {{ __('Schema Logs') }}
@endsection
@section('content')
    <div class="space-y-5">
        <div class="card desktop-screen-show md:block hidden">
            <div class="card-body p-6 pb-0">
                @if(count($transactions) == 0)
                <div class="flex items-center justify-center flex-col">
                    <p class="text-lg text-slate-600 dark:text-slate-100 mb-3">
                        {{ __("You don't have any transactions yet.") }}
                    </p>
                    <a href="{{ route('user.deposit.amount') }}" class="btn btn-dark inline-flex items-center justify-center min-w-[170px]">
                        {{ __('Deposit Now') }}
                    </a>
                </div>
                @else
                <div class="innerMenu grid xl:grid-cols-2 grid-cols-1 gap-5 mb-6">
                    <div class="filter">
                        <form action="{{ route('user.transactions') }}" method="get">
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
                                            <th scope="col" class="table-th">{{ __('Account') }}</th>
                                            <th scope="col" class="table-th">{{ __('Amount') }}</th>
                                            <th scope="col" class="table-th">{{ __('Gateway') }}</th>
                                            <th scope="col" class="table-th">{{ __('Fee') }}</th>
                                            <th scope="col" class="table-th">{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                        @foreach($transactions as $transaction)
                                        <tr>
                                            <td class="table-td">
                                                <div class="flex items-center">
                                                    <div class="flex-none">
                                                        <div class="w-10 h-10 lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mr-2">
                                                            @switch($transaction->type->value)
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
                                                            {{ $transaction->description }} @if(!in_array($transaction->approval_cause,['none',""]))
                                                                <span class="toolTip onTop optional-msg" data-tippy-content="{{ $transaction->approval_cause }}">
                                                                    <iconify-icon icon="lucide:mail"></iconify-icon>
                                                                </span>
                                                            @endif
                                                        </h4>
                                                        <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                                            {{ $transaction->created_at }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="table-td">
                                                {{ $transaction->tnx }}
                                            </td>
                                            <td class="table-td">
                                                {{ $transaction->target_id }}
                                            </td>
                                            <td class="table-td">
                                                <strong class="{{in_array($transaction->type,[TxnType::Subtract,TxnType::Investment,TxnType::SendMoney,TxnType::Withdraw,TxnType::WithdrawAuto,TxnType::SendMoneyInternal]) ?  'text-danger' : 'text-success'}}">
                                                    {{ (in_array($transaction->type,[TxnType::Subtract,TxnType::Investment,TxnType::SendMoney,TxnType::Withdraw,TxnType::WithdrawAuto,TxnType::SendMoneyInternal,TxnType::BonusSubtract]) ? '-': '+' ).$transaction->amount.' '.$transaction->currency }}
                                                </strong>
                                            </td>
                                            <td class="table-td">
                                                {{ transaction_method_name($transaction) }}
                                            </td>
                                            <td class="table-td">
                                                {{ $transaction->charge }} {{ $currency }}
                                            </td>
                                            <td class="table-td">
                                                @switch($transaction->status->value)
                                                    @case('pending')
                                                    <span class="badge-warning bg-opacity-30 capitalize rounded px-2 py-1">{{ __('Pending') }}</span>
                                                    @break
                                                    @case('success')
                                                    <span class="badge-success bg-opacity-30 capitalize rounded px-2 py-1">{{ __('Success') }}</span>
                                                    @break
                                                    @case('failed')
                                                    <span class="badge-danger bg-opacity-30 capitalize rounded px-2 py-1">{{ __('Canceled') }}</span>
                                                    @break
                                                @endswitch
                                            </td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-3 mt-auto">
                                    <div>
                                        @php
                                            $from = $transactions->firstItem(); // The starting item number on the current page
                                            $to = $transactions->lastItem(); // The ending item number on the current page
                                            $total = $transactions->total(); // The total number of items
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
                                    {{ $transactions->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="md:hidden block mobile-screen-show">
        <!-- Transactions -->
        <div class="card all-feature-mobile mobile-transactions mb-3">
            <div class="card-header">
                <h4 class="card-title">{{ __('All Transactions') }}</h4>
            </div>
            <div class="card-body p-3 mobile-transaction-filter">
                <div class="filter mb-3">
                    <form action="{{ route('user.transactions') }}" method="get">
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
                    @foreach($transactions as $transaction )
                        <div class="single-transaction flex justify-between text-xs bg-slate-100 dark:bg-slate-900 rounded-md p-2 py-3">
                            <div class="transaction-left w-3/4">
                                <div class="transaction-des">
                                    <div class="transaction-title mb-1 dark:text-white">{{ $transaction->description }}</div>
                                    <div class="transaction-id mb-1 dark:text-white">{{ $transaction->tnx }}</div>
                                    <div class="transaction-date mb-1 dark:text-white">{{ $transaction->created_at }}</div>
                                </div>
                            </div>
                            <div class="transaction-right text-right">
                                <div class="transaction-amount {{ txn_type($transaction->type->value,['add','sub']) }} mb-1 dark:text-white">
                                    {{ txn_type($transaction->type->value,['+','-']) }}{{ $transaction->amount . ' ' . $currency }}</div>
                                <div class="transaction-fee sub mb-1 dark:text-white">
                                    -{{ $transaction->charge . ' ' . $currency . ' ' . __('Fee') }} </div>
                                <div class="transaction-gateway mb-1 dark:text-white">{{ $transaction->method }}</div>

                                @if($transaction->status->value == App\Enums\TxnStatus::Pending->value)
                                    <div class="transaction-status text-warning">{{ __('Pending') }}</div>
                                @elseif($transaction->status->value == App\Enums\TxnStatus::Success->value)
                                    <div class="transaction-status text-success">{{ __('Success') }}</div>
                                @elseif($transaction->status->value == App\Enums\TxnStatus::Failed->value)
                                    <div class="transaction-status text-danger">{{ __('Canceled') }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $transactions->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
@endsection
