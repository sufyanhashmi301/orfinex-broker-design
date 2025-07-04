@extends('backend.layouts.app')
@section('title')
    {{ __('Referral Network Report') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        <form id="searchForm">
            @csrf
            <div class="flex gap-3 items-center">
                <div class="input-area">
                    <div class="relative">
                        <input type="text" name="email" id="email" class="form-control !pr-9" placeholder="Search with email">
                        <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center">
                            <iconify-icon icon="heroicons-solid:search"></iconify-icon>
                        </button>
                    </div>
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
        <div class="flex flex-col items-center justify-center h-full">
            <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M26 19.875V30.9167" stroke="rgba(220 0 0)" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="rgba(220 0 0)" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M25.988 37.5417H26.0075" stroke="rgba(220 0 0)" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            <p class="text-lg text-center text-slate-600 dark:text-slate-100 my-3">
                {{ __('Search by email to view referral network and transactions.') }}
            </p>
        </div>
        <div class="request_loader absolute text-center hidden" style="top: calc(50% - 27px); left: calc(50% - 27px);">
            <iconify-icon class="spining-icon text-5xl dark:text-slate-100" icon="lucide:loader"></iconify-icon>
        </div>
    </div>
    <div id="data__container" class="grid grid-cols-12 gap-5 hidden">
        <div class="lg:col-span-8 col-span-12">
            <div class="card h-full">
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
                                            <th scope="col" class="table-th">{{ __('Deposit') }}</th>
                                            <th scope="col" class="table-th">{{ __('Withdraw') }}</th>
                                            <th scope="col" class="table-th">{{ __('IB Bonus') }}</th>
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
        </div>
        <div class="lg:col-span-4 col-span-12">
            <div class="card">
                <div id="tree__container" class="card-body p-6" style="max-height: calc(100vh - var(--title-height) - var(--header-height) - var(--footer-height) - 73px); overflow: auto;">

                </div>
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
            dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
            searching: false,
            lengthChange: false,
            info: true,
            language: {
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    previous: "<iconify-icon icon=\"ic:round-keyboard-arrow-left\"></iconify-icon>",
                    next: "<iconify-icon icon=\"ic:round-keyboard-arrow-right\"></iconify-icon>"
                },
            },
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: "{{ route('admin.referral-network.report') }}",
                data: function (d) {
                    d.email = $('#email').val();
                },
                dataSrc: function (json) {
                    $('#tree__container').html(
                        json.tree_html || '<p class="text-sm text-center text-slate-600 dark:text-slate-100">No referral tree found.</p>'
                    );

                    const hasData = json.data && json.data.length > 0;

                    $('#empty__container').toggleClass('hidden', hasData);
                    $('#data__container').toggleClass('hidden', !hasData);
                    $('.request_loader').addClass('hidden');

                    if (!hasData) {
                        $('#empty__container p').text(json.message || 'No referral data available.');
                    }

                    initHorizontalTree();

                    return json.data;
                }
            },
            columns: [
                { data: 'user' },
                { data: 'level' },
                { data: 'deposit' },
                { data: 'withdraw' },
                { data: 'ib_bonus' },
            ]
        });

        $('#searchForm').on('submit', function (e) {
            e.preventDefault();
            $('.request_loader').removeClass('hidden');
            table.ajax.reload();
        });

        initHorizontalTree();
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
