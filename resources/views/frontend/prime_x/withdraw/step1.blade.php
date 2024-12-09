@extends('frontend::layouts.user')
@section('title')
    {{ __('My Wallet') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-3">
        <h4 class="text-xl text-slate-600 dark:text-slate-300">{{ __('Select Payout') }}</h4>
    </div>

    <div class="card mb-6">
      <div class="card-body hidden md:block p-3">
          <div class="progress-steps md:flex justify-between items-center gap-5">
              <div class="single-step current">
                  <div class="progress_bar mb-5"></div>
                  <div class="">
                      <div class="text-sm text-slate-600 dark:text-slate-300 mb-2">{{ __('Step - 1') }}</div>
                      <h4 class="leading-none text-xl text-dark dark:text-white">{{ __('Select Payout') }}</h4>
                  </div>
              </div>
              <div class="single-step">
                  <div class="progress_bar mb-5"></div>
                  <div class="">
                      <div class="text-sm text-slate-600 dark:text-slate-300 mb-2">{{ __('Step - 2') }}</div>
                      <h4 class="leading-none text-xl text-dark dark:text-white">{{ __('Withdraw Amount') }}</h4>
                  </div>
              </div>
              <div class="single-step">
                  <div class="progress_bar mb-5"></div>
                  <div class="">
                      <div class="text-sm text-slate-600 dark:text-slate-300 mb-2">{{ __('Step - 3') }}</div>
                      <h4 class="leading-none text-xl text-dark dark:text-white">{{ __('Success') }}</h4>
                  </div>
              </div>
          </div>
      </div>
    </div>

    <div class="grid md:grid-cols-2 col-span-1 gap-5 mb-6">
        <div class="card h-full p-6 mb-6">
            <div class="card-body">
                <div class="flex flex-wrap justify-between items-center mb-5">
                    <div class="space-x-3">
                        <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">
                            {{ $payout_wallet->title }}
                        </span>
                    </div>
                </div>
                <div class="mb-5">
                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                       {{ $payout_wallet->unique_id }}
                    </div>
                    <div class="text-slate-900 dark:text-white text-xl font-medium">
                        {{ $payout_wallet->available_balance }} {{$currency}}
                    </div>
                </div>
                <div class="flex space-x-2 items-center">
                    <a href="{{route('user.withdraw.step2')}}" class="btn btn-sm btn-outline-dark inline-flex items-center justify-center">
                        <span class="flex items-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="mingcute:refund-dollar-line"></iconify-icon>
                            <span>{{ __('Withdraw') }}</span>
                        </span>
                    </a>

                    <a href="#"  data-bs-toggle="modal" data-bs-target="#payoutRequest" class="btn btn-sm btn-outline-dark inline-flex items-center justify-center">
                        <span class="flex items-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                            <span>{{ __('Create Payout Request') }}</span>
                        </span>
                    </a>    
                </div>
            </div>
        </div>
        @include('frontend.prime_x.withdraw.include.__payout_request')
        <div class="card h-full p-6 mb-6">
            <div class="card-body">
                <div class="flex flex-wrap justify-between items-center mb-5">
                    <div class="space-x-3">
                        <span class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 capitalize">
                            {{ $affiliate_wallet->title }}
                        </span>
                    </div>
                </div>
                <div class="mb-5">
                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{ $affiliate_wallet->unique_id }}

                    </div>
                    <div class="text-slate-900 dark:text-white text-xl font-medium">
                        {{ $affiliate_wallet->available_balance }} {{$currency}}

                    </div>
                </div>
                <div class="flex space-x-2 items-center">
                    <a href="{{route('user.withdraw.step2')}}" class="btn btn-sm btn-outline-dark inline-flex items-center justify-center">
                        <span class="flex items-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="mingcute:refund-dollar-line"></iconify-icon>
                            <span>{{ __('Withdraw') }}</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
