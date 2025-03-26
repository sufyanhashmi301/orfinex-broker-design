<div class="tab-pane fade" id="withdraws" role="tabpanel" data-tab="withdraws">
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
                              @foreach ($withdraws as $withdraw)
                                <tr>
                                  <td scope="col" class="table-td">{{ $withdraw->tnx }}</td>
                                  <td scope="col" class="table-td">
                                    <strong class="{{ $withdraw->type !== 'subtract' && $withdraw->type !== 'investment' && $withdraw->type !==  'withdraw' && $withdraw->type !==  'send_money' ? 'text-success-500': 'text-danger-500'}}">{{ ($withdraw->type !== 'subtract' && $withdraw->type !== 'investment' && $withdraw->type !==  'withdraw' && $withdraw->type !==  'send_money' ? '+': '-' ).$withdraw->amount.' '.$currency }}</strong>
                                  </td>
                                  <td scope="col" class="table-td">
                                    {{ $withdraw->charge . ' ' . $currency }}
                                  </td>
                                  <td scope="col" class="table-td">
                                    {{ $withdraw->method }}
                                  </td>
                                  <td scope="col" class="table-td">
                                    @if($withdraw->status->value == 'pending')
                                        <div class="badge bg-warning-500 text-warning-500 bg-opacity-30 capitalize">
                                            Pending
                                        </div>
                                    @elseif($withdraw->status->value == 'success')
                                        <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
                                            Success
                                        </div>
                                    @elseif($withdraw->status->value == 'failed')
                                        <div class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">
                                            Cancelled
                                        </div>
                                    @endif
                                  </td>
                                  <td scope="col" class="table-td">
                                    @can('deposit-action')
                                        <span type="button" data-id="{{$withdraw->id}}" id="deposit-action">
                                            <button class="action-btn" data-bs-toggle="tooltip" title="" data-bs-original-title="Approval Process">
                                                <iconify-icon icon="lucide:eye"></iconify-icon>
                                            </button>
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
                                  $from = $withdraws->firstItem();
                                  $to = $withdraws->lastItem();
                                  $total = $withdraws->total();
                              @endphp
                              <p class="text-sm text-gray-700 py-3">
                                  Showing <span class="font-medium">{{ $from }}</span> to <span
                                      class="font-medium">{{ $to }}</span> of <span
                                      class="font-medium">{{ $total }}</span> results
                              </p>
                          </div>
                          {{ $withdraws->appends(request()->except('page'))->links() }}
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>


@push('single-script')
<script>
  $('body').on('click', '#deposit-action', function () {
      $('.deposit-action').empty();

      var id = $(this).data('id');
      $.ajax({
          url: '{{ route("admin.transactions.view", ":id") }}'.replace(':id', id),
          method: 'GET',
          success: function(response) {
              $('.deposit-action').append(response)
              imagePreview()
              $('#transaction-action-modal').modal('show');

          }
      });
  });
</script>
@endpush
