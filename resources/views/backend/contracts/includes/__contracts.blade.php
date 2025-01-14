@if (count($contracts) != 0)
    <div class="card p-3">
        <div class="card-body p-6 pt-1">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                  <th scope="col" class="table-th">{{ __('User') }}</th>
                                  <th scope="col" class="table-th">{{ __('Login') }}</th>
                                  <th scope="col" class="table-th">{{ __('User Profit Share') }}</th>
                                  <th scope="col" class="table-th">{{ __('Created at.') }}</th>
                                  <th scope="col" class="table-th">{{ __('Expired at.') }}</th>
                                  <th scope="col" class="table-th">{{ __('Signed at.') }}</th>
                                  <th scope="col" class="table-th">{{ __('Status') }}</th>
                                  <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contracts as $contract)

                                     @php
                                        $badge = '';
                                        if($contract->status == \App\Enums\ContractStatusEnums::PENDING) {
                                          $badge = 'badge-secondary';
                                        } elseif ($contract->status == \App\Enums\ContractStatusEnums::SIGNED) {
                                          $badge = 'badge-success';
                                        } elseif ($contract->status == \App\Enums\ContractStatusEnums::EXPIRED) {
                                          $badge = 'badge-danger';
                                        }
                                    @endphp

                                    <tr class="pt-1">
                                        <td scope="col" class="table-td " >
                                            {{ $contract->user->first_name . ' ' .  $contract->user->last_name }}
                                          </td>
                                        <td scope="col" class="table-td">{{ $contract->accountTypeInvestment->login }}</td>
                                        
                                        <td scope="col" class="table-td"> 
                                          {{ $contract->user_profit_share }}%
                                        </td>
                                        <td scope="col" class="table-td">
                                          {{ $contract->created_at }}
                                        </td>

                                        <td scope="col" class="table-td">
                                          -
                                        </td>

                                        <td scope="col" class="table-td">
                                            {{ $contract->status == \App\Enums\ContractStatusEnums::SIGNED ? $contract->signed_at : '-' }}
                                        </td>
                                        {{-- <td scope="col" class="table-td">{{ str_replace('_', ' ', $certificate->date_info) }}</td> --}}
                                        
                                        <td scope="col" class="table-td">
                                          <span class="badge {{ $badge }}">{{ str_replace('_', ' ', $contract->status) }}</span>
                                        </td>

                                        <td scope="col" class="table-td">
                                          <div class="btn-group">
                                            @if ($contract->status == \App\Enums\ContractStatusEnums::SIGNED)
                                              <a href="{{ asset($contract->file_path) }}" target="__blank" class="btn btn-sm btn-primary mr-1">View Contract</a>
                                            @endif

                                            @if ($contract->status == \App\Enums\ContractStatusEnums::PENDING)
                                              <a href="#" target="__blank" class="btn btn-sm btn-primary mr-1">Mark as Signed</a>
                                              <a href="#" target="__blank" class="btn btn-sm btn-primary mr-1">Mark as Expired</a>
                                            @endif

                                            @if ($contract->status == \App\Enums\ContractStatusEnums::EXPIRED)
                                              <a href="#" target="__blank" class="btn btn-sm btn-primary mr-1">Mark as Signed</a>
                                              <a href="#" target="__blank" class="btn btn-sm btn-primary mr-1">Mark as Pending</a>
                                            @endif

                                          </div>
                                        </td>

                                    </tr>

                                    
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@else
    <div class="card basicTable_wrapper items-center justify-center py-10 px-10">
        <div class="flex items-center justify-center flex-col gap-3">
            <img src="{{ asset('frontend/images/icon/danger.png') }}" alt="">
            <p class="text-lg text-center text-slate-600 dark:text-slate-100 mb-3">
                {{ __('Nothing to see here.') }}
            </p>
        </div>
    </div>
@endif

@push('single-script')
  <script>
    


  </script>
@endpush
