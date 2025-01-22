@extends('backend.layouts.app')
@section('title')
    {{ __('Payout Requests') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="innerMenu card p-6 mb-5">
        <ul class="nav nav-pills flex items-center overflow-x-auto list-none pl-0 pb-1 md:pb-0 gap-4 menu-open w-full">
            <li class="nav-item">
                <a href="?status=all" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request('status') === 'all' ? 'active' : '' }}" aria-controls="tabs-demoAccounts" aria-selected="false">{{ __('All Payout Requests') }}</a>
            </li>
            <li class="nav-item">
                <a href="?status={{ \App\Enums\PayoutRequestStatus::PENDING }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request('status') === \App\Enums\PayoutRequestStatus::PENDING ? 'active' : '' }}" aria-controls="tabs-demoAccounts" aria-selected="false">{{ __('Pending Payouts') }}</a>
            </li>
            <li class="nav-item">
                <a href="?status={{ \App\Enums\PayoutRequestStatus::APPROVED }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{  request('status') === \App\Enums\PayoutRequestStatus::APPROVED ? 'active' : '' }}" aria-controls="tabs-archivedAccounts" aria-selected="false">{{ __('Approved Payouts') }}</a>
            </li>
            <li class="nav-item">
                <a href="?status={{ \App\Enums\PayoutRequestStatus::DECLINE }}" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize text-nowrap rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{  request('status') === \App\Enums\PayoutRequestStatus::DECLINE ? 'active' : '' }}" aria-controls="tabs-archivedAccounts" aria-selected="false">{{ __('Declined Payouts') }}</a>
            </li>
        </ul>
    </div>
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="tab-content" id="trading-accounts">
                <div class="tab-pane fade show active" id="tabs-realAccounts" role="tabpanel" aria-labelledby="tabs-realAccounts-tab">
                    @include('backend.payout_request.includes.__requests')
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')
    <script>
        // grid or list view
        $('.list-view-btn').click(function () {
            const targetId = $(this).data('target');
            $('#' + targetId + ' .grid').removeClass('grid-view').addClass('list-view');
            $(this).addClass('active');
            $('.grid-view-btn').removeClass('active');
        });

        $('.grid-view-btn').click(function () {
            const targetId = $(this).data('target');
            $('#' + targetId + ' .grid').removeClass('list-view').addClass('grid-view');
            $(this).addClass('active');
            $('.list-view-btn').removeClass('active');
        });
    </script>
@endsection
