@extends('frontend::layouts.user')
@section('title')
    {{ __('Schema Logs') }}
@endsection
@section('content')
    <div class="mb-5">
        <ul class="m-0 p-0 list-none">
            <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter ">
                <a href="{{route('user.dashboard')}}">
                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                </a>
            </li>
            <li class="inline-block relative text-sm text-primary-500 font-Inter ">
                {{ __('Dashboard') }}
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ __('My Accounts') }}
            </li>
        </ul>
    </div>
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="card">
                <div class="card-header border-none">
                    <h4 class="card-title">{{ __('My Accounts') }}</h4>
                    <div>
                        <!-- BEGIN: Card Dropdown -->
                        <a href="{{route('user.schema')}}" class="btn inline-flex justify-center btn-dark btn-sm">
                            <span class="flex items-center">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="bi:plus"></iconify-icon>
                                <span>{{ __('Open New Account') }}</span>
                            </span>
                        </a>
                        <!-- END: Card Droopdown -->
                    </div>
                </div>
                <div class="card-body flex flex-col p-6 pt-0">
                    <div class="card-text h-full ">
                        <div>
                            <ul class="nav nav-tabs flex flex-row flex-wrap list-none border-b-0 pl-0 mb-4"
                                id="tabs-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a href="#tabs-realAccounts"
                                        class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent active dark:text-slate-300"
                                        id="tabs-realAccounts-tab" data-bs-toggle="pill" data-bs-target="#tabs-realAccounts" role="tab"
                                        aria-controls="tabs-realAccounts" aria-selected="true">Real</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="#tabs-demoAccounts"
                                        class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent dark:text-slate-300"
                                        id="tabs-demoAccounts-tab" data-bs-toggle="pill" data-bs-target="#tabs-demoAccounts" role="tab"
                                        aria-controls="tabs-demoAccounts" aria-selected="false">Demo</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a href="#tabs-archivedAccounts"
                                        class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-4 pb-2 my-2 hover:border-transparent focus:border-transparent dark:text-slate-300"
                                        id="tabs-archivedAccounts-tab" data-bs-toggle="pill" data-bs-target="#tabs-archivedAccounts" role="tab"
                                        aria-controls="tabs-archivedAccounts" aria-selected="false">Archived</a>
                                </li>
                            </ul>
                            <div class="flex justify-between mb-4">
                                <div class="input-area relative" style="padding-left: 3rem;">
                                    <label for="" class="inline-inputLabel">Sort:</label>
                                    <select class="form-control">
                                        <option selected>Choose...</option>
                                        <option>...</option>
                                    </select>
                                </div>
                                <div class="flex items-center space-x-2 sm:rtl:space-x-reverse md:flex hidden">
                                    <button type="button" class="btn btn-outline-dark btn-sm flex items-center justify-center grid-view-btn  active" data-target="trading-accounts">
                                        <iconify-icon class="text-xl" icon="heroicons:view-columns"></iconify-icon>
                                    </button>
                                    <button type="button" class="btn btn-outline-dark btn-sm flex items-center justify-center list-view-btn" data-target="trading-accounts">
                                        <iconify-icon class="text-xl" icon="heroicons:list-bullet"></iconify-icon>
                                    </button>
                                </div>
                            </div>
                            <div class="tab-content" id="trading-accounts">
                                <div class="tab-pane fade show active" id="tabs-realAccounts" role="tabpanel" aria-labelledby="tabs-realAccounts-tab">
                                    @include('frontend.default.user.forex.include.__real_accounts')
                                </div>
                                <div class="tab-pane fade" id="tabs-demoAccounts" role="tabpanel" aria-labelledby="tabs-demoAccounts-tab">
                                    @include('frontend.default.user.forex.include.__demo_accounts')
                                </div>
                                <div class="tab-pane fade" id="tabs-archivedAccounts" role="tabpanel" aria-labelledby="tabs-archivedAccounts-tab">
                                    @include('frontend.default.user.forex.include.__archive_accounts')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Account details -->
    @include('frontend.default.user.forex.modal.__trade')
    
    <!-- Modal for Account details -->
    @include('frontend.default.user.forex.modal.__account_details')

    <!-- Modal for Account leverage -->
    @include('frontend.default.user.forex.modal.__change_leverage')


    <!-- Modal for Account rename -->
    @include('frontend.default.user.forex.modal.__account_rename')


    <!-- Modal for Account password -->
    @include('frontend.default.user.forex.modal.__change_account_password')


    <!-- Modal for Account invest password -->
    @include('frontend.default.user.forex.modal.__change_investor_password')


    <!-- Modal for Account archive -->
    @include('frontend.default.user.forex.modal.__archive_account')

    <!-- Modal for Account unarchive -->
    @include('frontend.default.user.forex.modal.__unarchive_account')

@endsection

@section('script')
    @include('frontend.default.user.forex.fx-js')
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
