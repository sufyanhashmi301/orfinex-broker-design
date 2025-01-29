<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
    id="payoutRequest"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="flex items-center justify-between p-5">
                <h3 class="text-xl font-medium dark:text-white capitalize" id="addTagsLabel">
                    {{ __('Create Payout Request') }}
                </h3>
                <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                            dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="modal-body pb-6  pt-0" style="padding-right: 20px">
                {{-- <form action="{{ route('user.withdraw.payout_request') }}" method="post" class="space-y-5">
                    @csrf --}}
                    
                    <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                      <thead>
                        <tr>
                          <th scope="col" class="table-th">Funded Account Login</th>
                          <th scope="col" class="table-th">Total Profit</th>
                          <th scope="col" class="table-th">Your Profit Percentage</th>
                          <th scope="col" class="table-th">Your Profit</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php
                          $total_user_profit_share_amount = 0;
                        @endphp
                        @forelse ($funded_balances as $fb)
                          @php
                            $net_profit = $fb->profit - $fb->payout_pending;
                          @endphp
                          <tr>
                            <td class="table-td">
                              <b>
                                <a target="_blank" style="text-decoration: underline" href="{{ route('user.investment.trading-stats', ["account_id" => $fb->accountTypeInvestment->id ]) }}">{{ $fb->accountTypeInvestment->login }}</a>
                              </b>
                            </td>
                            <td class="table-td">{{ number_format($net_profit, 2) }} {{$currency}}</td>
                            <td class="table-td">{{ $fb->user_profit_share }}%</td>
                            @php
                              $user_profit_share_amount = ( $net_profit * $fb->user_profit_share ) / 100;
                              $total_user_profit_share_amount += $user_profit_share_amount;
                            @endphp
                            <td class="table-td">{{ number_format($user_profit_share_amount, 2) }} {{$currency}}</td>
                          </tr>
                        @empty
                          <tr>
                            <td colspan="4" style="padding-top: 5px"><center><small>No Available Profit</small></center></td>
                          </tr>
                        @endforelse
                        
                      </tbody>
                    </table>

                    <div style="padding-left: 20px; padding-top: 20px">
                      <b>Total Profit Share Amount: </b>{{ number_format($total_user_profit_share_amount, 2) . ' ' . $currency }} 
                    </div>

                    <div class="action-btns text-right">
                        <a href="{{ route('user.withdraw.payout_request', ['wallet_unique_id' => $payout_wallet->unique_id]) }}" class="btn btn-dark inline-flex items-center justify-center {{ $total_user_profit_share_amount == 0 ? 'disabled' : '' }}" >
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                            {{ __('Create Payout Request') }}
                        </a>
                    </div>

                    <style>
                      a.disabled {
                        opacity: 0.6;
                      }
                      a.disabled {
                        pointer-events: none
                      }
                    </style>
                {{-- </form> --}}
            </div>
        </div>
    </div>
</div>
