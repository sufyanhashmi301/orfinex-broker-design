<div class="pageTitle flex justify-between flex-wrap items-center mb-6">
    <h4 class="font-medium text-xl capitalize text-slate-700 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
        {{ __('All Referral Logs') }}
    </h4>
    <div class="sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4" id="tabs-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a href="#tabs-" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize px-6 py-3 focus:outline-none focus:ring-0 active dark:bg-slate-900 dark:text-slate-300"
                   id="tabs-generalTarget-tab" data-bs-toggle="pill" data-bs-target="#tabs-generalTarget" role="tab"
                   aria-controls="tabs-generalTarget" aria-selected="true">
                    <i icon-name="network"></i>
                    {{ __('General') }}
                </a>
            </li>

            @foreach($referrals->keys() as $raw)
                @php
                    $target = json_decode($raw,true);
                @endphp
                <li class="nav-item" role="presentation">
                    <a href="#tabs-t{{ $target['id'] }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                       id="tabs-t{{ $target['id'] }}-tab" data-bs-toggle="pill" data-bs-target="#tabs-t{{ $target['id'] }}" role="tab"
                       aria-controls="tabs-t{{ $target['id'] }}" aria-selected="false">
                        <i icon-name="boxes"></i>
                        @if(setting('site_referral','global') == 'level')
                            {{ __('Level') }} {{ $target['the_order'] }}
                        @else
                            {{ $target['name'] }}
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
<div class="card">
    <div class="card-body px-6 pt-3 table-responsive">
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="tabs-generalTarget" role="tabpanel" aria-labelledby="tabs-generalTarget-tab">
                <div class="desktop-screen-show hidden md:block">
                    <div class="overflow-x-auto -mx-6 dashcode-data-table">
                        <span class=" col-span-8  hidden"></span>
                        <span class="  col-span-4 hidden"></span>
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 data-table">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="table-th">{{ __('Description') }}</th>
                                            <th scope="col" class="table-th">{{ __('Transactions ID') }}</th>
                                            <th scope="col" class="table-th">{{ __('Amount') }}</th>
                                            <th scope="col" class="table-th">{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                        @foreach($generalReferrals as $raw)
                                        <tr>
                                            <td class="table-td">
                                                <div class="flex items-center">
                                                    <div class="flex-none">
                                                        <div class="w-10 h-10 lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mr-2">
                                                            <iconify-icon icon="heroicons:arrow-down-left"></iconify-icon>
                                                        </div>
                                                    </div>
                                                    <div class="flex-1 text-start">
                                                        <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                            {{ $raw->description }}
                                                        </h4>
                                                        <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                                            {{ $raw->created_at }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="table-td">
                                                <strong>{{ $raw->tnx }}</strong>
                                            </td>
                                            <td class="table-td">
                                                <strong class="green-color">+{{ $raw->amount.' '. $currency }} </strong>
                                            </td>
                                            <td class="table-td">
                                                <span class="block text-left">
                                                    <span class="inline-block text-center mx-auto py-1">
                                                        <span class="flex items-center space-x-3 rtl:space-x-reverse">
                                                            <span class="h-[6px] w-[6px] bg-success rounded-full inline-block ring-4 ring-opacity-30 ring-success-500"></span>
                                                            <span>{{ $raw->status }}</span>
                                                        </span>
                                                    </span>
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $generalReferrals->links() }}
                            </div>
                        </div>
                    </div>

                </div>
                <div class="mobile-screen-show md:hidden">
                    <!-- Transactions -->
                    <div class="all-feature-mobile mobile-transactions mb-3">
                        <div class="contents space-y-3">
                            @foreach($generalReferrals as $raw )
                                <div class="single-transaction flex justify-between text-xs bg-slate-100 dark:bg-slate-900 rounded-md p-2 py-3">
                                    <div class="transaction-left w-3/4">
                                        <div class="transaction-des">
                                            <div class="transaction-title mb-1 dark:text-white">{{ $raw->description }}</div>
                                            <div class="transaction-id mb-1 dark:text-white">{{ $raw->tnx }}</div>
                                            <div class="transaction-date mb-1 dark:text-white">{{ $raw->created_at }}</div>
                                        </div>
                                    </div>
                                    <div class="transaction-right">
                                        <div class="transaction-amount add mb-1 dark:text-white">
                                            + {{ $raw->amount .' '.$currency }}
                                        </div>
                                        <div class="transaction-gateway mb-1 dark:text-white">{{ $raw->method }}</div>

                                        @if($raw->status->value == App\Enums\TxnStatus::Pending->value)
                                            <div class="transaction-status text-warning">{{ __('Pending') }}</div>
                                        @elseif($raw->status->value ==  App\Enums\TxnStatus::Success->value)
                                            <div class="transaction-status text-success">{{ __('Success') }}</div>
                                        @elseif($raw->status->value ==  App\Enums\TxnStatus::Failed->value)
                                            <div class="transaction-status text-danger">{{ __('Canceled') }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            @if($generalReferrals->isEmpty())
                                <p class="text-center dark:text-white">{{ __('No Data Found') }}</p>
                            @endif
                        </div>
                        {{ $generalReferrals->onEachSide(1)->links() }}
                    </div>

                </div>
            </div>
            @foreach($referrals as $target => $referral)

                @php
                    $target = json_decode($target,true);
                @endphp

                <div class="tab-pane fade" id="tabs-t{{ $target['id'] }}" role="tabpanel" aria-labelledby="tabs-t{{ $target['id'] }}-tab">
                    <div class="desktop-screen-show hidden md:block">
                        <div class="overflow-x-auto -mx-6 dashcode-data-table">
                            <span class="col-span-8 hidden"></span>
                            <span class="col-span-4 hidden"></span>
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden">
                                    <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 data-table">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="table-th">{{ __('Description') }}</th>
                                                <th scope="col" class="table-th">{{ __('Transactions ID') }}</th>
                                                <th scope="col" class="table-th">{{ __('Type') }}</th>
                                                <th scope="col" class="table-th">{{ __('Amount') }}</th>
                                                <th scope="col" class="table-th">{{ __('Status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                        @foreach($referral->sortDesc() as $raw )
                                            <tr>
                                                <td class="table-td">
                                                    <div class="flex items-center">
                                                        <div class="flex-none">
                                                            <div class="w-10 h-10 lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mr-2">
                                                                <iconify-icon icon="heroicons:arrow-down-left"></iconify-icon>
                                                            </div>
                                                        </div>
                                                        <div class="flex-1 text-start">
                                                            <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                                {{ $raw->description }}
                                                            </h4>
                                                            <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                                                {{ $raw->created_at }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="table-td">
                                                    <strong>{{ $raw->tnx }}</strong>
                                                </td>
                                                <td class="table-td">
                                                    <div class="site-badge primary-bg">{{ $raw->target_type }}</div>
                                                </td>
                                                <td class="table-td">
                                                    <strong class="green-color">+{{ $raw->amount.' '. $currency }} </strong>
                                                </td>
                                                <td class="table-td">
                                                    <span class="block text-left">
                                                        <span class="inline-block text-center mx-auto py-1">
                                                            <span class="flex items-center space-x-3 rtl:space-x-reverse">
                                                                <span class="h-[6px] w-[6px] bg-success rounded-full inline-block ring-4 ring-opacity-30 ring-success-500"></span>
                                                                <span>{{ $raw->status }}</span>
                                                            </span>
                                                        </span>
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mobile-screen-show md:hidden">
                        <!-- Transactions -->
                        <div class="all-feature-mobile mobile-transactions mb-3">
                            <div class="contents">
                                @foreach($referral->sortDesc() as $raw )
                                    <div class="single-transaction flex justify-between text-xs bg-slate-100 dark:bg-slate-900 rounded-md p-2 py-3">
                                        <div class="transaction-left w-3/4">
                                            <div class="transaction-des">
                                                <div class="transaction-title mb-1 dark:text-white">{{ $raw->description }}</div>
                                                <div class="transaction-id mb-1 dark:text-white">{{ $raw->tnx }}</div>
                                                <div class="transaction-date mb-1 dark:text-white">{{ $raw->created_at }}</div>
                                            </div>
                                        </div>
                                        <div class="transaction-right">
                                            <div class="transaction-amount add mb-1 dark:text-white">
                                                +{{ $raw->amount .' '.$currency }}
                                            </div>
                                            <div class="transaction-gateway mb-1 dark:text-white"> {{  $raw->target_type }}</div>

                                            @if($raw->status->value == App\Enums\TxnStatus::Pending->value)
                                                <div class="transaction-status text-warning">{{ __('Pending') }}</div>
                                            @elseif($raw->status->value ==  App\Enums\TxnStatus::Success->value)
                                                <div class="transaction-status text-success">{{ __('Success') }}</div>
                                            @elseif($raw->status->value ==  App\Enums\TxnStatus::Failed->value)
                                                <div class="transaction-status text-danger">{{ __('Canceled') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>
