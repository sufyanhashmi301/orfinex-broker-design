<div class="col-span-12">
    <div class="card">
        <div class="card-body p-6 pt-0">
            <!-- BEGIN: Company Table -->
            @if(count($recentTransactions) == 0)    
                <div class="flex items-center justify-center flex-col gap-3">
                    <img src="{{ asset('frontend/images/icon/danger.png') }}" alt="">
                    <p class="text-lg text-slate-600 dark:text-slate-100 mb-3">
                        You don't have any transaction yet.
                    </p>
                    <a href="{{ route('user.deposit.amount') }}" class="btn btn-dark inline-flex items-center justify-center min-w-[170px]">
                        Deposit Now
                    </a>
                </div>
            @else
                <div class="overflow-x-auto -mx-6">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden ">
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                <thead>
                                    <tr>
                                        <th scope="col" class="table-th">{{ __('Action') }}</th>
                                        <th scope="col" class="table-th">{{ __('Account') }}</th>
                                        <th scope="col" class="table-th">{{ __('Wallet') }}</th>
                                        <th scope="col" class="table-th">{{ __('Status') }}</th>
                                        <th scope="col" class="table-th">{{ __('Fee') }}</th>
                                        <th scope="col" class="table-th">{{ __('Amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    {{-- @dd($recentTransactions) --}}
                                    @foreach($recentTransactions as $transaction )
                                    <tr>
                                        <td class="table-td">
                                            {{ ucfirst(str_replace('_',' ',$transaction->type->value )) }}
                                        </td>
                                        <td class="table-td"></td>
                                        <td class="table-td">
                                            <div class="flex items-center">
                                                <div class="flex-none">
                                                    <div class="w-8 h-8 rounded-[100%] ltr:mr-3 rtl:ml-3">
                                                        <img src="{{ asset('frontend/images/logo/BinancePay.svg') }}" alt="" class="w-full h-full rounded-[100%] object-cover">
                                                    </div>
                                                </div>
                                                <div class="flex-1 text-start">
                                                    <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                        BinancePay
                                                    </h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="table-td">
                                            @if($transaction->status->value == \App\Enums\TxnStatus::Pending->value)
                                                <span class="badge bg-slate-100 text-slate-900 capitalize pill">{{ __('Pending') }}</span>
                                            @elseif($transaction->status->value ==  \App\Enums\TxnStatus::Success->value)
                                                <span class="badge bg-primary text-slate-900 capitalize pill">{{ __('Success') }}</span>
                                            @elseif($transaction->status->value ==  \App\Enums\TxnStatus::Failed->value)
                                                <span class="badge bg-danger-500 text-slate-900 capitalize pill">{{ __('canceled') }}</span>
                                            @endif
                                        </td>
                                        <td class="table-td">
                                            1.5%
                                        </td>
                                        <td class="table-td">
                                            <span class="{{ txn_type($transaction->type->value,['green-color','red-color']) }}">
                                                {{ txn_type($transaction->type->value,['+','-']) .$transaction->amount.' '.$currency }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach

                                    @if($recentTransactions->isEmpty())
                                        <tr class="centered">
                                            <td colspan="5" class="table-td">{{ __('No Data Found') }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
