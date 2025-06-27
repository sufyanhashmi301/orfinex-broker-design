@extends('backend.layouts.app')
@section('title')
    {{ __('Transactions Report') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Transactions Report') }}
        </h4>
    </div>

    <div class="innerMenu card p-6 mb-5">
        <form id="filter-form">
            @csrf
            <div class="flex flex-col sm:flex-row justify-between flex-wrap sm:items-stretch gap-3">
                <div class="flex-1 w-full flex flex-col sm:flex-row sm:gap-3 gap-2">
                    <div class="flex-1 input-area">
                        <select name="email" id="email" class="form-control w-full"></select>
                    </div>
                    <div class="flex-1 input-area">
                        <select id="rangeSelect" class="form-control h-full">
                            <option value="">{{ __('-- Select Range --') }}</option>
                            <option value="today">{{ __('Today') }}</option>
                            <option value="yesterday">{{ __('Yesterday') }}</option>
                            <option value="last30">{{ __('Last 30 Days') }}</option>
                            <option value="thisMonth">{{ __('This Month') }}</option>
                            <option value="lastMonth">{{ __('Last Month') }}</option>
                            <option value="ytd">{{ __('Year to Date') }}</option>
                            <option value="lastYear">{{ __('Last Year') }}</option>
                            <option value="custom">{{ __('Custom Range') }}</option>
                        </select>
                    </div>
                    <div class="flex-1 input-area">
                        <div class="relative h-full">
                            <input type="date" name="created_at" id="created_at" class="form-control h-full !pr-12" data-mode="range" placeholder="Created At">
                            <button id="clearBtn" type="button" class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center">
                                <iconify-icon icon="mdi:window-close"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="flex sm:space-x-3 space-x-2 sm:justify-end items-center">
                    <button type="button" id="filter" class="btn btn-sm inline-flex items-center justify-center min-w-max h-full bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                        {{ __('Apply Filter') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="card relative">
        <div id="summary-content">
            @include('backend.transaction.include.__report_table')
        </div>
        <div id="processingIndicator" class="hidden text-center">
            {{-- <img src="{{ asset('global/images/loading.gif') }}" class="inline-block h-20" alt="Loader"> --}}
            <iconify-icon class="spining-icon text-5xl dark:text-slate-100" icon="lucide:loader"></iconify-icon>
        </div>
    </div>
@endsection
@section('script')
    <script !src="">

        const input = document.getElementById("created_at");
        const clearBtn = document.getElementById("clearBtn");
        const rangeSelect = document.getElementById("rangeSelect");

        const fp = flatpickr(input, {
            altInput: false,
            dateFormat: "Y-m-d",
            allowInput: false,
        });

        // Define range presets
        function getDateRanges() {
            const today = new Date();
            const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            const startOfLastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            const endOfLastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
            const startOfYear = new Date(today.getFullYear(), 0, 1);
            const endOfLastYear = new Date(today.getFullYear() - 1, 11, 31);
            const startOfLastYear = new Date(today.getFullYear() - 1, 0, 1);

            return {
                today: [today, today],
                yesterday: [new Date(today.getFullYear(), today.getMonth(), today.getDate() - 1), new Date(today.getFullYear(), today.getMonth(), today.getDate() - 1)],
                last30: [new Date(today.getFullYear(), today.getMonth(), today.getDate() - 29), today],
                thisMonth: [startOfMonth, endOfMonth],
                lastMonth: [startOfLastMonth, endOfLastMonth],
                ytd: [startOfYear, today],
                lastYear: [startOfLastYear, endOfLastYear]
            };
        }

        // Set range on selection
        rangeSelect.addEventListener("change", function () {
            const selected = this.value;
            const ranges = getDateRanges();

            if (selected === 'custom') {
                fp.clear();
                fp.open();
            } else if (ranges[selected]) {
                fp.setDate(ranges[selected], true); // second param triggers `onChange`
            }
        });

        // Clear button logic
        clearBtn.addEventListener("click", () => {
            fp.clear();
        });

        var users = @json($users);
        $('#email').select2({
            minimumInputLength: 1,

            data: users.map(function(user) {
                return {
                    id: user.id,
                    text: user.full_name,
                    email: user.email
                };
            }),

            matcher: function(params, data) {
                if ($.trim(params.term) === '') {
                    return null;
                }

                const term = params.term.toLowerCase();
                const nameMatch = data.text.toLowerCase().includes(term);
                const emailMatch = data.email.toLowerCase().includes(term);

                if (nameMatch || emailMatch) {
                    return data;
                }

                return null;
            },

            templateResult: function(data) {
                if (data.loading) return data.text;
                return $('<span>' + data.text + ' - <small>' + data.email + '</small></span>');
            },

            templateSelection: function(data) {
                return data.text + ' (' + data.email + ')';
            }
        });

        $('#filter').click(function () {
            $('#processingIndicator').removeClass('hidden');
            $.ajax({
                url: '{{ route("admin.transactions.user-summary") }}',
                method: 'POST',
                data: {
                    email: $('#email').val(),
                    created_at: $('#created_at').val(),
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    $('#processingIndicator').addClass('hidden');
                    $('#summary-content').html(response.html);
                    tippy(".shift-Away", {
                        placement: "top",
                        animation: "shift-away"
                    });
                },
                error: function (xhr) {
                    $('#summary-content').html('<p class="text-danger">Error: ' + xhr.responseText + '</p>');
                }
            });
        });

    </script>
@endsection
