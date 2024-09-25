@extends('backend.layouts.app')
@section('title')
    {{ __('Net Positions - Groups') }}
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
                <label for="group" class="form-label !w-auto min-w-max">{{ __('Select Group:') }}</label>
                <select id="group" class="form-control w-full">
                    <option value="" disabled selected>Select a group</option>
                    <option value="demo\plan1a-100k">{{ __('demo\plan1a-100k') }}</option>
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
                                    <th scope="col" class="table-th">{{ __('Symbol') }}</th>
                                    <th scope="col" class="table-th">{{ __('Buy Volume') }}</th>
                                    <th scope="col" class="table-th">{{ __('Sell Volume') }}</th>
                                    <th scope="col" class="table-th">{{ __('Net Volume') }}</th>
                                    <th scope="col" class="table-th">{{ __('Profit') }}</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                <tr>
                                    <td colspan="5" class="text-center text-sm p-5">Please select group to see positions.</td>
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
                var group = $('#group').val();
                $.ajax({
                    url: '{{ route("admin.netPositions.group") }}',
                    type: 'POST',
                    data: {
                        group: group,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        $('#table-body').empty(); // Clear previous data
                        console.log(data);
                        if (data.success && data.result.length > 0) {
                            $.each(data.result, function(index, position) {
                                $('#table-body').append(
                                    `<tr>
                                        <td class="table-td">${position.symbol}</td>
                                        <td class="table-td">${position.volumeBuyClients}</td>
                                        <td class="table-td">${position.volumeSellClients}</td>
                                        <td class="table-td">${position.volumeNet}</td>
                                        <td class="table-td">${position.profitClients}</td>
                                    </tr>`
                                );
                            });
                        } else {
                            $('#table-body').append('<tr><td colspan="14" class="text-center">No positions found for this group.</td></tr>');
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

