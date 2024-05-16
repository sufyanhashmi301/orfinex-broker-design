@php
    use App\Enums\SchemeStatus as SStatus;
    use App\Enums\InterestRateType as IRType;
@endphp

<div class="row g-gs">
@if(!blank($schemes))
    @foreach($schemes as $scheme)
    <div class="col-lg-4 col-sm-6">
        <div class="card plan-card">
            <div class="card-inner-group">
                <div class="card-inner">
                    <div class="card-title-group align-items-start">
                        <div class="card-title">
                            <h5 class="title align-center" id="{{ the_hash($scheme->id) }}">{{ data_get($scheme, 'name') }} <span class="ml-2 badge badge-pill badge-dim {{ data_get($scheme, 'status_badge_class') }}">{{ ucfirst(data_get($scheme, 'status')) }}</span></h5>
                            <p>{{ fst2n((data_get($scheme, 'type'))) }}/{{ fsst2n(data_get($scheme, 'sub_type')) }}/{{ data_get($scheme, 'stage') }}</p>
                            <p>{{ data_get($scheme, 'desc') }}</p>
                        </div>
                        <div class="card-tools mt-n1 mr-n1">
                            <div class="dropdown">
                                <a href="#" class="btn btn-icon btn-trigger" data-bs-toggle="dropdown">
                                    <i icon-name="more-vertical"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <ul class="">
                                        <li>
                                            <a href="javascript:void(0)" class="dropdown-item m-ivs-scheme" data-action="edit" data-view="modal" data-backdrop="static" data-uid="{{ the_hash($scheme->id) }}">
                                                <em class="icon ni ni-edit-fill"></em>
                                                <span>{{ __('Update Scheme') }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0)" class="dropdown-item m-ivs-scheme" data-action="scheme" data-view="modal" data-backdrop="static" data-uid="{{ the_hash($scheme->id) }}">
                                                <em class="icon ni ni-edit-fill"></em>
                                                <span>{{ __('Clone Scheme') }}</span>
                                            </a>
                                        </li>

                                        {{-- <li><a href="javascript:void(0)"><em class="icon ni ni-growth-fill"></em><span>{{ __('View Reports') }}</span></a></li> --}}

                                        @if(data_get($scheme, 'status') != SStatus::ACTIVE)
                                        <li>
                                            <a href="javascript:void(0)" class="dropdown-item m-ivs-update" data-action="{{ SStatus::ACTIVE }}" data-uid="{{ the_hash($scheme->id) }}">
                                                <em class="icon ni ni-spark-fill"></em>
                                                <span>{{ __('Mark Active') }}</span>
                                            </a>
                                        </li>
                                        @endif

                                        @if(data_get($scheme, 'status') != SStatus::INACTIVE)
                                        <li>
                                            <a href="javascript:void(0)" class="dropdown-item m-ivs-update" data-action="{{ SStatus::INACTIVE }}" data-uid="{{ the_hash($scheme->id) }}">
                                                <em class="icon ni ni-spark-off-fill"></em>
                                                <span>{{ __('Mark Inactive') }}</span>
                                            </a>
                                        </li>
                                        @endif

                                        @if(data_get($scheme, 'status') != SStatus::ARCHIVED)
                                        <li>
                                            <a href="javascript:void(0)" class="dropdown-item m-ivs-update" data-action="{{ SStatus::ARCHIVED }}" data-uid="{{ the_hash($scheme->id) }}">
                                                <em class="icon ni ni-archive-fill"></em>
                                                <span>{{ __('Mark Archive') }}</span>
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-inner">
                    <div class="row">
                        <div class="col-6">
                            <div class="plan-sum">
                                <div class="amount">
                                    {{ data_get($scheme, 'amount') }} <br>
                                        - <br>
                                    {{ data_get($scheme, 'amount_allotted') }} {{ base_currency() }} <br> </div>
{{--                                <span class="title">{{ __('Interest (:type)', ['type' => (data_get($scheme, 'rate_type') == IRType::PERCENT) ? 'P' : 'F']) }}</span>--}}
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="plan-sum">
                                <div class="amount">{{ data_get($scheme, 'term') }}</div>
                                <span class="title">{{ __(':unit (Term)', ['unit' => ucfirst(data_get($scheme, 'term_type'))]) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-inner">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="plan-info">
                                <span class="title">{{ __('Daily Draw Down') }}</span>
                                <span class="info">{{data_get($scheme, 'daily_drawdown_limit')}} </span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="plan-info">
                                <span class="title">{{ __('Max Draw Down') }}</span>
                                <span class="info">{{data_get($scheme, 'max_drawdown_limit')}}</span>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="plan-info">
                                <span class="title">{{ __('User Profit Share') }}</span>
                                <span class="info">{{ data_get($scheme, 'profit_share_user') }}%</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="plan-info">
                                <span class="title">{{ __('Admin Profit Share') }}</span>
                                <span class="info">{{data_get($scheme, 'profit_share_admin') }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif
</div>
