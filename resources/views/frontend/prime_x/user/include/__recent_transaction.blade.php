<div class="card">
    <header class="card-header noborder">
        <h4 class="card-title">{{ __('Recent Payments') }}</h4>
        <div>
            <a href="{{ route('user.billing.index') }}" class="btn-link inline-flex items-center">
                {{ __('See All Payments') }}
                <iconify-icon class="text-lg ltr:ml-1 rtl:mr-1" icon="lucide:chevron-right"></iconify-icon>
            </a>
        </div>
    </header>
    <div class="card-body p-6 pt-0">
        <!-- BEGIN: Company Table -->
        @if(count($transactions) == 0)
            <div class="flex items-center justify-center flex-col gap-3">
                <img src="{{ asset('frontend/images/icon/danger.png') }}" alt="">
                <p class="text-lg text-slate-600 dark:text-slate-100 mb-3">
                    {{ __("You don't have any transaction yet.") }}
                </p>
            </div>
        @else
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Challenge') }}</th>
                                    <th scope="col" class="table-th">{{ __('Amount to pay') }}</th>
                                    {{-- <th scope="col" class="table-th">{{ __('Order') }}</th> --}}
                                    <th scope="col" class="table-th">{{ __('Created At') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Invoice') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $txn)    
                                    @php
                                        $account = $accounts->find($txn->target_id);
                                        if(!empty($account)){
                                            $account_type = $account->getAccountTypeSnapshotData();
                                        } else {
                                            $account_type['title'] = 'N/A';
                                            continue;
                                        }
                                    @endphp
                                    <tr>
                                        <td class="table-td">
                                            <div class="text-start">
                                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                    {{ $account_type['title'] }}
                                                </h4>
                                                <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                                    TID: {{ $txn->tnx }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="table-td">
                                            <span class="font-semibold">
                                                {{ number_format($txn->final_amount, 2) . ' ' . $currency }}
                                            </span>
                                        </td>
                                        <td class="table-td">
                                            <div class="text-start">
                                                <span class="block">{{ $txn->created_at }}</span>
                                            </div>
                                        </td>

                                        @php
                                            $badge = '';
                                            if($txn->status == \App\Enums\TxnStatus::Success){
                                                $badge = 'success';
                                            } elseif ($txn->status == \App\Enums\TxnStatus::Pending) {
                                                $badge = 'warning';
                                            } elseif ($txn->status == \App\Enums\TxnStatus::Failed) {
                                                $badge = 'danger';
                                            }
                                        @endphp

                                        <td class="table-td">
                                            <span class="badge bg-{{ $badge }}-500 text-{{ $badge }}-500 bg-opacity-30 capitalize">
                                                {{ $txn->status }}
                                            </span>
                                        </td>
                                        <td class="table-td">
                                            
                                            <a href="{{ route('user.billing.generateInvoice', ["transaction_id" => $txn->id ]) }}" target="__blank" class="action-btn">
                                                <iconify-icon icon="heroicons-outline:download"></iconify-icon>
                                            </a>
                                        
                                        </td>
                                    </tr>
                                @endforeach
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
