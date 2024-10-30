@extends('backend.layouts.app')
@section('title')
    {{ __('Customer Details') }}
@endsection
@section('content')
    @php
        // Initialize the array to store statuses to exclude
        $excludedStatuses = [];

        // Mapping KYC Level IDs to corresponding KYCStatus values with their dynamic labels
        $statusLabels = [
            1 => ['statuses' => [\App\Enums\KYCStatus::Level1->value]],
            2 => [
                'statuses' => [
                    \App\Enums\KYCStatus::Level2->value,
                    \App\Enums\KYCStatus::Pending->value,
                    \App\Enums\KYCStatus::Rejected->value
                ],
                'additionalLabels' => [
                    \App\Enums\KYCStatus::Pending->value => 'pending',
                    \App\Enums\KYCStatus::Rejected->value => 'reject'
                ]
            ],
            3 => [
                'statuses' => [
                    \App\Enums\KYCStatus::Level3->value,
                    \App\Enums\KYCStatus::PendingLevel3->value,
                    \App\Enums\KYCStatus::RejectLevel3->value
                ],
                'additionalLabels' => [
                    \App\Enums\KYCStatus::PendingLevel3->value => 'pending',
                    \App\Enums\KYCStatus::RejectLevel3->value => 'reject'
                ]
            ]
        ];
    @endphp

    <div class="space-y-5 profile-page">
        <div class="grid grid-cols-12 gap-6">
            <div class="2xl:col-span-3 lg:col-span-4 col-span-12">
                <!-- User Status Update -->
            @can('all-type-status')
                @include('backend.user.include.__status_update')
            @endcan
            <!-- User Status Update End-->
            </div>
            <div class="2xl:col-span-9 lg:col-span-8 col-span-12">

                <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
                    <a href="" class="btn btn-sm btn-primary inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#addTags">
                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                        {{ __('Add Tag') }}
                    </a>

                    <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                        <a href="{{ url()->previous() }}" class="btn btn-sm btn-white inline-flex items-center justify-center">
                            {{ __('Go Back') }}
                        </a>
                        <button type="button" class="btn btKYC Leveln-sm btn-dark inline-flex items-center justify-center" style="min-width: fit-content !important;">
                            <iconify-icon class="text-base" icon="lucide:refresh-cw"></iconify-icon>
                        </button>
                    </div>
                </div>
                <div class="innerMenu">
                    <div class="card overflow-hidden mb-5">
                        <div class="card-body py-1">
                            <div class="grid md:grid-cols-3 col-span-1 gap-px bg-slate-100 dark:bg-slate-700">
                                <div class="bg-white dark:bg-secondary p-4">
                                    <div class="text-center space-y-2">
                                        <p class="text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                                            {{ __('Total Deposit') }}
                                        </p>
                                        <h6 class="text-slate-900 dark:text-white text-xl font-medium">
                                            {{ setting('currency_symbol','global') . $user->totalForexBalance() }}
                                        </h6>
                                    </div>
                                </div>
                                <div class="bg-white dark:bg-secondary p-4">
                                    <div class="text-center space-y-2">
                                        <p class="text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                                            {{ __('Total Withdraw') }}
                                        </p>
                                        <h6 class="text-slate-900 dark:text-white text-xl font-medium">
                                            {{ setting('currency_symbol','global') . $user->totalForexBalance() }}
                                        </h6>
                                    </div>
                                </div>
                                <div class="bg-white dark:bg-secondary p-4">
                                    <div class="text-center space-y-2">
                                        <p class="text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                                            {{ __('Net Deposits') }}
                                        </p>
                                        <h6 class="text-slate-900 dark:text-white text-xl font-medium">
                                            {{ setting('currency_symbol','global') . $user->totalForexBalance() }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid md:grid-cols-3 grid-cols-1 gap-4 mb-5">
                        <!-- BEGIN: Group Chart -->
                        <div class="card">
                            <div class="card-body pt-4 pb-3 px-4">
                                <div class="flex space-x-3 rtl:space-x-reverse">
                                    <div class="flex-none">
                                        <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-body dark:text-slate-300">
                                            <iconify-icon icon="mdi:currency-usd"></iconify-icon>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                                            {{ __('Used Margin') }}
                                        </div>
                                        <div class="text-slate-900 dark:text-white text-lg font-medium">
                                            {{ setting('currency_symbol','global') . $user->totalForexBalance() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body pt-4 pb-3 px-4">
                                <div class="flex space-x-3 rtl:space-x-reverse">
                                    <div class="flex-none">
                                        <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-body dark:text-slate-300">
                                            <iconify-icon icon="mdi:currency-usd"></iconify-icon>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                                            {{ __('Free Margin') }}
                                        </div>
                                        <div class="text-slate-900 dark:text-white text-lg font-medium">
                                            {{ setting('currency_symbol','global') . $user->totalForexEquity() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body pt-4 pb-3 px-4">
                                <div class="flex space-x-3 rtl:space-x-reverse">
                                    <div class="flex-none">
                                        <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-body dark:text-slate-300">
                                            <iconify-icon icon="mdi:currency-usd"></iconify-icon>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                                            {{ __('Wallet Balance') }}
                                        </div>
                                        <div class="text-slate-900 dark:text-white text-lg font-medium">
                                            $0
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END: Group Chart -->
                    </div>
                    <div class="site-tab-bars card p-3 mb-5">
                        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 gap-3 menu-open" id="pills-tab" role="tablist">
                            @canany(['customer-basic-manage','customer-change-password'])
                                <li class="nav-item" role="presentation">
                                    <a
                                        href=""
                                        class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 active"
                                        id="pills-informations-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#pills-informations"
                                        type="button"
                                        role="tab"
                                        aria-controls="pills-informations"
                                        aria-selected="true"
                                    >
                                        {{ __('Overview') }}
                                    </a>
                                </li>
                            @endcanany
                            @can('investment-list')
                                <li class="nav-item" role="presentation">
                                    <a
                                        href=""
                                        class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                                        id="pills-transfer-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#pills-transfer"
                                        type="button"
                                        role="tab"
                                        aria-controls="pills-transfer"
                                        aria-selected="true"
                                    >
                                        {{ __('Accounts') }}
                                    </a>
                                </li>
                            @endcan
                            <li class="nav-item" role="presentation">
                                <a
                                    href=""
                                    class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                                    id="pills-kyc-tab"
                                    data-bs-toggle="pill"
                                    data-bs-target="#pills-kyc"
                                    type="button"
                                    role="tab"
                                    aria-controls="pills-kyc"
                                    aria-selected="true"
                                >
                                    {{ __('KYC') }}
                                </a>
                            </li>
                            @can('investment-list')
                                <li class="nav-item" role="presentation">
                                    <a
                                        href=""
                                        class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                                        id="pills-transfer-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#ib-info"
                                        type="button"
                                        role="tab"
                                        aria-controls="ib-info"
                                        aria-selected="true"
                                    >
                                        {{ __('Partner') }}
                                    </a>
                                </li>
                            @endcan

                            @can('transaction-list')
                                <li class="nav-item" role="presentation">
                                    <a
                                        href=""
                                        class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                                        id="pills-transactions-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#pills-transactions"
                                        type="button"
                                        role="tab"
                                        aria-controls="pills-transactions"
                                        aria-selected="true"
                                    >
                                        {{ __('Transactions') }}
                                    </a>
                                </li>
                            @endcan

                            @if(setting('site_referral','global') == 'level')
                                <li class="nav-item" role="presentation">
                                    <a
                                        href=""
                                        class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                                        id="pills-direct-referral-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#pills-direct-referral"
                                        type="button"
                                        role="tab"
                                        aria-controls="pills-transfer"
                                        aria-selected="true"
                                    >
                                        {{ __('Direct Referrals') }}
                                    </a>
                                </li>
                            @endif
                            @if(setting('site_referral','global') == 'level')
                                <li class="nav-item" role="presentation">
                                    <a
                                        href=""
                                        class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                                        id="pills-ticket-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#pills-tree"
                                        type="button"
                                        role="tab"
                                        aria-controls="pills-transfer"
                                        aria-selected="true"
                                    >
                                        {{ __('Network') }}
                                    </a>
                                </li>
                            @endif


                            @canany(['support-ticket-list','support-ticket-action'])
                                <li class="nav-item" role="presentation">
                                    <a
                                        href=""
                                        class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                                        id="pills-ticket-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#pills-ticket"
                                        type="button"
                                        role="tab"
                                        aria-controls="pills-transfer"
                                        aria-selected="true"
                                    >
                                        {{ __('Ticket') }}
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a
                                        href=""
                                        class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                                        id="pills-ticket-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#pills-note"
                                        type="button"
                                        role="tab"
                                        aria-controls="pills-transfer"
                                        aria-selected="true"
                                    >
                                        {{ __('Add Note') }}
                                    </a>
                                </li>
                                    <li class="nav-item" role="presentation">
                                        <a
                                            href=""
                                            class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                                            id="pills-security-tab"
                                            data-bs-toggle="pill"
                                            data-bs-target="#pills-security"
                                            type="button"
                                            role="tab"
                                            aria-controls="pills-security"
                                            aria-selected="true"
                                        >
                                            {{ __('Security') }}
                                        </a>
                                    </li>
                            @endcanany
                        </ul>
                    </div>
                </div>
                <div class="tab-content" id="pills-tabContent">
                    <!-- basic Info -->
                @canany(['customer-basic-manage','customer-change-password'])
                    @include('backend.user.include.__basic_info')
                @endcanany

                <!-- investments -->
                @can('investment-list')
                     @include('backend.user.include.__accounts')
                @endcan

                <!-- KYC Tab -->
                @include('backend.user.include.__kycTab')

                <!-- IB -->
                @can('IB-List')
                    @include('backend.user.include.__ib_info')
                    @include('backend.user.include.__ib_approve')
{{--                    @include('backend.user.include.__ib_update')--}}
{{--                    @include('backend.user.include.__mib_add')--}}
{{--                    @include('backend.user.include.__mib_update')--}}
                @endcan

                <!-- earnings -->
                @can('profit-list')
                    @include('backend.user.include.__earnings')
                @endcan

                <!-- transaction -->
                @can('transaction-list')
                    @include('backend.user.include.__transactions')
                @endcan

                <!-- Referral Tree -->
                @if(setting('site_referral','global') == 'level')
                    @include('backend.user.include.__referral_direct')
                    @include('backend.user.include.__referral_add')

                @endif
                <!-- Referral Tree -->
                @if(setting('site_referral','global') == 'level')
                    @include('backend.user.include.__referral_tree')
                @endif

                <!-- ticket -->
                @canany(['support-ticket-list','support-ticket-action'])
                    @include('backend.user.include.__ticket')
                @endcan

                @include('backend.user.notes.index')

                @include('backend.user.include.__security')

                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Send Email -->
    @can('customer-mail-send')
        @include('backend.user.include.__mail_send',['name' => $user->first_name.' '.$user->last_name, 'id' => $user->id])
    @endcan
    <!-- Modal for Send Email-->

    {{-- Modal for add Forex Account --}}
    @include('backend.user.include.__forex_account')

    {{-- Modal for Add or Subtract Bonus --}}
    @include('backend.user.include.__bonus')

    <!-- Modal for Add or Subtract Balance -->
    @can('customer-balance-add-or-subtract')
        @include('backend.user.include.__balance')
    @endcan
    <!-- Modal for Add or Subtract Balance End-->
    {{--    @can('customer-balance-add-or-subtract')--}}
    @include('backend.user.include.__tags')
    @include('backend.user.include.__tag_delete')
    {{--    @endcan--}}
    <!-- Modal for Add or Subtract Balance -->
    {{--    @can('delete-user')--}}
    @include('backend.user.include.__delete_user',[ 'id' => $user->id])
    {{--    @endcan--}}
    <!-- Modal for Add or Subtract Balance End-->
    <!-- Modal for add referral-->
    {{--    @can('customer-mail-send')--}}
    @include('backend.user.include.__delete_direct_referral')
    {{--    @endcan--}}
    <!-- Modal for add referral-->


@endsection
@section('script')
    {{-- <script src="{{ asset('backend/js/choices.min.js') }}"></script> --}}

    <script>
        $(document).ready(function() {
            $("select.select2").select2({
                tags: true
            })
        });

        $('#bonus-form').on('submit', function(){
            $('.bonus-apply-now').prop('disabled', true)
        })

        function confirmDelete(tagId,tagName) {
            $('#risk_profile_tag_id').val(tagId)
            $('#risk_profile_tag_name').text(tagName)
            $('#deleteTagModal').modal('show');
        }
        $(document).ready(function() {

            function reloadPage() {
                // Reload the current page
                window.location.href = window.location.href;
            }
            // Set the form action dynamically when the modal is shown
            $('#deleteConfirmationModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var url = button.data('url');
                $('#deleteForm').attr('action', url);
            });

            // Handle form submission
            $('#deleteForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the form from submitting traditionally

                // Submit the form asynchronously using AJAX
                $.ajax({
                    type: 'POST', // or 'DELETE' depending on your form method
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function(response) {
                        // Handle success response
                        console.log(response); // Log the response to the console (for debugging)
                        // You can show a success message or perform other actions here
                        $('#deleteConfirmationModal').modal('hide'); // Close the modal, for example
                        tNotify('success',response.success)
                        window.location.href = "{{route('admin.user.index')}}";
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        // Handle error response
                        console.error(xhr.responseText); // Log the error response to the console (for debugging)
                        // You can show an error message or perform other actions here
                    }
                });
            });

            //account type selection for Balance Module
            $('#tradingAccount_balance').on('change', function () {
                var selectedOption = $(this).find('option:selected');
                var selectedAccountType = selectedOption.data('type');
                $('#selectedAccountType_balance').val(selectedAccountType);
            });

            //account type selection for Bonus Module
            $('#tradingAccount_bonus').on('change', function () {
                var selectedOption = $(this).find('option:selected');
                var selectedAccountType = selectedOption.data('type');
                $('#selectedAccountType_bonus').val(selectedAccountType);
            });

            //send mail modal form open
            $('body').on('click', '.delete-direct-referral', function () {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#referralName').html(name);
                $('#referralId').val(id);
            })
        });

        $('#enter-main-password').on('input', function () {
            var password = $(this).val();
            checkPassword(password,'main','create-forex-account');

        });

    </script>
    <script>
        $(document).ready(function () {
            function updateIslamicCheckboxState(accountType, isRealIslamic, isDemoIslamic) {
                var isIslamic = false;
                if (accountType === 'real') {
                    isIslamic = isRealIslamic == 1;
                } else if (accountType === 'demo') {
                    isIslamic = isDemoIslamic == 1;
                }
                $('#islamic-checkbox').prop('disabled', !isIslamic);
            }

            function updateLeverageAndDeposit(result) {
                $('#display-commission').text(result.commission);
                $('#display-spread').text(result.spread);
                $('#select-leverage').html(result.leverage);
                $('#display-leverage').text(result.display_leverage);
                $('#initial-deposit').text(result.first_min_deposit);
            }

            $('#account-type-tabs .nav-link').on('click', function () {
                $('#account-type-tabs .nav-link').removeClass('active');
                $(this).addClass('active');
                var accountType = $(this).data('type');
                $('#account-type').val(accountType);

                $('#islamic-checkbox').prop('checked', false);

                var isRealIslamic = $('#select-schema').find('option:selected').data('is-real-islamic');
                var isDemoIslamic = $('#select-schema').find('option:selected').data('is-demo-islamic');
                updateIslamicCheckboxState(accountType, isRealIslamic, isDemoIslamic);
            });

            $("#islamic-checkbox").on('change', function () {
                var isIslamic = $(this).is(':checked');
                var accountType = $('#account-type').val();
                var isRealIslamic = $('#select-schema').find('option:selected').data('is-real-islamic');
                var isDemoIslamic = $('#select-schema').find('option:selected').data('is-demo-islamic');
                updateIslamicCheckboxState(accountType, isRealIslamic, isDemoIslamic);
            });

            $("#select-schema").on('change', function (e) {
                "use strict";
                e.preventDefault();
                var id = $(this).val();
                var url = '{{ route("user.schema.select", ":id") }}';
                url = url.replace(':id', id);

                $.ajax({
                    url: url,
                    success: function (result) {
                        $('#first-min-amount').text(result.first_min_deposit);
                        updateLeverageAndDeposit(result);

                        $('#select-schema').data('is-real-islamic', result.is_real_islamic);
                        $('#select-schema').data('is-demo-islamic', result.is_demo_islamic);

                        $('#islamic-checkbox').prop('checked', false);
                        updateIslamicCheckboxState($('#account-type').val(), result.is_real_islamic, result.is_demo_islamic);
                    }
                });
            });

            var initialAccountType = $('#account-type').val();
            var initialIsRealIslamic = $('#select-schema').find('option:selected').data('is-real-islamic');
            var initialIsDemoIslamic = $('#select-schema').find('option:selected').data('is-demo-islamic');
            updateIslamicCheckboxState(initialAccountType, initialIsRealIslamic, initialIsDemoIslamic);

            $("#select-leverage").on('change', function () {
                var selectedLeverage = $(this).val();
                $('#display-leverage').text(selectedLeverage); // Update the display-leverage with the selected value
            });

            $("#selectWallet").on('change', function (e) {
                "use strict";
                $('.gatewaySelect').empty();
                $('.manual-row').empty();
                var wallet = $(this).val();
                if (wallet === 'gateway') {
                    $.get('{{ route('gateway.list') }}', function (data) {
                        $('.gatewaySelect').append(data);
                        $('select').niceSelect();
                    });
                }
            });

            $('body').on('change', '#gatewaySelect', function (e) {
                "use strict";
                e.preventDefault();
                $('.manual-row').empty();
                var code = $(this).val();
                var url = '{{ route("user.deposit.gateway", ":code") }}';
                url = url.replace(':code', code);
                $.get(url, function (data) {
                    if (data.credentials !== undefined) {
                        console.log(data.credentials);
                        $('.manual-row').append(data.credentials);
                        imagePreview();
                    }
                });

                $('#amount').on('keyup', function (e) {
                    "use strict";
                    var amount = $(this).val();
                    $('.amount').text(Number(amount));
                    $('.currency').text(currency);
                    var charge = globalData.charge_type === 'percentage' ? calPercentage(amount, globalData.charge) : globalData.charge;
                    $('.charge2').text(charge + ' ' + currency);
                    $('.total').text(Number(amount) + Number(charge) + ' ' + currency);
                });
            });

            $('#enter-main-password').on('input', function () {
                var password = $(this).val();
                checkPassword(password, 'main', 'create-forex-account');
            });
        });

    </script>
@endsection
