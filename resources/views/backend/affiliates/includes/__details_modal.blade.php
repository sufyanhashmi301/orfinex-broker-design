<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="commission-details-modal{{$user->id}}" tabindex="-1"  aria-modal="true" style="padding-top: 130px"
  role="dialog">
  <div class="modal-dialog modal-dialog-scrollable relative modal-lg w-auto pointer-events-none" >
      <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
          <div class="flex items-center justify-between p-5">
              <h3 class="text-xl font-medium dark:text-white capitalize" id="commission-details-modalLabel{{$user->id}}">
                  Commission Details of {{ $user->first_name }}'s Affiliate Account
              </h3>
              <button type="button"
                  class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                        dark:hover:bg-slate-600 dark:hover:text-white"
                  data-bs-dismiss="modal">
                  <svg aria-hidden="true" class="w-5 h-5" fill="#000000" viewBox="0 0 20 20"
                      xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                            11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                          clip-rule="evenodd"></path>
                  </svg>
                  <span class="sr-only">Close modal</span>
              </button>
          </div>
          <div class="modal-body p-6 pt-0" style="max-height: 500px">
            
            {{-- Basic Details --}}
            <div class="grid lg:grid-cols-2 grid-cols-1 gap-5 mb-6">
                <div class="card">
                    <div class="card-body p-6">
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-3">
                            Total Commission
                        </p>
                        <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                            {{ $commission->total_commission }} {{ $currency }}
                        </h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-6">
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-3">
                            Withdrawable Commission
                        </p>
                        <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                            {{ $commission->withdrawable_balance }} {{ $currency }}
                        </h6>
                    </div>
                </div>

                

                <div class="card">
                    <div class="card-body p-6">
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-3">
                            Pending Commission
                        </p>
                        <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                            @php
                                $total_pending_commissions = collect($commission->commission_pending)
                                    ->where('status', 'pending')
                                    ->sum('commission');
                            @endphp
                            {{ number_format($total_pending_commissions, 2) }} {{ $currency }}
                        </h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-6">
                        <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-3">
                            Commision Withdrawn
                        </p>
                        <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                            {{ $commission->commission_withdrawn }} {{ $currency }}
                        </h6>
                    </div>
                </div>
                
            </div>

            {{-- Pending Commission --}}
            <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-3">
                Pending Commissions
            </p>
            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="dataTable" >
                <thead>
                    <tr>
                        <th scope="col" class="table-th">{{ __('Commission Value') }}</th>
                        <th scope="col" class="table-th">{{ __('Receiving Date') }}</th>
                        <th scope="col" class="table-th">{{ __('Created At.') }}</th>
                        <th scope="col" class="table-th">{{ __('Retention Period') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                    
                    @forelse (collect($commission->commission_pending)->where('status', 'pending') as $item)

                        <tr>
                            <td scope="col" class="table-td">{{ number_format($item['commission'], 2) }} {{ $currency }}</td>
                            <td scope="col" class="table-td">{{ $item['receiving_date'] }}</td>
                            <td scope="col" class="table-td">{{ $item['created_at'] }}</td>
                            <td scope="col" class="table-td">{{ $item['balance_retention_period'] }} Days</td>
                        </tr>
                    
                    @empty
                        <tr>
                            <td scope="col" class="table-td" colspan="4">
                                <center>No Pending Commissions</center>
                            </td>
                        </tr>
                    @endforelse
                        
                </tbody>
            </table>
              
          </div>
          <div class="action-btns text-right mt-3 modal-footer p-3">
            <button type="button" class="btn btn-dark inline-flex items-center justify-center" data-bs-dismiss="modal">
                Close
            </button>
        </div>
      </div>
  </div>
</div>