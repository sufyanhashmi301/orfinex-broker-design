@php use App\Enums\TxnStatus; use App\Enums\TxnType; @endphp

@extends('frontend::layouts.user')
@section('title')
    {{ __('Accounts History') }}
@endsection
@section('content')
    @php
        $login = request()->get('login');
    @endphp
    <div class="flex flex-wrap items-center justify-between gap-3 pb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
            @yield('title')
        </h2>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div x-data="transactionFilter()" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-gray-200 px-4 py-5 xl:px-6 xl:py-6 dark:border-gray-800">
            @include('frontend::user.transaction.include.__tabs_nav')

            <form action="{{ route('user.history.tradingAccounts') }}" method="get" class="flex-1 flex flex-col sm:flex-row sm:justify-end sm:items-center gap-3">
                <div class="input-area relative">
                    <select name="type" class="dark:bg-dark-900 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                        <option>{{ __('Select Type') }}</option>
                        <option value="trades-report" @if(request()->get('type') == 'trades-report' ) selected @endif>{{ __('Trades') }}</option>
                        <option value="balance-report" @if(request()->get('type') == 'balance-report' ) selected @endif>{{ __('Balance Report') }}</option>
                    </select>
                </div>
                <div class="input-area relative">
                    <select name="login" class="dark:bg-dark-900 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                        <option>{{ __('Select Account') }}</option>
                        @foreach($forexAccounts as $forexAccount)
                            <option value="{{ $forexAccount->login }}" @if(request()->get('login') == $forexAccount->login ) selected @endif>{{ $forexAccount->login }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-area relative">
                    <input type="date" class="dark:bg-dark-900 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 flatpickr" name="start_date" value="{{ request()->get('start_date') }}"/>
                </div>
                <div class="input-area relative min-w-[170px]">
                    <input type="date" class="dark:bg-dark-900 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800 flatpickr" name="end_date" value="{{ request()->get('end_date') }}"/>
                </div>
                <button type="submit" class="inline-flex items-center gap-2 rounded bg-white px-4 py-1 text-sm font-medium text-gray-700 shadow-theme-xs ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                    <i data-lucide="arrow-up-from-dot" class="w-4 mr-2"></i>
                    {{ __('Filter') }}
                </button>
            </form>
        </div>

        @if(count($orders) == 0 && count($transactions) == 0)
            <div class="flex items-center justify-center flex-col py-10 px-10">
                <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="text-lg text-slate-600 dark:text-slate-100 mb-3">
                    {{ isset($login) ? __('No record found.') : __('Kindly select the account to view the orders') }}
                </p>
            </div>
        @elseif(count($orders) > 0)
            <div class="custom-scrollbar overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr>
                            <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                <span class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Created At') }}
                                </span>
                            </th>
                            <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                <span class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Ticket') }}
                                </span>
                            </th>
                            <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                <span class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Symbol') }}
                                </span>
                            </th>
                            <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                <span class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('SL') }}
                                </span>
                            </th>
                            <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                <span class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('TP') }}
                                </span>
                            </th>
                            <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                <span class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Volume') }}
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors duration-150">
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-gray-500 text-theme-sm dark:text-gray-400 font-mono">
                                        {{ $order['orderPlacementTime'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ $order['ticket'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ $order['symbol'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ $order['priceSL'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ $order['priceTP'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ $order['volumeInitial'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif(count($transactions) > 0)
            <div class="custom-scrollbar overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr>
                            <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                <span class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Created At') }}
                                </span>
                            </th>
                            <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                <span class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Type') }}
                                </span>
                            </th>
                            <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                <span class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Deal') }}
                                </span>
                            </th>
                            <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                <span class="font-medium text-gray-500 text-xs dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('Amount') }}
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($transactions as $transaction)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors duration-150">
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-gray-500 text-theme-sm dark:text-gray-400 font-mono">
                                        {{ $transaction['time'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span 
                                        :class="transaction['action'] === 'Deposit' ? 'bg-success-50 dark:bg-success-500/15 text-success-700 dark:text-success-500' : 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500'"
                                        class="text-theme-xs rounded-full px-2 py-0.5 font-medium">
                                        {{ $transaction['action'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ $transaction['deal'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ $transaction['amount'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
