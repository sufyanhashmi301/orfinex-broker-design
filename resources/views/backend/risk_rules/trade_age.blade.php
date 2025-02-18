@extends('backend.layouts.rms')
@section('title')
    {{ __('Trade Age Analysis') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>

        @if (count($risk_rule->criteria) != 0)
          <a href="" class="btn btn-primary inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#config-modal">
            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:bolt"></iconify-icon>
            Configure Parameters
          </a>
        @endif

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
                                    <th scope="col" class="table-th">{{ __('Position') }}</th>
                                    <th scope="col" class="table-th">{{ __('Price Open') }}</th>
                                    <th scope="col" class="table-th">{{ __('Price Current') }}</th>
                                    <th scope="col" class="table-th">{{ __('Profit/Loss (+/- 1hr)') }}</th>
                                    <th scope="col" class="table-th">{{ __('Trade Created at') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                              @forelse ($data as $item)
                                <tr class="item-row" data-trade-status="{{ $item['profit'] > 0 ? 'profit' : 'loss' }}">
                                  @php
                                    $account = $accounts->where('login', $item['login'])->first();
                                    $user = $account ? $account->user : null;
                                  @endphp
                                  <td class="table-td">
                                      {{ $user ? ($user->first_name . ' ' . $user->last_name) : 'N/A' }}
                                  </td>
                                  <td class="table-td">{{ $item['login'] }}</td>
                                  <td class="table-td">{{ $item['symbol'] }}</td>
                                  <td class="table-td">{{ $item['position'] }}</td>
                                  <td class="table-td">{{ $item['priceOpen'] }}</td>
                                  <td class="table-td">{{ $item['priceCurrent'] }}</td>
                                  <td class="table-td"> <span class="badge badge-{{ $item['profit'] < 0 ? 'danger' : 'success' }}">{{ $item['profit'] < 0 ? $item['profit'] * -1 : $item['profit'] }} {{ $currency }}</span> </td>
                                  <td class="table-td">{{ \Carbon\Carbon::createFromFormat('m/d/Y H:i:s', $item['timeCreate'])->format('h:i:s A, d M Y') }}</td>
                                </tr>
                              @empty
                                <tr>
                                  <td colspan="8" style="padding: 10px"> <center><small>No Data Available!</small></center> </td>
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

    @if (count($risk_rule->criteria) != 0)
      {{-- Configuration Modal --}}
      @include('backend.risk_rules.includes.configure-modal')
    @endif

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
    
@endsection
