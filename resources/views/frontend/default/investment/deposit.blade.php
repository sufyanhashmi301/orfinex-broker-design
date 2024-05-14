@extends('frontend::layouts.user')

@section('title', __('Deposit'))

@section('content')
    <div class="nk-content-body">
        <div class="nk-block-head">
            <div class="nk-block-between-sm g-4">
                <div class="nk-block-head-content">
                    <h2 class="nk-block-title fw-normal">{{ __('Deposit') }}</h2>
                    <div class="nk-block-des">
                        <p>{{ __('Select from payment options below.') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head-sm">
                <div class="nk-block-head-content">
                    <h6 class="overline-title">Recent methods</h6>
                </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <a href="" class="card card-bordered mb-4" data-toggle="modal" data-target="#modalDeposit">
                  <div class="card-inner">
                    <div class="invest-ov pt-0">
                    	<div class="invest-ov-details justify-content-between">
                    		<div class="invest-ov-info">
                          <div class="d-flex">
                            <span class="bg-btc-dim icon-circle icon ni ni-sign-btc"></span>
                            <div class="ml-2">
                        			<div class="amount lead-text">Bitcoin (BTC)</div>
                        			<div class="title">10 - 10,000 USD</div>
                            </div>
                          </div>
                    		</div>
                    		<div class="invest-ov-stats text-right">
                    			<p class="title mb-1 mt-0">
                            Up to 72h
                            <em class="icon ni ni-spark-fill text-primary ml-1"></em>
                          </p>
                    			<p class="title mb-1 mt-0">
                            Network fee
                            <em class="icon ni ni-circle-fill text-primary ml-1"></em>
                          </p>
                    		</div>
                    	</div>
                    </div>
                  </div>
                </a>
              </div>
            </div>
            <div class="nk-block-head-sm">
                <div class="nk-block-head-content">
                    <h6 class="overline-title">Electronic money</h6>
                </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <a href="" class="card card-bordered mb-4">
                  <div class="card-inner">
                    <div class="invest-ov pt-0">
                    	<div class="invest-ov-details justify-content-between">
                    		<div class="invest-ov-info">
                          <div class="d-flex">
                            <span class="bg-eth-dim icon-circle icon ni ni-sign-eth"></span>
                            <div class="ml-2">
                        			<div class="amount lead-text">Ethereum</div>
                        			<div class="title">10 - 10,000 USD</div>
                            </div>
                          </div>
                    		</div>
                    		<div class="invest-ov-stats text-right">
                    			<p class="title mb-1 mt-0">
                            Up to 72h
                            <em class="icon ni ni-spark-fill text-primary ml-1"></em>
                          </p>
                    			<p class="title mb-1 mt-0">
                            No commission
                            <em class="icon ni ni-circle-fill text-primary ml-1"></em>
                          </p>
                    		</div>
                    	</div>
                    </div>
                  </div>
                </a>
              </div>
              <div class="col-md-6">
                <a href="" class="card card-bordered mb-4">
                  <div class="card-inner">
                    <div class="invest-ov pt-0">
                    	<div class="invest-ov-details justify-content-between">
                    		<div class="invest-ov-info">
                          <div class="d-flex">
                            <span class="bg-success-dim icon-circle icon ni ni-tether"></span>
                            <div class="ml-2">
                        			<div class="amount lead-text">Tether (USDT OMNI)</div>
                        			<div class="title">10 - 10,000 USD</div>
                            </div>
                          </div>
                    		</div>
                    		<div class="invest-ov-stats text-right">
                    			<p class="title mb-1 mt-0">
                            Up to 72h
                            <em class="icon ni ni-spark-fill text-primary ml-1"></em>
                          </p>
                    			<p class="title mb-1 mt-0">
                            No commission
                            <em class="icon ni ni-circle-fill text-primary ml-1"></em>
                          </p>
                    		</div>
                    	</div>
                    </div>
                  </div>
                </a>
              </div>
            </div>
        </div>
    </div>
@endsection

@push('modal')
    @include('user.forex-trading.modal-deposit')
@endpush
