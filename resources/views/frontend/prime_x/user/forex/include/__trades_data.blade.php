<div class="card-body px-6 pt-3">
    <div class="overflow-x-auto -mx-6">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden basicTable_wrapper" style="min-height: calc(-263px + 100vh);">
                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                    <thead>
                        <tr>
                            <th scope="col" class="table-th">{{ __('Time') }}</th>
                            <th scope="col" class="table-th">{{ __('Symbol') }}</th>
                            <th scope="col" class="table-th">{{ __('Type') }}</th>
                            <th scope="col" class="table-th">{{ __('Volume') }}</th>
                            <th scope="col" class="table-th">{{ __('S/L') }}</th>
                            <th scope="col" class="table-th">{{ __('T/P') }}</th>
                            <th scope="col" class="table-th">{{ __('Status') }}</th>
                            <th scope="col" class="table-th">{{ __('Profit') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($orders->isEmpty())
                        <tr>
                            <td class="table-td text-center" colspan="10">
                                <span class="block my-4">
                                    {{ __('No Trades found for this account.') }}
                                </span>
                            </td>
                        </tr>
                    @else
                        @foreach($orders as $order)
                            <tr>
                                <td class="table-td">{{$order->Time}}</td>
                                <td class="table-td">{{$order->Symbol}}</td>
                                <td class="table-td">
                                    @if($order->Action == 0)
                                        <span class="badge badge-success text-white capitalize">
                                            {{ __('Buy') }}
                                        </span>
                                    @elseif($order->Action == 1)
                                        <span class="badge badge-danger text-white capitalize">
                                            {{ __('Sell') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="table-td">
                                    {{ $order->Volume / 10000 }}
                                </td>
                                <td class="table-td">{{$order->PriceSL}}</td>
                                <td class="table-td">{{$order->PriceTP}}</td>
                                <td class="table-td">
                                    @if($order->Volume != $order->VolumeClosed)
                                        <span class="badge badge-success text-white capitalize">
                                            {{ __('Open') }}
                                        </span>
                                    @else
                                        <span class="badge badge-danger text-white capitalize">
                                            {{ __('Closed') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="table-td">{{$order->Profit}}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-3 mt-auto">
                    <div>
                        @php
                            $from = $orders->firstItem(); // The starting item number on the current page
                            $to = $orders->lastItem(); // The ending item number on the current page
                            $total = $orders->total(); // The total number of items
                        @endphp

                        <p class="text-sm text-gray-700 dark:text-slate-300 px-3">
                            {{ __('Showing') }}
                            <span class="font-medium">{{ $from }}</span>
                            {{ __('to') }}
                            <span class="font-medium">{{ $to }}</span>
                            {{ __('of') }}
                            <span class="font-medium">{{ $total }}</span>
                            {{ __('results') }}
                        </p>
                    </div>
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
