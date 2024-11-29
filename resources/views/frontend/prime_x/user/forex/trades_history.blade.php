@extends('frontend::layouts.user')
@section('title')
    {{ __('Orders History') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            @yield('title')
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <div class="input-area relative">
                <select id="accountLogin" class="form-control">
                    <option value="">{{ __('Select Forex Account') }}</option>
                    @foreach($realForexAccounts as $account)
                        <option value="{{ $account->login }}">{{ $account->account_name.' #'.$account->login }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-area relative">
                <select id="tradeStatus" class="form-control">
                    <option value="">{{ __('All Trades') }}</option>
                    <option value="open">{{ __('Open Trades') }}</option>
                    <option value="close">{{ __('Close Trades') }}</option>
                </select>
            </div>
            <div class="input-area">
                <div class="relative">
                    <input type="text" id="tradeDate" class="form-control" placeholder="Select Date" style="max-height: 37px;" data-mode="range" readonly>
                    <button id="dateClear" class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center">
                        <iconify-icon icon="lucide:x"></iconify-icon>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card" id="trades-table-body">
        <div class="basicTable_wrapper items-center justify-center" id="card-placeholder">
            <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            <p class="text-lg text-center text-slate-600 dark:text-slate-100 my-3">
                {{ __(' Kindly select the account to view the orders') }}
            </p>
        </div>
    </div>
@endsection
@section('script')
    <script !src="">
        $(document).ready(function(){
            var flatpickr = $("#tradeDate").flatpickr({
                defaultDate: false,
            });

            $('body').on('click', '#dateClear', function (e){
                flatpickr.clear();
            })

        });

        $('#accountLogin, #tradeDate, #tradeStatus').on('change', function() {
            const login = $('#accountLogin').val();
            const date = $('#tradeDate').val();
            const status = $('#tradeStatus').val();

            $.ajax({
                url: '{{ route("user.forex.getOrders") }}',
                type: 'GET',
                data: {
                    account_login: login,
                    trade_date: date,
                    trade_status: status,
                },
                success: function (response) {
                    $('#card-placeholder').hide();
                    $('#trades-table-body').html(response);
                }
            })
        });

        // Handler for pagination links inside the modal
        $('body').on('click', '#trades-table-body nav a', function(event) {
            event.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $('#trades-table-body').html(response);
                }
            });
        });

    </script>
@endsection
