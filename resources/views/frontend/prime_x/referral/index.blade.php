@extends('frontend::layouts.partner')
@section('title')
    {{ __('Partner Dashboard') }}
@endsection
@section('content')

    {{--    @if (request()->routeIs('user.referral')) --}}
    {{--        @if (auth()->user()->ib_status == \App\Enums\IBStatus::APPROVED && auth()->user()->ibQuestionAnswers) --}}
    {{--            @include('frontend::referral.include.__dashboard') --}}
    {{--            @include('frontend::referral.modal.__qr_code') --}}
    {{--        @else --}}
    @if (auth()->user()->ib_status == \App\Enums\IBStatus::PENDING)
        <div class="card basicTable_wrapper items-center justify-center">
            <div class="card-body p-6">
                <div class="max-w-2xl progress-steps-form">
                    <div class="transaction-status text-center">
                        <div
                            class="icon h-20 w-20 bg-warning text-warning bg-opacity-30 rounded-full flex flex-col items-center justify-center mx-auto">
                            <iconify-icon icon="icomoon-free:hour-glass" class="text-4xl"></iconify-icon>
                        </div>
                        <h2 class="text-3xl dark:text-white my-5">{{ __('Partner Request Pending') }}</h2>
                        <p class="text-sm mb-3 dark:text-white">
                            {{ __("Your partnership request is under review and we'll confirm with you shortly. Stay tuned!") }}
                        </p>
                        <div class="flex flex-wrap items-center justify-center gap-3">
                            <a href="{{ setting('IB_partner_agreement_link', 'document_links', false) }}" target="_blank"
                                class="btn btn-light inline-flex items-center justify-center mr-2">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="carbon:document"></iconify-icon>
                                <span>{{ __('Read Partner Agreement') }}</span>
                            </a>
                            <a href="{{ setting('trust_pilot_review_link', 'platform_links', 'javascript:void(0);') }}"
                                target="_blank" class="btn btn-dark inline-flex items-center justify-center">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2"
                                    icon="simple-icons:trustpilot"></iconify-icon>
                                <span>{{ __('Read Our Reviews on Trustpilot') }}</span>
                            </a>
                        </div>
                        <div class="mt-5">
                            <p class="text-sm dark:text-slate-300">
                                {{ __('If you face any issue, please visit our') }}
                                <a href="{{ setting('customer_support_link', 'platform_links', 'javascript:void(0);') }}"
                                    class="btn-link">{{ __('Customer Support') }}</a>
                                {{ __('or Email us at') }}
                                <a href="mailto:{{ setting('support_email', 'global') }}"
                                    class="btn-link">{{ setting('support_email', 'global') }}</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif(auth()->user()->ib_status == \App\Enums\IBStatus::UNPROCESSED && !isset(auth()->user()->ref_id))
        <div class="card">
            <div class="p-6">
                <h4 class="card-title mb-2">
                    {{ __('Become a Introducing Broker') }}
                </h4>
                <p class="dark:text-white">
                    {{ __('Make sure your details are correct-after applying, we will reach you to discuss your experience. We will also answer all the questions you might have.') }}
                </p>
            </div>
            <div class="card-body px-6 pb-6">
                <form action="{{ route('user.ib-program.store') }}" method="POST" id="ib-from-create" class="space-y-4">
                    @csrf

                    @foreach ($ibQuestions as $qIndex => $ibQuestion)
                        @foreach (json_decode($ibQuestion->fields) as $field)
                            <div class="input-area">
                                <div class="grid grid-cols-12">
                                    <div class="col-span-12">
                                        <label class="form-label text-lg font-medium">{{ $field->name }}</label>
                                    </div>
                                    @if ($field->type === 'text')
                                        <div class="md:col-span-6 col-span-12">
                                            <input name="fields[{{ $field->name }}]" class="form-control !text-lg"
                                                type="text" value=""
                                                @if ($field->validation === 'required') required @endif>
                                        </div>
                                    @elseif($field->type === 'checkbox')
                                        <div class="col-span-12">
                                            @foreach ($field->options as $index => $option)
                                                <div class="checkbox-area mb-2">
                                                    <label for="flexCheckDefault{{ $qIndex }}{{ $index }}"
                                                        class="inline-flex items-center cursor-pointer">
                                                        <input class="hidden" type="checkbox"
                                                            name="fields[{{ $field->name }}][]"
                                                            value="{{ $option }}"
                                                            id="flexCheckDefault{{ $qIndex }}{{ $index }}"
                                                            @if ($field->validation === 'required') required @endif />
                                                        <span
                                                            class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                            <img src="{{ asset('frontend/images/icon/ck-white.svg') }}"
                                                                alt=""
                                                                class="h-[10px] w-[10px] block m-auto opacity-0">
                                                        </span>
                                                        <span class="text-slate-500 dark:text-slate-400 text-sm leading-6">
                                                            {{ $option }}
                                                        </span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($field->type === 'radio')
                                        <div class="col-span-12">
                                            @foreach ($field->options as $option)
                                                <div class="basicRadio mb-2">
                                                    <label class="flex items-center cursor-pointer">
                                                        <input type="radio" class="hidden"
                                                            name="fields[{{ $field->name }}]" value="{{ $option }}"
                                                            @if ($field->validation === 'required') required @endif>
                                                        <span
                                                            class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                                        <span
                                                            class="text-slate-500 dark:text-slate-400 text-sm leading-6 capitalize">
                                                            {{ $option }}
                                                        </span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($field->type === 'dropdown')
                                        <div class="md:col-span-6 col-span-12 select2-lg">
                                            <select name="fields[{{ $field->name }}]"
                                                class="select2 form-control w-full mt-2 py-2"
                                                @if ($field->validation === 'required') required @endif>
                                                @foreach ($field->options as $option)
                                                    <option value="{{ $option }}"
                                                        class="inline-block font-Inter font-normal text-sm text-slate-600"">
                                                        {{ $option }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                    <br>
                    <br>
                    <div class="input-area">
                        <div class="checkbox-area">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="hidden" name="checkbox" id="agreement-check" required>
                                <span
                                    class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                    <img src="{{ asset('frontend/assets/images/icon/ck-white.svg') }}" alt=""
                                        class="h-[10px] w-[10px] block m-auto opacity-0">
                                </span>
                                <span class="text-slate-500 dark:text-slate-400 text-sm leading-6">
                                    {{ __('I have read and agree with the ') }}
                                    <a href="javascript:;" class="btn-link">{{ __('IB Agreement') }}</a>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <div class="text-right">
                            <button type="button" class="btn btn-dark save-btn">{{ __('Register') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
    {{--    @endif --}}
    @if (request()->routeIs('user.referral.members'))
        @include('frontend::referral.include.__members')
    @endif
    @if (request()->routeIs('user.referral.advertisement.material'))
        @include('frontend::referral.include.__advertisement_material')
    @endif
    @if (request()->routeIs('user.referral.network'))
        @include('frontend::referral.include.__network')
    @endif
    @if (request()->routeIs('user.referral.reports'))
        @include('frontend::referral.include.__reports')
    @endif
    @if (request()->routeIs('user.referral.history'))
        @include('frontend::referral.include.__history')
    @endif
    {{-- IB account modal --}}
    @include('frontend::referral.modal.__ib_form')
    {{--    @endif --}}

@endsection
@section('script')
    <script>
        (function($) {
            "use strict";
            $('.data-table').DataTable().destroy();

            $(".data-table").DataTable({
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
                    search: "Search:"
                },
            });
        })(jQuery);

        $('body').on('change', '#language', function() {
            var selectedLanguage = $(this).val();
            $.ajax({
                url: '{{ route('user.referral.advertisement.material') }}',
                type: 'GET',
                data: {
                    language: selectedLanguage
                },
                success: function(data) {
                    // Update the content of the active tab with the filtered advertisements
                    $('#advertisement-container').html(data);
                    var activeTabContent = $('.nav-pills .nav-link.active').attr('href');
                    $('#tabs-socialMediaMaterial').removeClass('show active');
                    $('#tabs-websiteBanners').removeClass('show active');
                    $(activeTabContent).addClass('show active');

                },
                error: function() {
                    // t.error('Error fetching advertisements.');
                }
            });
        });

        $('body').on('click', '.save-btn', function() {
            if ($('#agreement-check').is(':checked')) {
                var btn = $(this);
                btn.prop('disabled', true);
                let form = document.querySelector('#ib-from-create');
                let formData = new FormData(form);
                // formData.append('amount', $('#active_wallet_amount').val());
                console.log('aa');
                var url = $('#ib-from-create').attr('action');
                submit_form(formData, btn, url, '', 'ibForm');
            } else {
                tNotify('error', '{{ __('Kindly check the agreement before proceeding!') }}')
            }

        });

        function copyRef() {
            /* Get the text field */
            var copyApi = document.getElementById("refLink");
            /* Select the text field */
            copyApi.select();
            copyApi.setSelectionRange(0, 999999999); /* For mobile devices */
            /* Copy the text inside the text field */
            document.execCommand('copy');
            $('#copy').text($('#copied').val())
        }

        // Initialize Flatpickr for date range picker (same as admin side)
        flatpickr(".flatpickr-created-at", {
            mode: "range",
            dateFormat: "Y-m-d",
            allowInput: true
        });

        $(document).ready(function() {
            // Function to update summary cards (using IB dashboard design)
            function updateSummaryCards(summary) {
                // Update IB Balance (from user model)
                $('#ib-balance').text('$' + summary.ib_balance);

                // Update Current IB Wallet Balance
                $('#current-ib-wallet').text('$' + summary.current_ib_wallet_balance);

                // Show filtered results
                $('#filtered-amount').text('$' + summary.total_amount);
                $('#filtered-count').text(summary.total_count + ' showing');

                // Update filter date range
                if (summary.filter_start_date && summary.filter_end_date) {
                    if (summary.filter_start_date === summary.filter_end_date) {
                        $('#oldest-entry-date').text(summary.filter_start_date);
                        $('#date-range-info').text('Single day filter');
                    } else {
                        $('#oldest-entry-date').text(summary.filter_start_date + ' - ' + summary.filter_end_date);
                        $('#date-range-info').text('Filter date range');
                    }
                } else {
                    $('#oldest-entry-date').text('All time');
                    $('#date-range-info').text('No date filter');
                }

                // Show summary cards
                $('#ib-summary-cards').show();
            }

            // Function to get date ranges for predefined filters (same as admin side)
            function getDateRanges() {
                const today = new Date();
                const formatDate = (date) => {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                };

                return {
                    '3_days': {
                        start: formatDate(new Date(today.getTime() - 3 * 24 * 60 * 60 * 1000)),
                        end: formatDate(today)
                    },
                    '5_days': {
                        start: formatDate(new Date(today.getTime() - 5 * 24 * 60 * 60 * 1000)),
                        end: formatDate(today)
                    },
                    '15_days': {
                        start: formatDate(new Date(today.getTime() - 15 * 24 * 60 * 60 * 1000)),
                        end: formatDate(today)
                    },
                    '1_month': {
                        start: formatDate(new Date(today.getFullYear(), today.getMonth() - 1, today.getDate())),
                        end: formatDate(today)
                    },
                    '3_months': {
                        start: formatDate(new Date(today.getFullYear(), today.getMonth() - 3, today.getDate())),
                        end: formatDate(today)
                    }
                };
            }

            // Handle predefined date range selection (same as admin side)
            $('#ib-bonus-date-filter').on('change', function() {
                const selectedRange = $(this).val();
                const ranges = getDateRanges();

                if (selectedRange && ranges[selectedRange]) {
                    const range = ranges[selectedRange];
                    const dateRangeString = range.start + ' to ' + range.end;
                    $('#ib-bonus-created-at').val(dateRangeString);

                    // Trigger data reload
                    fetchTransactions();
                } else if (selectedRange === '') {
                    // Clear date filter
                    $('#ib-bonus-created-at').val('');
                    fetchTransactions();
                }
            });

            function fetchTransactions(url = '{{ route('user.referral.history') }}') {
                // Get all filter values (same as admin side)
                let login = $('#ib-bonus-login').val();
                let deal = $('#ib-bonus-deal').val();
                let symbol = $('#ib-bonus-symbol').val();
                let dateFilter = $('#ib-bonus-date-filter').val();
                let createdAt = $('#ib-bonus-created-at').val();

                // Extract page parameter from URL if it exists
                let page = 1;
                if (url.includes('page=')) {
                    const urlParams = new URLSearchParams(url.split('?')[1]);
                    page = urlParams.get('page') || 1;
                }

                console.log('Fetching transactions for page:', page, 'URL:', url); // Debug log

                $.ajax({
                    url: '{{ route('user.referral.history') }}', // Always use base URL
                    type: 'GET',
                    data: {
                        login: login,
                        deal: deal,
                        symbol: symbol,
                        date_filter: dateFilter,
                        created_at: createdAt,
                        page: page, // Explicitly send page parameter
                    },
                    beforeSend: function() {
                        $('#transaction-table-body').html(
                            '<tr><td colspan="7" class="text-center">Loading...</td></tr>');
                        $('#transaction-mobile-body').html('<p class="text-center p-4">Loading...</p>');
                    },
                    success: function(response) {
                        if (response.html.trim() === "") {
                            $('#transaction-table-body').html(
                                '<tr><td colspan="7" class="text-center">No transactions found</td></tr>'
                            );
                            $('#transaction-mobile-body').html(
                                '<p class="text-center p-4">No transactions found</p>');
                        } else {
                            $('#transaction-table-body').html(response.html);
                            $('#transaction-mobile-body').html(response.html_mobile);
                        }
                        $('.pagination-container').html(response.pagination);
                        $('#total-records').html(`
                            {{ __('Showing') }}
                                <span class="font-medium">${response.total > 0 ? 1 : 0}</span>
                            {{ __('to') }}
                                <span class="font-medium">${response.total}</span>
                            {{ __('of') }}
                                <span class="font-medium">${response.total}</span>
                            {{ __('results') }}
                        `);

                        // Update summary cards if summary data is available
                        if (response.summary) {
                            updateSummaryCards(response.summary);
                        }

                        // Store selections in localStorage
                        localStorage.setItem('ib_login', login);
                        localStorage.setItem('ib_deal', deal);
                        localStorage.setItem('ib_symbol', symbol);
                        localStorage.setItem('ib_date_filter', dateFilter);
                        localStorage.setItem('ib_created_at', createdAt);

                        // Reattach event handlers
                        attachPaginationEvents();
                    },
                    error: function() {
                        alert('Error loading transactions.');
                    }
                });
            }

            function attachPaginationEvents() {
                $('.pagination a').off('click').on('click', function(e) {
                    e.preventDefault(); // Prevent page refresh
                    e.stopPropagation(); // Stop event bubbling

                    let url = $(this).attr('href');
                    if (url && !$(this).parent().hasClass('disabled') && !$(this).parent().hasClass(
                            'active')) {
                        console.log('Pagination clicked, URL:', url); // Debug log

                        // Update browser URL without refreshing page
                        window.history.pushState({}, '', url);

                        // Fetch data via AJAX
                        fetchTransactions(url);
                    }

                    return false; // Extra prevention of default behavior
                });
            }

            function resetFiltersIfNavigatedBack() {
                let isNavigatedBack = performance.navigation.type === 2 || sessionStorage.getItem(
                    'navigatedBack') === 'true';

                if (isNavigatedBack) {
                    console.log("Navigated back - resetting filters");

                    // Clear stored filters
                    localStorage.removeItem('ib_login');
                    localStorage.removeItem('ib_deal');
                    localStorage.removeItem('ib_symbol');
                    localStorage.removeItem('ib_date_filter');
                    localStorage.removeItem('ib_created_at');

                    // Reset filter values
                    $('#ib-bonus-login').val('');
                    $('#ib-bonus-deal').val('');
                    $('#ib-bonus-symbol').val('');
                    $('#ib-bonus-date-filter').val('');
                    $('#ib-bonus-created-at').val('');

                    sessionStorage.removeItem('navigatedBack'); // Reset flag
                }
            }

            function restoreSelections() {
                if (localStorage.getItem('ib_login')) {
                    $('#ib-bonus-login').val(localStorage.getItem('ib_login'));
                }
                if (localStorage.getItem('ib_deal')) {
                    $('#ib-bonus-deal').val(localStorage.getItem('ib_deal'));
                }
                if (localStorage.getItem('ib_symbol')) {
                    $('#ib-bonus-symbol').val(localStorage.getItem('ib_symbol'));
                }
                if (localStorage.getItem('ib_date_filter')) {
                    $('#ib-bonus-date-filter').val(localStorage.getItem('ib_date_filter'));
                }
                if (localStorage.getItem('ib_created_at')) {
                    $('#ib-bonus-created-at').val(localStorage.getItem('ib_created_at'));
                }
            }

            // Detect if user navigated back
            window.addEventListener('pageshow', function(event) {
                if (event.persisted || (performance.getEntriesByType("navigation")[0]?.type ===
                        "back_forward")) {
                    sessionStorage.setItem('navigatedBack', 'true');
                }
            });

            // Reset filters if user navigated back
            resetFiltersIfNavigatedBack();

            // Restore previous selections if they exist
            restoreSelections();

            // Set default to 3 months if no filters are stored and no filters applied
            function initializeDefaultFilter() {
                const hasStoredFilters = localStorage.getItem('ib_login') ||
                    localStorage.getItem('ib_deal') ||
                    localStorage.getItem('ib_symbol') ||
                    localStorage.getItem('ib_date_filter') ||
                    localStorage.getItem('ib_created_at');

                // Check if we have URL parameters (including page parameter)
                const urlParams = new URLSearchParams(window.location.search);
                const hasUrlParams = urlParams.toString().length > 0;

                if (hasUrlParams) {
                    // If URL has parameters, populate form fields and fetch data
                    if (urlParams.get('login')) $('#ib-bonus-login').val(urlParams.get('login'));
                    if (urlParams.get('deal')) $('#ib-bonus-deal').val(urlParams.get('deal'));
                    if (urlParams.get('symbol')) $('#ib-bonus-symbol').val(urlParams.get('symbol'));
                    if (urlParams.get('date_filter')) $('#ib-bonus-date-filter').val(urlParams.get('date_filter'));
                    if (urlParams.get('created_at')) $('#ib-bonus-created-at').val(urlParams.get('created_at'));

                    // Fetch transactions with current URL (including page parameter)
                    fetchTransactions(window.location.href);
                } else if (!hasStoredFilters && !$('#ib-bonus-date-filter').val()) {
                    $('#ib-bonus-date-filter').val('3_months');
                    // Trigger the change event to set the date range
                    $('#ib-bonus-date-filter').trigger('change');
                } else {
                    // If filters are already set, fetch transactions to populate summary cards
                    fetchTransactions();
                }
            }

            // Initialize default filter on page load
            initializeDefaultFilter();

            // Filter button click handler (same as admin side)
            $('#ib-bonus-filter-btn').on('click', function() {
                fetchTransactions();
            });

            // Handle export form submission (same as admin side)
            $('#ib-export-form').on('submit', function() {
                $('#export-ib-bonus-login').val($('#ib-bonus-login').val());
                $('#export-ib-bonus-deal').val($('#ib-bonus-deal').val());
                $('#export-ib-bonus-symbol').val($('#ib-bonus-symbol').val());
                $('#export-ib-bonus-date-filter').val($('#ib-bonus-date-filter').val());
                $('#export-ib-bonus-created-at').val($('#ib-bonus-created-at').val());
            });

            // Handle browser back/forward buttons
            window.addEventListener('popstate', function(event) {
                // When user uses browser back/forward, reload data via AJAX
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('login')) $('#ib-bonus-login').val(urlParams.get('login'));
                if (urlParams.get('deal')) $('#ib-bonus-deal').val(urlParams.get('deal'));
                if (urlParams.get('symbol')) $('#ib-bonus-symbol').val(urlParams.get('symbol'));
                if (urlParams.get('date_filter')) $('#ib-bonus-date-filter').val(urlParams.get(
                    'date_filter'));
                if (urlParams.get('created_at')) $('#ib-bonus-created-at').val(urlParams.get('created_at'));

                fetchTransactions(window.location.href);
            });

            // Attach pagination event initially
            attachPaginationEvents();
        });
    </script>
@endsection
