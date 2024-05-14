@php

    use App\Enums\InvestmentStatus;

    // $currency = base_currency();
    $is_running = ($plan->status==InvestmentStatus::ACTIVE) ? true : false;
    $is_pending = ($plan->status==InvestmentStatus::PENDING) ? true : false;

@endphp

<div class="flex flex-row items-center">
    @if($plan->user_id != 1 )
        <div class="pr-3 form-hidden-inputs form-hidden-inputs-convert hidden">
            <div class="custom-control custom-checkbox notext align-middle">
                <input type="checkbox" class="custom-control-input" name="selectedPlans[]"
                       value="{{ the_hash($plan->id)}}" id="pid-{{ (the_hash($plan->id)) }}">
                <label class="custom-control-label" for="pid-{{ (the_hash($plan->id))}}"></label>
            </div>
        </div>
    @endif
    <div class="flex-1 py-3 px-6">
        <div class="flex items-center">
            <div class="flex-none {{ ($is_running ? ' is-running' : (($is_pending) ? ' is-pending' : '')) }}">
                <iconify-icon icon="dashicons:{{ ($plan->status==InvestmentStatus::ACTIVE) ? 'update' : 'offer' }}" class="text-4xl text-success-500 mr-2"></iconify-icon>
            </div>
            <div class="flex-1 text-start">
                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                    {{ data_get($plan, 'summary_title') }}
                </h4>
            </div>
        </div>
    </div>
    <div class="flex-1 py-3 px-6 nk-plan-term">
        {{ show_date(data_get($plan, 'term_start'), true) }}
    </div>
    <div class="flex-1 py-3 px-6 nk-plan-amount">
        {{ money(data_get($plan, 'received'), $currency) }}
    </div>
    <div class="flex-1 py-3 px-6">
        <a class="btn btn-sm btn-dark btn-trans" href="{{ route('user.pricing.details', ['id' => the_hash($plan->id)]) }}">Fund Matrics</a>
    </div>
    
</div>
