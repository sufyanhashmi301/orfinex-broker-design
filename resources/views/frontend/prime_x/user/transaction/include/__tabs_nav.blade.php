<div class="innerMenu overflow-x-auto mb-5">
    <div class="inline-block min-w-full align-middle">
        <div class="overflow-hidden">
            <ul class="nav nav-tabs custom-tabs inline-flex items-center overflow-hidden rounded list-none border-0 pl-0">
                <li class="nav-item">
                    <a href="{{ route('user.history.transactions') }}" class="btn btn-sm inline-flex justify-center btn-outline-primary loaderBtn {{ isActive('user.history.transactions') }}">
                        {{ __('Transactions') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user.history.tradingAccounts') }}" class="btn btn-sm inline-flex justify-center btn-outline-primary loaderBtn {{ isActive('user.history.tradingAccounts') }}">
                        {{ __('Accounts') }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
