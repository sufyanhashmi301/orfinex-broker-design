@php use App\Enums\TxnStatus; use App\Enums\TxnType; @endphp

@extends('frontend::layouts.user')
@section('title')
    {{ __('Accounts Orders Log') }}
@endsection
@section('content')
    <div class="space-y-5">
        <?php
            $login = request()->get('login');
        ?>
        @if(count($transactions) == 0 && isset($login) )
        <div class="card basicTable_wrapper flex items-center justify-center flex-col p-6">
            <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="text-lg text-slate-600 dark:text-slate-100 my-3">
                {{ __("You don't have any transaction yet.") }}
            </p>
            <a href="{{ route('user.deposit.amount') }}" class="btn btn-primary inline-flex items-center justify-center min-w-[170px]">
                {{ __('Deposit Now') }}
            </a>
        </div>
        @elseif(count($transactions) == 0 && !isset($login))
        <div class="card basicTable_wrapper flex items-center justify-center flex-col p-6">
            <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="text-lg text-slate-600 dark:text-slate-100 my-3">
                {{ __('Kindly select the account to view the orders or') }}
            </p>
            <a href="{{ route('user.deposit.amount') }}" class="btn btn-primary inline-flex items-center justify-center min-w-[170px]">
                {{ __('Deposit Now') }}
            </a>
        </div>
        @else
        <div class="card desktop-screen-show md:block hidden">
            <div class="card-body p-6">
                <div class="innerMenu grid xl:grid-cols-2 grid-cols-1 gap-5 mb-10">
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
                                <input type="date" class="form-control flatpickr flatpickr-input active" name="start_date" value="{{ request()->get('start_date') }}"/>
                                <input type="date" class="form-control flatpickr flatpickr-input active" name="end_date" value="{{ request()->get('end_date') }}"/>
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
                                        <th scope="col" class="table-th">{{ __('Login') }}</th>
                                        <th scope="col" class="table-th">{{ __('Order') }}</th>
                                        <th scope="col" class="table-th">{{ __('Symbol') }}</th>
                                        <th scope="col" class="table-th">{{ __('Price Order') }}</th>
                                        <th scope="col" class="table-th">{{ __('Rate Margin') }}</th>
                                        <th scope="col" class="table-th">{{ __('Position Id') }}</th>

                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
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
{{--                                <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-3 mt-auto">--}}
{{--                                    <div>--}}
{{--                                        @php--}}
{{--                                            $from = $transactions->firstItem(); // The starting item number on the current page--}}
{{--                                            $to = $transactions->lastItem(); // The ending item number on the current page--}}
{{--                                            $total = $transactions->total(); // The total number of items--}}
{{--                                        @endphp--}}

{{--                                        <p class="text-sm text-gray-700 px-3">--}}
{{--                                            Showing--}}
{{--                                            <span class="font-medium">{{ $from }}</span>--}}
{{--                                            to--}}
{{--                                            <span class="font-medium">{{ $to }}</span>--}}
{{--                                            of--}}
{{--                                            <span class="font-medium">{{ $total }}</span>--}}
{{--                                            results--}}
{{--                                        </p>--}}
{{--                                    </div>--}}
{{--                                    {{  $transactions->links() }}--}}
{{--                                </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
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
