<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="violation-data{{ $account->id }}" tabindex="-1" aria-labelledby="violation-data{{ $account->id }}ModalLabel"
    aria-modal="true" role="dialog">
    <div class="modal-dialog modal-md top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">

            <div class="flex items-center justify-between p-5 rounded-t">
                <h3 class="text-xl font-medium dark:text-white capitalize">
                    {{ __('Account Violation Details') }}
                </h3>
                <button type="button"
                    class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                    data-bs-dismiss="modal">
                    <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" viewbox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-6 pt-0">
                
              @if (!empty($account->violation_data))
                  <div class="mt-2" style="font-size: 14px">
                    <b class="capitalize">Balance:</b> {{ $account->violation_data['balance'] }} {{ $currency }}
                  </div>
                  <div class="mt-2" style="font-size: 14px">
                    <b class="capitalize">Equity:</b> {{ $account->violation_data['equity'] }} {{ $currency }}
                  </div>
                  <div class="mt-2" style="font-size: 14px">
                    <b class="capitalize">Floating PnL:</b> {{ $account->violation_data['floating_pnl'] }} {{ $currency }}
                  </div>
                  <div class="mt-2" style="font-size: 14px">
                    <b class="capitalize">Daily Drawdown PnL:</b> 
                    {{ $account->violation_data['daily_drawdown_pnl'] }} {{ $currency }} 
                    <b>
                      {!! $account->violation_data['daily_drawdown_remaining_loss_limit'] == 'Limit Over' ? '<span style="color: red">(Violated)</span>' :  
                      ' (Remaining Limit' . $account->violation_data['daily_drawdown_remaining_loss_limit'] . ') ' . $currency !!}
                    </b>
                  </div>
                  <div class="mt-2" style="font-size: 14px">
                    <b class="capitalize">Max Drawdown PnL:</b> {{ $account->violation_data['max_drawdown_pnl'] }} {{ $currency }}
                    <b>
                      {!! $account->violation_data['max_drawdown_remaining_loss_limit'] == 'Limit Over' ? '<span style="color: red">(Violated)</span>' :  ' (Remaining Limit: ' . $account->violation_data['max_drawdown_remaining_loss_limit'] . ' ' . $currency . ') ' !!}
                    </b>
                  </div>
                  <div class="mt-2" style="font-size: 14px">
                    <b class="capitalize">Violated at:</b> {{ \Carbon\Carbon::parse($account->violation_data['violated_at'])->format('d M, Y h:i:s A') }}
                  </div>
              @else
                <center>No Violation Data Available</center>
              @endif

            </div>

            <div class="modal-footer p-3">
              <button class="btn btn-primary float-right" data-bs-dismiss="modal">Close</button>
              <button class="btn btn-primary float-right mr-2" data-bs-toggle="modal" data-bs-target="#restore-violation{{$account->id}}" data-bs-dismiss="modal">Restore</button>
            </div>

        </div>
        
    </div>
</div>
