@extends('backend.setting.country.index')
@section('title')
    {{ __('Countries') }}
@endsection
@section('country-content')
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('ID') }}</th>
                                    <th scope="col" class="table-th">{{ __('Country') }}</th>
                                    <th scope="col" class="table-th">{{ __('Country Code') }}</th>
                                    <th scope="col" class="table-th">{{ __('ISO 3166-2') }}</th>
                                    <th scope="col" class="table-th">{{ __('ISO 3166-3') }}</th>
                                    <th scope="col" class="table-th">{{ __('Currency Code') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach($countries as $country)
                                    <tr>
                                        <td class="table-td">
                                            {{$country->country_id}}
                                        </td>
                                        <td class="table-td">
                                            {{$country->name}}
                                        </td>
                                        <td class="table-td">
                                            {{$country->country_code}}
                                        </td>
                                        <td class="table-td">
                                            {{$country->ISO_3166_2}}
                                        </td>
                                        <td class="table-td">
                                            {{$country->ISO_3166_3}}
                                        </td>
                                        <td class="table-td">
                                            {{$country->currency_code}}
                                        </td>
                                        <td class="table-td">
                                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                <input type="checkbox" value="" class="sr-only peer" @if($country->status == 1) checked @endif>
                                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                            </label>
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
