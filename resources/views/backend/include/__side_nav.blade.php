<div id="bodyOverlay" class="w-screen h-screen fixed top-0 bg-slate-900 bg-opacity-50 backdrop-blur-sm z-10 hidden"></div>
<div class="logo-segment">
    <a class="flex items-center" href="{{route('admin.dashboard')}}">
        @php
            $logoSrc = setting('site_favicon','global')
                ? asset(setting('site_favicon','global'))
                : asset('backend/images/example_favicon.png');
        @endphp
        <img src="{{ $logoSrc }}" class="black_logo h-8" alt="logo">
        <img src="{{ $logoSrc }}" class="white_logo h-8" alt="logo">
        <span class="logo-title ltr:ml-3 rtl:mr-3 text-xl font-Inter font-medium text-white">
            {{ __('Backoffice') }}
        </span>
    </a>
    <!-- Sidebar Type Button -->
    <button class="sidebarCloseIcon text-2xl">
        <iconify-icon class="text-slate-200" icon="clarity:window-close-line"></iconify-icon>
    </button>
</div>
<div id="nav_shadow" class="nav_shadow h-[60px] absolute top-[80px] nav-shadow z-[1] w-full transition-all duration-200 pointer-events-none opacity-0"></div>
<div class="sidebar-menus bg-white dark:bg-slate-800 py-2 px-4 h-[calc(100%-100px)] overflow-y-auto z-50" id="sidebar_menus">
    <ul class="sidebar-menu flex flex-column mt-3">
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
                            <a href="{{route('admin.user.index', ['status' => 'all'])}}" class="{{ isActive('admin.user.index') }}">
                                {{ __('Customers') }}
                            </a>
                        </li>
                        {{-- <li>
                            <a href="{{ route('admin.user.active') }}" class="{{ isActive('admin.user.active') }}">
                                {{ __('Active Customers') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.user.disabled') }}" class="{{ isActive('admin.user.disabled') }}">
                                {{ __('Disabled Customers') }}
                            </a>
                        </li> --}}

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

        @if (\App\Models\VoiceCall::where('status', 1)->exists())
            <li class="">
                <a href="{{ route('admin.twilio.index') }}" class="navItem {{ isActive('admin.twilio*') }}">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" style="font-size: 20px" icon="lucide:phone"></iconify-icon>
                        <span>{{ __('Voice Calls') }}</span>
                    </span>
                </a>
            </li>
        @endif
        

        @canany(['kyc-list','kyc-action','kyc-form-manage','risk-profile-tag'])
            <li class="">
                <a href="{{ route('admin.kyc.index', ['status' => 'all']) }}" class="navItem {{ isActive('admin.kyc*') }}">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon " style="font-size: 20px" icon="lucide:lock-open"></iconify-icon>
                        <span>{{ __('KYCs') }}</span>
                    </span>
                    {{-- <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon> --}}
                </a>
                {{-- <ul class="sidebar-submenu">
                    @canany(['kyc-list','kyc-action'])
                        <li>
                            <a href="#" class="#">
                                {{ __('Pending KYC') }}
                            </a>
                        </li>
                        <li>
                            <a href="#" class="{{ isActive('admin.kyc.rejected') }}">
                                {{ __('Rejected KYC') }}
                            </a>
                        </li>
                        <li>
                            <a href="#" class="{{ isActive('admin.kyc.all') }}">
                                {{ __('All KYC Logs') }}
                            </a>
                        </li>
                    @endcanany
                </ul> --}}
            </li>
        @endcanany

        {{-- *************************************************************  Staff Management *********************************************************--}}
        @canany(['role-list','role-create','role-edit','staff-list','staff-create','staff-edit'])
            @canany(['staff-list','staff-create','staff-edit'])
                <li>
                    <a href="{{route('admin.staff.index')}}" class="navItem {{ isActive('admin.staff*') }}">
                        <span class="flex items-center">
                            <iconify-icon class="nav-icon" icon="lucide:user-cog"></iconify-icon>
                            <span>{{ __('Staffs') }}</span>
                        </span>
                    </a>
                </li>
            @endcanany
        @endcanany

        {{-- *************************************************************  Plan Management *********************************************************--}}
        @can('account-type-list')
            <li class="{{ isActive(['admin.account_type*']) }}">
                <a href="javascript:void(0);" class="navItem">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="lucide:package-plus"></iconify-icon>
                        <span>Plans</span>
                    </span>
                    <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon>
                </a>
                <ul class="sidebar-submenu">
                    
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

                </ul>
            </li>
        @endcan
           
        {{-- @can('account-activity-list')
            <li class="">
                <a href="" class="navItem">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="lucide:cast"></iconify-icon>
                        <span>{{ __('Accounts Activities') }}</span>
                    </span>
                    <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{route('admin.accounts_activity.log' )}}?pending-approvals" class="{{ request()->has('pending-approvals') ? 'active' : '' }}">
                            {{ __('Pending Approvals') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.accounts_activity.log') }}?violated-acounts" class="{{ request()->has('violated-acounts') ? 'active' : '' }}">
                            {{ __('Violated Accounts') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.accounts_activity.log') }}" class="{{ url()->current() === route('admin.accounts_activity.log') && empty(request()->query()) ? 'active' : '' }}">
                            {{ __('All Accounts Logs') }}
                        </a>
                    </li>
                </ul>
            </li>
        @endcan --}}

        @php
            $pending_approvals_count = App\Models\AccountActivity::where(['status' => 'admin_approve', 'action' => 0])->with(['accountTypeInvestment.user'])
                                                                ->whereHas('accountTypeInvestment',   function($query) {
                                                                    $query->whereHas('user'); 
                                                                })->count();
        @endphp

        @canany(['account-list', 'account-trading-history', 'account-activity-list'])
            <li class="">
                <a href="javascript:void(0)" class="navItem">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="lucide:clipboard-list"></iconify-icon>
                        <span>{{ __('Accounts') }}</span>
                        <div style="position: relative; top: -2px; background: #dc3545" class="badge text-white ml-2">{{ $pending_approvals_count }}</div>
                    </span>
                    <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon>
                </a>
                <ul class="sidebar-submenu">
                    @can('account-list')
                        <li>
                            <a href="{{route('admin.accounts.index', ['status' => 'all'])}}" class="{{ isActive('admin.accounts.index') }}">
                                Accounts 
                            </a>
                        </li>
                    @endcan

                    @can('account-activity-list')
                        <li>
                            <a href="{{route('admin.accounts_activity.log', ['status' => \App\Enums\AccountActivityStatusEnums::ADMIN_APPROVE ] )}}" class="{{ isActive('admin.accounts_activity*') }}">
                                {{ __('Pending Approvals') }} 
                                <div style="float: right; position: relative; top: -4px" class="badge badge-danger">{{ $pending_approvals_count }}</div>
                            </a>
                        </li>
                    @endcan
                    
                    @can('account-trading-history')
                        <li>
                            <a href="{{route('admin.account.trading_stats.history')}}" class="{{ isActive('admin.account.trading_stats.history') }}">
                                Trading History
                            </a>
                        </li>
                    @endcan

                    
                    
                </ul>

            </li>
        @endcanany
           
        @if (setting('active_trader_type', 'features') == \App\Enums\TraderType::MT5)
            @can('risk-hub-view')
                <li>
                    <a href="{{ route('admin.risk-rule.quick_trades') }}" class="navItem {{ isActive('admin.risk-rule.quick_trades') }}">
                        <span class="flex items-center">
                            <iconify-icon class="nav-icon" icon="lucide:circle-alert"></iconify-icon>
                            <span>{{ __('Risk Hub') }}</span>
                        </span>
                    </a>
                </li>
            @endcan    
        @endif
        
        

     

        {{-- *************************************************************  Transactions *********************************************************--}}
        {{-- @canany(['transaction-list','investment-list','profit-list'])
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
        @endcanany --}}

        {{-- *************************************************************  Certificates *********************************************************--}}
        @canany(['certificate-manage', 'certificate-awarded-list'])
            <li class="" >
                <a href="{{ route('admin.certificates.index') }}" class="navItem {{ isActive('admin.certificates.index') }}">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="lucide:file-badge"></iconify-icon>
                        <span>{{ __('Certificates') }}</span>
                    </span>
                    {{-- <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon> --}}
                </a>
                {{-- <ul class="sidebar-submenu">
                    
                    @can('certificate-awarded-list')
                        <li class="">
                            <a href="{{ route('admin.certificates.index') }}" class="{{ isActive('admin.certificates.index') }}">
                                {{ __('Certificates') }}
                            </a>
                        </li>
                    @endcan
                </ul> --}}
            </li>
        @endcanany

        {{-- *************************************************************  Contracts *********************************************************--}}
        @can('contract-list')
            <li class="" >
                <a href="{{ route('admin.contracts.index', ['status' => 'all']) }}" class="navItem {{ isActive('admin.contracts.index') }}">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="lucide:file-text"></iconify-icon>
                        <span>{{ __('Contracts') }}</span>
                    </span>
                    {{-- <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon> --}}
                </a>
                {{-- <ul class="sidebar-submenu">
                    
                    <li class="">
                        <a href="javascript:void(0);" class="">
                            {{ __('Signed Contracts') }}
                        </a>
                    </li>
                    <li class="">
                        <a href="javascript:void(0);" class="">
                            {{ __('Pending Contracts') }}
                        </a>
                    </li>
                    <li class="">
                        <a href="javascript:void(0);" class="">
                            {{ __('Expired Contracts') }}
                        </a>
                    </li>
                </ul> --}}
            </li>
        @endcan
        

        {{-- *************************************************************  Addons *********************************************************--}}
        {{-- @can('addon-list')
            <li class="" >
                <a href="{{route('admin.addons.index')}}" class="navItem {{ isActive('admin.addons.index') }}">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="lucide:package-plus"></iconify-icon>
                        <span>{{ __('Addons') }}</span>
                    </span>
                </a>
            </li> 
        @endcan --}}
        

        {{-- *************************************************************  Essentials *********************************************************--}}
        @canany(['automatic-gateway-manage','manual-gateway-manage','deposit-list','deposit-action',
        'withdraw-list','withdraw-method-manage','withdraw-action','target-manage','referral-create',
        'referral-list','referral-edit','referral-delete','ranking-list','ranking-create','ranking-edit'])

            <li class="">
                <a href="{{route('admin.payout_requests.index', ["status" => 'all'])}}" class="navItem {{ isActive('admin.payout_requests.index') }}">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="lucide:hand-coins"></iconify-icon>
                        <span>{{ __('Payouts') }}</span>
                    </span>
                </a>
            </li>

            @canany(['automatic-gateway-manage','manual-gateway-manage','deposit-list','deposit-action'])
                <li class="{{ isActive(['admin.deposit*']) }}">
                    <a href="{{ route('admin.deposit.manual.pending') }}" class="navItem {{ isActive('admin.deposit.manual.pending') }}">
                        <span class="flex items-center">
                            <iconify-icon class="nav-icon" icon="lucide:arrow-down-circle"></iconify-icon>
                            <span>{{ __('Payments') }}</span>
                        </span>
                        {{-- <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon> --}}
                    </a>
                    {{-- <ul class="sidebar-submenu">
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
                    </ul> --}}
                </li>
            @endcanany

            @canany(['withdraw-list','withdraw-method-manage','withdraw-action','withdraw-schedule'])

                <li class="{{ isActive(['admin.withdraw*']) }}">
                    <a href="{{ route('admin.withdraw.pending') }}" class="navItem {{ isActive('admin.withdraw.pending')  }}">
                        <span class="flex items-center">
                            <iconify-icon class="nav-icon" icon="lucide:arrow-up-circle"></iconify-icon>
                            <span>Withdraws</span>
                        </span>
                        {{-- <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon> --}}
                    </a>
                    {{-- <ul class="sidebar-submenu">
                        @canany(['withdraw-list','withdraw-action'])
                            <li class="">
                                <a href="{{ route('admin.withdraw.pending') }}" class="{{ isActive('admin.withdraw.pending')  }}">
                                    Pending Withdraw
                                </a>
                            </li>
                        @endcanany
                        @can('withdraw-list')
                            <li class="">
                                <a href="{{ route('admin.withdraw.history') }}" class="{{ isActive('admin.withdraw.history') }}">
                                    Withdraw History
                                </a>
                            </li>
                        @endcan
                    </ul> --}}
                </li>
            @endcanany
        @endcanany

        @canany(['affiliate-list'])
            <li class="">
                <a href="{{ route('admin.affiliates.index') }}" class="navItem {{ isActive('admin.affiliates.index') }}">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="lucide:share-2"></iconify-icon>
                        <span>{{ __('Affiliates') }}</span>
                    </span>
                    {{-- <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon> --}}
                </a>
                {{-- <ul class="sidebar-submenu">
                    @can('affiliate-config')
                        <li class="">
                            <a href="{{ route('admin.affiliate-rules.create') }}" class="{{ isActive('admin.affiliate-rules.create') }}">
                                {{ __('Configure Affiliate Rules') }}
                            </a>
                        </li> 
                    @endcan
                
                    @can('affiliate-list')
                        <li class="">
                            <a href="{{ route('admin.affiliates.index') }}" class="{{ isActive('admin.affiliates.index') }}">
                                {{ __('Affiliates') }}
                            </a>
                        </li>
                    @endcan
                    
                </ul> --}}
            </li> 
        @endcan

        @canany(['affiliate-config', 'addon-list', 'certificate-manage'])
            <li class="">
                <a href="javascript:void(0)" class="navItem ">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="lucide:wrench"></iconify-icon>
                        <span>Configure</span>
                    </span>
                    <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon>
                </a>
                <ul class="sidebar-submenu">
                
                    @can('affiliate-config')
                        <li class="">
                            <a href="{{ route('admin.affiliate-rules.create') }}" class="{{ isActive('admin.affiliate-rules.create') }}">
                                Affiliate Rules
                            </a>
                        </li> 
                    @endcan

                    @can('certificate-manage')
                        <li class="">
                            <a href="{{ route('admin.certificates.manage') }}" class="{{ isActive('admin.certificates.manage') }}">
                                Certificates
                            </a>
                        </li>
                    @endcan

                    {{-- <li class="" >
                        <a href="#" class="">
                            Contract Templates
                        </a>
                    </li>  --}}

                    @can('addon-list')
                        <li class="" >
                            <a href="{{route('admin.addons.index')}}" class="{{ isActive('admin.addons.index') }}">
                                Addons
                            </a>
                        </li> 
                    @endcan

                    
                    
                </ul>
            </li> 
        @endcan
        
        @can('discount-code-list')
            <li class="">
                <a href="{{ route('admin.discounts.index') }}" class="navItem {{ isActive('admin.discounts.index') }}">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="lucide:circle-percent"></iconify-icon>
                        <span>{{ __('Coupons') }}</span>
                    </span>
                </a>
            </li>
        @endcan

        @can('leaderboard-view')
            <li class="">
                <a href="{{ route('admin.leaderboard.index') }}" class="navItem {{ isActive('admin.leaderboard.index') }}">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="lucide:trophy"></iconify-icon>
                        <span>{{ __('Leaderboard') }}</span>
                    </span>
                </a>
            </li>
        @endcan

        {{-- ************************************************************* Others *********************************************************--}}
        @canany(['subscriber-list','subscriber-mail-send'])
            <li class="">
                <a href="{{ route('admin.subscriber') }}" class="navItem {{ isActive('admin.subscriber') }}">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="lucide:mail-open"></iconify-icon>
                        <span>{{ __('Subscribers') }}</span>
                    </span>
                </a>
            </li>
        @endcanany
        @canany(['support-ticket-list','support-ticket-action'])
            <li class="">
                <a href="{{ route('admin.ticket.index') }}" class="navItem {{ isActive('admin.ticket*') }}">
                    <span class="flex items-center">
                        <iconify-icon class="nav-icon" icon="lucide:headset"></iconify-icon>
                        <span>{{ __('Tickets') }}</span>
                    </span>
                </a>
            </li>
        @endcanany

    </ul>
</div>
<div class="stickySetting_menu sticky z-50 bottom-0 px-6 py-4">
    {{-- ************************************************************* Site  Settings *********************************************************--}}
    @canany(['site-setting','email-setting','plugin-setting','page-manage'])
        @canany(['site-setting','email-setting','plugin-setting'])
            <a href="{{ route('admin.settings.index') }}" class="navItem {{ isActive(['admin.settings*']) }}">
                <span class="flex items-center">
                    <iconify-icon class="nav-icon" icon="lucide:settings"></iconify-icon>
                    <span>{{ __('Settings') }}</span>
                </span>
            </a>
        @endcanany
    @endcanany
</div>
