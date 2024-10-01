@extends('backend.setting.payment.index')
@section('title')
    {{ __('Automatic Payment Gateway') }}
@endsection
@section('payment-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Automatic Payment Gateway') }}
        </h4>
    </div>
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Logo') }}</th>
                                    <th scope="col" class="table-th">{{ __('Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Supported Currency') }}</th>
                                    <th scope="col" class="table-th">{{ __('Payout Available') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Manage') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach($gateways as $gateway)
                                <tr>
                                    <td class="table-td">
                                        <img class="h-7" src="{{ $gateway->logo }}" alt="">
                                    </td>
                                    <td class="table-td">{{ $gateway->name }}</td>
                                    <td class="table-td">
                                        {{ count(json_decode($gateway->supported_currencies,true)) }}
                                    </td>
                                    <td class="table-td">
                                        @if($gateway->is_withdraw != 0)
                                            <div class="badge bg-success-500 text-white capitalize">
                                                {{ __('Yes') }}
                                            </div>
                                        @else
                                            <div class="badge bg-danger-500 text-white capitalize">
                                                {{ __('No') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        @if($gateway->status == 1)
                                            <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
                                                {{ __('Activated') }}
                                            </div>
                                        @else
                                            <div class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">
                                                {{ __('Deactivated') }}
                                            </div>
                                        @endif
                                    </td>

                                    <td class="table-td">
                                        <button
                                            class="action-btn"
                                            type="button"
                                            data-bs-toggle="modal"
                                            data-bs-target="#manage-{{$gateway->id}}"
                                        >
                                            <iconify-icon icon="lucide:settings-2"></iconify-icon>
                                        </button>
                                    </td>
                                </tr>
                                <!--  Manage Modal -->
                                @include('backend.automatic_gateway.include.__manage')
                                <!-- Manage Modal End-->

                                @endforeach
                            </tbody>
                        </table>
                        <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto">
                            <div>
                                @php
                                    $from = $gateways->firstItem(); // The starting item number on the current page
                                    $to = $gateways->lastItem(); // The ending item number on the current page
                                    $total = $gateways->total(); // The total number of items
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
                            {{ $gateways->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
