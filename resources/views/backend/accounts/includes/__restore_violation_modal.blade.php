<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="restore-violation{{ $account->id }}" tabindex="-1" aria-labelledby="restore-violation{{ $account->id }}ModalLabel"
    aria-modal="true" role="dialog">
    <div class="modal-dialog modal-md top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
      <form action="{{ route('admin.account.restore_violated_account', ['id' => $account->id]) }}" method="POST" class="restore-violated-account-form">
        @csrf
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">

            <div class="flex items-center justify-between p-5 rounded-t">
                <h3 class="text-xl font-medium dark:text-white capitalize">
                    Restore Violated Account: <b>#{{$account->login}}</b>
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

              <div class="site-input-area relative">
                <label for="" class="form-label ">Restore to...</label>
                <select name="restore_method" id="" class="form-control restore-to">
                  <option value="original">Original Allotted Funds</option>
                  <option value="custom">Custom Value</option>
                </select>
              </div>  

              @php
                $allotted_funds_with_profit_target = $account->getRuleSnapshotData()['allotted_funds'] + $account->getRuleSnapshotData()['profit_target'];
                $allotted_funds_with_dd = $account->getRuleSnapshotData()['allotted_funds'] - $account->getRuleSnapshotData()['daily_drawdown_limit'];
              @endphp

              <div class="site-input-area relative mt-3">
                <label for="" class="form-label">Equity and Balance ({{ $allotted_funds_with_dd + 1 . ' ' . $currency }} - {{ $allotted_funds_with_profit_target - 1 . ' ' . $currency }})</label>
                <input required type="number" name="balance" class="form-control equity-balance" style="margin: 0" name="title" data-original-value="{{ $account->getRuleSnapshotData()['allotted_funds'] }}" value="" required="">
              </div> 
              
              <br>

              <h6>Original Account Details</h6>
              <div class="mt-2" style="font-size: 14px">
                <b class="capitalize">Account Title:</b> {{ $account->getAccountTypeSnapshotData()['title'] }}
                <br>
                <b class="capitalize">Allotted Funds:</b> {{ $account->getRuleSnapshotData()['allotted_funds'] }} {{ $currency }}
                <br>
                <b class="capitalize">Profit Target:</b> {{ $account->getRuleSnapshotData()['profit_target'] }} {{ $currency }}
                <br>
                <b class="capitalize">Daily Drawdown:</b> {{ $account->getRuleSnapshotData()['daily_drawdown_limit'] }} {{ $currency }}
                <br>
                <b class="capitalize">Max. Drawdown:</b> {{ $account->getRuleSnapshotData()['max_drawdown_limit'] }} {{ $currency }}
                <br>
                <b class="capitalize">Min. Trading Days:</b> {{ $account->getRuleSnapshotData()['trading_days'] ?? 'N/A' }} Days
              </div>

            </div>

            <div class="modal-footer p-3">
              <button type="button" class="btn btn-primary float-right" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary float-right mr-2 restore-btn">Restore</button>
            </div>
          </div>
        </form>
        <style>
          input.equity-balance:read-only {
            background: #eee
          }
          button:disabled {
            opacity: 0.6;
          }
        </style>
    </div>
</div>

@push('single-script')
  <script>
    let restore_method_evaluate = () => {
      for(let i=0; i < $('.restore-to').length; i++) {
        if($('.restore-to').eq(i).val() == 'original') {
          $('.equity-balance').eq(i).val( $('.equity-balance').eq(i).attr('data-original-value') )
          $('.equity-balance').eq(i).prop('readonly', true)
        } 

        if($('.restore-to').eq(i).val() == 'custom') {
          $('.equity-balance').eq(i).prop('readonly', false)
        } 
      }
      
    }
    restore_method_evaluate()

    $('.restore-to').on('change', function() {
      restore_method_evaluate()
    })
    $('.restore-violated-account-form').on('submit', function() {
      $('.restore-btn').prop('disabled', true)
    })
  </script>
@endpush
