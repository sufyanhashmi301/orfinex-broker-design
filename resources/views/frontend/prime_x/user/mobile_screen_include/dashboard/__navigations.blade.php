<div class="card all-feature-mobile mb-3 mobile-screen-show">
    <div class="card-header">
        <h4 class="card-title">
            {{ __('All Navigations') }}
        </h4>
    </div>
    <div class="card-body p-3">
        <div class="grid grid-cols-3 gap-2 gap-y-5 contents">
            <div class="single text-center py-2">
                <a href="{{ route('user.schema') }}">
                    <img src="{{ asset('frontend/materials/schema.png') }}" class="h-7 mx-auto" alt="">
                    <div class="text-sm dark:text-white">{{ __('New Account') }}</div>
                </a>
            </div>
            <div class="single text-center py-2">
                <a href="{{ route('user.deposit.amount') }}">
                    <img src="{{ asset('frontend/materials/deposit.png') }}" class="h-7 mx-auto" alt="">
                    <div class="text-sm dark:text-white">{{ __('Deposit') }}</div>
                </a>
            </div>
            <div class="single text-center py-2">
                <a href="{{ route('user.withdraw.view') }}">
                    <img src="{{ asset('frontend/materials/withdraw.png') }}" class="h-7 mx-auto" alt="">
                    <div class="text-sm dark:text-white">{{ __('Withdraw') }}</div>
                </a>
            </div>
            <div class="single text-center py-2">
                <a href="{{ route('user.transfer') }}">
                    <img src="{{ asset('frontend/materials/wallet-exchange.png') }}" class="h-7 mx-auto" alt="">
                    <div class="text-sm dark:text-white">{{ __('Transfer') }}</div>
                </a>
            </div>
            <div class="single text-center py-2">
                <a href="{{ route('user.referral') }}">
                    <img src="{{ asset('frontend/materials/referral.png') }}" class="h-7 mx-auto" alt="">
                    <div class="text-sm dark:text-white">{{ __('Partnership') }}</div>
                </a>
            </div>
            <div class="single text-center py-2">
                <a href="{{ route('user.transactions') }}">
                    <img src="{{ asset('frontend/materials/transactions.png') }}" class="h-7 mx-auto" alt="">
                    <div class="text-sm dark:text-white">{{ __('Transactions') }}</div>
                </a>
            </div>
        </div>
        <div class="moretext hidden mt-5">
            <div class="grid grid-cols-3 gap-2 gap-y-5 contents">
                <div class="single text-center py-2">
                    <a href="{{ route('user.send-money.log') }}">
                        <img src="{{ asset('frontend/materials/transfer-log.png') }}" class="h-7 mx-auto" alt="">
                        <div class="text-sm dark:text-white">{{ __('Transfer Log') }}</div>
                    </a>
                </div>
                <div class="single text-center py-2">
                    <a href="{{ route('user.deposit.log') }}">
                        <img src="{{ asset('frontend/materials/deposit-log.png') }}" class="h-7 mx-auto" alt="">
                        <div class="text-sm dark:text-white">{{ __('Deposit Log') }}</div>
                    </a>
                </div>
                <div class="single text-center py-2">
                    <a href="{{ route('user.withdraw.log') }}">
                        <img src="{{ asset('frontend/materials/withdraw-log.png') }}" class="h-7 mx-auto" alt="">
                        <div class="text-sm dark:text-white">{{ __('Withdraw Log') }}</div>
                    </a>
                </div>
                <div class="single text-center py-2">
                    <a href="{{ route('user.ranking-badge') }}">
                        <img src="{{ asset('frontend/materials/ranking.png') }}" class="h-7 mx-auto" alt="">
                        <div class="text-sm dark:text-white">{{ __('Ranking Badge') }}</div>
                    </a>
                </div>
                <div class="single text-center py-2">
                    <a href="{{ route('user.setting.profile') }}">
                        <img src="{{ asset('frontend/materials/settings.png') }}" class="h-7 mx-auto" alt="">
                        <div class="text-sm dark:text-white">{{ __('Settings') }}</div>
                    </a>
                </div>
                <div class="single text-center py-2">
                    <a href="{{ route('user.ticket.index') }}">
                        <img src="{{ asset('frontend/materials/support-ticket.png') }}" class="h-7 mx-auto" alt="">
                        <div class="text-sm dark:text-white">{{ __('Support Ticket') }}</div>
                    </a>
                </div>
                <div class="single text-center py-2">
                    <a href="{{ route('user.notification.all') }}">
                        <img src="{{ asset('frontend/materials/profile.png') }}" class="h-7 mx-auto" alt="">
                        <div class="text-sm dark:text-white">{{ __('Notifications') }}</div>
                    </a>
                </div>
            </div>
        </div>
        <div class="text-center mt-5">
            <button class="moreless-button btn btn-dark btn-sm">{{ __('Load more') }}</button>
        </div>
    </div>
</div>
