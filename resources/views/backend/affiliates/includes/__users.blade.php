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
                                    {{-- <th scope="col" class="table-th">{{ __('Referrals') }}</th> --}}
                                    <th scope="col" class="table-th">{{ __('Total Purchase Amount') }}</th>
                                    <th scope="col" class="table-th">{{ __('Total Commission') }}</th>
                                    <th scope="col" class="table-th">{{ __('Commision Withdrawn') }}</th>
                                    <th scope="col" class="table-th">{{ __('Commission Pending') }}</th>
                                    <th scope="col" class="table-th">{{ __('Highest Commission Earned') }}</th>
                                    <th scope="col" class="table-th">{{ __('Withdrawable Balance') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                

                                    <tr class="pt-1">
                                        <td scope="col" class="table-td "></td>
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
