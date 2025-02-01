@if (count($contracts) != 0)
    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class="col-span-8 hidden"></span>
                <span class="col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700"
                            id="dataTable" >
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
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach ($contracts as $contract)
                                    @php
                                        if(!isset($contract->accountTypeInvestment->login)) {
                                            continue;
                                        }

                                        $badge = '';
                                        if ($contract->status == \App\Enums\ContractStatusEnums::PENDING) {
                                            $badge = 'badge-secondary';
                                        } elseif ($contract->status == \App\Enums\ContractStatusEnums::SIGNED) {
                                            $badge = 'badge-success';
                                        } elseif ($contract->status == \App\Enums\ContractStatusEnums::EXPIRED) {
                                            $badge = 'badge-danger';
                                        }
                                    @endphp

                                    <tr class="pt-1">
                                        <td scope="col" class="table-td ">
                                            {{ $contract->user->first_name . ' ' . $contract->user->last_name }}
                                        </td>
                                        <td scope="col" class="table-td">
                                            {{ $contract->accountTypeInvestment->login }}</td>

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
                                            <span
                                                class="badge {{ $badge }}">{{ str_replace('_', ' ', $contract->status) }}</span>
                                        </td>

                                        <td scope="col" class="table-td">
                                            <div class="btn-group">
                                                @can('contract-view')
                                                    @if ($contract->status == \App\Enums\ContractStatusEnums::SIGNED)
                                                        <a href="{{ asset($contract->file_path) }}" target="__blank" class="btn btn-sm btn-primary mr-1">View Contract</a>
                                                    @endif
                                                @endcan

                                                @can('contract-edit')
                                                    @if ($contract->status == \App\Enums\ContractStatusEnums::PENDING)
                                                    <a href="#" target="__blank"
                                                        class="btn btn-sm btn-primary mr-1 mark-as-signed"
                                                        data-status="pending" data-id="{{ $contract->id }}"
                                                            data-login="{{ $contract->accountTypeInvestment->login }}">Mark
                                                            as Signed</a>
                                                        <a href="#" target="__blank"
                                                            class="btn btn-sm btn-primary mr-1 mark-as-expired"
                                                            data-status="pending" data-id="{{ $contract->id }}"
                                                            data-expiry="{{ $contract->expiry_at }}"
                                                            data-login="{{ $contract->accountTypeInvestment->login }}">Mark
                                                            as Expired</a>
                                                    @endif

                                                    @if ($contract->status == \App\Enums\ContractStatusEnums::EXPIRED)
                                                        <a href="#" target="__blank"
                                                            class="btn btn-sm btn-primary mr-1 mark-as-signed"
                                                            data-status="expired" data-id="{{ $contract->id }}"
                                                            data-login="{{ $contract->accountTypeInvestment->login }}">Mark
                                                            as Signed</a>
                                                        <a href="#" target="__blank"
                                                            class="btn btn-sm btn-primary mr-1 mark-as-pending"
                                                            data-id="{{ $contract->id }}"
                                                            data-login="{{ $contract->accountTypeInvestment->login }}"
                                                            data-status="expired">Mark as Pending</a>
                                                    @endif
                                                @endcan
                                                

                                            </div>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 mt-auto">
                            <div>
                                @php
                                    $from = $contracts->firstItem();
                                    $to = $contracts->lastItem();
                                    $total = $contracts->total();
                                @endphp
                                <p class="text-sm text-gray-700 py-3">
                                    Showing <span class="font-medium">{{ $from }}</span> to <span
                                        class="font-medium">{{ $to }}</span> of <span
                                        class="font-medium">{{ $total }}</span> results
                                </p>
                            </div>
                            {{ $contracts->appends(request()->except('page'))->links() }}
                        </div>
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
    <script></script>
@endpush
