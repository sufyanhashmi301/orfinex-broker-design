@extends('frontend::layouts.user')
@section('title')
    {{ __('Withdraw Now') }}
@endsection
@section('content')
    <div class="mb-5">
        <ul class="m-0 p-0 list-none">
            <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter ">
                <a href="{{route('user.dashboard')}}">
                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                    <iconify-icon icon="heroicons-outline:chevron-right"
                                  class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                </a>
            </li>
            <li class="inline-block relative text-sm text-primary-500 font-Inter ">
                {{ __('Dashboard') }}
                <iconify-icon icon="heroicons-outline:chevron-right"
                              class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ __('Withdraw Money') }}
            </li>
        </ul>
    </div>
    <div class="grid grid-cols-12 gap-5">
        <div class="col-span-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('Withdraw Money') }}</h4>
                    <div>
                        <a href="{{ route('user.withdraw.account.index') }}" class="btn btn-dark">
                            {{ __('Withdraw Account') }}
                        </a>
                    </div>
                </div>
                <div class="card-body p-6">
                    <div class="progress-steps-form">
                        <form action="{{ route('user.withdraw.now') }}" method="post">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5">
                                <div class="input-area relative mb-5">
                                    <label for="exampleFormControlInput1"
                                           class="form-label">{{ __('Account to withdraw:') }}</label>
                                    <div class="input-group select2-lg">
                                        <select id="tradingAccount" name="target_id"
                                                class="select2 form-control !text-lg w-full mt-2 py-2">
                                            <option selected
                                                    class="inline-block font-Inter font-normal text-sm text-slate-600"
                                                    disabled>--{{ __('Select Account') }}--
                                            </option>
                                            @foreach($forexAccounts as $forexAccount)
                                                <option value="{{ $forexAccount->login }}" data-type="forex"
                                                        class="inline-block font-Inter font-normal text-sm text-slate-600">{{ $forexAccount->login }}
                                                    - {{ $forexAccount->account_name }} ({{ $forexAccount->equity }} {{$currency}})</option>
                                            @endforeach
                                            @if(auth()->user()->ib_status == \App\Enums\IBStatus::APPROVED && isset(auth()->user()->ib_login))
                                                <option value="{{ auth()->user()->ib_login }}" data-type="ib-account"
                                                        class="inline-block font-Inter font-normal text-sm text-slate-600">{{ auth()->user()->ib_login }}
                                                    - {{ __('IB') }} ({{ auth()->user()->ib_balance }} {{$currency}})</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5">
                                <div class="input-area relative">
                                    <label for="exampleFormControlInput1"
                                           class="form-label">{{ __('Withdraw Account') }}</label>
                                    <div class="input-group select2-lg">
                                        <select name="withdraw_account" id="withdrawAccountId"
                                                class="select2 form-control !text-lg w-full mt-2 py-2">
                                            <option selected
                                                    class="inline-block font-Inter font-normal text-sm text-slate-600"
                                                    disabled>{{ __('Withdraw Method') }}</option>
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->id }}"
                                                        class="inline-block font-Inter font-normal text-sm text-slate-600">{{ $account->method_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div
                                        class="font-Inter text-xs text-red-500 pt-2 inline-block processing-time"></div>
                                </div>
                                <div class="input-area relative">
                                    <label for="exampleFormControlInput1" class="form-label">{{ __('Amount') }}</label>
                                    <div class="relative">
                                        <input type="text" name="amount"
                                               oninput="this.value = validateDouble(this.value)"
                                               class="form-control !text-lg withdrawAmount" placeholder="Enter Amount">
                                    </div>
                                    <div
                                        class="font-Inter text-xs text-red-500 pt-2 inline-block withdrawAmountRange"></div>
                                </div>
                            </div>
                            <div class="transaction-list mt-5">
                                <div class="user-panel-title">
                                    <h3 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">{{ __('Withdraw Details') }}</h3>
                                </div>
                                <div
                                    class="max-w-[1005px] mx-auto shadow-base dark:shadow-none my-8 rounded-md overflow-x-auto">
                                    <table
                                        class="table w-full border-collapse table-fixed dark:border-slate-700 dark:border">
                                        <tbody class="selectDetailsTbody">
                                        <tr class="border-b border-slate-100 dark:border-slate-700 detailsCol">
                                            <td class="text-slate-900 dark:text-slate-300 text-sm font-normal ltr:text-left ltr:last:text-right rtl:text-right rtl:last:text-left px-6 py-4">
                                                <strong>{{ __('Withdraw Amount') }}</strong>
                                            </td>
                                            <td><span class="withdrawAmount"></span> {{$currency}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="buttons text-right mt-4">
                                <button type="submit" class="btn inline-flex justify-center btn-dark">
                                    {{ __('Withdraw Money') }}<i class="anticon anticon-double-right"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        "use strict";
        var info = [];
        $("#withdrawAccountId").on('change', function (e) {
            e.preventDefault();

            $('.selectDetailsTbody').children().not(':first', ':second').remove();
            var accountId = $(this).val()
            var amount = $('.withdrawAmount').val();

            if (!isNaN(accountId)) {
                var url = '{{ route("user.withdraw.details",['accountId' => ':accountId', 'amount' => ':amount']) }}';
                url = url.replace(':accountId', accountId,);
                url = url.replace(':amount', amount);

                $.get(url, function (data) {
                    $(data.html).insertAfter(".detailsCol");
                    info = data.info;
                    $('.withdrawAmountRange').text(info.range)
                    $('.processing-time').text(info.processing_time)
                })
            }


        })

        $(".withdrawAmount").on('keyup', function (e) {
            "use strict"
            e.preventDefault();
            var amount = $(this).val()
            var charge = info.charge_type === 'percentage' ? calPercentage(amount, info.charge) : info.charge
            $('.withdrawAmount').text(amount)
            $('.withdrawFee').text(charge)
            $('.processing-time').text(info.processing_time)
            $('.withdrawAmountRange').text(info.range)
            $('.pay-amount').text(((amount * info.rate) - (charge * info.rate)).toFixed(2) + ' ' + info.pay_currency)
        })
    </script>
@endsection
