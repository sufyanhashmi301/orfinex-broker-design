<div class="nk-pps-apps">
    <div class="nk-pps-result">
        <em class="icon icon-circle icon-circle-xxl ni ni-check bg-success"></em>
        <h4 class="title">{{ __('Congratulation!') }}</h4>
        <div class="nk-pps-text md">
            <p class="caption-text">
                <span>
                    {!! __("You have successfully request for the funded account worth :amount on the plan of ':scheme' using your account balance.", [
                    'scheme' => '<strong>'.data_get($invest, 'scheme.name').'</strong>',
                    'amount' => '<strong>'.data_get($invest, 'total').'</strong>'
                ]) !!}</span>
            </p>
            <p class="sub-text-sm">{{ __('You will receive your Funded Account soon (Maximum 24 hours), once our team verify it for you') }}</p>
        </div>
        <div class="nk-pps-action">
            <ul class="btn-group-vertical align-center gy-3">
                <li><a href="{{ route('user.pricing.investment.dashboard') }}" class="btn btn-lg btn-mw btn-primary">{{ __('Go to Dashboard') }}</a></li>
                <li><a href="{{ route('user.pricing.investment.plans') }}" class="link link-primary">{{ __('Check our available plans') }}</a></li>
            </ul>
        </div>
        <div class="nk-pps-notes text-center">{{ __("Please feel free to contact us if you have any question.") }}</div>
    </div>
</div>
