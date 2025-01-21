<div class="col-span-12">
    <div class="card">
        <header class="card-header noborder">
            <h4 class="card-title">{{ __('Recent Transactions') }}</h4>
            <div>
                <a href="{{ route('user.history.transactions') }}" class="btn-link loaderBtn inline-flex items-center">
                    {{ __('See All') }}
                    <iconify-icon class="text-lg ltr:ml-1 rtl:mr-1" icon="lucide:chevron-right"></iconify-icon>
                </a>
            </div>
        </header>
        <div class="card-body p-6 pt-0">
            <!-- BEGIN: Company Table -->
            @if(count($recentTransactions) == 0)
                <div class="flex items-center justify-center flex-col gap-3">
                    <img src="{{ asset('frontend/images/icon/danger.png') }}" alt="">
                    <p class="text-lg text-slate-600 dark:text-slate-100 mb-3">
                        {{ __("You don't have any transaction yet.") }}
                    </p>
                    <a href="{{ route('user.deposit.methods') }}" class="btn btn-dark inline-flex items-center justify-center min-w-[170px]">
                        {{ __('Deposit Now') }}
                    </a>
                </div>
            @else
                <div class="overflow-x-auto -mx-6">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden ">
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                <thead>
                                    <tr>
                                        <th scope="col" class="table-th">{{ __('Description') }}</th>
                                        <th scope="col" class="table-th">{{ __('Transactions ID') }}</th>
                                        <th scope="col" class="table-th">{{ __('Account') }}</th>
                                        <th scope="col" class="table-th">{{ __('Amount') }}</th>
                                        <th scope="col" class="table-th">{{ __('Gateway') }}</th>
                                        <th scope="col" class="table-th">{{ __('Fee') }}</th>
                                        <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @dd($recentTransactions) --}}
                                    @foreach($recentTransactions as $transaction )
                                    <tr>
                                        <td class="table-td">
                                            <div class="flex items-center">
                                                <div class="flex-none">
                                                    <div class="w-10 h-10 lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mr-2">
                                                        @switch($transaction->type->value)
                                                            @case('send_money')
                                                            <iconify-icon icon="ph:arrow-right-bold"></iconify-icon>
                                                            @break
                                                            @case('send_money_internal')
                                                            <iconify-icon icon="ph:arrow-right-bold"></iconify-icon>
                                                            @break
                                                            @case('receive_money')
                                                            <iconify-icon icon="ph:arrow-left-bold"></iconify-icon>
                                                            @break
                                                            @case('send_money_internal')
                                                            <iconify-icon icon="ph:arrow-left-bold"></iconify-icon>
                                                            @break
                                                            @case('deposit')
                                                            <iconify-icon icon="octicon:download-16"></iconify-icon>
                                                            @break
                                                            @case('manual_deposit')
                                                            <iconify-icon icon="octicon:download-16"></iconify-icon>
                                                            @break
                                                            @case('investment')
                                                            <iconify-icon icon="fluent:arrow-swap-24-regular"></iconify-icon>
                                                            @break
                                                            @case('withdraw')
                                                            <iconify-icon icon="akar-icons:arrow-back"></iconify-icon>
                                                            @break
                                                            @default()
                                                            <iconify-icon icon="lucide:backpack"></iconify-icon>
                                                        @endswitch
                                                    </div>
                                                </div>
                                                <div class="flex-1 text-start">
                                                    <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                        {{ $transaction->description }} @if(!in_array($transaction->approval_cause,['none',""]))
                                                            <span class="toolTip onTop optional-msg" data-tippy-content="{{ $transaction->approval_cause }}">
                                                                <iconify-icon icon="lucide:mail"></iconify-icon>
                                                            </span>
                                                        @endif
                                                    </h4>
                                                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                                        {{ $transaction->created_at }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="table-td">{{ $transaction->tnx }}</td>
                                        <td class="table-td">
                                            {{ $transaction->target_id }}
                                        </td>
                                        <td class="table-td">
                                            <strong class="{{ $transaction->type->value !== 'subtract' && $transaction->type->value !== 'investment' && $transaction->type->value !==  'withdraw' && $transaction->type->value !==  'send_money' && $transaction->type->value !==  'bonus_refund' && $transaction->type->value !==  'bonus_subtract' ? 'text-success': 'text-danger'}}">
                                                {{ txn_type($transaction->type->value,['+','-']) .$transaction->amount.' '.$currency }}
                                            </strong>
                                        </td>
                                        <td class="table-td">
                                            {{ $transaction->method }}
                                        </td>
                                        <td class="table-td">
                                            {{$transaction->charge.' '.$currency }}
                                        </td>
                                        <td class="table-td">
                                            @if($transaction->status->value == \App\Enums\TxnStatus::Pending->value)
                                                <span class="badge-warning capitalize rounded px-2 py-1">{{ __('Pending') }}</span>
                                            @elseif($transaction->status->value ==  \App\Enums\TxnStatus::Success->value)
                                                <span class="badge-success bg-opacity-30 capitalize rounded px-2 py-1">{{ __('Success') }}</span>
                                            @elseif($transaction->status->value ==  \App\Enums\TxnStatus::Failed->value)
                                                <span class="badge-danger bg-opacity-30 capitalize rounded px-2 py-1">{{ __('canceled') }}</span>
                                            @endif
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
