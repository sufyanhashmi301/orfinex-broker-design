<div class="card">
    <div class="card-body p-6 pt-3">
        <div class="overflow-x-auto -mx-6">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden ">
                    <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                        <thead>
                            <tr>
                                <th scope="col" class="table-th">{{ __('Position') }}</th>
                                <th scope="col" class="table-th">{{ __('Time') }}</th>
                                <th scope="col" class="table-th">{{ __('Symbol') }}</th>
                                <th scope="col" class="table-th">{{ __('Type') }}</th>
                                <th scope="col" class="table-th">{{ __('Volume') }}</th>
                                <th scope="col" class="table-th">{{ __('Price') }}</th>
                                <th scope="col" class="table-th">{{ __('S/L') }}</th>
                                <th scope="col" class="table-th">{{ __('T/P') }}</th>
                                <th scope="col" class="table-th">{{ __('Swap') }}</th>
                                <th scope="col" class="table-th">{{ __('Profit') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($deals->isEmpty())
                            <tr>
                                <td class="table-td text-center" colspan="10">
                                    <span class="block my-4">
                                        {{ __('No Open Trades found for this account.') }}
                                    </span>
                                </td>
                            </tr>
                        @else
                            @foreach($deals as $deal)
                                <tr>
                                    <td class="table-td">{{ $deal->Position }}</td>
                                    <td class="table-td">{{$deal->TimeCreate}}</td>
                                    <td class="table-td">{{$deal->Symbol}}</td>
                                    <td class="table-td">
                                        @if($deal->Action == 0)
                                            <span class="badge bg-success text-white capitalize">
                                                {{ __('Buy') }}
                                            </span>
                                        @elseif($deal->Action == 1)
                                            <span class="badge bg-danger text-white capitalize">
                                                {{ __('Sell') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        {{ $deal->Volume / 10000 }}
                                    </td>
                                    <td class="table-td">{{$deal->PriceOpen}}</td>
                                    <td class="table-td">{{$deal->PriceSL}}</td>
                                    <td class="table-td">{{$deal->PriceTP}}</td>
                                    <td class="table-td">{{$deal->Storage}}</td>
                                    <td class="table-td">{{$deal->Profit}}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    {{  $deals->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
