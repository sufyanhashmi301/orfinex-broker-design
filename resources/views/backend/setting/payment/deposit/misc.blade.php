@extends('backend.setting.payment.deposit.index')
@section('title')
    {{ __('Misc Settings') }}
@endsection
@section('page-title')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
@endsection
@section('deposit-content')
    <div class="max-w-3xl mx-auto">
        <div class="card">
            <div class="card-body p-6">
                <form action="" method="post">
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Deposit Request Limit') }}</label>
                        <input type="text" name="" class="form-control" placeholder="5">
                    </div>
                    <div class="input-area text-right mt-10">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            {{ __('Save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
