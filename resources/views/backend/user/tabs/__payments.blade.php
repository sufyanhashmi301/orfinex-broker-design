<div class="tab-pane fade" id="payments" role="tabpanel">
    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class="col-span-8 hidden"></span>
                <span class="col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700"
                            id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">Transaction ID</th>
                                    <th scope="col" class="table-th">Amount</th>
                                    <th scope="col" class="table-th">Fee</th>
                                    <th scope="col" class="table-th">Gateway</th>
                                    <th scope="col" class="table-th">Status</th>
                                    <th scope="col" class="table-th">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach ($payments as $payment)
                                  <tr>
                                    <td scope="col" class="table-td">{{ $payment->tnx }}</td>
                                    <td scope="col" class="table-td">
                                      <strong class="{{ $payment->type !== 'subtract' && $payment->type !== 'investment' && $payment->type !==  'withdraw' && $payment->type !==  'send_money' ? 'text-success-500': 'text-danger-500'}}">{{ ($payment->type !== 'subtract' && $payment->type !== 'investment' && $payment->type !==  'withdraw' && $payment->type !==  'send_money' ? '+': '-' ).$payment->amount.' '.$currency }}</strong>
                                    </td>
                                    <td scope="col" class="table-td">
                                      {{ $payment->charge . ' ' . $currency }}
                                    </td>
                                    <td scope="col" class="table-td">
                                      {{ $payment->method }}
                                    </td>
                                    <td scope="col" class="table-td">
                                      @if($payment->status->value == 'pending')
                                          <div class="badge bg-warning-500 text-warning-500 bg-opacity-30 capitalize">
                                              Pending
                                          </div>
                                      @elseif($payment->status->value == 'success')
                                          <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
                                              Success
                                          </div>
                                      @elseif($payment->status->value == 'failed')
                                          <div class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">
                                              Cancelled
                                          </div>
                                      @endif
                                    </td>
                                    <td scope="col" class="table-td">
                                      @can('deposit-action')
                                          <span type="button" data-id="{{ $payment->id }}" id="deposit-action">
                                              <button class="round-icon-btn action-btn red-btn" data-bs-toggle="tooltip" title="" data-bs-original-title="Approval Process">
                                                  <iconify-icon class="" style="font-size: 16px" icon="lucide:eye"></iconify-icon>
                                              </button>

                                              
                                          </span>

                                          <span type="button" class="ml-1" data-id="{{ $payment->id }}" id="show-invoice">
                                              <a href="{{ route('admin.invoice.show', ["id" =>  $payment->id ]) }}" target="__blank" class="round-icon-btn action-btn red-btn" data-bs-toggle="tooltip" title="" data-bs-original-title="Invoice">
                                                  <iconify-icon class="" style="font-size: 16px" icon="lucide:receipt"></iconify-icon>
                                              </a>
                                          </span>

                                      @endcan
                                    </td>
                                  </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div
                            class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 mt-auto">
                            <div>
                                @php
                                    $from = $payments->firstItem();
                                    $to = $payments->lastItem();
                                    $total = $payments->total();
                                @endphp
                                <p class="text-sm text-gray-700 py-3">
                                    Showing <span class="font-medium">{{ $from }}</span> to <span
                                        class="font-medium">{{ $to }}</span> of <span
                                        class="font-medium">{{ $total }}</span> results
                                </p>
                            </div>
                            {{ $payments->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Pending Deposit Approval -->
@can('deposit-action')
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="deposit-action-modal" tabindex="-1" aria-labelledby="deposit-action-modal" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
      <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="popup-body-text deposit-action p-6">

                </div>
            </div>
        </div>
    </div>
</div>
@endcan

@push('single-script')
  <script>
    $('body').on('click', '#deposit-action', function () {
        $('.deposit-action').empty();
        var id = $(this).data('id');
        var url = '{{ route("admin.deposit.action",":id") }}';
        url = url.replace(':id', id);
        $.get(url, function (data) {
            $('.deposit-action').append(data)
            imagePreview()
        });

        $('#deposit-action-modal').modal('toggle');
    })
  </script>
@endpush
