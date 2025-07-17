@if ($hasData)
    <div class="card overflow-hidden">
        <div class="grid grid-cols-12">
            <div class="lg:col-span-7 col-span-12">
                @include('backend.leaderboard.include.__top3', ['top3' => $top3, 'hasData' => $hasData, 'currencySymbol' => $currencySymbol])
                <div class="relative px-6 pt-3">
                    <div class="overflow-x-auto -mx-6 dashcode-data-table">
                        <span class="col-span-8 hidden"></span>
                        <span class="col-span-4 hidden"></span>
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="table-th">#</th>
                                            <th scope="col" class="table-th">{{ __('User') }}</th>
                                            <th scope="col" class="table-th">{{ __('IB Group') }}</th>
                                            <th scope="col" class="table-th">{{ __('Network Users') }}</th>
                                            <th scope="col" class="table-th">{{ __('Incoming Payments') }}</th>
                                            <th scope="col" class="table-th">{{ __('Outgoing Payments') }}</th>
                                            <th scope="col" class="table-th"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="processingIndicator" class="text-center">
                        <iconify-icon class="spining-icon text-5xl dark:text-slate-100" icon="lucide:loader"></iconify-icon>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-5 col-span-12 hidden lg:block">
                @include('backend.leaderboard.include.__top1', ['top1' => $top1, 'top1Details' => $top1Details, 'currencySymbol' => $currencySymbol])
            </div>
        </div>
    </div>
@else
    <div class="card basicTable_wrapper p-6">
        <div class="flex-1 flex items-center justify-center flex-col gap-3">
            <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M26 19.875V30.9167" stroke="rgba(220 0 0)" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="rgba(220 0 0)" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M25.988 37.5417H26.0075" stroke="rgba(220 0 0)" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            <h3 class="text-lg font-medium text-slate-800 dark:text-slate-100">
                {{ __('No leaderboard data found') }}
            </h3>
            <p class="text-sm text-center text-slate-600 dark:text-slate-100">
                {{ __('No users matched the selected IB group or date range.') }}
            </p>
        </div>
    </div>
@endif