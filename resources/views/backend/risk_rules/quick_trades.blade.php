@extends('backend.layouts.rms')
@section('title')
    {{ __('Quick Trades Analysis') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="innerMenu card p-6 mb-5 active">
      <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open w-full">
          <li class="nav-item">
              <a href="javascript:void(0)" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 active trades-status-filter" id="all-trades-filter">
                  All Trades
              </a>
          </li>
          <li class="nav-item">
              <a href="javascript:void(0)" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 trades-status-filter" id="profit-trades-filter">
                  Profit Trades Only
              </a>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 trades-status-filter" id="loss-trades-filter">
                Loss Trades Only
            </a>
        </li>
      </ul>
    </div>
    <div class="innerMenu card p-5 mb-3">
      <form action="{{ route('admin.risk-rule.quick_trades') }}" method="GET" >
        <div class="flex items-center gap-3">
            
            <div class="input-area relative flex items-center gap-5">
                <label for="group" class="form-label !w-auto min-w-max" style="position: relative; top: 4px">{{ __('Data From') }}</label>
                @php
                  if( request()->has('dataFrom') ) {
                    $data_from = request('dataFrom');
                    $data_to = request('dataTo');
                  } else {
                    $data_from = explode(' ', $risk_rule->data_from)[0];
                    $data_to = explode(' ', $risk_rule->data_to)[0];
                  }
                @endphp
                <input type="date" class="form-control" name="dataFrom" value="{{ $data_from }}" min="2024-{{ \Carbon\Carbon::now()->format('m') }}-01" max="{{ \Carbon\Carbon::today()->toDateString() }}">
            </div>
            <div class="input-area relative flex items-center gap-5">
              <label for="group" class="form-label !w-auto min-w-max ml-5" style="position: relative; top: 4px">{{ __('Data To') }}</label>
              <input type="date" class="form-control" name="dataTo" value="{{ $data_to }}" min="2024-{{ \Carbon\Carbon::now()->format('m') }}-01" max="{{ \Carbon\Carbon::today()->toDateString() }}">
            </div>

            {{-- <div class="input-area relative flex items-center gap-5">
              <label for="group" class="form-label !w-auto min-w-max ml-5" style="position: relative; top: 4px">{{ __('Profit or Loss') }}</label>
              <select class="form-control" name="reportFlag" id="">
                <option value="0" selected>All Trades</option>
                <option value="1">Profit Trades Only</option>
                <option value="2">Loss Trades Only</option>
              </select>
            </div> --}}


            <button type="submit" id="fetch-positions" style="padding-top: 7px; padding-bottom: 7px " class="btn inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 ml-5 !font-normal dark:text-white">
                <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                {{ __('Fetch Data') }}
            </button>
          </div>
        </form>
    </div>
    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class="col-span-8 hidden"></span>
                <span class="col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="positions-table">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('User') }}</th>
                                    <th scope="col" class="table-th">{{ __('Login ID') }}</th>
                                    <th scope="col" class="table-th">{{ __('Symbol') }}</th>
                                    <th scope="col" class="table-th">{{ __('Trade Time Difference') }}</th>
                                    <th scope="col" class="table-th">{{ __('Trade Started At') }}</th>
                                    <th scope="col" class="table-th">{{ __('Trade Closed At') }}</th>
                                    <th scope="col" class="table-th">{{ __('Profit/Loss') }}</th>
                                    <th scope="col" class="table-th">{{ __('Price') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                              @forelse ($data as $item)
                                <tr class="item-row" data-trade-status="{{ $item['profit'] > 0 ? 'profit' : 'loss' }}">
                                  @php
                                    $account = $accounts->where('login', $item['loginID'])->first();
                                    $user = $account ? $account->user : null;
                                  @endphp
                                  <td class="table-td">
                                      {{ $user ? ($user->first_name . ' ' . $user->last_name) : 'N/A' }}
                                  </td>
                                  <td class="table-td">{{ $item['loginID'] }}</td>
                                  <td class="table-td">{{ $item['symbol'] }}</td>
                                  <td class="table-td">{{ $item['timeDifference'] }} seconds</td>
                                  <td class="table-td">{{ \Carbon\Carbon::parse($item['positionOpenDateTime'])->format('g:i:s A, d M Y') }}
                                  </td>
                                  <td class="table-td">{{ \Carbon\Carbon::parse($item['positionCloseDateTime'])->format('g:i:s A, d M Y') }}</td>
                                  <td class="table-td"> <span class="badge badge-{{ $item['profit'] < 0 ? 'danger' : 'success' }}">{{ $item['profit'] < 0 ? $item['profit'] * -1 : $item['profit'] }} {{ $currency }}</span> </td>
                                  <td class="table-td">{{ $item['price'] }} {{ $currency }}</td>
                                </tr>
                              @empty
                                <tr>
                                  <td colspan="7" style="padding: 10px"> <center><small>No Data Available!</small></center> </td>
                                </tr>
                              @endforelse
                              
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="processingIndicator" class="text-center hidden">
                <iconify-icon class="spining-icon text-5xl dark:text-slate-100" icon="lucide:loader"></iconify-icon>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>

      $('.trades-status-filter').on('click', function(){
        $('.trades-status-filter').removeClass('active')
        $(this).addClass('active')
      })

      $('#all-trades-filter').on('click', function() {
        $('.item-row').css('display', 'table-row')
      })
      $('#profit-trades-filter').on('click', function() {
        $('.item-row').css('display', 'table-row')
        $('.item-row').each(function() {
            if ($(this).attr('data-trade-status') != 'profit') {
                $(this).css('display', 'none');
            }
        });

      })
      $('#loss-trades-filter').on('click', function() {
        $('.item-row').css('display', 'table-row')
        $('.item-row').each(function() {
            if ($(this).attr('data-trade-status') != 'loss') {
                $(this).css('display', 'none');
            }
        });

      })
    </script>
    <script>
        // $(document).ready(function() {
        //     const positionsTable = $('#positions-table').DataTable({
        //         dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
        //         processing: true,
        //         searching: false,
        //         lengthChange: false,
        //         info: true,
        //         autoWidth: false,
        //         language: {
        //             lengthMenu: "Show _MENU_ entries",
        //             info: "Showing _START_ to _END_ of _TOTAL_ entries",
        //             paginate: {
        //                 previous: "<iconify-icon icon=\"ic:round-keyboard-arrow-left\"></iconify-icon>",
        //                 next: "<iconify-icon icon=\"ic:round-keyboard-arrow-right\"></iconify-icon>"
        //             },
        //             search: "Search:",
        //             processing: '<iconify-icon icon="lucide:loader"></iconify-icon>'
        //         },
        //         data: [], // Start with an empty data array
        //         columns: [
        //             { data: 'login', name: 'login' },
        //             { data: 'symbol', name: 'symbol' },
        //             { data: 'action', name: 'action' },
        //             { data: 'position', name: 'position' },
        //             { data: 'priceOpen', name: 'priceOpen' },
        //             { data: 'priceCurrent', name: 'priceCurrent' },
        //             { data: 'priceSL', name: 'priceSL' },
        //             { data: 'priceTP', name: 'priceTP' },
        //             { data: 'volume', name: 'volume' },
        //             { data: 'profit', name: 'profit' },
        //             { data: 'rateProfit', name: 'rateProfit' },
        //             { data: 'rateMargin', name: 'rateMargin' },
        //             { data: 'reason', name: 'reason' },
        //             { data: 'timeCreate', name: 'timeCreate' },
        //         ]
        //     });

        //     $('#fetch-positions').click(function() {
        //         const group = $('#group').val();

        //         $('#processingIndicator').removeClass('hidden');
        //         positionsTable.clear();

        //         $.ajax({
        //             url: '{{ route('admin.positions.group') }}',
        //             type: 'POST',
        //             data: {
        //                 group: group,
        //                 _token: '{{ csrf_token() }}'
        //             },
        //             success: function(data) {
        //                 positionsTable.rows.add(data.data).draw();
        //             },
        //             error: function(xhr, status, error) {
        //                 alert('An error occurred: ' + error);
        //             },
        //             complete: function() {
        //                 $('#processingIndicator').addClass('hidden');
        //             }
        //         });
        //     });
        // });
    </script>
@endsection
