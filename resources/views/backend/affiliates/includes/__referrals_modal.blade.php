<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" style="padding-top: 130px" id="refferals-modal{{$user->id}}" tabindex="-1"  aria-modal="true"
  role="dialog">
  <div class="modal-dialog modal-dialog-scrollable top-1/2 !-translate-y-1/2 relative modal-lg w-auto pointer-events-none">
      <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
          <div class="flex items-center justify-between p-5">
              <h3 class="text-xl font-medium dark:text-white capitalize" id="refferals-modalLabel{{$user->id}}">
                  All Referrals of {{ $user->first_name }}
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
            
            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="dataTable" >
                <thead>
                    <tr>
                        <th scope="col" class="table-th">{{ __('#') }}</th>
                        <th scope="col" class="table-th">{{ __('User') }}</th>
                        <th scope="col" class="table-th">{{ __('Affiliate Account Exists') }}</th>
                        <th scope="col" class="table-th">{{ __('Affiliate ID') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                    @foreach ($referrals as $referral)

                        <tr class="table-td">
                            <td scope="col" class="table-td">{{ $loop->index + 1 }}</td>
                            <td scope="col" class="table-td">{{ $referral->first_name . ' ' . $referral->last_name . ' (' . $referral->email . ')' }}</td>
                            <td scope="col" class="table-td">
                                @if ($referral->userAffiliate)
                                    <span class="text-success-500" data-bs-toggle="tooltip" data-bs-title="Active">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1.75rem" height="1.75rem" viewBox="0 0 24 24">
                                            <path fill="currentColor" fill-rule="evenodd" d="M12 1.25C6.063 1.25 1.25 6.063 1.25 12S6.063 22.75 12 22.75S22.75 17.937 22.75 12S17.937 1.25 12 1.25M7.53 11.97a.75.75 0 0 0-1.06 1.06l3 3a.75.75 0 0 0 1.06 0l7-7a.75.75 0 0 0-1.06-1.06L10 14.44z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                @else
                                    <span class="text-danger-500" data-bs-toggle="tooltip" data-bs-title="Unverified">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="1.75rem" height="1.75rem" viewBox="0 0 24 24">
                                            <path fill="currentColor" fill-rule="evenodd" d="M12 1.25C6.063 1.25 1.25 6.063 1.25 12S6.063 22.75 12 22.75S22.75 17.937 22.75 12S17.937 1.25 12 1.25M9.702 8.641a.75.75 0 0 0-1.061 1.06L10.939 12l-2.298 2.298a.75.75 0 0 0 1.06 1.06L12 13.062l2.298 2.298a.75.75 0 0 0 1.06-1.06L13.06 12l2.298-2.298a.75.75 0 1 0-1.06-1.06L12 10.938z" clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                @endif
                            </td>
                            <td scope="col" class="table-td"><span class="badge badge-primary">{{ $referral->userAffiliate->referral_link ?? '-' }}</span></td>
                        </tr>
                        
                    @endforeach
                        
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