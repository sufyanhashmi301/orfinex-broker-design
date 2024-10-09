@extends('frontend::layouts.user')
@section('title')
    {{ __('My Wallet') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4 class="text-xl text-slate-600 dark:text-slate-300">{{ __('Account Details') }}</h4>
    </div>
    <div class="grid md:grid-cols-3 col-span-1 gap-5 mb-6">
        <div class="card h-full p-6 mb-6">
            <div class="card-body">
                <div class="flex flex-wrap justify-between items-center mb-5">
                    <div class="space-x-3">
                        <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">
                            {{ __('USD') }}
                        </span>
                        <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">
                            {{ __('Standard') }}
                        </span>
                    </div>
                    <div class="dropdown relative">
                        <button class="text-xl text-center block w-full " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="text-lg inline-flex h-6 w-6 flex-col items-center justify-center border border-slate-200 dark:border-slate-700 rounded dark:text-slate-400">
                                <iconify-icon icon="bi:three-dots-vertical"></iconify-icon>
                            </span>
                        </button>
                        {{--<ul class=" dropdown-menu w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                            <li>
                                <a href="" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white dropdown-account-info">
                                    {{ __('Submenu') }}
                                </a>
                            </li>
                        </ul>--}}
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
                <div class="flex space-x-2 items-center">
                    <a href="{{route('user.deposit.amount')}}" class="btn btn-sm btn-outline-dark loaderBtn inline-flex items-center justify-center">
                        <span class="flex items-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="hugeicons:credit-card-pos"></iconify-icon>
                            <span>{{ __('Deposit') }}</span>
                        </span>
                    </a>
                    <a href="{{route('user.withdraw.view')}}" class="btn btn-sm btn-outline-dark loaderBtn inline-flex items-center justify-center">
                        <span class="flex items-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="mingcute:refund-dollar-line"></iconify-icon>
                            <span>{{ __('Withdraw') }}</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card h-full p-6 mb-6">
            <div class="card-body">
                <div class="flex flex-wrap justify-between items-center mb-5">
                    <div class="space-x-3">
                        <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">
                            {{ __('USD') }}
                        </span>
                        <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">
                            {{ __('Standard') }}
                        </span>
                    </div>
                    <div class="dropdown relative">
                        <button class="text-xl text-center block w-full " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="text-lg inline-flex h-6 w-6 flex-col items-center justify-center border border-slate-200 dark:border-slate-700 rounded dark:text-slate-400">
                                <iconify-icon icon="bi:three-dots-vertical"></iconify-icon>
                            </span>
                        </button>
                        {{--<ul class=" dropdown-menu w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                            <li>
                                <a href="" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white dropdown-account-info">
                                    {{ __('Submenu') }}
                                </a>
                            </li>
                        </ul>--}}
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
                <div class="flex space-x-2 items-center">
                    <a href="{{route('user.deposit.amount')}}" class="btn btn-sm btn-outline-dark loaderBtn inline-flex items-center justify-center">
                        <span class="flex items-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="hugeicons:credit-card-pos"></iconify-icon>
                            <span>{{ __('Deposit') }}</span>
                        </span>
                    </a>
                    <a href="{{route('user.withdraw.view')}}" class="btn btn-sm btn-outline-dark loaderBtn inline-flex items-center justify-center">
                        <span class="flex items-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="mingcute:refund-dollar-line"></iconify-icon>
                            <span>{{ __('Withdraw') }}</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card border border-dashed h-full border-slate-200 dark:border-slate-700 p-6 mb-6">
            <div class="card-body h-full flex flex-col items-center justify-center gap-5">
                <iconify-icon class="text-2xl" icon="ic:outline-dashboard-customize"></iconify-icon>
                <a href="" class="btn-link loaderBtn">
                    {{ __('Open Additional Account') }}
                </a>
            </div>
        </div>
    </div>

    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4 class="text-xl text-slate-600 dark:text-slate-300">{{ __('Recent Transactions') }}</h4>
    </div>
    <div class="card">
        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                            <tr>
                                <th scope="col" class="table-th">{{ __('Description') }}</th>
                                <th scope="col" class="table-th">{{ __('Wallet') }}</th>
                                <th scope="col" class="table-th">{{ __('Transactions ID') }}</th>
                                <th scope="col" class="table-th">{{ __('Amount') }}</th>
                                <th scope="col" class="table-th">{{ __('Fee') }}</th>
                                <th scope="col" class="table-th">{{ __('Status') }}</th>
                                <th scope="col" class="table-th">{{ __('Method') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($wallets as $raw)
                                <tr>
                                    <td class="table-td">
                                        <div class="flex items-center">
                                            <div class="flex-none">
                                                <div class="w-10 h-10 lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mr-2">
                                                    <iconify-icon icon="octicon:download-16"></iconify-icon>
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
                                        {{ w2n_by_wallet_id($raw->target_id) }}
                                    </td>
                                    <td class="table-td">
                                        {{ $raw->tnx }}
                                    </td>
                                    <td class="table-td">
                                                <span class="font-medium">
                                                    +{{$raw->amount.' '.$currency }}
                                                </span>
                                    </td>
                                    <td class="table-td">
                                                <span class="font-medium">
                                                    -{{ $raw->charge }} {{ $currency }}
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

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
