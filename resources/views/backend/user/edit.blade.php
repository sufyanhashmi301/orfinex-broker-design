@extends('backend.layouts.app')
@section('title')
    {{ __('Customer Details') }}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('backend/css/choices.min.css') }}" >
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content flex-wrap flex-md-nowrap">
                            <div class="flex flex-wrap flex-md-nowrap align-items-stretch gap-2 mb-2 mb-md-0">
                                <span data-bs-toggle="modal" data-bs-target="#addTags">
                                <a href="javascript:void(0)" class="btn btn-dark btn-sm">
                                    <i icon-name="plus"></i>
                                    Add Tag
                                </a>
                                </span>
                                {{--                                {{dd($user->riskProfileTags)}}--}}
                                @foreach($user->riskProfileTags as $tag)
                                    <div class="position-relative px-3 py-1 rounded bg-white border">
                                        <div class="position-absolute left-0 bg-danger rounded-full" style="width: 8px; height: 8px; top: calc(50% - 4px);"></div>
                                        <span class="small ps-3 pe-1">{{$tag->name}}</span>
                                        <a href="#" class="btn-link text-dark" onclick="confirmDelete({{ $tag->id }},'{{ $tag->name }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x">
                                                <path d="M18 6 6 18"/>
                                                <path d="m6 6 12 12"/>
                                            </svg>
                                        </a>
                                    </div>
                                @endforeach

                            </div>
                            <div class="content">
                                <a href="{{ url()->previous() }}" class="title-btn"><i
                                        icon-name="corner-down-left"></i>{{ __('Back') }}</a>
                                <a href="{{ url()->current() }}" class="title-btn"><i
                                        icon-name="refresh-ccw"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xxl-3 col-xl-6 col-lg-8 col-md-6 col-sm-12">
                    <div class="profile-card">
                        <div class="top">
                            <div class="avatar">
                                @if(null != $user->avatar)
                                    <img
                                        class="avatar-image"
                                        src="{{asset($user->avatar)}}"
                                        alt="{{$user->first_name}}"
                                    />
                                @else
                                    <span class="avatar-text">{{$user->first_name[0] .$user->last_name[0] }}</span>
                                @endif
                            </div>
                            <div class="title-des">
                                <h4>{{$user->first_name .' '. $user->last_name}}</h4>
                                <p>{{ucwords($user->city)}}@if($user->city != ''), @endif{{ $user->country }}</p>
                            </div>
                            <div class="btns">
                                @can('customer-mail-send')
                                    <span type="button" data-bs-toggle="modal" data-bs-target="#sendEmail"><a
                                            href="javascript:void(0);" class="site-btn-round blue-btn"
                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Send Email"><i
                                                icon-name="mail"></i></a></span>
                                @endcan
                                @can('customer-login')
                                    <a href="{{ route('admin.user.login',$user->id) }}" target="_blank"
                                       class="site-btn-round red-btn" data-bs-toggle="tooltip" title=""
                                       data-bs-placement="top" data-bs-original-title="Login As User">
                                        <i icon-name="user-plus"></i>
                                    </a>
                                @endcan
                                @can('customer-balance-add-or-subtract')
                                    <span data-bs-toggle="modal" data-bs-target="#addSubBal">
                                    <a href="javascript:void(0);" type="button" class="site-btn-round primary-btn"
                                       data-bs-toggle="tooltip" title="" data-bs-placement="top"
                                       data-bs-original-title="Add Funds">
                                    <i icon-name="wallet"></i></a></span>
                                @endcan
                                {{--                                @can('Delete User')--}}
                                <span data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal">
                                    <a href="javascript:void(0);" type="button" class="site-btn-round red-btn"
                                       data-bs-toggle="tooltip" title="" data-bs-placement="top"
                                       data-bs-original-title="Delete User">
                                    <i icon-name="user-minus"></i></a></span>

                            </div>
                        </div>
                        <div class="site-card">
                            <div class="site-card-body">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="admin-user-balance-card">
                                            <div class="wallet-name">
                                                <div class="name">{{ __('Total Forex Balance') }}</div>
                                                <div class="chip-icon">
                                                    <img class="chip"
                                                         src="{{asset('backend/materials/chip.png')}}"
                                                         alt=""
                                                    />
                                                </div>
                                            </div>
                                            <div class="wallet-info">
                                                <div class="wallet-id">{{ setting('site_currency','global') }}</div>
                                                <div
                                                    class="balance">{{ setting('currency_symbol','global') . $user->totalForexBalance() }}</div>
                                            </div>
                                        </div>
                                        <div class="admin-user-balance-card">
                                            <div class="wallet-name">
                                                <div class="name">{{ __('Total Forex Equity') }}</div>
                                                <div class="chip-icon">
                                                    <img
                                                        class="chip"
                                                        src="{{asset('backend/materials/chip.png')}}"
                                                        alt=""
                                                    />
                                                </div>
                                            </div>
                                            <div class="wallet-info">
                                                <div class="wallet-id">{{ setting('site_currency','global') }}</div>
                                                <div
                                                    class="balance">{{ setting('currency_symbol','global') . $user->totalForexEquity() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- User Status Update -->
                    @can('all-type-status')
                        @include('backend.user.include.__status_update')
                    @endcan
                    <!-- User Status Update End-->

                    </div>
                </div>


                <div class="col-xxl-9 col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="site-tab-bars">
                        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                            @canany(['customer-basic-manage','customer-change-password'])
                                <li class="nav-item" role="presentation">
                                    <a
                                        href=""
                                        class="nav-link active"
                                        id="pills-informations-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#pills-informations"
                                        type="button"
                                        role="tab"
                                        aria-controls="pills-informations"
                                        aria-selected="true"
                                    ><i icon-name="user"></i>{{ __('Informations') }}</a>
                                </li>
                            @endcanany
                            @can('investment-list')
                                <li class="nav-item" role="presentation">
                                    <a
                                        href=""
                                        class="nav-link"
                                        id="pills-transfer-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#pills-transfer"
                                        type="button"
                                        role="tab"
                                        aria-controls="pills-transfer"
                                        aria-selected="true"
                                    ><i icon-name="anchor"></i>{{ __('Forex Accounts') }}</a>
                                </li>
                            @endcan
                            @can('investment-list')
                                <li class="nav-item" role="presentation">
                                    <a
                                        href=""
                                        class="nav-link"
                                        id="pills-transfer-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#ib-info"
                                        type="button"
                                        role="tab"
                                        aria-controls="ib-info"
                                        aria-selected="true"
                                    ><i icon-name="anchor"></i>{{ __('IB') }}</a>
                                </li>
                            @endcan

                            @can('profit-list')
                                <li class="nav-item" role="presentation">
                                    <a
                                        href=""
                                        class="nav-link"
                                        id="pills-deposit-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#pills-deposit"
                                        type="button"
                                        role="tab"
                                        aria-controls="pills-deposit"
                                        aria-selected="true"
                                    ><i icon-name="credit-card"></i>{{ __('Earnings') }}</a>
                                </li>
                            @endcan

                            @can('transaction-list')
                                <li class="nav-item" role="presentation">
                                    <a
                                        href=""
                                        class="nav-link"
                                        id="pills-transactions-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#pills-transactions"
                                        type="button"
                                        role="tab"
                                        aria-controls="pills-transactions"
                                        aria-selected="true"
                                    ><i icon-name="cast"></i>{{ __('Transactions') }}</a>
                                </li>
                            @endcan

                            @if(setting('site_referral','global') == 'level')
                                <li class="nav-item" role="presentation">
                                    <a
                                        href=""
                                        class="nav-link"
                                        id="pills-ticket-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#pills-tree"
                                        type="button"
                                        role="tab"
                                        aria-controls="pills-transfer"
                                        aria-selected="true"
                                    ><i icon-name="network"></i>{{ __('Referral Tree') }}</a>
                                </li>
                            @endif


                            @canany(['support-ticket-list','support-ticket-action'])
                                <li class="nav-item" role="presentation">
                                    <a
                                        href=""
                                        class="nav-link"
                                        id="pills-ticket-tab"
                                        data-bs-toggle="pill"
                                        data-bs-target="#pills-ticket"
                                        type="button"
                                        role="tab"
                                        aria-controls="pills-transfer"
                                        aria-selected="true"
                                    ><i icon-name="wrench"></i>{{ __('Ticket') }}</a>
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

@endsection
@section('script')
    <script src="{{ asset('backend/js/choices.min.js') }}"></script>

    <script>
        function confirmDelete(tagId,tagName) {
            $('#risk_profile_tag_id').val(tagId)
            $('#risk_profile_tag_name').text(tagName)
            $('#deleteTagModal').modal('show');
        }
        var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
            removeItemButton: true,
            // maxItemCount:7,
            // searchResultLimit:7,
            // renderChoiceLimit:7
        });
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
        });

    </script>
@endsection
