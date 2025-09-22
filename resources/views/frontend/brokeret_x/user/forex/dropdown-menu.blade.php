<div x-show="openDropDown" @click.outside="openDropDown = false" class="shadow-theme-lg dark:bg-gray-dark absolute top-full right-0 z-40 w-max space-y-1 rounded-2xl border border-gray-200 bg-white p-2 dark:border-gray-800">
    <div class="dropdown-header flex justify-around gap-3 border-b dark:border-slate-700 p-3">
        @if($account->account_type == 'demo' && $account->status == \App\Enums\ForexAccountStatus::Ongoing)
            <a href="{{route('user.deposit.methods')}}" class="text-center text-theme-sm text-gray-500 dark:text-gray-400 dropdown-deposit-demo-account"
                @click.prevent="$store.modals.open('depositDemo', {
                    login: '{{ $account->login }}'
                })">
                <div class="lg:h-[32px] lg:w-[32px] lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mx-auto">
                    <i data-lucide="download" class="w-5"></i>
                </div>
                {{ __('Deposit') }}
            </a>
        @endif

        @if($account->account_type == 'real' && $account->status == \App\Enums\ForexAccountStatus::Ongoing)
            <a href="{{route('user.deposit.methods')}}" class="text-center text-theme-sm text-gray-500 dark:text-gray-400">
                <div class="lg:h-[32px] lg:w-[32px] lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mx-auto">
                    <i data-lucide="download" class="w-5"></i>
                </div>
                {{ __('Deposit') }}
            </a>
            <a href="{{route('user.withdraw.view')}}" class="text-center text-theme-sm text-gray-500 dark:text-gray-400">
                <div class="lg:h-[32px] lg:w-[32px] lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mx-auto">
                    <i data-lucide="upload" class="w-5"></i>
                </div>
                {{ __('Withdraw') }}
            </a>
        @endif
        <a class="text-center text-theme-sm text-gray-500 dark:text-gray-400" 
            href=""
            type="button"
            @click.prevent="$store.modals.open('tradeModal', {
                login: '{{ $account->login }}',
                account_name: '{{ $account->account_name }}',
                account_type: '{{ $account->account_type }}',
                server: '{{ $account->server }}',
                account_currency: '{{ $account->account_currency }}'
            })">
            <div class="lg:h-[32px] lg:w-[32px] lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mx-auto">
                <i data-lucide="chart-candlestick" class="w-5"></i>
            </div>
            {{ __('Trade') }}
        </a>
    </div>
    <ul>
        <li>
            <a class="text-theme-sm flex w-full rounded-lg px-3 py-2 text-left font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300 dropdown-account-info"
                href="#"
                type="button"
                @click.prevent="$store.modals.open('accountDetails', {
                    login: '{{ $account->login }}',
                    account_name: '{{ $account->account_name }}',
                    server: '{{ $account->server }}',
                    schema_title: '{{ $account->schema->title }}',
                    account_type: '{{ $account->account_type }}',
                    leverage: '{{ $account->leverage }}',
                    balance: '{{ get_mt5_account_balance($account->login) }}',
                    free_margin: '{{ $account->free_margin }}',
                    equity: '{{ get_mt5_account_equity($account->login) }}'
                })">
                {{ __('Account Details') }}
            </a>
        </li>
        <li>
            <a class="text-theme-sm flex w-full rounded-lg px-3 py-2 text-left font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300 dropdown-update-leverage"
                href=""
                type="button"
                @click.prevent="$store.modals.loadLeverage({
                    id: '{{ $account->id }}',
                    login: '{{ $account->login }}',
                    action: '{{ route('user.forex.get.leverage') }}'
                })">
                {{ __('Change leverage') }}
            </a>
        </li>
        <li>
            <a class="text-theme-sm flex w-full rounded-lg px-3 py-2 text-left font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300 dropdown-update-name"
                href=""
                type="button"
                @click.prevent="$store.modals.open('accountRename', {
                    login: '{{ $account->login }}',
                    account_name: '{{ $account->account_name }}'
                })">
                {{ __('Rename account') }}
            </a>
        </li>
        @if($account->schema->is_update_trading_password)
            <li>
                <a class="text-theme-sm flex w-full rounded-lg px-3 py-2 text-left font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300 dropdown-update-password"
                    href=""
                    type="button"
                    @click.prevent="$store.modals.open('changeAccountPass', {
                        login: '{{ $account->login }}',
                        main_password: '{{ $account->main_password }}',
                        invest_password: '{{ $account->invest_password }}'
                    })">
                    {{ __('Change trading password') }}
                </a>
            </li>
        @endif
        <li>
            <a class="text-theme-sm flex w-full rounded-lg px-3 py-2 text-left font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300 dropdown-update-password"
                href=""
                type="button"
                @click.prevent="$store.modals.open('changeInvestorPass', {
                    login: '{{ $account->login }}',
                    investor_password: '{{ $account->invest_password }}'
                })">
                {{ __('Change investor password') }}
            </a>
        </li>
        <li>
            @if($account->status == \App\Enums\ForexAccountStatus::Archive)
                <a class="text-theme-sm flex w-full rounded-lg px-3 py-2 text-left font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300 archive-login" 
                    href=""
                    type="button"
                    @click.prevent="$store.modals.open('unarchiveAccount', {
                        login: '{{ $account->login }}'
                    })">
                    {{ __('Unarchive account') }}
                </a>
            @else
                <a class="text-theme-sm flex w-full rounded-lg px-3 py-2 text-left font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300 archive-login"
                    href=""
                    type="button"
                    @click.prevent="$store.modals.open('archiveAccount', {
                        login: '{{ $account->login }}'
                    })">
                    {{ __('Archive account') }}
                </a>
            @endif
        </li>
    </ul>
</div>
