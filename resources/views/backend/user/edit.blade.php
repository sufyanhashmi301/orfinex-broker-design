@extends('backend.layouts.app')
@section('title')
    {{ __('Customer Details') }}
@endsection
@section('content')
<div class="page-content">
    <div class="transition-all duration-150 container-fluid" id="page_layout">
        <div id="content_layout">
            <div>
                <div class="flex justify-between flex-wrap items-center mb-6">
                    <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center">
                        <span data-bs-toggle="modal" data-bs-target="#addTags">
                            <a href="javascript:void(0)" class="btn btn-dark btn-sm inline-flex items-center justify-center">
                                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                                Add Tag
                            </a>
                        </span>
                        {{--{{dd($user->riskProfileTags)}}--}}
                        @foreach($user->riskProfileTags as $tag)
                            <div class="flex items-center gap-2 px-3 py-2 rounded bg-white border">
                                <div class="bg-danger-500 rounded-full" style="width: 8px; height: 8px;"></div>
                                <span class="text-sm pl-3 pr-1">{{$tag->name}}</span>
                                <a href="#" class="btn-link text-dark" onclick="confirmDelete({{ $tag->id }},'{{ $tag->name }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x">
                                        <path d="M18 6 6 18"/>
                                        <path d="m6 6 12 12"/>
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                        <a href="{{ url()->previous() }}" class="btn btn-dark btn-sm inline-flex items-center justify-center">
                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                            {{ __('Back') }}
                        </a>
                        <a href="{{ url()->current() }}" class="btn btn-dark btn-sm inline-flex items-center justify-center">
                            <iconify-icon class="text-lg" icon="lucide:refresh-ccw"></iconify-icon>
                        </a>
                    </div>
                </div>
                <div class="space-y-5 profile-page">
                    <div class="profiel-wrap px-[35px] pb-10 md:pt-[84px] pt-10 rounded-lg bg-white dark:bg-slate-800 lg:flex lg:space-y-0 space-y-6 justify-between items-end relative z-[1]">
                        <div class="bg-slate-900 dark:bg-slate-700 absolute left-0 top-0 md:h-1/2 h-[150px] w-full z-[-1] rounded-t-lg">
                        </div>
                        <div class="profile-box flex-none md:text-start text-center">
                            <div class="md:flex items-end md:space-x-6 rtl:space-x-reverse">
                                <div class="flex-none">
                                    <div class="md:h-[186px] md:w-[186px] h-[140px] w-[140px] md:ml-0 md:mr-0 ml-auto mr-auto md:mb-0 mb-4 rounded-full ring-4 ring-slate-100 relative bg-slate-300 dark:bg-slate-900 dark:text-white text-slate-900 flex flex-col items-center justify-center">
                                        @if(null != $user->avatar)
                                            <img
                                                class="w-full h-full object-cover rounded-full"
                                                src="{{asset($user->avatar)}}"
                                                alt="{{$user->first_name}}"
                                            />
                                        @else
                                            <span class="text-4xl">{{$user->first_name[0] .$user->last_name[0] }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="text-2xl font-medium text-slate-900 dark:text-slate-200 mb-[3px]">
                                        {{$user->first_name .' '. $user->last_name}}
                                    </div>
                                    <div class="text-sm font-light text-slate-600 dark:text-slate-400">
                                        {{ucwords($user->city)}}@if($user->city != ''), @endif{{ $user->country }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end profile box -->
                        <div class="profile-info-500 md:flex md:text-start items-center text-center flex-1 max-w-[516px] md:space-y-0 space-y-4">
                            <div class="flex-1">
                                <div class="text-base text-slate-900 dark:text-slate-300 font-medium mb-1">
                                    {{ setting('currency_symbol','global') . $user->totalForexBalance() }}
                                </div>
                                <div class="text-sm text-slate-600 font-light dark:text-slate-300">
                                    {{ __('Total Forex Balance') }}
                                </div>
                            </div>
                            <!-- end single -->
                            <div class="flex-1">
                                <div class="text-base text-slate-900 dark:text-slate-300 font-medium mb-1">
                                    {{ setting('currency_symbol','global') . $user->totalForexEquity() }}
                                </div>
                                <div class="text-sm text-slate-600 font-light dark:text-slate-300">
                                    {{ __('Total Forex Equity') }}
                                </div>
                            </div>
                            <!-- end single -->
                            <div class="flex-1">
                                <div class="flex justify-center space-x-3 rtl:space-x-reverse">
                                    @can('customer-mail-send')
                                        <span type="button" data-bs-toggle="modal" data-bs-target="#sendEmail">
                                            <a href="javascript:void(0);" class="toolTip onTop action-btn"
                                                data-tippy-theme="dark" data-tippy-content="Send Email">
                                                <iconify-icon icon="lucide:mail"></iconify-icon>
                                            </a>
                                        </span>
                                    @endcan
                                    @can('customer-login')
                                        <a href="{{ route('admin.user.login',$user->id) }}" target="_blank"
                                            class="toolTip onTop action-btn" data-tippy-theme="dark" data-tippy-content="Login As User">
                                            <iconify-icon icon="lucide:user-plus"></iconify-icon>
                                        </a>
                                    @endcan
                                    @can('customer-balance-add-or-subtract')
                                        <span data-bs-toggle="modal" data-bs-target="#addSubBal">
                                            <a href="javascript:void(0);" type="button" class="toolTip onTop action-btn"
                                                data-tippy-theme="dark" data-tippy-content="Add Funds">
                                                <iconify-icon icon="lucide:wallet"></iconify-icon>
                                            </a>
                                        </span>
                                    @endcan
                                    {{--@can('Delete User')--}}
                                    <span data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal">
                                        <a href="javascript:void(0);" type="button" class="toolTip onTop action-btn"
                                            data-tippy-theme="dark" data-tippy-content="Delete User">
                                            <iconify-icon icon="lucide:user-minus"></iconify-icon>
                                        </a>
                                    </span>
                                </div>
                            </div>
                            <!-- end single -->
                        </div>
                        <!-- profile info-500 -->
                    </div>
                    <div class="grid grid-cols-12 gap-6">
                        <div class="lg:col-span-3 col-span-12">
                            <!-- User Status Update -->
                            @can('all-type-status')
                                @include('backend.user.include.__status_update')
                            @endcan
                            <!-- User Status Update End-->
                        </div>
                        <div class="lg:col-span-9 col-span-12">
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
                                                {{ __('Forex Accounts') }}
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
            </div>
        </div>
    </div>
</div>

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

            //forex account type selection
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

    </script>
@endsection
