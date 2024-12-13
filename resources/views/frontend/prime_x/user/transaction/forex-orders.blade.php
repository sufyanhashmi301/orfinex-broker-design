@php use App\Enums\TxnStatus; use App\Enums\TxnType; @endphp

@extends('frontend::layouts.user')
@section('title')
    {{ __('Accounts History') }}
@endsection
@section('content')
    @include('frontend::user.transaction.include.__tabs_nav')
    <div class="space-y-5">
        <?php
            $login = request()->get('login');
        ?>
        <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
            <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
                @yield('title')
            </h4>
            <form action="{{ route('user.history.tradingAccounts') }}" method="get" class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                <div class="input-area relative min-w-[170px]">
                    <select name="type" class="form-control">
                        <option>{{ __('Select Type') }}</option>
                        <option value="trades-report" @if(request()->get('type') == 'trades-report' ) selected @endif>{{ __('Trades') }}</option>
                        <option value="balance-report" @if(request()->get('type') == 'balance-report' ) selected @endif>{{ __('Balance Report') }}</option>
                    </select>
                </div>
                <div class="input-area relative min-w-[170px]">
                    <select name="login" class="form-control">
                        <option>{{ __('Select Account') }}</option>
                        @foreach($forexAccounts as $forexAccount)
                            <option value="{{ $forexAccount->login }}" @if(request()->get('login') == $forexAccount->login ) selected @endif>{{ $forexAccount->login }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-area relative min-w-[170px]">
                    <input type="text" class="form-control flatpickr" name="start_date" value="{{ request()->get('start_date') }}"/>
                </div>
                <div class="input-area relative min-w-[170px]">
                    <input type="text" class="form-control flatpickr" name="end_date" value="{{ request()->get('end_date') }}"/>
                </div>
                <button type="submit" class="apply-btn h-10 btn btn-dark inline-flex items-center justify-center">
                    {{ __('Filter') }}
                </button>
            </form>
        </div>
        @if(count($orders) == 0 && count($transactions) == 0 && isset($login) )
            <div class="card basicTable_wrapper flex items-center justify-center flex-col p-6">
                <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="text-lg text-center text-slate-600 dark:text-slate-100 my-3">
                    {{ __('No record found.') }}
                </p>
            </div>
        @elseif(count($orders) == 0 && count($transactions) == 0 && !isset($login))
            <div class="card basicTable_wrapper flex items-center justify-center flex-col p-6">
                <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="text-lg text-center text-slate-600 dark:text-slate-100 my-3">
                    {{ __('Kindly select the account to view the orders') }}
                </p>
            </div>
        @elseif(count($orders) > 0)
            <div class="card">
                <div class="card-body p-6 pt-3">
                    <div class="overflow-x-auto -mx-6">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden basicTable_wrapper">
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="table-th">{{ __('Created At') }}</th>
                                            <th scope="col" class="table-th">{{ __('Ticket') }}</th>
                                            <th scope="col" class="table-th">{{ __('Symbol') }}</th>
                                            <th scope="col" class="table-th">{{ __('SL') }}</th>
                                            <th scope="col" class="table-th">{{ __('TP') }}</th>
                                            <th scope="col" class="table-th">{{ __('Volume') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                        @foreach($orders as $order)
                                            <tr>
                                                <td class="table-td">
                                                    {{ $order['orderPlacementTime'] }}
                                                </td>
                                                <td class="table-td">
                                                    {{ $order['ticket'] }}
                                                </td>
                                                <td class="table-td">
                                                    {{ $order['symbol'] }}
                                                </td>
                                                <td class="table-td">
                                                    {{ $order['priceSL'] }}
                                                </td>
                                                <td class="table-td">
                                                    {{ $order['priceTP'] }}
                                                </td>
                                                <td class="table-td">
                                                    {{ $order['volumeInitial'] }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(count($transactions) > 0)
            <div class="card">
                <div class="card-body p-6 pt-3">
                    <div class="overflow-x-auto -mx-6">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden basicTable_wrapper">
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="table-th">{{ __('Created At') }}</th>
                                        <th scope="col" class="table-th">{{ __('Type') }}</th>
                                        <th scope="col" class="table-th">{{ __('Deal') }}</th>
                                        <th scope="col" class="table-th">{{ __('Amount') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td class="table-td">
                                                {{ $transaction['time'] }}
                                            </td>
                                            <td class="table-td">
                                                @if($transaction['action'] == 'Deposit')
                                                    <span class="badge badge-success">{{ $transaction['action'] }}</span>
                                                @else
                                                    <span class="badge badge-danger">{{ $transaction['action'] }}</span>
                                                @endif
                                            </td>
                                            <td class="table-td">
                                                {{ $transaction['deal'] }}
                                            </td>
                                            <td class="table-td">
                                                {{ $transaction['amount'] }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
