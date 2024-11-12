<div id="bodyOverlay" class="w-screen h-screen fixed top-0 bg-slate-900 bg-opacity-50 backdrop-blur-sm z-10 hidden"></div>
<div class="logo-segment flex-wrap">
    <a href="{{route('admin.dashboard')}}" class="items-center md:flex hidden">
        <img src="{{ asset(setting('site_logo','global')) }}" class="black_logo max-w-[160px]" alt="Logo"/>
        <img src="{{ asset(setting('site_logo_light','global')) }}" class="white_logo max-w-[160px]" alt="Logo"/>
    </a>
    <!-- Sidebar Type Button -->
    <button class="sidebarCloseIcon text-2xl">
        <iconify-icon class="text-slate-900 dark:text-slate-200" icon="clarity:window-close-line"></iconify-icon>
    </button>
</div>
<div id="nav_shadow" class="nav_shadow h-[60px] absolute top-[80px] nav-shadow z-[1] w-full transition-all duration-200 pointer-events-none opacity-0"></div>
<div class="sidebar-menus bg-white dark:bg-slate-800 py-2 px-4 h-[calc(100%-80px)] overflow-y-auto z-50" id="sidebar_menus">
    <ul class="sidebar-menu flex flex-column">
        <li>
            <a href="{{route('admin.dashboard')}}" class="navItem {{ isActive('admin.dashboard') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:layout-dashboard"></iconify-icon>
                    <span>{{ __('Dashboard') }}</span>
                </span>
            </a>
        </li>

        {{-- *************************************************************  Customer Management *********************************************************--}}
        @canany(['customer-list','customer-login','customer-mail-send','customer-basic-manage','customer-balance-add-or-subtract','customer-change-password','all-type-status'])
            <li class="side-nav-item side-nav-dropdown {{ isActive(['admin.user*','admin.notification*']) }}">
                <a href="javascript:void(0);" class="navItem">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="lucide:users"></iconify-icon>
                        <span>{{ __('Customers') }}</span>
                    </span>
                    <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon>
                </a>
                <ul class="sidebar-submenu">
                    @canany(['customer-list','customer-login','customer-mail-send','customer-basic-manage','customer-balance-add-or-subtract','customer-change-password','all-type-status'])
                        <li>
                            <a href="{{route('admin.user.index')}}" class="{{ isActive('admin.user.index') }}">
                                {{ __('All Customers') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.user.active') }}" class="{{ isActive('admin.user.active') }}">
                                {{ __('Active Customers') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.user.disabled') }}" class="{{ isActive('admin.user.disabled') }}">
                                {{ __('Disabled Customers') }}
                            </a>
                        </li>

                    @endcanany
                    @can('customer-mail-send')
                        <li>
                            <a href="{{ route('admin.user.mail-send.all') }}" class="{{ isActive('admin.user.mail-send.all') }}">
                                {{ __('Send Email to all') }}
                            </a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endcanany

        @canany(['kyc-list','kyc-action','kyc-form-manage','risk-profile-tag'])
            <li class="{{ isActive(['admin.kyc*']) }}">
                <a href="javascript:void(0);" class="navItem">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="mdi:user-check-outline"></iconify-icon>
                        <span>{{ __('Compliance & KYC') }}</span>
                    </span>
                    <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon>
                </a>
                <ul class="sidebar-submenu">
                    @canany(['kyc-list','kyc-action'])
                        <li>
                            <a href="{{ route('admin.kyc.pending') }}" class="{{ isActive('admin.kyc.pending') }}">
                                {{ __('Pending KYC') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.kyc.rejected') }}" class="{{ isActive('admin.kyc.rejected') }}">
                                {{ __('Rejected KYC') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.kyc.all') }}" class="{{ isActive('admin.kyc.all') }}">
                                {{ __('All KYC Logs') }}
                            </a>
                        </li>
                    @endcanany
                </ul>
            </li>
        @endcanany

        {{-- *************************************************************  Staff Management *********************************************************--}}
        @canany(['role-list','role-create','role-edit','staff-list','staff-create','staff-edit'])
            @canany(['staff-list','staff-create','staff-edit'])
                <li>
                    <a href="{{route('admin.staff.index')}}" class="navItem {{ isActive('admin.staff*') }}">
                        <span class="flex items-center">
                            <iconify-icon class="nav-icon" icon="lucide:user-cog"></iconify-icon>
                            <span>{{ __('Manage Staffs') }}</span>
                        </span>
                    </a>
                </li>
            @endcanany
        @endcanany

        {{-- *************************************************************  Plan Management *********************************************************--}}
        @canany(['schedule-manage','schema-list','schema-create','schema-edit'])
            <li class="{{ isActive(['admin.schedule*','admin.account-type*','admin.ibAccountType*']) }}">
                <a href="javascript:void(0);" class="navItem">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="lucide:album"></iconify-icon>
                        <span>{{ __('Account Type') }}</span>
                    </span>
                    <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon>
                </a>
                <ul class="sidebar-submenu">
                    @can('schema-edit')
                        <li>
                            <a href="{{ route('admin.account-type.index', ['type' => \App\Enums\AccountType::CHALLENGE]) }}"
                            class="{{ isActive('admin.account-type*') && request('type') === \App\Enums\AccountType::CHALLENGE ? 'active' : '' }}">
                                {{ __('Challenge') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.account-type.index', ['type' => \App\Enums\AccountType::FUNDED]) }}"
                            class="{{ isActive('admin.account-type*') && request('type') === \App\Enums\AccountType::FUNDED ? 'active' : '' }}">
                                {{ __('Funded') }}
                            </a>
                        </li>
                        {{-- <li>
                            <a href="{{route('admin.account-type.index')}}" class="">
                                {{ __('Direct Funded') }}
                            </a>
                        </li> --}}
                        <li>
                            <a href="{{ route('admin.account-type.index', ['type' => \App\Enums\AccountType::AUTO_EXPIRE]) }}"
                            class="{{ isActive('admin.account-type*') && request('type') === \App\Enums\AccountType::AUTO_EXPIRE ? 'active' : '' }}">
                                {{ __('Auto Expire') }}
                            </a>
                        </li>
                    @endcan
                    {{--@can('schema-edit')
                        <li>
                            <a href="{{route('admin.ibAccountType.index')}}" class="{{ isActive('admin.ibAccountType*') }}">
                                {{ __('IB Account Type') }}
                            </a>
                        </li>
                    @endcan--}}

                    {{--                        @can('schema-edit')--}}
{{--                        <li class="side-nav-item {{ isActive('admin.profit.deduction*') }}">--}}
{{--                            <a href="{{route('admin.profit.deduction.index')}}"><i--}}
{{--                                    icon-name="airplay"></i><span>{{ __('Manage Profits') }}</span></a>--}}
{{--                        </li>--}}
                    {{--                        @endcan--}}

                </ul>
            </li>

            @can('investment-list')
                <li class="">
                    <a href="javascript:void(0);" class="navItem">
                        <span class="flex items-center">
                            <iconify-icon class="nav-icon" icon="lucide:contact-2"></iconify-icon>
                            <span>{{ __('Accounts') }}</span>
                        </span>
                        <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{route('admin.accounts.challengeAccounts')}}" class="{{ isActive('admin.accounts.challengeAccounts') }}">
                                {{ __('Challenge') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.accounts.fundedAccounts') }}" class="{{ isActive('admin.accounts.fundedAccounts') }}">
                                {{ __('Funded') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.accounts.directFundedAccounts') }}" class="{{ isActive('admin.accounts.directFundedAccounts') }}">
                                {{ __('Direct Funded') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.accounts.trialAccounts') }}" class="{{ isActive('admin.accounts.trialAccounts') }}">
                                {{ __('Trial') }}
                            </a>
                        </li>
                    </ul>

                </li>
            @endcan
        @endcanany

        <li>
            <a href="{{ route('admin.activePositions') }}" class="navItem {{ isActive('admin.activePositions') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="hugeicons:setting-error-03"></iconify-icon>
                    <span>{{ __('Risk Hub') }}</span>
                </span>
            </a>
        </li>

        {{-- *************************************************************  Advertisement Management *********************************************************--}}
        @canany(['advertisement-material-list','advertisement-material-create','advertisement-material-edit'])

            {{--@canany(['target-manage','referral-create','referral-list','referral-edit','referral-delete'])
                <li class="{{ isActive(['admin.referral*']) }}">
                    <a href="javascript:void(0);" class="navItem">
                        <span class="flex items-center">
                            <iconify-icon class="nav-icon" icon="lucide:settings-2"></iconify-icon>
                            <span>{{ __('Manage Referral') }}</span>
                        </span>
                        <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon>
                    </a>
                    <ul class="sidebar-submenu">
                        @canany(['referral-create','referral-list','referral-edit','referral-delete'])
                            <li>
                                <a href="{{ route('admin.referral.level.index') }}" class="{{ isActive('admin.referral.level*') }}">
                                    {{ __('Multi Level Referral') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.referral.index') }}" class="{{ isActive('admin.referral.index') }}">
                                    {{ __('Targets Referral') }}
                                </a>
                            </li>
                        @endcanany

                    </ul>
                </li>
            @endcanany--}}

            {{--@canany(['ib-list','ib-action','ib-form-manage'])
                <li class="{{ isActive(['admin.ib*']) }}">
                    <a href="javascript:void(0);" class="navItem">
                        <span class="flex items-center">
                            <iconify-icon class="nav-icon" icon="lucide:users"></iconify-icon>
                            <span>{{ __('Manage IB') }}</span>
                        </span>
                        <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon>
                    </a>
                    <ul class="sidebar-submenu">
                        @canany(['ib-list','ib-action'])
                            <li>
                                <a href="{{ route('admin.ib.pending.list') }}" class="{{ isActive('admin.ib.pending.list') }}">
                                    {{ __('Pending IB') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.ib.approved.list') }}" class="{{ isActive('admin.ib.approved.list') }}">
                                    {{ __('Approved IB') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.ib.rejected.list') }}" class="{{ isActive('admin.ib.rejected.list') }}">
                                    {{ __('Rejected IB') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.ib.all.list') }}" class="{{ isActive('admin.ib.all.list') }}">
                                    {{ __('All IB Logs') }}
                                </a>
                            </li>
                        @endcanany
                        @can('ib-form-manage')
                            <li>
                                <a href="{{ route('admin.ib-form.index') }}" class="{{ isActive('admin.ib-form*') }}">
                                    {{ __('IB Form') }}
                                </a>
                            </li>
                        @endcan
                        @can('advertisement-material-edit')
                            <li>
                                <a href="{{route('admin.advertisement_material.index')}}" class="{{ isActive('admin.advertisement_material*') }}">
                                    {{ __('IB Resources') }}
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany--}}
        @endcanany

        {{-- *************************************************************  Transactions *********************************************************--}}
        @canany(['transaction-list','investment-list','profit-list'])
            @can('transaction-list')
                <li class="">
                    <a href="{{route('admin.transactions')}}" class="navItem {{ isActive('admin.transactions') }}">
                        <span class="flex items-center">
                            <iconify-icon class="nav-icon" icon="lucide:cast"></iconify-icon>
                            <span>{{ __('Transactions') }}</span>
                        </span>
                    </a>
                </li>
            @endcan
        @endcanany

        {{-- *************************************************************  Essentials *********************************************************--}}
        @canany(['automatic-gateway-manage','manual-gateway-manage','deposit-list','deposit-action',
        'withdraw-list','withdraw-method-manage','withdraw-action','target-manage','referral-create',
        'referral-list','referral-edit','referral-delete','ranking-list','ranking-create','ranking-edit'])
            @canany(['automatic-gateway-manage','manual-gateway-manage','deposit-list','deposit-action'])
                <li class="{{ isActive(['admin.deposit*']) }}">
                    <a href="javascript:void(0);" class="navItem">
                        <span class="flex items-center">
                            <iconify-icon class="nav-icon" icon="lucide:arrow-down-circle"></iconify-icon>
                            <span>{{ __('Payments') }}</span>
                        </span>
                        <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon>
                    </a>
                    <ul class="sidebar-submenu">
                        @canany(['deposit-list','deposit-action'])
                            <li class="">
                                <a href="{{ route('admin.deposit.manual.pending') }}" class="{{ isActive('admin.deposit.manual.pending') }}">
                                    {{ __('Pending Payments') }}
                                </a>
                            </li>
                            <li class="">
                                <a href="{{ route('admin.deposit.history') }}" class="{{ isActive('admin.deposit.history') }}">
                                    {{ __('Payment History') }}
                                </a>
                            </li>
                        @endcanany
                    </ul>
                </li>
            @endcanany

            @canany(['withdraw-list','withdraw-method-manage','withdraw-action','withdraw-schedule'])
                <li class="{{ isActive(['admin.withdraw*']) }}">
                    <a href="javascript:void(0);" class="navItem">
                        <span class="flex items-center">
                            <iconify-icon class="nav-icon" icon="lucide:landmark"></iconify-icon>
                            <span>{{ __('Payout') }}</span>
                        </span>
                        <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon>
                    </a>
                    <ul class="sidebar-submenu">
                        @canany(['withdraw-list','withdraw-action'])
                            <li class="">
                                <a href="{{ route('admin.withdraw.pending') }}" class="{{ isActive('admin.withdraw.pending')  }}">
                                    {{ __('Pending Payout') }}
                                </a>
                            </li>
                        @endcanany
                        @can('withdraw-list')
                            <li class="">
                                <a href="{{ route('admin.withdraw.history') }}" class="{{ isActive('admin.withdraw.history') }}">
                                    {{ __('Payout History') }}
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany
        @endcanany

        <li class="">
            <a href="{{ route('admin.discounts.index') }}" class="navItem {{ isActive('admin.discounts.index') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="bxs:discount"></iconify-icon>
                    <span>{{ __('Discount Codes') }}</span>
                </span>
            </a>
        </li>

        <li class="">
            <a href="{{ route('admin.leaderboard.index') }}" class="navItem {{ isActive('admin.leaderboard.index') }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:trophy"></iconify-icon>
                    <span>{{ __('Leaderboard') }}</span>
                </span>
            </a>
        </li>

        {{-- ************************************************************* Others *********************************************************--}}
        @canany(['subscriber-list','subscriber-mail-send'])
            <li class="">
                <a href="{{ route('admin.subscriber') }}" class="navItem {{ isActive('admin.subscriber') }}">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="lucide:mail-open"></iconify-icon>
                        <span>{{ __('All Subscriber') }}</span>
                    </span>
                </a>
            </li>
        @endcanany
        @canany(['support-ticket-list','support-ticket-action'])
            <li class="">
                <a href="{{ route('admin.ticket.index') }}" class="navItem {{ isActive('admin.ticket*') }}">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="lucide:wrench"></iconify-icon>
                        <span>{{ __('Support Tickets') }}</span>
                    </span>
                </a>
            </li>
        @endcanany


        {{-- @can('custom-css')
            <li>
                <a href="{{ route('admin.custom-css') }}" class="navItem {{ isActive('admin.custom-css') }}">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="lucide:braces"></iconify-icon>
                        <span>{{ __('Custom CSS') }}</span>
                    </span>
                </a>
            </li>
        @endcan --}}

        {{--<li class="">
            <a href="javascript:void(0);" class="navItem">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="mdi:partnership"></iconify-icon>
                    <span>{{ __('Partnership') }}</span>
                </span>
                <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon>
            </a>
            <ul class="sidebar-submenu">
                <li class="">
                    <a href="{{ route('admin.symbols.index') }}" class="{{ isActive('admin.symbols*') }}">
                        {{ __('Symbols') }}
                    </a>
                </li>
                <li class="">
                    <a href="{{ route('admin.symbol-groups.index') }}" class="{{ isActive('admin.symbol-groups*') }}">
                        {{ __('Symbol Groups') }}
                    </a>
                </li>
                <li class="">
                    <a href="{{ route('admin.rebate-rules.index') }}" class="{{ isActive('admin.rebate-rules*') }}">
                        {{ __('Rebate Rules') }}
                    </a>
                </li>
            </ul>
        </li>--}}

{{-- ************************************************************* Site  Settings *********************************************************--}}
        @canany(['site-setting','email-setting','plugin-setting','page-manage'])
            @canany(['site-setting','email-setting','plugin-setting'])
                <li class="mt-auto">
                    <a href="{{ route('admin.settings.index') }}" class="navItem {{ isActive(['admin.settings*']) }}">
                        <span class="flex items-center">
                            <iconify-icon class="nav-icon" icon="lucide:settings"></iconify-icon>
                            <span>{{ __('Settings') }}</span>
                        </span>
                    </a>
                </li>
            @endcanany
        @endcanany
    </ul>
</div>

