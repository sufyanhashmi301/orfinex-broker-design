@php use App\Enums\TxnStatus; use App\Enums\TxnType; @endphp

@extends('frontend::layouts.user')
@section('title')
    {{ __('Accounts Orders Log') }}
@endsection
@section('content')
    <div class="mb-5">
        <ul class="m-0 p-0 list-none">
            <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter ">
                <a href="{{route('user.dashboard')}}">
                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                </a>
            </li>
            <li class="inline-block relative text-sm text-primary-500 font-Inter ">
                {{ __('History') }}
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ __('Accounts Orders Log') }}
            </li>
        </ul>
    </div>
    <div class="space-y-5">
        <div class="card desktop-screen-show md:block hidden">
            <header class=" card-header noborder">
                <h4 class="card-title">
                    {{ __('Accounts Orders Log') }}
                </h4>
            </header>
            <div class="card-body px-6 pb-6">
                <div class="grid xl:grid-cols-2 grid-cols-1 gap-5 mb-10">
                    <div class="filter">
                        <form action="{{ route('user.forex.transactions') }}" method="get">
                            <div class="search flex gap-3 items-center">
                                {{--                                    <input type="text" class="form-control" id="search" placeholder="Search"--}}
                                {{--                                        value="{{ request('query') }}"--}}
                                {{--                                        name="query"/>--}}
                                <select   name="login" class="select2 form-control !text-lg w-full mt-2 py-2">
                                    <option selected disabled>--{{ __('Select Account') }}--</option>
                                    @foreach($forexAccounts as $forexAccount)
                                        <option value="{{ $forexAccount->login }}" @if(request()->get('login') == $forexAccount->login ) selected @endif>{{ $forexAccount->login }}</option>
                                    @endforeach
                                </select>
                                <input type="date" class="form-control" name="start_date" value="{{ request()->get('start_date') }}"/>
                                <input type="date" class="form-control" name="end_date" value="{{ request()->get('end_date') }}"/>
                                <button type="submit" class="btn btn-dark btn-sm">
                                    <i icon-name="search"></i>
                                    {{ __('Search') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                    $login = request()->get('login');
                ?>
                @if(count($transactions) == 0 && isset($login) )
                    <div class="flex items-center justify-center flex-col">
                        <p class="text-lg text-slate-600 dark:text-slate-100 mb-3">
                            You don't have any transaction yet.
                        </p>
                        <a href="{{ route('user.deposit.amount') }}" class="btn btn-dark inline-flex items-center justify-center min-w-[170px]">
                            Deposit Now
                        </a>
                    </div>
                @elseif(count($transactions) == 0 && !isset($login))
                        <div class="flex items-center justify-center flex-col">
                            <p class="text-lg text-slate-600 dark:text-slate-100 mb-3">
                                Kindly select the account to view the orders or
                            </p>
                            <a href="{{ route('user.deposit.amount') }}" class="btn btn-dark inline-flex items-center justify-center min-w-[170px]">
                                Deposit Now
                            </a>
                        </div>
                    @else
                    <div class="overflow-x-auto -mx-6">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden ">
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                    <thead class=" border-t border-slate-100 dark:border-slate-800">
                                        <tr>
                                            <th scope="col" class="table-th">{{ __('Description') }}</th>
                                            <th scope="col" class="table-th">{{ __('Login') }}</th>
                                            <th scope="col" class="table-th">{{ __('Order') }}</th>
                                            <th scope="col" class="table-th">{{ __('Symbol') }}</th>
                                            <th scope="col" class="table-th">{{ __('Price Order') }}</th>
                                            <th scope="col" class="table-th">{{ __('Rate Margin') }}</th>
                                            <th scope="col" class="table-th">{{ __('Position Id') }}</th>

                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                        @foreach($transactions as $transaction)
                                        <tr>
                                            <td class="table-td">
                                                <div class="flex items-center">
                                                    <div class="flex-none">
                                                    <div class="w-10 h-10 lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mr-2">
                                                        <iconify-icon icon="lucide:backpack"></iconify-icon>
                                                    </div>
                                                    </div>
                                                    <div class="flex-1 text-start">
                                                    <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                        {{ $transaction->Print }}
                                                    </h4>
                                                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                                        {{ date('Y-m-d H:i:s', $transaction->TimeSetup) }}}}
                                                    </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="table-td">
                                                {{ $transaction->Login }}
                                            </td>
                                            <td class="table-td">
                                                {{ $transaction->Order }}
                                            </td>
                                            <td class="table-td">
                                                {{ $transaction->Symbol }}
                                            </td>
                                            <td class="table-td">
                                                {{ $transaction->PriceOrder }}
                                            </td>
                                            <td class="table-td">
                                                {{ $transaction->RateMargin }}
                                            </td>
                                            <td class="table-td">
                                                {{ $transaction->PositionId }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
{{--                                {{  $transactions->links() }}--}}
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
                <h4 class="card-title">{{ __('Accounts Orders Log') }}</h4>
            </div>
            <div class="card-body p-3 mobile-transaction-filter">
                <div class="filter mb-3">
                    <form action="{{ route('user.forex.transactions') }}" method="get">
                        <div class="search flex items-center flex-wrap gap-2">
                            <select   name="login" class="select2 form-control !text-lg w-full mt-2 py-2">
                                <option selected disabled>--{{ __('Select Account') }}--</option>
                                @foreach($forexAccounts as $forexAccount)
                                    <option value="{{ $forexAccount->login }}" @if(request()->get('login') == $forexAccount->login ) selected @endif>{{ $forexAccount->login }}</option>
                                @endforeach
                            </select>
                            <div class="w-full flex gap-2">
                                <input type="date" class="form-control" name="start_date" value="{{ request()->get('start_date') }}"/>
                                <input type="date" class="form-control" name="end_date" value="{{ request()->get('end_date') }}"/>
                            </div>
                            <button type="submit" class="apply-btn h-10 btn btn-dark inline-flex items-center justify-center w-full">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
                <div class="contents space-y-3">
                    @foreach($transactions as $transaction )
                        <div class="single-transaction flex justify-between text-xs bg-slate-100 dark:bg-slate-900 rounded-md p-2 py-3">
                            <div class="transaction-left w-3/4">
                                <div class="transaction-des">
                                    <div class="transaction-title mb-1 dark:text-white">{{ $transaction->Print }}</div>
                                    <div class="transaction-id mb-1 dark:text-white">{{ $transaction->Order }}</div>
                                    <div class="transaction-date mb-1 dark:text-white">{{ date('Y-m-d H:i:s', $transaction->TimeSetup) }}</div>
                                </div>
                            </div>
                            <div class="transaction-right text-right">
                                <div class="transaction-gateway mb-1 dark:text-white">{{ $transaction->Symbol }}</div>
                                <div class="transaction-amount  mb-1 dark:text-white">
                                    {{$transaction->PriceOrder}}</div>
                                <div class="transaction-amount mb-1 dark:text-white">
                                    {{  $transaction->RateMargin }} </div>
                                <div class="transaction-amount mb-1 dark:text-white">
                                    {{  $transaction->PositionId }} </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>
@endsection
