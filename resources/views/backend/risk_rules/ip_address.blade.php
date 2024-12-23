@extends('backend.layouts.rms')
@section('title')
    {{ __('IP Address Analysis') }}
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
                                    <th scope="col" class="table-th">{{ __('IP Address Count') }}</th>
                                    <th scope="col" class="table-th">{{ __('IP Address(es)') }}</th>
                                    <th scope="col" class="table-th">{{ __('Registration Time') }}</th>
                                    <th scope="col" class="table-th">{{ __('Last Access Time') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                              @forelse ($data as $item)
                                <tr class="item-row">
                                  @php
                                    $account = $accounts->where('login', $item['loginID'])->first();
                                    $user = $account ? $account->user : null;
                                  @endphp
                                  <td class="table-td">
                                      {{ $user ? ($user->first_name . ' ' . $user->last_name) : 'N/A' }}
                                  </td>
                                  <td class="table-td">{{ $item['loginID'] }}</td>
                                  <td class="table-td">{{ $item['ip_count'] }}</td>
                                  <td class="table-td">
                                    @forelse ($item['ip_addresses'] as $ip)
                                      <span class="badge badge-primary mr-2">{{ $item['lastIP'] == '' ? 'N/A' : $ip }}</span>
                                    @endforeach
                                  </td>
                                  <td class="table-td">{{ $item['registrationTime'] }}</td>
                                  <td class="table-td">{{ $item['lastAccessTime'] }}</td>
                                  
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

    @if (count($risk_rule->criteria) != 0)
      {{-- Configuration Modal --}}
      @include('backend.risk_rules.includes.configure-modal')
    @endif

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
