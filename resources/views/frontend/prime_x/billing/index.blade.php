@extends('frontend::layouts.user')
@section('title')
    {{ __('Billing') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4 class="text-xl text-slate-600 dark:text-slate-300">
            @yield('title')
        </h4>
    </div>

    <div class="card">
        <div class="card-body px-6 pb-6">
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
                                @foreach ($billing_transactions as $txn)
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
                                            
                                            <a href="{{ route('user.billing.generateInvoice', ["transaction_id" => $txn->id ]) }}" class="action-btn">
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
        </div>
    </div>
@endsection
