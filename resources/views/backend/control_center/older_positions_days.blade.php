@extends('backend.layouts.app')
@section('title')
    {{ __('Older Positions - Days') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="innerMenu card p-5 mb-3">
        <div class="flex items-center gap-3">
            <div class="max-w-xl w-full input-area relative flex items-center gap-5">
                <label for="days" class="form-label !w-auto min-w-max">{{ __('Select Days:') }}</label>
                <select id="days" class="form-control w-full">
                    <option value="" disabled selected>Select days</option>
                    <option value="7">{{ __('7 days') }}</option>
                    <option value="15">{{ __('15 days') }}</option>
                    <option value="30">{{ __('30 days') }}</option>
                </select>
            </div>
            <button id="fetch-positions" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                {{ __('Fetch Positions') }}
            </button>
        </div>
    </div>
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Login') }}</th>
                                    <th scope="col" class="table-th">{{ __('Symbol') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                    <th scope="col" class="table-th">{{ __('Position') }}</th>
                                    <th scope="col" class="table-th">{{ __('Open Price') }}</th>
                                    <th scope="col" class="table-th">{{ __('Current Price') }}</th>
                                    <th scope="col" class="table-th">{{ __('SL') }}</th>
                                    <th scope="col" class="table-th">{{ __('TP') }}</th>
                                    <th scope="col" class="table-th">{{ __('Volume') }}</th>
                                    <th scope="col" class="table-th">{{ __('Profit') }}</th>
                                    <th scope="col" class="table-th">{{ __('Profit Rate') }}</th>
                                    <th scope="col" class="table-th">{{ __('Margin Rate') }}</th>
                                    <th scope="col" class="table-th">{{ __('Reason') }}</th>
                                    <th scope="col" class="table-th">{{ __('Created') }}</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                <tr>
                                    <td colspan="14" class="text-center text-sm p-5">Please select days to see positions.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#fetch-positions').click(function() {
                var days = $('#days').val();
                $.ajax({
                    url: '{{ route("admin.positions.days") }}',
                    type: 'POST',
                    data: {
                        days: days,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        $('#table-body').empty(); // Clear previous data
                        console.log(data);
                        if (data.success && data.result.length > 0) {
                            $.each(data.result, function(index, position) {
                                $('#table-body').append(
                                    `<tr>
                                        <td class="table-td">${position.login}</td>
                                        <td class="table-td">${position.symbol}</td>
                                        <td class="table-td">${position.action}</td>
                                        <td class="table-td">${position.position}</td>
                                        <td class="table-td">${position.priceOpen}</td>
                                        <td class="table-td">${position.priceCurrent}</td>
                                        <td class="table-td">${position.priceSL}</td>
                                        <td class="table-td">${position.priceTP}</td>
                                        <td class="table-td">${position.volume}</td>
                                        <td class="table-td">${position.profit}</td>
                                        <td class="table-td">${position.rateProfit}</td>
                                        <td class="table-td">${position.rateMargin}</td>
                                        <td class="table-td">${position.reason}</td>
                                        <td class="table-td">${position.timeCreate}</td>
                                    </tr>`
                                );
                            });
                        } else {
                            $('#table-body').append('<tr><td colspan="14" class="text-center">No positions found.</td></tr>');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr);
                    }
                });
            });
        });
    </script>
@endsection
