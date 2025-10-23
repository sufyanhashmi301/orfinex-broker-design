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
                    \App\Enums\KYCStatus::Rejected->value,
                ],
                'additionalLabels' => [
                    \App\Enums\KYCStatus::Pending->value => 'pending',
                    \App\Enums\KYCStatus::Rejected->value => 'reject',
                ],
            ],
            3 => [
                'statuses' => [
                    \App\Enums\KYCStatus::Level3->value,
                    \App\Enums\KYCStatus::PendingLevel3->value,
                    \App\Enums\KYCStatus::RejectLevel3->value,
                ],
                'additionalLabels' => [
                    \App\Enums\KYCStatus::PendingLevel3->value => 'pending',
                    \App\Enums\KYCStatus::RejectLevel3->value => 'reject',
                ],
            ],
        ];
    @endphp

    <div class="space-y-5 profile-page">
        <div class="grid grid-cols-12 gap-6">
            <div class="2xl:col-span-3 lg:col-span-4 col-span-12">
                <!-- User Status Update -->
                @can('customer-edit')
                    @include('backend.user.include.__status_update')
                @endcan
                <!-- User Status Update End-->
            </div>
            <div class="2xl:col-span-9 lg:col-span-8 col-span-12">
                <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
                    @can('customer-add-tag')
                        <a href="" class="btn btn-sm btn-primary inline-flex items-center justify-center" type="button"
                            data-bs-toggle="modal" data-bs-target="#addTags">
                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                            {{ __('Add Tag') }}
                        </a>
                    @endcan
                    <div class="flex space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                        <a href="{{ url()->previous() }}"
                            class="btn btn-sm btn-white inline-flex items-center justify-center">
                            {{ __('Go Back') }}
                        </a>
                        <button type="button" class="btn btn-sm btn-dark inline-flex items-center justify-center"
                            style="min-width: fit-content !important;">
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
                                            {{ __('Current Forex Balance') }}
                                        </p>
                                        <h6 class="text-slate-900 dark:text-white text-xl font-medium">
                                            {{ setting('currency_symbol', 'global') . mt5_total_balance($user->id) }}
                                        </h6>
                                    </div>
                                </div>
                                <div class="bg-white dark:bg-secondary p-4">
                                    <div class="text-center space-y-2">
                                        <p class="text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                                            {{ __('Net Deposits') }}
                                        </p>
                                        <h6 class="text-slate-900 dark:text-white text-xl font-medium">
                                            {{ setting('currency_symbol', 'global') . $user->totalDeposit() }}
                                        </h6>
                                    </div>
                                </div>
                                <div class="bg-white dark:bg-secondary p-4">
                                    <div class="text-center space-y-2">
                                        <p class="text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                                            {{ __('Net Withdraw') }}
                                        </p>
                                        <h6 class="text-slate-900 dark:text-white text-xl font-medium">
                                            {{ setting('currency_symbol', 'global') . $user->totalWithdraw() }}
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
                                        <div
                                            class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-body dark:text-slate-300">
                                            <iconify-icon icon="mdi:currency-usd"></iconify-icon>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                                            {{ __('Current Used Margin') }}
                                        </div>
                                        <div class="text-slate-900 dark:text-white text-lg font-medium">
                                            {{ setting('currency_symbol', 'global') . mt5_total_used_margin($user->id) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body pt-4 pb-3 px-4">
                                <div class="flex space-x-3 rtl:space-x-reverse">
                                    <div class="flex-none">
                                        <div
                                            class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-body dark:text-slate-300">
                                            <iconify-icon icon="mdi:currency-usd"></iconify-icon>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                                            {{ __('Current Free Margin') }}
                                        </div>
                                        <div class="text-slate-900 dark:text-white text-lg font-medium">
                                            {{ setting('currency_symbol', 'global') . mt5_total_free_margin($user->id) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body pt-4 pb-3 px-4">
                                <div class="flex space-x-3 rtl:space-x-reverse">
                                    <div class="flex-none">
                                        <div
                                            class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-body dark:text-slate-300">
                                            <iconify-icon icon="mdi:currency-usd"></iconify-icon>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                                            {{ __('Wallet Balance') }}
                                        </div>
                                        <div class="text-slate-900 dark:text-white text-lg font-medium">
                                            {{ setting('currency_symbol', 'global') . $user->totalWalletBalance() }}

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END: Group Chart -->
                    </div>
                    <div class="site-tab-bars card p-3 mb-5">
                        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 gap-3 menu-open" id="pills-tab"
                            role="tablist">
                            @canany(['customer-edit'])
                                <li class="nav-item" role="presentation">
                                    <a href=""
                                        class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 active"
                                        id="pills-informations-tab" data-bs-toggle="pill" data-bs-target="#pills-informations"
                                        type="button" role="tab" aria-controls="pills-informations" aria-selected="true">
                                        {{ __('Overview') }}
                                    </a>
                                </li>
                            @endcanany
                            @can('customer-accounts-list')
                                <li class="nav-item" role="presentation">
                                    <a href=""
                                        class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                                        id="pills-transfer-tab" data-bs-toggle="pill" data-bs-target="#pills-transfer"
                                        type="button" role="tab" aria-controls="pills-transfer" aria-selected="true">
                                        {{ __('Accounts') }}
                                    </a>
                                </li>
                            @endcan
                            @can('customer-kyc-manage')
                                <li class="nav-item" role="presentation">
                                    <a href=""
                                        class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                                        id="pills-kyc-tab" data-bs-toggle="pill" data-bs-target="#pills-kyc" type="button"
                                        role="tab" aria-controls="pills-kyc" aria-selected="true">
                                        {{ __('KYC') }}
                                    </a>
                                </li>
                            @endcan

                            @can('customer-ib-partner-list')
                                <li class="nav-item" role="presentation">
                                    <a href=""
                                        class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                                        id="pills-transfer-tab1" data-bs-toggle="pill" data-bs-target="#ib-info"
                                        type="button" role="tab" aria-controls="ib-info" aria-selected="true">
                                        {{ __('Partner') }}
                                    </a>
                                </li>
                            @endcan

                            @can('customer-transactions-list')
                                <li class="nav-item" role="presentation">
                                    <a href=""
                                        class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                                        id="pills-transactions-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-transactions" type="button" role="tab"
                                        aria-controls="pills-transactions" aria-selected="true">
                                        {{ __('Transactions') }}
                                    </a>
                                </li>
                            @endcan
                            @can('customer-ib-bonus-list')
                                <li class="nav-item" role="presentation">
                                    <a href=""
                                        class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                                        id="pills-bonus-tab" data-bs-toggle="pill" data-bs-target="#pills-bonus"
                                        type="button" role="tab" aria-controls="pills-bonus" aria-selected="true">
                                        {{ __('IB Bonus') }}
                                    </a>
                                </li>
                            @endcan
                            @can('customer-direct-referrals-list')
                                @if (setting('site_referral', 'global') == 'level')
                                    <li class="nav-item" role="presentation">
                                        <a href=""
                                            class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                                            id="pills-direct-referral-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-direct-referral" type="button" role="tab"
                                            aria-controls="pills-transfer" aria-selected="true">
                                            {{ __('Direct Referrals') }}
                                        </a>
                                    </li>
                                @endif
                            @endcan
                            @can('customer-network-tree')
                                @if (setting('site_referral', 'global') == 'level')
                                    <li class="nav-item" role="presentation">
                                        <a href=""
                                            class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                                            id="pills-ticket-tab" data-bs-toggle="pill" data-bs-target="#pills-tree"
                                            type="button" role="tab" aria-controls="pills-transfer"
                                            aria-selected="true">
                                            {{ __('Network') }}
                                        </a>
                                    </li>
                                @endif
                            @endcan

                            @canany(['customer-tickets-list'])
                                <li class="nav-item" role="presentation">
                                    <a href=""
                                        class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                                        id="pills-ticket-tab" data-bs-toggle="pill" data-bs-target="#pills-ticket"
                                        type="button" role="tab" aria-controls="pills-transfer" aria-selected="true">
                                        {{ __('Ticket') }}
                                    </a>
                                </li>
                            @endcanany
                            @can('customer-notes-list')
                                <li class="nav-item" role="presentation">
                                    <a href=""
                                        class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                                        id="pills-ticket-tab" data-bs-toggle="pill" data-bs-target="#pills-note"
                                        type="button" role="tab" aria-controls="pills-transfer" aria-selected="true">
                                        {{ __('Add Note') }}
                                    </a>
                                </li>
                            @endcan
                            @can('customer-change-password')
                                <li class="nav-item" role="presentation">
                                    <a href=""
                                        class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                                        id="pills-security-tab" data-bs-toggle="pill" data-bs-target="#pills-security"
                                        type="button" role="tab" aria-controls="pills-security" aria-selected="true">
                                        {{ __('Security') }}
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                </div>
                <div class="tab-content" id="pills-tabContent">
                    <!-- basic Info -->
                    @canany(['customer-edit'])
                        @include('backend.user.include.__basic_info')
                    @endcanany

                    <!-- investments -->
                    @can('customer-accounts-list')
                        @include('backend.user.include.__accounts')
                    @endcan
                    {{-- Modal for add Forex Account --}}
                    @can('customer-account-create')
                        @include('backend.user.include.__forex_account')
                    @endcan
                    @can('customer-account-mapping')
                        @include('backend.user.include.__forex_account_mapping')
                    @endcan
                    @can('customer-ib-bonus-list')
                        @include('backend.user.include.__ib_bonus')
                    @endcan
                    @can('customer-kyc-manage')
                        <!-- KYC Tab -->
                        @include('backend.user.include.__kycTab')
                    @endcan

                    <!-- IB -->
                    {{--                @can('IB-List') --}}

                    @can('customer-ib-partner-list')
                        @include('backend.user.include.__ib_info')
                    @endcan
                    @can('customer-approve-ib-member')
                        @include('backend.user.include.__ib_approve')
                        @include('backend.user.include.__ib_disable')
                    @endcan

                    {{--                @endcan --}}

                    <!-- earnings -->
                    @can('profit-list')
                        @include('backend.user.include.__earnings')
                    @endcan

                    <!-- transaction -->
                    @can('customer-transactions-list')
                        @include('backend.user.include.__transactions')
                    @endcan

                    <!-- Referral Tree -->
                    @if (setting('site_referral', 'global') == 'level')
                        @include('backend.user.include.__referral_direct')
                        @include('backend.user.include.__referral_add')
                    @endif
                    <!-- Referral Tree -->
                    @if (setting('site_referral', 'global') == 'level')
                        @include('backend.user.include.__referral_tree')
                    @endif

                    <!-- ticket -->
                    @canany(['customer-tickets-list'])
                        @include('backend.user.include.__ticket')
                    @endcanany
                    @can('customer-notes-list')
                        @include('backend.user.notes.index')
                    @endcan
                    @can('customer-change-password')
                        @include('backend.user.include.__security')
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Send Email -->
    @can('customer-mail-send')
        @include('backend.user.include.__mail_send', [
            'name' => $user->first_name . ' ' . $user->last_name,
            'id' => $user->id,
        ])
    @endcan
    <!-- Modal for Send Email-->



    {{-- Modal for Add or Subtract Bonus --}}
    @include('backend.user.include.__bonus')

    <!-- Modal for Add or Subtract Balance -->

    @include('backend.user.include.__balance')

    <!-- Modal for Add or Subtract Balance End-->
    {{--    @can('customer-balance-add-or-subtract') --}}
    @include('backend.user.include.__tags')
    @include('backend.user.include.__tag_delete')
    {{--    @endcan --}}
    <!-- Modal for Add or Subtract Balance -->
    {{--    @can('delete-user') --}}
    @include('backend.user.include.__delete_user', ['id' => $user->id])
    {{--    @endcan --}}
    <!-- Modal for Add or Subtract Balance End-->
    <!-- Modal for add referral-->
    {{--    @can('customer-mail-send') --}}
    @include('backend.user.include.__delete_direct_referral')
    {{--    @endcan --}}
    <!-- Modal for add referral-->


@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('frontend/css/intlTelInput.css') }}">
@endsection

@section('script')
    {{-- <script src="{{ asset('backend/js/choices.min.js') }}"></script> --}}
    <script src="{{ asset('frontend/js/intlTelInput.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $("select.select2").select2({
                tags: true
            });

            // Phone field initialization is handled in __basic_info.blade.php
            // No duplicate initialization needed here
        });

        $('#bonus-form').on('submit', function() {
            $('.bonus-apply-now').prop('disabled', true)
        })

        function confirmDelete(tagId, tagName) {
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
            $('#deleteConfirmationModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var url = button.data('url');
                $('#deleteForm').attr('action', url);
            });

            // Handle form submission
            $('#deleteForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the form from submitting traditionally

                const $form = $(this);
                const $deleteBtn = $('#deleteBtn');
                const $deleteIcon = $('#deleteIcon');
                const $deleteText = $('#deleteText');
                const $adminKeyInput = $('#admin_key');
                const $adminKeyError = $('#admin-key-error');

                // Clear previous errors
                if ($adminKeyInput.length) {
                    $adminKeyInput.removeClass('is-invalid');
                    $adminKeyError.hide().text('');
                }

                // Show loading state for admin key modal
                if ($deleteBtn.length && $('#deleteConfirmationModall').hasClass('show')) {
                    $deleteBtn.prop('disabled', true);
                    $deleteIcon.attr('icon', 'svg-spinners:ring-resize').removeClass('animate-spin');
                    $deleteText.text('Deleting...');
                }

                // Submit the form asynchronously using AJAX
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        // Reset loading state
                        if ($deleteBtn.length && $('#deleteConfirmationModall').hasClass(
                                'show')) {
                            $deleteBtn.prop('disabled', false);
                            $deleteIcon.attr('icon', 'lucide:check');
                            $deleteText.text('Delete');
                        }

                        if (response.success) {
                            // Handle success response
                            tNotify('success', response.message || response.success);

                            // Close the appropriate modal
                            if ($('#deleteConfirmationModall').hasClass('show')) {
                                $('#deleteConfirmationModall').modal('hide');
                            } else {
                                $('#deleteConfirmationModal').modal('hide');
                            }

                            // Redirect with delay for admin key deletion, immediate for others
                            if ($('#deleteConfirmationModall').hasClass('show')) {
                                setTimeout(function() {
                                    window.location.href = response.redirect ||
                                        "{{ route('admin.user.index') }}";
                                }, 1500);
                            } else {
                                window.location.href = response.redirect ||
                                    "{{ route('admin.user.index') }}";
                            }
                        } else {
                            // Handle error in success response
                            tNotify('error', response.message || 'An error occurred');

                            // Display field-specific errors for admin key
                            if (response.errors && response.errors.admin_key && $adminKeyInput
                                .length) {
                                $adminKeyInput.addClass('is-invalid');
                                $adminKeyError.text(response.errors.admin_key[0] || response
                                    .errors.admin_key).show();
                            }
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        // Reset loading state
                        if ($deleteBtn.length && $('#deleteConfirmationModall').hasClass(
                                'show')) {
                            $deleteBtn.prop('disabled', false);
                            $deleteIcon.attr('icon', 'lucide:check');
                            $deleteText.text('Delete');
                        }

                        let errorMessage = 'An unexpected error occurred. Please try again.';

                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            // Handle validation errors for admin key
                            if (xhr.responseJSON.errors && xhr.responseJSON.errors.admin_key &&
                                $adminKeyInput.length) {
                                $adminKeyInput.addClass('is-invalid');
                                $adminKeyError.text(xhr.responseJSON.errors.admin_key[0] || xhr
                                    .responseJSON.errors.admin_key).show();
                            }
                        }

                        tNotify('error', errorMessage);
                    }
                });
            });

            //account type selection for Balance Module
            $('#tradingAccount_balance').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var selectedAccountType = selectedOption.data('type');
                $('#selectedAccountType_balance').val(selectedAccountType);
            });

            //account type selection for Bonus Module
            $('#tradingAccount_bonus').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var selectedAccountType = selectedOption.data('type');
                $('#selectedAccountType_bonus').val(selectedAccountType);
            });

            //send mail modal form open
            $('body').on('click', '.delete-direct-referral', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#referralName').html(name);
                $('#referralId').val(id);
            })
        });

        $('#enter-main-password').on('input', function() {
            var password = $(this).val();
            checkPassword(password, 'main', 'create-forex-account');

        });


        $('body').on('change', '#kycLevelSelect', function() {
            var level = $(this).val();
            $('.kycData').empty();

            $.ajax({
                url: '{{ route('admin.kyc.kycMethods') }}',
                type: "GET",
                data: {
                    kyc_level: level
                },
                success: function(data) {
                    $('#kycTypeSelect').empty();
                    $('#kycTypeSelect').append('<option value="">Select Level</option>');

                    // Append new options based on the KYC records
                    $.each(data.kycs, function(index, kyc) {
                        $('#kycTypeSelect').append('<option value="' + kyc.id + '">' + kyc
                            .name + '</option>');
                    });
                }
            })
        });

        $('body').on('change', '#kycTypeSelect', function(e) {
            "use strict";
            e.preventDefault();

            $('.kycData').empty();
            var id = $(this).val();
            var url = '{{ route('admin.kyc.data', ':id') }}';
            url = url.replace(':id', id);

            $.get(url, function(data) {
                console.log(data);
                $('.kycData').append(data);
                imagePreview();
            });
        });

        $(document).ready(function() {
            // Function to update the Islamic checkbox state
            function updateIslamicCheckboxState(modalId, accountType, isRealIslamic, isDemoIslamic) {
                var isIslamic = false;
                if (accountType === 'real') {
                    isIslamic = isRealIslamic == 1;
                } else if (accountType === 'demo') {
                    isIslamic = isDemoIslamic == 1;
                }
                $(`#${modalId} #islamic-checkbox`).prop('disabled', !isIslamic);
            }

            // Function to update leverage options
            function updateLeverageAndDeposit(modalId, result) {
                $(`#${modalId} #select-leverage`).html(result.leverage); // Update leverage options
            }

            // Handle schema selection changes
            $('.modal').on('change', '#select-schema', function(e) {
                e.preventDefault();
                var modalId = $(this).closest('.modal').attr('id'); // Get the modal ID
                var id = $(this).val();
                var url = '{{ route('user.schema.select', ':id') }}';
                url = url.replace(':id', id);

                // Fetch schema details via AJAX
                $.ajax({
                    url: url,
                    success: function(result) {
                        if (result) {
                            updateLeverageAndDeposit(modalId, result);
                            $(`#${modalId} #select-schema`).data('is-real-islamic', result
                                .is_real_islamic);
                            $(`#${modalId} #select-schema`).data('is-demo-islamic', result
                                .is_demo_islamic);
                            $(`#${modalId} #islamic-checkbox`).prop('checked', false);
                            updateIslamicCheckboxState(modalId, $(`#${modalId} #account-type`)
                                .val(), result.is_real_islamic, result.is_demo_islamic);
                        } else {
                            console.error('Invalid response from server');
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX request failed:', xhr.responseText);
                    }
                });
            });

            // Password validation
            function checkPassword(password, type, submitButtonId) {
                var lengthCheck = password.length >= 8 && password.length <= 15;
                var lettersCheck = /[a-z]/.test(password) && /[A-Z]/.test(password);
                var numberCheck = /\d/.test(password);
                var specialCheck = /[!@#$%^&*(),.?":{}|<>]/.test(password);

                $(`#${type}-length-check`).toggleClass('text-danger', !lengthCheck).toggleClass('text-success',
                    lengthCheck);
                $(`#${type}-letters-check`).toggleClass('text-danger', !lettersCheck).toggleClass('text-success',
                    lettersCheck);
                $(`#${type}-number-check`).toggleClass('text-danger', !numberCheck).toggleClass('text-success',
                    numberCheck);
                $(`#${type}-special-check`).toggleClass('text-danger', !specialCheck).toggleClass('text-success',
                    specialCheck);

                if (lengthCheck && lettersCheck && numberCheck && specialCheck) {
                    $(submitButtonId).prop('disabled', false);
                } else {
                    $(submitButtonId).prop('disabled', true);
                }
            }

            $('.modal').on('input', '#enter-main-password', function() {
                var modalId = $(this).closest('.modal').attr('id');
                var password = $(this).val();
                checkPassword(password, 'main', `#${modalId} #create-forex-account`);
            });

            $('#partner_status_btn').on('change', function() {
                @if ($user->ib_status == 'approved')
                    $('#disableIBModal').modal('show');
                @else
                    $('#addIBModal').modal('show');
                @endif
            });
        });
    </script>
@endsection
