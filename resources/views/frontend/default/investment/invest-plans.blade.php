@extends('frontend::layouts.user')
@section('title')
    {{ __('Payments') }}
@endsection
@section('style')
    <style>
        .select2-container .select2-selection--single {
            height: 48px !important;
        }
        .dark .select2-container--default .select2-selection--single {
            border-color: rgb(51 65 85)
        }
    </style>
@endsection
@section('content')
    @php

        use App\Enums\FundedSchemeTypes as FTypes;
        use App\Enums\FundedSchemeSubTypes as FSSTypes;

    @endphp

    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 dark:text-white inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ __('Money well funded') }}
        </h4>
        <div class="">
            <ul class="nav nav-tabs flex items-center space-x-2" id="plans-tabs" role="tablist">
                <li role="presentation">
                    <a href="#challenge-tab-pane" class="nav-link p-0 type active" id="challenge-tab" data-bs-toggle="pill" data-bs-target="#challenge-tab-pane" role="tab" aria-controls="challenge-tab-pane" aria-selected="true">
                        <span class="tab-btn">Challenge Funding</span>
                    </a>
                </li>
                <span class="pricing-tab-switcher"></span>
                <li role="presentation">
                    <a href="#direct-tab-pane" class="nav-link p-0 type" id="direct-tab" data-bs-toggle="pill" data-bs-target="#direct-tab-pane" role="tab" aria-controls="direct-tab-pane" aria-selected="true">
                        <span class="tab-btn">Direct Funding</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="card p-4 shadow rounded-xl mb-3">
        <div class="tab-content" id="plans-tab-content">
            <div class="tab-pane fade show active" id="challenge-tab-pane" role="tabpanel" aria-labelledby="challenge-tab">
                <div class="grid md:grid-cols-2 grid-cols-1">
                    <div class="text-center space-x-3 space-y-3 md:space-y-0">
                        <button class="btn btn-sm btn-outline-dark inline-flex items-center justify-center challenge-btn active" data-challenge="two_step_challenge" id="step-challenge__2">
                            2 Step Challenge
                            {{-- {{fsst2n(\App\Enums\FundedSchemeSubTypes::TWO_STEP_CHALLENGE)}} --}}
                        </button>
                        <button class="btn btn-sm btn-outline-dark inline-flex items-center justify-center challenge-btn" data-challenge="single_step_challenge" id="step-challenge__1">
                            1 Step Challenge
                            {{-- {{fsst2n(\App\Enums\FundedSchemeSubTypes::SINGLE_STEP_CHALLENGE)}} --}}
                        </button>
                    </div>
                    <div class="text-center space-x-3 space-y-3 md:space-y-0" id="phaseButtons" style="">
                        <button class="btn btn-sm btn-outline-dark inline-flex items-center justify-center phase-btn active" data-phase="1">
                            Phase 1
                        </button>
                        <button class="btn btn-sm btn-outline-dark inline-flex items-center justify-center phase-btn" data-phase="2">
                            Phase 2
                        </button>
                    </div>
                </div>
            </div>
    
            <div class="tab-pane fade" id="direct-tab-pane" role="tabpanel" aria-labelledby="direct-tab">
                <div class="grid grid-cols-1">
                    <div class="text-center space-x-3 space-y-3 md:space-y-0">
                        <button class="btn btn-sm btn-outline-dark inline-flex items-center leverage-btn mb-3 md:mb-0 active" data-leverage="{{\App\Enums\FundedSchemeSubTypes::LEVERAGE_1}}">
                            Leverage 1
                            {{-- {{fsst2n(\App\Enums\FundedSchemeSubTypes::LEVERAGE_1)}} --}}
                        </button>
                        <button class="btn btn-sm btn-outline-dark inline-flex items-center leverage-btn mb-3 md:mb-0 ms-2" data-leverage="{{\App\Enums\FundedSchemeSubTypes::LEVERAGE_2}}">
                            Leverage 2
                            {{-- {{fsst2n(\App\Enums\FundedSchemeSubTypes::LEVERAGE_2)}} --}}
                        </button>
                        <button class="btn btn-sm btn-outline-dark inline-flex items-center leverage-btn mb-3 md:mb-0 ms-2" data-leverage="{{\App\Enums\FundedSchemeSubTypes::LEVERAGE_3}}">
                            Leverage 3
                            {{-- {{fsst2n(\App\Enums\FundedSchemeSubTypes::LEVERAGE_3)}} --}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center" id="sppiner-loader" style="display: none">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div id="append-data"></div>
    
    <br>
    <input type="hidden" id="main-type" value="challenge">
    <input type="hidden" id="sub-type" value="two_step_challenge">
    <input type="hidden" id="stage" value="1">
    <input type="hidden" id="show-data-url" value="{{route('user.pricing.show.data')}}">

@endsection

@section('script')
    @include('frontend.default.investment.invest-plans-jquery')

    <script>
        $('body').on('click', '.invest-btn', function () {

            $('.funded-main-type').val($('#main-type').val());
            $('.funded-sub-type').val($('#sub-type').val());
            $('.funded-stage').val($('#stage').val());
            var form = $(this).closest('form');
            // $('#f'+formId).submit()
            form.submit()
            // var url = btn.data('url');
            // submit_form(formData, btn, url);

        });
    </script>
@endsection
