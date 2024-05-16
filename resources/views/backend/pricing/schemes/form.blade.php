@php
    use App\Enums\SchemePayout as SPayout;
    use App\Enums\FundedSchemeTypes as FTypes;
    use App\Enums\FundedSchemeSubTypes as FSTypes;
    use App\Enums\FundedGroups as FGroups;
    use App\Enums\FundedApproval as FApproval;
    use App\Enums\FundedStage as FStage;
    use App\Enums\SchemeStatus as SStatus;

    $uid = (!blank($scheme)) ? the_hash($scheme->id) : request()->get('uid');

    if ($type=='new' && has_route('admin.pricing.scheme.save')) {
        $form_action = route('admin.pricing.scheme.save');
    } else {
        $form_action = route('admin.pricing.scheme.update', ['id' => $uid]);
    }
@endphp
{{--{{dd($type,$form_action)}}--}}

<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
        <div class="modal-body modal-body-md">
            <h5 class="title nk-modal-title">{{ __(':Type Scheme / Plan', ['type' => $type]) }}</h5>
            <form action="{{ $form_action }}" class="form-validate is-alter"{!! ($type!='new') ? ' data-confirm="update"' : '' !!}>
                <div class="row gy-2">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="form-label" for="scheme-name">{{ __('Scheme Name') }}</label>
                            <div class="form-control-wrap">
                                <input type="text" name="name" class="form-control" id="scheme-name" value="{{ data_get($scheme, 'name', '') }}" required>
                            </div>
                            <div class="form-note">{{ __("The name of funded scheme.") }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="shortcode">{{ __('Short Name') }}</label>
                            <div class="form-control-wrap">
                                <input type="text" name="short" class="form-control" id="shortcode" maxlength="2" value="{{ data_get($scheme, 'short', '') }}" required>
                            </div>
                            <div class="form-note">{{ __("The short name for plan.") }}</div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="scheme-desc">{{ __('Scheme Description') }}</label>
                            <div class="form-control-wrap">
                                <input type="text" name="desc" class="form-control" id="scheme-desc" value="{{ data_get($scheme, 'desc', '') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="divider md mb-3 stretched"></div>
                <div class="row gy-3">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label" for="invest-amount">{{ __('Investment Amount') }}</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="form-control-wrap">
                                        <input type="text" name="amount" class="form-control" id="invest-amount" min="0.01" data-msg-min="{{ __("More than 0.01") }}" value="{{ data_get($scheme, 'amount') }}" required>
                                    </div>
                                    <div class="form-note">{{ __('Funded (:currency)', ['currency' => base_currency()]) }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="form-control-wrap">
                                            <input type="text" name="amount_allotted" class="form-control" id="invest-allotted" value="{{ data_get($scheme, 'amount_allotted') }}">
                                    </div>
                                    <div class="form-note">{{ __('Allotted (:currency)', ['currency' => base_currency()]) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label" for="interest-rate">{{ __('Draw down Limits') }}</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="form-control-wrap">
                                        <input name="daily_drawdown_limit" type="text" class="form-control" id="daily-draw-down" value="{{ data_get($scheme, 'daily_drawdown_limit') }}" required>
                                    </div>
                                    <div class="form-note">{{ __('Daily Draw down') }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="form-control-wrap">
                                        <input name="max_drawdown_limit" type="text" class="form-control" id="max-drawdown- limit" value="{{ data_get($scheme, 'max_drawdown_limit') }}" required>
                                    </div>
                                    <div class="form-note">{{ __('Maximum Draw down') }}</div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6"  >
                        <div class="form-group">
                            <label class="form-label" for="invest-amount">{{ __('Profits') }}</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="form-control-wrap">
                                        <input type="text" name="profit_share_user" class="form-control" id="profit_share_user" min="0.01" data-msg-min="{{ __("More than 0.01") }}" value="{{ data_get($scheme, 'profit_share_user') }}" required>
                                    </div>
                                    <div class="form-note">{{ __('Profit Share User (%)') }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="form-control-wrap">
                                        <input type="text" name="profit_share_admin" class="form-control" id="profit_share_admin" value="{{ data_get($scheme, 'profit_share_admin') }}">
                                    </div>
                                    <div class="form-note">{{ __('Profit Share Admin (%)') }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="form-control-wrap">
                                        <input type="text" name="profit_target" class="form-control" id="profit_target" value="{{ data_get($scheme, 'profit_target') }}">
                                    </div>
                                    <div class="form-note">{{ __('Profit Target') }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="form-control-wrap">
                                        <input type="text" name="leverage" class="form-control" id="leverage" value="{{ data_get($scheme, 'leverage') }}">
                                    </div>
                                    <div class="form-note">{{ __('Leverage') }}</div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6" >
                        <div class="form-group">
                            <label class="form-label" for="interest-rate">{{ __('Trading Days') }}</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="form-control-wrap">
                                        <input name="min_trading_days" type="text" class="form-control" id="min_trading_days" value="{{ data_get($scheme, 'min_trading_days') }}" required>
                                    </div>
                                    <div class="form-note">{{ __('Min Trading Days') }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="form-control-wrap">
                                        <input name="max_trading_days" type="text" class="form-control" id="max_trading_days" value="{{ data_get($scheme, 'max_trading_days') }}" required>
                                    </div>
                                    <div class="form-note">{{ __('Max Trading Days') }}</div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12" >
                        <div class="form-group">
                            <label class="form-label" for="interest-rate">{{ __('Mt5 Groups') }}</label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <div class="form-control-wrap">
                                        <input name="swap_group" type="text" class="form-control" id="swap_group" value="{{ data_get($scheme, 'swap_group') }}" required>
                                    </div>
                                    <div class="form-note">{{ __('Swap Group') }}</div>
                                </div>
                                <div class="col-4">
                                    <div class="form-control-wrap">
                                        <input name="swap_free_group" type="text" class="form-control" id="swap_free_group" value="{{ data_get($scheme, 'swap_free_group') }}" required>
                                    </div>
                                    <div class="form-note">{{ __('Swap Free Group') }}</div>
                                </div>
                                <div class="col-4">
                                    <div class="form-control-wrap">
                                        <input name="discount_price" type="text" class="form-control" id="discount_price" value="{{ data_get($scheme, 'discount_price') }}" required>
                                    </div>
                                    <div class="form-note">{{ __('Discounted Amount') }}</div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-lg-4">
                        <label class="form-label" for="interest-period">{{ __('Types') }}</label>
                        <div class="form-control-wrap">
                            <select name="type" class="form-select form-control" id="type">


                            @foreach(get_enums(FTypes::class, false) as $term)
                                    <option{{ (data_get($scheme, 'type') == $term) ? ' selected' : '' }} value="{{ $term }}">{{ fst2n($term)  }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-6 col-lg-4">
                        <label class="form-label" for="interest-period">{{ __('Sub Types') }}</label>
                        <div class="form-control-wrap">
                            <select name="sub_type" class="form-select form-control" id="type">
                            @foreach(get_enums(FSTypes::class, false) as $term)
                                    <option{{ (data_get($scheme, 'sub_type') == $term) ? ' selected' : '' }} value="{{ $term }}">{{ fsst2n($term)  }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-6 col-lg-4">
                        <div class="form-group">
                            <label class="form-label" for="payout-policy">{{ __('Stage') }}</label>
                            <div class="form-control-wrap">

                                <select name="stage" class="form-select form-control" id="stage" required>
                                    <option>{{__('Select') }}</option>
                                    @foreach(get_enums(FStage::class, false) as $term)
                                        <option{{ (data_get($scheme, 'stage') == $term) ? ' selected' : '' }}{{ ($term == 'after_matured') ? ' disabled' : '' }} value="{{ $term }}">{{ str_replace('_', ' ', ucfirst($term)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-4">
                        <div class="form-group">
                            <label class="form-label" for="payout-policy">{{ __('Approval') }}</label>
                            <div class="form-control-wrap">
                                <select name="approval" class="form-select form-control" id="approval" required>
                                    @foreach(get_enums(FApproval::class, false) as $term)
                                        <option{{ (data_get($scheme, 'approval') == $term) ? ' selected' : '' }}{{ ($term == 'after_matured') ? ' disabled' : '' }} value="{{ $term }}">{{ str_replace('_', ' ', ucfirst($term)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="divider md mb-3 stretched"></div>
                <div class="row gy-3">
                    <div class="col-12">
                        <div class="row gy-1">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="form-control-wrap mb-1">
                                        <div class="custom-control custom-control-labeled custom-switch">
                                            <input name="ea_boat" type="checkbox" class="custom-control-input" id="ea_boat"{{ (data_get($scheme, 'ea_boat')) ? ' checked=""' : '' }}>
                                            <label class="custom-control-label" for="ea_boat">{{ __('Set as EA/ Boat.') }}</label>
                                        </div>
                                    </div>
                                    <div class="form-control-wrap mb-1">
                                        <div class="custom-control custom-control-labeled custom-switch">
                                            <input name="trading_news" type="checkbox" class="custom-control-input" id="trading_news"{{ (data_get($scheme, 'trading_news', 1)) ? ' checked=""' : '' }}>
                                            <label class="custom-control-label" for="trading_news">{{ __('Set as trading news.') }}</label>
                                        </div>
                                    </div>
                                    <div class="form-control-wrap">
                                        <div class="custom-control custom-control-labeled custom-switch">
                                            <input name="is_discount" type="checkbox" class="custom-control-input" id="is_discount"{{ (data_get($scheme, 'is_discount')) ? ' checked=""' : '' }}>
                                            <label class="custom-control-label" for="is_discount">{{ __('Set as Discount Allowed.') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <div class="custom-control custom-control-labeled custom-switch">
                                            <input name="re_attempt_discount" type="checkbox" class="custom-control-input" id="re_attempt_discount"{{ (data_get($scheme, 're_attempt_discount')) ? ' checked=""' : '' }}>
                                            <label class="custom-control-label" for="re_attempt_discount">{{ __('Set as Re-attempt discount.') }}</label>
                                        </div>
                                    </div>
                                    <div class="form-control-wrap">
                                        <div class="custom-control custom-control-labeled custom-switch">
                                            <input name="weekend_holding" type="checkbox" class="custom-control-input" id="weekend_holding"{{ (data_get($scheme, 'weekend_holding')) ? ' checked=""' : '' }}>
                                            <label class="custom-control-label" for="weekend_holding">{{ __('Set as weekend disabled.') }}</label>
                                        </div>
                                    </div>
                                    <div class="form-control-wrap">
                                        <div class="custom-control custom-control-labeled custom-switch">
                                            <input name="refundable" type="checkbox" class="custom-control-input" id="refundable"{{ (data_get($scheme, 'refundable')) ? ' checked=""' : '' }}>
                                            <label class="custom-control-label" for="refundable">{{ __('Set as refundable amount disabled.') }}</label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="divider md mb-3 stretched"></div>
                <div class="row gy-3">
                    <div class="col-12">
                        @if($type!='new')
                        <div class="notes mt-4">
                            <ul>
                                <li class="alert-note is-plain text-danger">
                                    <em class="icon ni ni-alert"></em>
                                    <p><strong>{{ __('Notes:') }}</strong> {{ __('Your changes does not affect on old subscription as only affect to new subscription.') }}</p>
                                </li>
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="divider md stretched"></div>
                <div class="row">
                    <div class="col-12">
                        <div class="align-center flex-nowrap g-3">
                            <div class="col">
                                <div class="custom-control custom-switch">
                                    <input name="status" id="plan-status" type="checkbox" class="custom-control-input"{{ (data_get($scheme, 'status', SStatus::INACTIVE) == SStatus::ACTIVE) ? ' checked=""' : ''}}>
                                    <label for="plan-status" class="custom-control-label">{{ __('Active') }}</label>
                                </div>
                            </div>
                            <div class="col">
                                <ul class="align-center justify-content-end flex-nowrap gx-4">
                                    <li class="order-last">
                                        @if(data_get($scheme, 'id'))
                                            <input name="id" type="hidden" value="{{ data_get($scheme, 'id') }}">
                                        @endif
                                        <button type="button" class="btn btn-primary m-ivs-save" data-action="update">
                                            <span class="spinner-border spinner-border-sm hide" role="status" aria-hidden="true"></span>
                                            <span>{{ (($type=='new') ? __('Add Scheme') : __('Save Scheme')) }}</span>
                                        </button>
                                    </li>
                                    <li class="d-none d-sm-inline">
                                        <a href="#" data-dismiss="modal" class="link link-danger">{{ __('Cancel') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
