@if (count($users) != 0)
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
                                    <th scope="col" class="table-th">{{ __('Referred By') }}</th>
                                    <th scope="col" class="table-th">{{ __('Affiliate Account Exists') }}</th>
                                    <th scope="col" class="table-th">{{ __('Affiliate ID') }}</th>
                                    <th scope="col" class="table-th">{{ __('Total Commission') }}</th>
                                    <th scope="col" class="table-th">{{ __('Withdrawable Commission') }}</th>
                                    <th scope="col" class="table-th">{{ __('Details') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach ($users as $user)

                                    @php
                                        $referrer = 'N/A';
                                        if($user->referrer) { 
                                            $referrer = $user->referrer->first_name . ' ' . $user->referrer->last_name . ' (' . $user->referrer->email . ')';
                                        }
                                    @endphp

                                    <tr class="table-td">
                                        <td scope="col" class="table-td">{{ $user->first_name . ' ' . $user->last_name . ' (' . $user->email . ')' }}</td>
                                        <td scope="col" class="table-td">{{ $referrer }}</td>
                                        <td scope="col" class="table-td">
                                            @if ($user->userAffiliate)
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
                                        <td scope="col" class="table-td"><span class="badge badge-primary">{{ $user->userAffiliate->referral_link ?? '-' }}</span></td>
                                        <td scope="col" class="table-td">{{ number_format($user->userAffiliate->total_commission ?? 0.00, 2) . ' ' . $currency }}</td>
                                        <td scope="col" class="table-td">{{ number_format($user->userAffiliate->withdrawable_balance ?? 0.00, 2) . ' ' . $currency }}</td>
                                        <td scope="col" class="table-td">
                                            @if (count($user->referrals) != 0)
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#refferals-modal{{$user->id}}">View Referrals ({{ count($user->referrals) }}) </button>

                                                @include('backend.affiliates.includes.__referrals_modal', ['user' => $user, 'referrals' => $user->referrals])
                                            @endif

                                            @if ($user->userAffiliate)
                                                <button class="btn btn-primary btn-sm ml-1" data-bs-toggle="modal" data-bs-target="#commission-details-modal{{$user->id}}">Commission Details</button>
                                                @include('backend.affiliates.includes.__details_modal', ['user' => $user, 'commission' => $user->userAffiliate ])
                                            @endif

                                            @if (count($user->referrals) == 0 && !$user->userAffiliate)
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    
                                @endforeach
                                    
                            </tbody>
                        </table>
                        <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 mt-auto">
                            <div>
                                @php
                                    $from = $users->firstItem();
                                    $to = $users->lastItem();
                                    $total = $users->total();
                                @endphp
                                <p class="text-sm text-gray-700 py-3">
                                    Showing <span class="font-medium">{{ $from }}</span> to <span
                                        class="font-medium">{{ $to }}</span> of <span
                                        class="font-medium">{{ $total }}</span> results
                                </p>
                            </div>
                            {{ $users->appends(request()->except('page'))->links() }}
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
