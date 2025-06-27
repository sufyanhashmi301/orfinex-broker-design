@extends('backend.setting.payment.index')
@section('title')
    {{ __('Currency Settings') }}
@endsection
@section('payment-content')
    <h4 class="pageTitle font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-5">
        {{ 'Currency Rates' }}
    </h4>
    <div class="card mb-5">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('ID') }}</th>
                                    <th scope="col" class="table-th">{{ __('Country') }}</th>
                                    <th scope="col" class="table-th">{{ __('Currency Code') }}</th>
                                    <th scope="col" class="table-th">{{ __('Rate') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach($countries as $country)
                                    <tr>
                                        <td class="table-td">
                                            {{$country->id}}
                                        </td>
                                        <td class="table-td">
                                            {{$country->name}}
                                        </td>
                                        <td class="table-td">
                                            {{$country->currency_code}}
                                        </td>
                                        <td class="table-td">
                                            {{$country->rate->rate}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto">
                            <div>
                                @php
                                    $from = $countries->firstItem(); // The starting item number on the current page
                                    $to = $countries->lastItem(); // The ending item number on the current page
                                    $total = $countries->total(); // The total number of items
                                @endphp

                                <p class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-medium">{{ $from }}</span>
                                    to
                                    <span class="font-medium">{{ $to }}</span>
                                    of
                                    <span class="font-medium">{{ $total }}</span>
                                    results
                                </p>
                            </div>
                            {{ $countries->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
