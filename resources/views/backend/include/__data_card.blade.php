<div class="row g-3 mb-3">
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
        <div class="data-card">
            <div class="icon bg-light">
                <i icon-name="users"></i>
            </div>
            <div class="content">
                <h4 class="count">{{ $data['register_user'] }}</h4>
                <p>{{ __('Registered User') }}</p>
            </div>
            <a class="link" href="{{ route('admin.user.index') }}"><i icon-name="external-link"></i></a>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
        <div class="data-card">
            <div class="icon bg-light">
                <i icon-name="user-check"></i>
            </div>
            <div class="content">
                <h4 class="count">{{ $data['active_user'] }}</h4>
                <p>{{ __('Active Users') }}</p>
            </div>
            <a class="link" href="{{ route('admin.user.active') }}"><i icon-name="external-link"></i></a>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
        <div class="data-card">
            <div class="icon bg-light">
                <i icon-name="user-cog"></i>
            </div>
            <div class="content">
                <h4 class="count">{{ $data['total_staff'] }}</h4>
                <p>{{ __('Site Staff') }}</p>
            </div>
            <a class="link" href="{{ route('admin.staff.index') }}"><i icon-name="external-link"></i></a>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
        <div class="data-card">
            <div class="icon bg-light">
                <i icon-name="wallet"></i>
            </div>
            <div class="content">
                <h4>{{ $currencySymbol }}<span class="count">{{ round($data['total_deposit'],2) }}</span></h4>
                <p>{{ __('Total Deposits') }}</p>
            </div>
            <a class="link" href="{{ route('admin.deposit.history') }}"><i icon-name="external-link"></i></a>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
        <div class="data-card">
            <div class="icon bg-light">
                <i icon-name="landmark"></i>
            </div>
            <div class="content">
                <h4>{{ $currencySymbol }}<span class="count">{{ round($data['total_withdraw'],2) }}</span></h4>
                <p>{{ __('Total Withdraw') }}</p>
            </div>
            <a class="link" href="{{ route('admin.withdraw.history') }}"><i icon-name="external-link"></i></a>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
        <div class="data-card">
            <div class="icon bg-light">
                <i icon-name="link"></i>
            </div>
            <div class="content">
                <h4 class="count">{{ $data['total_referral'] }}</h4>
                <p>{{ __('Total Referral') }}</p>
            </div>
            <a class="link" href="{{ route('admin.referral.index') }}"><i icon-name="external-link"></i></a>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
        <div class="data-card">
            <div class="icon bg-light">
                <i icon-name="send"></i>
            </div>
            <div class="content">
                <h4>{{ $currencySymbol }}<span class="count">{{ round($data['total_send'],2) }}</span></h4>
                <p>{{ __('Total Send') }}</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
        <div class="data-card">
            <div class="icon bg-light">
                <i icon-name="droplet"></i>
            </div>
            <div class="content">
                <h4><span class="count">{{ 0 }}</span></h4>
                <p>{{ __('Total KYC') }}</p>
            </div>
            <a class="link" href="javascript:void(0);"><i icon-name="external-link"></i></a>
        </div>
    </div>


    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
        <div class="data-card">
            <div class="icon bg-light">
                <i icon-name="package-plus"></i>
            </div>
            <div class="content">
                <h4>{{$data['total_live_forex_accounts']}}</h4>
                <p>{{ __('Live Accounts') }}</p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
        <div class="data-card">
            <div class="icon bg-light">
                <i icon-name="package-plus"></i>
            </div>
            <div class="content">
                <h4>{{$data['total_demo_forex_accounts']}}</h4>
                <p>{{ __('Demo Accounts') }}</p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
        <div class="data-card">
            <div class="icon bg-light">
                <i icon-name="webhook"></i>
            </div>
            <div class="content">
                <h4 class="count">{{ $data['total_gateway'] }}</h4>
                <p>{{ __('Total Gateways') }}</p>
            </div>
            <a class="link" href="{{ route('admin.gateway.automatic') }}"><i icon-name="external-link"></i></a>
        </div>
    </div>

    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
        <div class="data-card">
            <div class="icon bg-light">
                <i icon-name="help-circle"></i>
            </div>
            <div class="content">
                <h4 class="count">{{ $data['total_ticket'] }}</h4>
                <p>{{ __('Total Ticket') }}</p>
            </div>
            <a class="link" href="{{ route('admin.ticket.index') }}"><i icon-name="external-link"></i></a>
        </div>
    </div>

</div>
