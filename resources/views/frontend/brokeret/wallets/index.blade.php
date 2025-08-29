@extends('frontend::layouts.user')
@section('title')
    {{ __('My Wallet') }}
@endsection
@section('content')
    <div class="pageTitle flex flex-wrap items-center justify-between gap-3 mb-3">
        <h4 class="text-xl font-semibold text-gray-800 dark:text-white/90">
            @yield('title')
        </h4>
    </div>
    <div class="grid sm:grid-cols-2 xl:grid-cols-3 col-span-1 gap-5 mb-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="card-body">
                <div class="flex flex-wrap justify-between items-center mb-5">
                    <div class="space-x-3">
                        <span class="inline-flex items-center justify-center gap-1 rounded bg-gray-100 px-2.5 py-0.5 text-sm font-medium text-gray-700 dark:bg-white/5 dark:text-white/80">
                            {{ __('USD') }}
                        </span>
                        <span class="inline-flex items-center justify-center gap-1 rounded bg-gray-100 px-2.5 py-0.5 text-sm font-medium text-gray-700 dark:bg-white/5 dark:text-white/80">
                            {{ __('Standard') }}
                        </span>
                    </div>
                    <div class="dropdown relative">
                        <button class="text-xl text-center block w-full " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="text-lg inline-flex h-6 w-6 flex-col items-center justify-center border border-slate-200 dark:border-slate-700 rounded dark:text-slate-400">
                                <i data-lucide="more-vertical"></i>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="mb-5">
                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{ __('E-') }}{{ data_get($mainWallet,'wallet_id') }}
                    </div>
                    <div class="text-slate-900 dark:text-white text-xl font-medium">
                        {{ data_get($mainWallet,'amount') }} {{$currency}}
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <x-link-button href="{{route('user.deposit.methods')}}" class="flex-1" variant="primary" icon="banknote-arrow-up" iconPosition="left">
                        {{ __('Deposit') }}
                    </x-link-button>
                    <x-link-button href="{{route('user.withdraw.view')}}" class="flex-1" variant="outline" icon="banknote-arrow-down" iconPosition="left">
                        {{ __('Withdraw') }}
                    </x-link-button>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="card-body">
                <div class="flex flex-wrap justify-between items-center mb-5">
                    <div class="space-x-3">
                        <span class="inline-flex items-center justify-center gap-1 rounded bg-gray-100 px-2.5 py-0.5 text-sm font-medium text-gray-700 dark:bg-white/5 dark:text-white/80">
                            {{ __('USD') }}
                        </span>
                        <span class="inline-flex items-center justify-center gap-1 rounded bg-gray-100 px-2.5 py-0.5 text-sm font-medium text-gray-700 dark:bg-white/5 dark:text-white/80">
                            {{ __('Standard') }}
                        </span>
                    </div>
                    <div class="dropdown relative">
                        <button class="text-xl text-center block w-full " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="text-lg inline-flex h-6 w-6 flex-col items-center justify-center border border-slate-200 dark:border-slate-700 rounded dark:text-slate-400">
                                <i data-lucide="more-vertical"></i>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="mb-5">
                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{ __('IB-') }}{{ data_get($ibWallet,'wallet_id') }}
                    </div>
                    <div class="text-slate-900 dark:text-white text-xl font-medium">
                        {{ data_get($ibWallet,'amount') }} {{$currency}}
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <x-link-button href="{{route('user.deposit.methods')}}" class="flex-1" variant="primary" icon="banknote-arrow-up" iconPosition="left">
                        {{ __('Deposit') }}
                    </x-link-button>
                    <x-link-button href="{{route('user.withdraw.view')}}" class="flex-1" variant="outline" icon="banknote-arrow-down" iconPosition="left">
                        {{ __('Withdraw') }}
                    </x-link-button>
                </div>
            </div>
        </div>
        <div class="hidden xl:block rounded-2xl border border-dashed border-gray-200 bg-white p-5 sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="h-full flex flex-col items-center justify-center gap-5">
                <i data-lucide="grid-2x2-plus" class="text-brand-500"></i>
                <x-text-link href="javascript:void(0)" variant="text">
                    {{ __('Open Additional Account') }}
                </x-text-link>
            </div>
        </div>
    </div>

    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4 class="text-xl font-semibold text-gray-800 dark:text-white/90">{{ __('Recent Transactions') }}</h4>
    </div>
    <div class="desktop-screen-show md:block hidden">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            @if(count($wallets) == 0)
                <div class="flex items-center justify-center flex-col py-10 px-10">
                    <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <p class="text-lg text-slate-600 dark:text-slate-100 mb-3">
                        {{ __("You don't have any transactions yet.") }}
                    </p>
                    <a href="{{ route('user.deposit.methods') }}" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium text-white transition rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600 transition-colors duration-200">
                        {{ __('Deposit Now') }}
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden ">
                            <table class="min-w-full">
                                <thead class="border-b border-gray-100 dark:border-gray-800">
                                    <tr>
                                        <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                            <span class="font-medium text-gray-500 text-theme-sm dark:text-gray-400">
                                                {{ __('Description') }}
                                            </span>
                                        </th>
                                        <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                            <span class="font-medium text-gray-500 text-theme-sm dark:text-gray-400">
                                                {{ __('Wallet') }}
                                            </span>
                                        </th>
                                        <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                            <span class="font-medium text-gray-500 text-theme-sm dark:text-gray-400">
                                                {{ __('Transactions ID') }}
                                            </span>
                                        </th>
                                        <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                            <span class="font-medium text-gray-500 text-theme-sm dark:text-gray-400">
                                                {{ __('Method') }}
                                            </span>
                                        </th>
                                        <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                            <span class="font-medium text-gray-500 text-theme-sm dark:text-gray-400">
                                                {{ __('Amount') }}
                                            </span>
                                        </th>
                                        <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                            <span class="font-medium text-gray-500 text-theme-sm dark:text-gray-400">
                                                {{ __('Fee') }}
                                            </span>
                                        </th>
                                        <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                            <span class="font-medium text-gray-500 text-theme-sm dark:text-gray-400">
                                                {{ __('Status') }}
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                    @foreach($wallets as $raw)
                                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors duration-150">
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 flex items-center justify-center overflow-hidden rounded-full">
                                                        <i data-lucide="download"></i>
                                                    </div>
                                                    <div>
                                                        <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                                            {{ $raw->description }}
                                                        </span>
                                                        <span class="block text-gray-500 text-theme-xs dark:text-gray-400">
                                                            {{ $raw->created_at }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <span class="text-gray-500 text-theme-sm dark:text-gray-400 font-mono">
                                                    {{ w2n_by_wallet_id($raw->target_id) }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <span class="text-gray-500 text-theme-sm dark:text-gray-400 font-mono">
                                                    {{ $raw->tnx }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <span class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    {{transaction_method_name($raw)}}
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <span class="text-gray-500 text-theme-sm dark:text-gray-400 font-medium">
                                                    +{{$raw->amount.' '.$currency }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <span class="text-gray-500 text-theme-sm dark:text-gray-400 font-medium">
                                                    -{{ $raw->charge }} {{ $currency }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                @switch($raw->status->value)
                                                    @case('pending')
                                                    <x-badge variant="warning" style="light" size="sm">
                                                        {{ __('Pending') }}
                                                    </x-badge>
                                                    @break
                                                    @case('success')
                                                    <x-badge variant="success" style="light" size="sm">
                                                        {{ __('Success') }}
                                                    </x-badge>
                                                    @break
                                                    @case('failed')
                                                    <x-badge variant="error" style="light" size="sm">
                                                        {{ __('Canceled') }}
                                                    </x-badge>
                                                    @break
                                                @endswitch
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if(count($wallets) > 0)
                                <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-3 mt-auto">
                                    <div>
                                        @php
                                            $from = $wallets->firstItem(); // The starting item number on the current page
                                            $to = $wallets->lastItem(); // The ending item number on the current page
                                            $total = $wallets->total(); // The total number of items
                                        @endphp

                                        <p class="text-sm text-gray-700 dark:text-slate-300 px-3">
                                            Showing
                                            <span class="font-medium">{{ $from }}</span>
                                            to
                                            <span class="font-medium">{{ $to }}</span>
                                            of
                                            <span class="font-medium">{{ $total }}</span>
                                            results
                                        </p>
                                    </div>
                                    {{  $wallets->links() }}
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="md:hidden block mobile-screen-show">        
        @if(count($wallets) == 0)
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-center flex-col p-4">
                    <svg width="42" height="43" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <p class="text-sm text-slate-600 dark:text-slate-100 my-3">
                        {{ __("You don't have any transactions yet.") }}
                    </p>
                </div>
            </div>
        @else
            <div class="all-feature-mobile mobile-transactions mb-3">
                <div class="mobile-transaction-filter">
                    <div class="contents space-y-3">
                        @foreach($wallets as $raw)
                            <div class="flex justify-between gap-3 rounded-xl border border-gray-200 bg-white p-4 px-2 shadow-xs dark:border-gray-800 dark:bg-white/[0.03]">
                                <div class="transaction-left flex-1 min-w-0">
                                    <div class="transaction-des">
                                        <div class="transaction-title text-sm font-semibold mb-2 text-gray-900 dark:text-white">
                                            {{ $raw->description }}
                                        </div>
                                        <div class="transaction-id text-xs text-gray-500 dark:text-gray-400 mb-1 font-mono">
                                            {{ $raw->tnx }}
                                        </div>
                                        <div class="transaction-date text-xs text-gray-500 dark:text-gray-400 mb-1">
                                            {{ $raw->created_at }}
                                        </div>
                                    </div>
                                </div>
                                <div class="transaction-right text-right ml-4 flex-shrink-0">
                                    <div class="transaction-amount font-semibold mb-2 text-sm dark:text-white">
                                        +{{$raw->amount.' '.$currency }}
                                    </div>
                                    <div class="transaction-fee sub mb-1 text-xs text-gray-500 dark:text-gray-400">
                                        -{{  $raw->charge.' '. $currency .' '.__('Fee') }}
                                    </div>
                                    <div class="transaction-gateway mb-2 text-xs text-gray-600 dark:text-gray-300">
                                        {{ transaction_method_name($raw) }}
                                    </div>
                                    <div class="transaction-status">
                                        @switch($raw->status->value)
                                            @case('pending')
                                            <x-badge variant="warning" style="light" size="sm">
                                                {{ __('Pending') }}
                                            </x-badge>
                                            @break
                                            @case('success')
                                            <x-badge variant="success" style="light" size="sm">
                                                {{ __('Success') }}
                                            </x-badge>
                                            @break
                                            @case('failed')
                                            <x-badge variant="error" style="light" size="sm">
                                                {{ __('canceled') }}
                                            </x-badge>
                                            @break
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{ $wallets->onEachSide(1)->links() }}
                </div>
            </div>
        @endif
    </div>

@endsection
