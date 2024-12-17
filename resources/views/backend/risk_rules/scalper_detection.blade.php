@extends('backend.layouts.rms')
@section('title')
    {{ __('Scalper Detection') }}
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
              <a href="&sort=htol" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 sort-data {{ request('sort') == 'htol' ? 'active' : '' }}" id="sort-htol">
                  Sort: High to Low
              </a>
          </li>
          <li class="nav-item">
            <a href="&sort=ltoh" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 sort-data {{ request('sort') == 'ltoh' ? 'active' : '' }}" id="sort-ltoh">
              Sort: Low to High
            </a>
        </li>
      </ul>
    </div>
    <div class="innerMenu card p-5 mb-3">
      <form action="{{ route('admin.risk-rule.scalper_detection') }}" method="GET" >
        <div class="flex items-center gap-3">
          
            <?php
              $today = \Carbon\Carbon::now();
              $minDate = $today->copy()->subDays(9)->format('Y-m-d'); // 10 days ago (including today)
              $maxDate = $today->format('Y-m-d'); // Today
            ?>

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
                <input type="date" class="form-control" name="dataFrom" value="{{ $data_from }}" min="{{ $minDate }}" max="{{ $maxDate }}">
            </div>
            <div class="input-area relative flex items-center gap-5">
              <label for="group" class="form-label !w-auto min-w-max ml-5" style="position: relative; top: 4px">{{ __('Data To') }}</label>
              <input type="date" class="form-control" name="dataTo" value="{{ $data_to }}" min="{{ $minDate }}" max="{{ $maxDate }}">
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
                                    <th scope="col" class="table-th">{{ __('Total Trades') }}</th>
                                    <th scope="col" class="table-th">{{ __('Risk Grade') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                              @forelse ($data as $item)
                                <tr class="item-row" data-total-trades="{{ $item['totalTrades'] }}">
                                  @php
                                    $account = $accounts->where('login', $item['loginID'])->first();
                                    $user = $account ? $account->user : null;
                                  @endphp
                                  <td class="table-td">
                                      {{ $user ? ($user->first_name . ' ' . $user->last_name) : 'N/A' }}
                                  </td>
                                  <td class="table-td">{{ $item['loginID'] }}</td>
                                  <td class="table-td">{{ $item['totalTrades'] }}</td>
                                  <td class="table-td"> <span class="badge badge-primary" style="width: 80px; height: 15px"></span> </td>
                                  
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

      $('.sort-data').on('click', function() {
        $('.sort-data').removeClass('active')
        $(this).addClass('active')
      })

      $(document).on('click', '.sort-data', function(e) {
        e.preventDefault(); // Prevent default link action

        const clickedLink = $(this).attr('href'); // Get the href of the clicked link
        const url = new URL(window.location.href); // Create a URL object

        // Extract the sort parameter from the clicked link
        const sortParam = clickedLink.split('=').pop(); // Get "htol" or "ltoh"

        // Update the URL with the new sort parameter
        url.searchParams.set('sort', sortParam);

        // Update the URL in the browser without reloading the page
        window.history.pushState(null, '', url);

        location.reload();
      });
      
    </script>
    
@endsection
