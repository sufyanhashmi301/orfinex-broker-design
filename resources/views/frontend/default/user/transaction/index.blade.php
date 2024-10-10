@php use App\Enums\TxnStatus; use App\Enums\TxnType; @endphp

@extends('frontend::layouts.user')
@section('title')
    {{ __('Schema Logs') }}
@endsection
@section('content')
    <div class="mb-5">
        <ul class="m-0 p-0 list-none">
            <li class="inline-block relative top-[3px] text-base text-primary font-Inter ">
                <a href="{{route('user.dashboard')}}">
                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                </a>
            </li>
            <li class="inline-block relative text-sm text-primary font-Inter ">
                {{ __('History') }}
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ __('All Transactions') }}
            </li>
        </ul>
    </div>
    <div class="space-y-5">
        <div class="card desktop-screen-show md:block hidden">
            <header class=" card-header noborder">
                <h4 class="card-title">
                    {{ __('All Transactions') }}
                </h4>
            </header>
            <div class="card-body px-6 pb-6">
                @if(count($transactions) == 0)
                    <div class="flex items-center justify-center flex-col">
                        <p class="text-lg text-slate-600 dark:text-slate-100 mb-3">
                            You don't have any transaction yet.
                        </p>
                        <a href="{{ route('user.deposit.amount') }}" class="btn btn-dark inline-flex items-center justify-center min-w-[170px]">
                            Deposit Now
                        </a>
                    </div>
                @else
                    <div class="grid xl:grid-cols-2 grid-cols-1 gap-5 mb-6">
                        <div class="filter">
                            <form action="{{ route('user.transactions') }}" method="get">
                                <div class="search flex gap-3 items-center">
                                    <input type="text" class="form-control" id="search" placeholder="Search"
                                        value="{{ request('query') }}"
                                        name="query"/>
                                    <input type="date" class="form-control" name="date" value="{{ request()->get('date') }}"/>
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
                            <div class="overflow-hidden ">
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                    <thead class=" border-t border-slate-100 dark:border-slate-800">
                                        <tr>
                                            <th scope="col" class="table-th">{{ __('Description') }}</th>
                                            <th scope="col" class="table-th">{{ __('Transactions ID') }}</th>
                                            <th scope="col" class="table-th">{{ __('Type') }}</th>
                                            <th scope="col" class="table-th">{{ __('Amount') }}</th>
                                            <th scope="col" class="table-th">{{ __('Fee') }}</th>
                                            <th scope="col" class="table-th">{{ __('Status') }}</th>
                                            <th scope="col" class="table-th">{{ __('Method') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
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
                                                            @case('receive_money')
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
                                                            <span class="optional-msg" data-bs-toggle="tooltip" title="" data-bs-original-title="{{ $transaction->approval_cause }}">
                                                                <i icon-name="mail"></i>
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
                                                <div class="badge bg-primary text-primary bg-opacity-30 capitalize rounded-3xl">
                                                    {{ str_replace('_',' ',$transaction->type->value) }}
                                                </div>
                                            </td>
    {{--                                        @if($transaction->type == 'withdraw')--}}
    {{--                                            {{dd(App\Enums\TxnType::Withdraw)}}--}}
    {{--                                            @endif--}}
                                            <td class="table-td">
                                                <strong class="{{in_array($transaction->type,[TxnType::Subtract,TxnType::Investment,TxnType::SendMoney,TxnType::Withdraw,TxnType::WithdrawAuto]) ?  'red-color' : 'green-color'}}">{{ (in_array($transaction->type,[TxnType::Subtract,TxnType::Investment,TxnType::SendMoney,TxnType::Withdraw,TxnType::WithdrawAuto]) ? '-': '+' ).$transaction->amount.' '.$transaction->currency }}</strong>
                                            </td>
                                            <td class="table-td">
                                                {{ $transaction->charge }} {{ $currency }}
                                            </td>
                                            <td class="table-td">
                                                <span class="block text-left">
                                                    <span class="inline-block text-center mx-auto py-1">
                                                        <span class="flex items-center space-x-3 rtl:space-x-reverse">
                                                            @switch($transaction->status->value)
                                                                @case('pending')
                                                                    <span class="h-[6px] w-[6px] bg-warning rounded-full inline-block ring-4 ring-opacity-30 ring-warning-500"></span>
                                                                    <span>{{ __('Pending') }}</span>
                                                                    @break
                                                                @case('success')
                                                                    <span class="h-[6px] w-[6px] bg-success rounded-full inline-block ring-4 ring-opacity-30 ring-success-500"></span>
                                                                    <span>{{ __('Success') }}</span>
                                                                    @break
                                                                @case('failed')
                                                                    <span class="h-[6px] w-[6px] bg-danger rounded-full inline-block ring-4 ring-opacity-30 ring-danger-500"></span>
                                                                    <span>{{ __('canceled') }}</span>
                                                                    @break
                                                            @endswitch
                                                        </span>
                                                    </span>
                                                </span>
                                            </td>
                                            <td class="table-td">
                                                {{ ucfirst($transaction->method) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {{  $transactions->links() }}
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
                            <input type="text" class="form-control" placeholder="Search" value="{{ request('query') }}" name="query"/>
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
                                    {{txn_type($transaction->type->value,['+','-']).$transaction->amount .' '.$currency}}</div>
                                <div class="transaction-fee sub mb-1 dark:text-white">
                                    -{{  $transaction->charge.' '. $currency .' '.__('Fee') }} </div>
                                <div class="transaction-gateway mb-1 dark:text-white">{{ $transaction->method }}</div>


                                @if($transaction->status->value == App\Enums\TxnStatus::Pending->value)
                                    <div class="transaction-status text-warning">{{ __('Pending') }}</div>
                                @elseif($transaction->status->value ==  App\Enums\TxnStatus::Success->value)
                                    <div class="transaction-status text-success">{{ __('Success') }}</div>
                                @elseif($transaction->status->value ==  App\Enums\TxnStatus::Failed->value)
                                    <div class="transaction-status text-danger">{{ __('canceled') }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                {{  $transactions->onEachSide(1)->links() }}
            </div>

        </div>

    </div>
@endsection
