<div class="grid md:grid-cols-2 grid-cols-1 gap-3 mb-6">
    <div class="card">
        <div class="card-body p-6">
            <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-3">
                Current Balance
            </p>
            <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                <span class="d-balance d-stats">{{ isset($valid_accounts[0]) ? number_format($valid_accounts[0]->accountTypeInvestmentStat->balance, 2) : '--.--' }}</span> {{ $currency }}
            </h6>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-6">
            <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-3">
                Current Equity
            </p>
            <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                <span class="d-equity d-stats">{{ isset($valid_accounts[0]) ? number_format($valid_accounts[0]->accountTypeInvestmentStat->current_equity, 2) : '--.--' }}</span> {{ $currency }}
            </h6>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-6">
            <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-3">
                Total PnL
            </p>
            <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                <span class="badge {{ isset($valid_accounts[0]) ? ($valid_accounts[0]->accountTypeInvestmentStat->total_pnl < 0 ? 'badge-danger' : 'badge-success') : '' }} badge-success" style="font-size: 24px; background: none; padding: 0">
                    <span class="d-total-pnl d-stats">{{ isset($valid_accounts[0]) ? number_format($valid_accounts[0]->accountTypeInvestmentStat->total_pnl, 2) : '--.--' }}</span>&nbsp;{{ $currency }}
                <span>
            </h6>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-6">
            <p class="text-slate-600 dark:text-slate-400 text-sm font-medium mb-3">
                Floating Profit
            </p>
            <h6 class="block mb- text-2xl text-slate-900 dark:text-white font-medium leading-none">
                <span class="d-floating d-stats">{{ isset($valid_accounts[0]) ? number_format($valid_accounts[0]->accountTypeInvestmentStat->current_equity - $valid_accounts[0]->accountTypeInvestmentStat->balance, 2) : '--.--' }}</span> {{ $currency }}
            </h6>
        </div>
    </div>
    
</div>

<div class="">
    <h6 class="">Accounts</h6>
</div>
<div class="overflow-x-auto -mx-6" style="max-height: 200px; overflow: auto">
    <div class="inline-block min-w-full align-middle">
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" >
                {{-- <thead class="bg-slate-200 dark:bg-slate-700">
                    <tr>
                        <th scope="col" class="table-th">{{ __('Title') }}</th>
                        <th scope="col" class="table-th">{{ __('Login') }}</th>
                        <th scope="col" class="table-th">{{ __('Allotted Funds') }}</th>
                        <th scope="col" class="table-th">{{ __('Phase Type') }}</th>
                        <th scope="col" class="table-th">{{ __('Status') }}</th>
                    </tr>
                </thead> --}}
                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700" >
                    @forelse ($valid_accounts as $account)
                        <tr class="account-short-view" data-login="{{ $account->login }}">
                            <td class="table-td font-semibold">{{ $account->getAccountTypeSnapshotData()['title'] }}</td>
                            <td class="table-td">{{ $account->login }}</td>
                            <td class="table-td">
                                <span class="badge bg-primary" style="color: #fff">{{ str_replace('_', ' ', $account->getPhaseSnapshotData()['type']) }}</span>
                            </td>
                            <td class="table-td">
                                <span class="badge bg-primary" style="color: #fff">{{ $account->status }}</span>
                            </td>
                        </tr>
                    @empty
                        <br>
                        <tr>
                            <td class="table-td" colspan="4">
                                <center style="text-transform: none;">
                                    <p style="max-width: 400px">You don't have any active account yet! Accounts' summary will be available once you have an active account.</p>
                                </center>
                                <br><br>
                                <center><a href="{{ route('user.account.buy') }}" class="btn btn-primary mt-5">Buy Account Now</a></center>
                            </td>
                        </tr>
                    @endforelse
                    
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .account-short-view {
        cursor: pointer;
    }
    .account-short-view:hover {
        background: rgba(185, 185, 185, 0.1);
        
    }
</style>

@push('single-script')
    <script>
        $('.account-short-view').on('click', function() {

            $('.d-stats').text('--.--')

            $.ajax({
                url: "{{ route('user.account_stats.login') }}",        // The URL to send the request to
                type: 'GET',       // The HTTP method
                data: { login: $(this).attr('data-login') }, // Data to send (optional)
                success: function(response) {
                    // Code to execute if the request is successful
                    $('.d-login').text('#' + response['login'])
                    $('.d-balance').text(response['balance'])
                    $('.d-equity').text(response['current_equity'])
                    $('.d-total-pnl').text(response['total_pnl'])
                    $('.d-floating').text(response['floating'])
                    // console.log(response);
                },
                error: function(xhr, status, error) {
                    // Code to execute if the request fails
                    console.error(error);
                }
            });
        })
    </script>
@endpush
