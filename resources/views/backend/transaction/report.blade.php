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
            <div class="flex flex-col sm:flex-row justify-between flex-wrap sm:items-center gap-3">
                <div class="flex-1 w-full flex flex-col sm:flex-row sm:gap-3 gap-2">
                    <div class="flex-1 input-area">
                        <input type="text" name="email" id="email" class="form-control h-full" placeholder="Search User By Email">
                    </div>

                    <div class="flex-1 input-area">
                        <div class="relative">
                            <input type="date" name="created_at" id="created_at" class="form-control h-full !pr-12" data-mode="range" placeholder="Created At">
                            <button id="clearBtn" type="button" class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center">
                                <iconify-icon icon="mdi:window-close"></iconify-icon>
                            </button>
                        </div>
                    </div>
                    <div class="input-area relative">
                        <button type="button" id="filter" class="btn btn-sm inline-flex items-center justify-center min-w-max h-full bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                            <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                            {{ __('Apply Filter') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-body basicTable_wrapper flex-1 p-6" id="summary-content">
            <div class="flex-1 flex flex-col justify-center items-center gap-3">
                <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M26 19.875V30.9167" stroke="rgba(220 0 0)" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="rgba(220 0 0)" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M25.988 37.5417H26.0075" stroke="rgba(220 0 0)" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <p class="text-lg text-center text-slate-600 dark:text-slate-100 mb-3">
                    {{ __('Apply Filters to View Report') }}
                </p>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script !src="">

        const input = document.getElementById("created_at");
        const clearBtn = document.getElementById("clearBtn");

        const fp = flatpickr(input, {
            altInput: false,
            dateFormat: "Y-m-d",
            allowInput: false,
        });

        // Clear button logic
        clearBtn.addEventListener("click", () => {
            fp.clear();
        });

        $('#filter').click(function () {
            $.get("{{ route('admin.transactions.user-summary') }}", {
                email: $('#email').val(),
                created_at: $('#created_at').val()
            }, function (res) {
                $('#summary-content').html(res.html);
                tippy(".shift-Away", {
                    placement: "top",
                    animation: "shift-away"
                });
            });
        });
    </script>
@endsection
