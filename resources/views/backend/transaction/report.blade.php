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
            <div class="flex flex-col sm:flex-row sm:items-start justify-between flex-wrap gap-3">
                <div class="flex-1 w-full flex flex-col sm:flex-row sm:gap-3 gap-2">
                    <div class="flex-1 input-area">
                        <select name="user_id" id="userId" class="form-control w-full">
                            @if(request('user_id') && isset($selectedUser))
                                <option value="{{ $selectedUser->id }}" selected>
                                    {{ $selectedUser->full_name }} ({{ $selectedUser->email }})
                                </option>
                            @endif
                        </select>
                    </div>
                    <div class="flex-1 input-area">
                        <select id="rangeSelect" class="form-control">
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
                        <div class="relative">
                            <input type="date" name="created_at" id="created_at" class="form-control" data-mode="range" placeholder="Created At" style="height: 37px;">
                            <button id="clearBtn" type="button" class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center">
                                <iconify-icon icon="mdi:window-close"></iconify-icon>
                            </button>
                        </div>
                        <span class="text-xs font-light dark:text-slate-200">
                            {{ __('Double click for a single date') }}
                        </span>
                    </div>
                </div>
                <div class="flex sm:space-x-3 space-x-2 sm:justify-end">
                    <button type="button" id="filter" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white" style="min-height: 34px;">
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
@section('style')
    <style>
        .select2-container .select2-selection--single {
            height: 37px;
        }
    </style>
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

        $('#userId').select2({
            placeholder: 'Search user by name or email',
            minimumInputLength: 1,
            ajax: {
                url: '{{ route("admin.user.search") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { q: params.term };
                },
                processResults: function (data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            },
            templateResult: function (user) {
                if (user.loading) return user.text;

                const avatar = user.avatar ? `<img src="${user.avatar}" class="w-5 h-5 rounded-full mr-2 inline-block align-middle">` : '';
                return $(`<span>${avatar}${user.text} <small class="text-muted">(${user.email})</small></span>`);
            },
            templateSelection: function (user) {
                if (!user || typeof user !== 'object') return '';

                const name = user.text || '';
                const email = user.email || '';

                return email ? `${name} (${email})` : name;
            }
        });

        $('#filter').click(function () {
            $('#processingIndicator').removeClass('hidden');
            $.ajax({
                url: '{{ route("admin.transactions.user-summary") }}',
                method: 'POST',
                data: {
                    user_id: $('#userId').val(),
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
