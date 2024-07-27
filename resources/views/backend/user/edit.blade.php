@extends('backend.layouts.app')
@section('title')
    {{ __('Customer Details') }}
@endsection
@section('content')
    <div class="space-y-5 profile-page">
        <div class="grid grid-cols-12 gap-6">
            <div class="lg:col-span-4 col-span-12">
                <!-- User Status Update -->
                @can('all-type-status')
                    @include('backend.user.include.__status_update')
                @endcan
                <!-- User Status Update End-->
            </div>
            <div class="lg:col-span-8 col-span-12">
                <div class="card overflow-hidden mb-5">
                    <div class="card-body py-1">
                        <div class="grid md:grid-cols-3 col-span-1 gap-px bg-slate-100 dark:bg-slate-700">
                            <div class="bg-white dark:bg-slate-800 p-4">
                                <div class="text-center space-y-2">
                                    <p class="text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                                        {{ __('Total Deposit') }}
                                    </p>
                                    <h6 class="text-slate-900 dark:text-white text-xl font-medium">
                                        {{ setting('currency_symbol','global') . $user->totalForexBalance() }}
                                    </h6>
                                    <p class="text-slate-800 dark:text-slate-300 text-sm">
                                        <span class="text-success-500">
                                            {{ __('+452%') }}
                                        </span>
                                        {{ __('in last 7 days') }}
                                    </p>
                                </div>
                            </div>
                            <div class="bg-white dark:bg-slate-800 p-4">
                                <div class="text-center space-y-2">
                                    <p class="text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                                        {{ __('Total Withdraw') }}
                                    </p>
                                    <h6 class="text-slate-900 dark:text-white text-xl font-medium">
                                        {{ setting('currency_symbol','global') . $user->totalForexBalance() }}
                                    </h6>
                                    <p class="text-slate-800 dark:text-slate-300 text-sm">
                                        <span class="text-success-500">
                                            {{ __('+452%') }}
                                        </span>
                                        {{ __('in last 7 days') }}
                                    </p>
                                </div>
                            </div>
                            <div class="bg-white dark:bg-slate-800 p-4">
                                <div class="text-center space-y-2">
                                    <p class="text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                                        {{ __('Level Commission') }}
                                    </p>
                                    <h6 class="text-slate-900 dark:text-white text-xl font-medium">
                                        {{ setting('currency_symbol','global') . $user->totalForexBalance() }}
                                    </h6>
                                    <p class="text-slate-800 dark:text-slate-300 text-sm">
                                        <span class="text-success-500">
                                            {{ __('+452%') }}
                                        </span>
                                        {{ __('in last 7 days') }}
                                    </p>
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
                                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-slate-900">
                                        <iconify-icon icon="mdi:currency-usd"></iconify-icon>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                                        {{ __('Primary Balance') }}
                                    </div>
                                    <div class="text-slate-900 dark:text-white text-lg font-medium">
                                        $682
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body pt-4 pb-3 px-4">
                            <div class="flex space-x-3 rtl:space-x-reverse">
                                <div class="flex-none">
                                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-slate-900">
                                        <iconify-icon icon="mdi:currency-usd"></iconify-icon>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                                        {{ __('Investment Balance') }}
                                    </div>
                                    <div class="text-slate-900 dark:text-white text-lg font-medium">
                                        $682
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body pt-4 pb-3 px-4">
                            <div class="flex space-x-3 rtl:space-x-reverse">
                                <div class="flex-none">
                                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-slate-900">
                                        <iconify-icon icon="mdi:currency-usd"></iconify-icon>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                                        {{ __('Enrollment Commissions') }}
                                    </div>
                                    <div class="text-slate-900 dark:text-white text-lg font-medium">
                                        $682
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
                                    class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 active"
                                    id="pills-informations-tab"
                                    data-bs-toggle="pill"
                                    data-bs-target="#pills-informations"
                                    type="button"
                                    role="tab"
                                    aria-controls="pills-informations"
                                    aria-selected="true"
                                >
                                    {{ __('Informations') }}
                                </a>
                            </li>
                        @endcanany
                        @can('investment-list')
                            <li class="nav-item" role="presentation">
                                <a
                                    href=""
                                    class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
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
                        @can('investment-list')
                            <li class="nav-item" role="presentation">
                                <a
                                    href=""
                                    class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                                    id="pills-transfer-tab"
                                    data-bs-toggle="pill"
                                    data-bs-target="#ib-info"
                                    type="button"
                                    role="tab"
                                    aria-controls="ib-info"
                                    aria-selected="true"
                                >
                                    {{ __('IB') }}
                                </a>
                            </li>
                        @endcan

                        @can('profit-list')
                            <li class="nav-item" role="presentation">
                                <a
                                    href=""
                                    class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                                    id="pills-deposit-tab"
                                    data-bs-toggle="pill"
                                    data-bs-target="#pills-deposit"
                                    type="button"
                                    role="tab"
                                    aria-controls="pills-deposit"
                                    aria-selected="true"
                                >
                                    {{ __('Earnings') }}
                                </a>
                            </li>
                        @endcan

                        @can('transaction-list')
                            <li class="nav-item" role="presentation">
                                <a
                                    href=""
                                    class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
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
                                    class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
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
                                    class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                                    id="pills-ticket-tab"
                                    data-bs-toggle="pill"
                                    data-bs-target="#pills-tree"
                                    type="button"
                                    role="tab"
                                    aria-controls="pills-transfer"
                                    aria-selected="true"
                                >
                                    {{ __('Referral Tree') }}
                                </a>
                            </li>
                        @endif


                        @canany(['support-ticket-list','support-ticket-action'])
                            <li class="nav-item" role="presentation">
                                <a
                                    href=""
                                    class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
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
                        @endcanany
                    </ul>
                </div>

                <div class="tab-content" id="pills-tabContent">
                    <!-- basic Info -->
                    @canany(['customer-basic-manage','customer-change-password'])
                        @include('backend.user.include.__basic_info')
                    @endcanany

                    <!-- investments -->
                    @can('investment-list')
                        @include('backend.user.include.__investments')
                    @endcan

                    <!-- IB -->
                    @can('IB-List')
                        @include('backend.user.include.__ib_info')
                        @include('backend.user.include.__ib_add')
                        @include('backend.user.include.__ib_update')
                        @include('backend.user.include.__mib_add')
                        @include('backend.user.include.__mib_update')
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

            //account type selection
            $('#tradingAccount').on('change', function () {
                var selectedOption = $(this).find('option:selected');
                var selectedAccountType = selectedOption.data('type');
                $('#selectedAccountType').val(selectedAccountType);
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
@endsection
