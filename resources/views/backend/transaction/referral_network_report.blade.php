@extends('backend.layouts.app')
@section('title')
    {{ __('Referral Network Report') }}
@endsection
@section('content')
    <div class="pageTitle flex flex-col justify-between sm:flex-row gap-3 mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        <form id="searchForm">
            @csrf
            <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                <div class="flex-1 input-area">
                    <div class="relative">
                        <input type="text" name="email" id="email" class="form-control h-9 !pr-9" placeholder="Search with email">
                        <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center">
                            <iconify-icon icon="heroicons-solid:search"></iconify-icon>
                        </button>
                    </div>
                </div>
                <div class="flex-1 input-area">
                    <select id="rangeSelect" class="form-control h-9">
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
                        <input type="date" name="created_at" id="created_at" class="form-control h-9 !pr-9" data-mode="range" placeholder="Created At">
                        <button id="clearBtn" type="button" class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center">
                            <iconify-icon icon="mdi:window-close"></iconify-icon>
                        </button>
                    </div>
                    <span class="text-xs font-light dark:text-slate-200">
                        {{ __('Double click for a single date') }}
                    </span>
                </div>
                <div class="input-area relative">
                    <button type="submit" id="filter" class="btn btn-white btn-sm inline-flex items-center justify-center min-w-max" style="min-height: 34px;">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                        {{ __('Apply Filter') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div id="empty__container" class="card relative h-full" style="height: calc(100vh - var(--title-height) - var(--header-height) - var(--footer-height) - 73px)">
        <div class="flex flex-col items-center justify-center h-full p-6">
            <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M26 19.875V30.9167" stroke="rgba(220 0 0)" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="rgba(220 0 0)" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M25.988 37.5417H26.0075" stroke="rgba(220 0 0)" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            <p class="text-lg text-center text-slate-600 dark:text-slate-100 my-3">
                {{ __('Search by email to view the referral network and their payments.') }}
            </p>
        </div>
        <div class="request_loader absolute text-center hidden" style="top: calc(50% - 27px); left: calc(50% - 27px);">
            <iconify-icon class="spining-icon text-5xl dark:text-slate-100" icon="lucide:loader"></iconify-icon>
        </div>
    </div>
    <div id="data__container" class="hidden">
        <div id="network_totals_container" class="grid xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 place-content-center mb-6"></div>
        <div class="card mb-6">
            <div class="card-body relative px-6">
                <div class="overflow-x-auto -mx-6 dashcode-data-table">
                    <span class=" col-span-8  hidden"></span>
                    <span class="  col-span-4 hidden"></span>
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden ">
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="network-report-dataTable">
                                <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('User') }}</th>
                                    <th scope="col" class="table-th">{{ __('Level') }}</th>
                                    <th scope="col" class="table-th">{{ __('Total Incoming') }}</th>
                                    <th scope="col" class="table-th">{{ __('Total Outgoing') }}</th>
                                    <th scope="col" class="table-th">{{ __('Total IB Bonus') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div id="processingIndicator" class="hidden text-center">
                    {{-- <img src="{{ asset('global/images/loading.gif') }}" class="inline-block h-20" alt="Loader"> --}}
                    <iconify-icon class="spining-icon text-5xl dark:text-slate-100" icon="lucide:loader"></iconify-icon>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-between sm:flex-row gap-3 mb-6">
            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                {{ __('Referral Network Tree') }}
            </h4>
            <div class="outline-buttons">
                <div class="groupButtons">
                    <button type="button" class="btn btn-outline-dark btn-sm inline-flex items-center justify-center changeTree__btn active" data-target="vertical" style="min-width: fit-content;">
                        <iconify-icon class="text-lg" icon="iconoir:network-reverse"></iconify-icon>
                    </button>
                    <button type="button" class="btn btn-outline-dark btn-sm inline-flex items-center justify-center changeTree__btn" data-target="horizontal" style="min-width: fit-content;">
                        <iconify-icon class="text-lg" icon="iconoir:network-right"></iconify-icon>
                    </button>
                </div>
            </div>
        </div>
        <div class="card">
            <div id="tree__container" class="card-body p-6">

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>

        const table = $('#network-report-dataTable')
        .on('processing.dt', function (e, settings, processing) {
            $('#processingIndicator').css('display', processing ? 'block' : 'none');
        }).DataTable({
            dom: "<'grid grid-cols-12 gap-5 px-6 mt-6'<'col-span-4'l><'col-span-8 flex justify-end'f><'#pagination.flex items-center'>><'min-w-full't><'flex justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'ip>",
            paging: true,
            ordering: true,
            info: true,
            searching: true,
            lengthChange: true,
            lengthMenu: [10, 25, 50, 100],
            language: {
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    previous: "<iconify-icon icon=\"ic:round-keyboard-arrow-left\"></iconify-icon>",
                    next: "<iconify-icon icon=\"ic:round-keyboard-arrow-right\"></iconify-icon>"
                },
                search: "Search:"
            },
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: "{{ route('admin.referral-network.report') }}",
                data: function (d) {
                    const emailInput = $('#email').val();
                    const urlParams = new URLSearchParams(window.location.search);
                    const emailParam = urlParams.get('email');

                    d.email = emailInput || emailParam;
                    d.created_at = $('#created_at').val();
                },
                dataSrc: function (json) {
                    $('#tree__container').html(
                        json.tree_html || '<p class="text-sm text-center text-slate-600 dark:text-slate-100">No referral tree found.</p>'
                    );

                    if (json && json.network_totals) {
                        renderNetworkTotals(json.network_totals);

                        tippy(".shift-Away", {
                            placement: "top",
                            animation: "shift-away"
                        });
                    }

                    const hasData = json.data && json.data.length > 0;

                    $('#empty__container').toggleClass('hidden', hasData);
                    $('#data__container').toggleClass('hidden', !hasData);
                    $('.request_loader').addClass('hidden');

                    if (!hasData) {
                        $('#empty__container p').text(json.message || 'No referral data available.');
                    }

                    initVerticalTree();
                    initHorizontalTree();

                    return json.data;
                }
            },
            columns: [
                { data: 'user', name: 'user' },
                { data: 'level', name: 'level' },
                { data: 'incoming', name: 'incoming' },
                { data: 'outgoing', name: 'outgoing' },
                { data: 'ib_bonus', name: 'ib_bonus' },
                { data: 'action', name: 'action' },
            ]
        });

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

        // Toggle child row
        $('#network-report-dataTable tbody').on('click', 'button.toggle-details', function () {
            const tr = $(this).closest('tr');
            const row = table.row(tr);

            if (row.child.isShown()) {
                row.child.hide();
                $(this).text('+');
            } else {
                row.child(row.data().details).show();
                $(this).text('–');
            }
        });

        function renderNetworkTotals(totals) {
            let html = '';
            totals.forEach(row => {
                html += `<div class="card rounded p-4">
                    <div class="text-slate-600 dark:text-slate-400 text-sm mb-1 font-medium">
                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="${row.desc}">
                            ${row.type}
                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px] ml-1"></iconify-icon>
                        </span>
                    </div>
                    <div class="text-slate-900 dark:text-white text-lg font-medium">
                        ${row.total}
                    </div>
                    <ul class="min-w-[184px] mt-4 flex justify-between flex-wrap items-center text-center gap-4">
                        <li class="flex-1 flex items-center text-xs text-slate-600 dark:text-slate-300 gap-1">
                            <span class="bg-success-500 ring-success-500 inline-flex h-[6px] w-[6px] bg-success-500 ring-opacity-25 rounded-full ring-4"></span>
                            <span class="ml-2">Completed:</span>
                            <span>${row.success}</span>
                        </li>
                        <li class="flex-1 flex items-center text-xs text-slate-600 dark:text-slate-300 gap-1">
                            <span class="bg-warning-500 ring-warning-500 inline-flex h-[6px] w-[6px] bg-warning-500 ring-opacity-25 rounded-full ring-4"></span>
                            <span class="ml-2">Pending:</span>
                            <span>${row.pending}</span>
                        </li>
                        <li class="flex-1 flex items-center text-xs text-slate-600 dark:text-slate-300 gap-1">
                            <span class="bg-danger-500 ring-danger-500 inline-flex h-[6px] w-[6px] bg-danger-500 ring-opacity-25 rounded-full ring-4"></span>
                            <span class="ml-2">Rejected:</span>
                            <span>${row.rejected}</span>
                        </li>
                    </ul>
                </div>`;
            });

            $('#network_totals_container').html(html);
        }

        $('#searchForm').on('submit', function (e) {
            e.preventDefault();
            $('.request_loader').removeClass('hidden');
            table.ajax.reload();
        });

        $(document).ready(function () {
            const urlParams = new URLSearchParams(window.location.search);
            const emailParam = urlParams.get('email');

            if (emailParam) {
                $('#email').val(emailParam);
                $('.request_loader').removeClass('hidden');
                table.ajax.reload();
            }
        });

        $('.changeTree__btn').on('click', function () {
            const target = $(this).data('target');

            $('.changeTree__btn').removeClass('active');
            $(this).addClass('active');

            // Show selected tree
            $('.tree-view-block').addClass('hidden');
            $(`.${target}-tree`).removeClass('hidden');
        });

        function initVerticalTree() {
            // Hide all child containers and their .person blocks on load
            $('.hv-item-children').each(function () {
                $(this).hide();
                $(this).find('.person').hide();
                $(this).siblings('.hv-item-parent').addClass('hide-line');
                // Find and hide the level-summary within the same hv-item parent container
                $(this).closest('.hv-item').find('.level-summary').hide();
            });

            // Add toggle button to each parent with children
            $('.hv-item').each(function () {
                var $children = $(this).children('.hv-item-children');
                var $parent = $(this).children('.hv-item-parent');

                if ($children.length) {
                    var $btn = $(`
                            <button class="h-5 w-5 btn-primary rounded-full inline-flex items-center justify-center mx-auto mt-1 toggle-btn">
                                <iconify-icon icon="lucide:plus"></iconify-icon>
                            </button>
                        `);

                    $parent.find('.person').append($btn);

                    // Toggle logic
                    $btn.on('click', function () {
                        var isVisible = $children.is(':visible');
                        $children.toggle();
                        $children.find('.person').toggle(!isVisible);
                        $parent.toggleClass('hide-line', isVisible);
                        // Toggle the level-summary visibility
                        $parent.find('.level-summary').toggle(!isVisible);

                        // Change the icon accordingly
                        var $icon = $(this).find('iconify-icon');
                        $icon.attr('icon', isVisible ? 'lucide:plus' : 'lucide:minus');
                    });
                }
            });
        }

        function initHorizontalTree() {
            $('.treeview__level').each(function () {
                const $level = $(this);
                const $nextUl = $level.next('ul');
                $level.find('.level-summary').hide();

                if ($nextUl.length) {
                    $nextUl.hide();

                    // Avoid duplicate buttons
                    if (!$level.find('.horizontal-toggle-btn').length) {
                        const $toggleBtn = $(`
                                <button class="h-5 w-5 btn-primary rounded inline-flex items-center justify-center horizontal-toggle-btn">
                                    <iconify-icon icon="lucide:plus"></iconify-icon>
                                </button>
                            `);
                        $level.find('.text-start').append($toggleBtn);

                        $toggleBtn.on('click', function () {
                            const isVisible = $nextUl.is(':visible');
                            $nextUl.slideToggle(200);
                            $level.find('.level-summary').toggle(!isVisible);

                            var $icon = $(this).find('iconify-icon');
                            $icon.attr('icon', isVisible ? 'lucide:plus' : 'lucide:minus');
                        });
                    }
                }
            });
        }
    </script>
@endsection
