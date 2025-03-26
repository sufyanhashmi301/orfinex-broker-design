<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="payoutRequestDetails{{ $request->id }}" tabindex="-1" aria-labelledby="payoutRequestDetails{{ $request->id }}ModalLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="flex items-center justify-between p-5">
                <h3 class="text-xl font-medium dark:text-white capitalize" id="payoutRequestDetails{{ $request->id }}Label">
                    Payout Request Details
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
            <div class="modal-body p-6 pt-0">
                
              <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                <thead>
                  <tr>
                    <th scope="col" class="table-th">Funded Account Login</th>
                    <th scope="col" class="table-th">Total Profit</th>
                    <th scope="col" class="table-th">User Profit Percentage</th>
                    <th scope="col" class="table-th">User Profit</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($request->detail as $single_request)
                    <tr>
                      <td class="table-td">
                        {{ $single_request['login'] }}
                      </td>
                      <td class="table-td">{{ number_format($single_request['total_profit'], 2) }} {{$currency}}</td>
                      <td class="table-td">{{ $single_request['user_profit_percentage'] }}%</td>
                      <td class="table-td">{{ number_format($single_request['user_profit_share_amount'], 2) }} {{$currency}}</td>
                    </tr>
                  @endforeach
                  
                </tbody>
              </table>

              <div style="padding-top: 20px;padding-left: 12px;">
                <b>Total Profit Share Amount: </b>{{ number_format($request->user_profit_share_amount, 2) . ' ' . $currency }} 
              </div>
              
              <div class="action-btns text-right">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                  {{ __('Close') }}
               </button>
              </div>

            </div>
        </div>
    </div>
</div>