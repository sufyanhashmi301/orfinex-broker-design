<div class="modal fade" tabindex="-1" role="dialog" id="migrate-investment">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            <div class="modal-body modal-body-md">
                <h5 class="title nk-modal-title">{{ __("Migrate the Investment") }}</h5>
                <p class="text-danger"><strong>{{ __("Please confirm that you want to migrate this ACTIVE plan.") }}</strong></p>
                <form action="{{ route('admin.pricing.plan.migrate', ['id' => the_hash($invest->id)]) }}" method="POST" data-confirm="migrate" class="form-validate is-alter">
                    <div class="form-group">
                        <label class="form-label" for="migrate-note">{{ __('Migration Plan') }}</label>
                        <div class="form-control-wrap">
                            <input type="hidden" id="invetsment_id" value="{{the_hash($invest->id)}}">
                            <select class="form-select" name="migrate-method" id="plan_id">
                                @foreach($plans as $plan)
                                    <option value="{{ the_hash($plan->id)}}">{{ $plan->name }}</option>
                                @endforeach
{{--                                    <option value="{{ \App\Enums\RefundType::PARTIAL }}">{{ __("Return :Type Amount", ['type' => \App\Enums\RefundType::PARTIAL]) }}</option>--}}
                            </select>
                        </div>
                        <div class="form-note">{{ __('after migration this plan will start from scratch.') }}</div>
                    </div>

                    <ul class="align-center flex-wrap flex-sm-nowrap gx-1 gy-2">
                        <li>
                            <button type="button" class="btn btn-lg btn-danger m-ivs-migrate-plan">{{ __('Migrate the Plan') }}</button>
                        </li>
                        <li>
                            <button type="button" class="btn btn-lg btn-white" data-dismiss="modal" data-target="#migrate-investment">{{ __('Dismiss') }}</button>
                        </li>
                    </ul>
                </form>
                <div class="divider stretched"></div>

            </div>
        </div>
    </div>
</div>
