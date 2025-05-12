@extends('frontend::layouts.partner')
@section('title')
    {{ __('Partner Dashboard') }}
@endsection
@section('content')

{{--    @if(request()->routeIs('user.referral'))--}}
{{--        @if(auth()->user()->ib_status == \App\Enums\IBStatus::APPROVED && auth()->user()->ibQuestionAnswers)--}}
{{--            @include('frontend::referral.include.__dashboard')--}}
{{--            @include('frontend::referral.modal.__qr_code')--}}
{{--        @else--}}
            @if(auth()->user()->ib_status == \App\Enums\IBStatus::PENDING )

            <div class="card basicTable_wrapper items-center justify-center">
                <div class="card-body p-6">
                    <div class="max-w-2xl progress-steps-form">
                        <div class="transaction-status text-center">
                            <div class="icon h-20 w-20 bg-warning text-warning bg-opacity-30 rounded-full flex flex-col items-center justify-center mx-auto">
                                <iconify-icon icon="icomoon-free:hour-glass" class="text-4xl"></iconify-icon>
                            </div>
                            <h2 class="text-3xl dark:text-white my-5">{{ __('Partner Request Pending') }}</h2>
                            <p class="text-sm mb-3 dark:text-white">
                                {{ __("Your partnership request is under review and we'll confirm with you shortly. Stay tuned!") }}
                            </p>
                            <div class="flex flex-wrap items-center justify-center gap-3">
                                <a href="{{setting('IB_partner_agreement_link','document_links',false)}}" target="_blank" class="btn btn-light inline-flex items-center justify-center mr-2">
                                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="carbon:document"></iconify-icon>
                                    <span>{{ __('Read Partner Agreement') }}</span>
                                </a>
                                <a href="{{setting('trust_pilot_review_link','platform_links','javascript:void(0);')}}" target="_blank" class="btn btn-dark inline-flex items-center justify-center">
                                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="simple-icons:trustpilot"></iconify-icon>
                                    <span>{{ __('Read Our Reviews on Trustpilot') }}</span>
                                </a>
                            </div>
                            <div class="mt-5">
                                <p class="text-sm dark:text-slate-300">
                                    {{ __('If you face any issue, please visit our') }}
                                    <a href="{{setting('customer_support_link','platform_links','javascript:void(0);')}}" class="btn-link">{{ __('Customer Support') }}</a>
                                    {{ __('or Email us at') }}
                                    <a href="mailto:{{ setting('support_email','global')}}" class="btn-link">{{ setting('support_email','global')}}</a>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
       @elseif((auth()->user()->ib_status == \App\Enums\IBStatus::UNPROCESSED) && !isset(auth()->user()->ref_id))

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
                    <form action="{{ route('user.ib-program.store') }}" method="POST" id="ib-from-create"
                          class="space-y-4">
                        @csrf

                        @foreach($ibQuestions as $qIndex=>$ibQuestion)
                            @foreach(json_decode($ibQuestion->fields) as $field)
                                <div class="input-area">
                                    <div class="grid grid-cols-12">
                                        <div class="col-span-12">
                                            <label class="form-label text-lg font-medium">{{ $field->name }}</label>
                                        </div>
                                        @if($field->type === 'text')
                                            <div class="md:col-span-6 col-span-12">
                                                <input name="fields[{{ $field->name }}]" class="form-control !text-lg" type="text" value="" @if($field->validation === 'required') required @endif>
                                            </div>
                                        @elseif($field->type === 'checkbox')
                                            <div class="col-span-12">
                                                @foreach($field->options as $index=>$option)
                                                    <div class="checkbox-area mb-2">
                                                        <label for="flexCheckDefault{{$qIndex}}{{$index}}" class="inline-flex items-center cursor-pointer">
                                                            <input class="hidden" type="checkbox"
                                                                   name="fields[{{ $field->name }}][]"
                                                                   value="{{ $option }}" id="flexCheckDefault{{$qIndex}}{{$index}}"
                                                                   @if($field->validation === 'required') required @endif />
                                                            <span class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                                <img
                                                                    src="{{ asset('frontend/images/icon/ck-white.svg') }}"
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
                                                @foreach($field->options as $option)
                                                    <div class="basicRadio mb-2">
                                                        <label class="flex items-center cursor-pointer">
                                                            <input type="radio" class="hidden"
                                                                   name="fields[{{ $field->name }}]"
                                                                   value="{{ $option }}" @if($field->validation === 'required') required @endif>
                                                            <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                                            <span class="text-slate-500 dark:text-slate-400 text-sm leading-6 capitalize">
                                                                {{ $option }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif($field->type === 'dropdown')
                                            <div class="md:col-span-6 col-span-12 select2-lg">
                                                <select name="fields[{{ $field->name }}]" class="select2 form-control w-full mt-2 py-2" @if($field->validation === 'required') required @endif>
                                                    @foreach($field->options as $option)
                                                        <option value="{{ $option }}" class="inline-block font-Inter font-normal text-sm text-slate-600"">
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
                                    <span class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                            <img src="{{ asset('frontend/assets/images/icon/ck-white.svg') }}" alt="" class="h-[10px] w-[10px] block m-auto opacity-0">
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
{{--    @endif--}}
    @if(request()->routeIs('user.referral.members'))
        @include('frontend::referral.include.__members')
    @endif
    @if(request()->routeIs('user.referral.advertisement.material'))
        @include('frontend::referral.include.__advertisement_material')
    @endif
    @if(request()->routeIs('user.referral.network'))
        @include('frontend::referral.include.__network')
    @endif
    @if(request()->routeIs('user.referral.reports'))
        @include('frontend::referral.include.__reports')
    @endif
    @if(request()->routeIs('user.referral.history'))
        @include('frontend::referral.include.__history')
    @endif
    {{-- IB account modal --}}
    @include('frontend::referral.modal.__ib_form')
    {{--    @endif--}}

@endsection
@section('script')
    <script>
        (function ($) {
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

        $('body').on('change', '#language', function () {
            var selectedLanguage = $(this).val();
            $.ajax({
                url: '{{ route("user.referral.advertisement.material") }}',
                type: 'GET',
                data: { language: selectedLanguage },
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

        $('body').on('click', '.save-btn', function () {
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
                tNotify('error','{{ __('Kindly check the agreement before proceeding!') }}')
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

        $(document).ready(function () {
            function fetchTransactions(url = '{{ route("user.referral.history") }}') {
                let status = $('#transaction-status').val();
                let date = $('#transaction-date').val();

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        transaction_status: status,
                        transaction_date: date,
                    },
                    beforeSend: function () {
                        $('#transaction-table-body').html('<tr><td colspan="7" class="text-center">Loading...</td></tr>');
                    },
                    success: function (response) {
                        if (response.html.trim() === "") {
                            $('#transaction-table-body').html('<tr><td colspan="7" class="text-center">No transactions found</td></tr>');
                        } else {
                            $('#transaction-table-body').html(response.html);
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

                        // Store selections in localStorage
                        localStorage.setItem('transaction_status', status);
                        localStorage.setItem('transaction_date', date);

                        // Reattach event handlers
                        attachPaginationEvents();
                    },
                    error: function () {
                        alert('Error loading transactions.');
                    }
                });
            }

            function attachPaginationEvents() {
                $('.pagination a').off('click').on('click', function (e) {
                    e.preventDefault();
                    let url = $(this).attr('href');
                    if (url) {
                        fetchTransactions(url);
                    }
                });
            }

            function resetFiltersIfNavigatedBack() {
                let isNavigatedBack = performance.navigation.type === 2 || sessionStorage.getItem('navigatedBack') === 'true';

                if (isNavigatedBack) {
                    console.log("Navigated back - resetting filters");

                    // Clear stored filters
                    localStorage.removeItem('transaction_status');
                    localStorage.removeItem('transaction_date');

                    // Reset dropdown values
                    $('#transaction-status').val('');
                    $('#transaction-date').val('');

                    sessionStorage.removeItem('navigatedBack'); // Reset flag
                }
            }

            function restoreSelections() {
                if (localStorage.getItem('transaction_status')) {
                    $('#transaction-status').val(localStorage.getItem('transaction_status'));
                }
                if (localStorage.getItem('transaction_date')) {
                    $('#transaction-date').val(localStorage.getItem('transaction_date'));
                }
            }

            // Detect if user navigated back
            window.addEventListener('pageshow', function (event) {
                if (event.persisted || (performance.getEntriesByType("navigation")[0]?.type === "back_forward")) {
                    sessionStorage.setItem('navigatedBack', 'true');
                }
            });

            // Reset filters if user navigated back
            resetFiltersIfNavigatedBack();

            // Restore previous selections if they exist
            restoreSelections();

            // Attach event handlers to filters
            $('#transaction-date, #transaction-status').on('change', function () {
                fetchTransactions();
            });

            // Attach pagination event initially
            attachPaginationEvents();
        });
    </script>
@endsection
