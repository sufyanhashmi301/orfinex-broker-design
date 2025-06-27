@if(count($incomingSummary))
    <div class="card-header">
        <h4 class="card-title">{{ __('Incoming Transactions') }}</h4>
    </div>
    <div class="card-body p-6">
        <div class="grid xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 place-content-center">
            @each('backend.transaction.include.__report_card', $incomingSummary, 'row')
        </div>
    </div>
@endif

@if(count($outgoingSummary))
    <div class="card-header">
        <h4 class="card-title">{{ __('Outgoing Transactions') }}</h4>
    </div>
    <div class="card-body p-6">
        <div class="grid xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 place-content-center">
            @each('backend.transaction.include.__report_card', $outgoingSummary, 'row')
        </div>
    </div>
@endif
