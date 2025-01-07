@if( 0 == 0 )
    <div class="card p-3">
        <div class="card-body p-6 pt-1">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Hook') }}</th>
                                    <th scope="col" class="table-th">{{ __('Account Title') }}</th>
                                    <th scope="col" class="table-th">{{ __('Login') }}</th>
                                    {{-- <th scope="col" class="table-th">{{ __('Allotted Funds') }}</th> --}}
                                    <th scope="col" class="table-th">{{ __('Phase Type') }}</th>
                                    <th scope="col" class="table-th">{{ __('Phase Step') }}</th>
                                    {{-- <th scope="col" class="table-th">{{ __('Phase Started At') }}</th> --}}
                                    <th scope="col" class="table-th">{{ __('Phase Action') }}</th>
                                    {{-- <th scope="col" class="table-th">{{ __('Investment Status') }}</th> --}}

                                    <th scope="col" class="table-th">{{ __('Detail') }}</th>
                                    <th scope="col" class="table-th">{{ __('Activity Timestamp') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                
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
                {{ __("Nothing to see here.") }}
            </p>
        </div>
    </div>
@endif
