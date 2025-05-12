@extends('frontend::layouts.user')
@section('title')
    {{ __('Schema Logs') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center gap-5 mb-5">
        <ul class="nav nav-tabs custom-tabs inline-flex items-center overflow-hidden rounded list-none border-0 pl-0" id="tabs-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a href="#tabs-realAccounts"
                    class="btn btn-sm inline-flex justify-center btn-outline-primary active"
                    id="tabs-realAccounts-tab" data-bs-toggle="pill" data-bs-target="#tabs-realAccounts" role="tab"
                    aria-controls="tabs-realAccounts" aria-selected="true">{{ __('Real') }}</a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="#tabs-demoAccounts"
                    class="btn btn-sm inline-flex justify-center btn-outline-primary"
                    id="tabs-demoAccounts-tab" data-bs-toggle="pill" data-bs-target="#tabs-demoAccounts" role="tab"
                    aria-controls="tabs-demoAccounts" aria-selected="false">{{ __('Demo') }}</a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="#tabs-archivedAccounts"
                    class="btn btn-sm inline-flex justify-center btn-outline-primary"
                    id="tabs-archivedAccounts-tab" data-bs-toggle="pill" data-bs-target="#tabs-archivedAccounts" role="tab"
                    aria-controls="tabs-archivedAccounts" aria-selected="false">{{ __('Archived') }}</a>
            </li>
        </ul>
        <div class="flex sm:justify-end items-center gap-3">
            <div class="flex items-center space-x-2 sm:rtl:space-x-reverse md:flex hidden">
                <button type="button" class="btn btn-outline-secondary btn-sm inline-flex items-center justify-center grid-view-btn active" data-target="trading-accounts">
                    <iconify-icon class="text-lg" icon="heroicons:view-columns"></iconify-icon>
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm inline-flex items-center justify-center list-view-btn" data-target="trading-accounts">
                    <iconify-icon class="text-lg" icon="heroicons:list-bullet"></iconify-icon>
                </button>
            </div>
            <a href="{{route('user.schema')}}" class="btn loaderBtn inline-flex justify-center btn-primary btn-sm">
                <span class="flex items-center">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="bi:plus"></iconify-icon>
                    <span>{{ __('Open New Account') }}</span>
                </span>
            </a>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="tab-content" id="trading-accounts">
                <div class="tab-pane fade show active" id="tabs-realAccounts" role="tabpanel" aria-labelledby="tabs-realAccounts-tab">
                    @include('frontend::.user.forex.include.__real_accounts')
                </div>
                <div class="tab-pane fade" id="tabs-demoAccounts" role="tabpanel" aria-labelledby="tabs-demoAccounts-tab">
                    @include('frontend::.user.forex.include.__demo_accounts')
                </div>
                <div class="tab-pane fade" id="tabs-archivedAccounts" role="tabpanel" aria-labelledby="tabs-archivedAccounts-tab">
                    @include('frontend::.user.forex.include.__archive_accounts')
                </div>
            </div>
        </div>
    </div>

    <h4 class="font-medium text-xl capitalize text-slate-900 my-5">
        {{ __('Download Platform') }}
    </h4>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($platformLinks as $platformLink)
            <div class="card p-4">
                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                    <div class="flex-1 flex items-center space-x-2 rtl:space-x-reverse">
                        <div class="flex-none">
                            @switch($platformLink->os)
                                @case('window')
                                <iconify-icon class="text-2xl dark:text-slate-300" icon="material-symbols:window-sharp"></iconify-icon>
                                @break
                                @case('mac')
                                <iconify-icon class="text-2xl dark:text-slate-300" icon="fa6-brands:app-store-ios"></iconify-icon>
                                @break
                                @case('android')
                                <iconify-icon class="text-2xl dark:text-slate-300" icon="ion:logo-google-playstore"></iconify-icon>
                                @break
                                @case('ios')
                                <iconify-icon class="text-2xl dark:text-slate-300" icon="fa6-brands:apple"></iconify-icon>
                                @break
                                @case('android_apk')
                                <iconify-icon class="text-2xl dark:text-slate-300" icon="material-symbols:android"></iconify-icon>
                                @break
                                @case('web')
                                <iconify-icon class="text-2xl dark:text-slate-300" icon="mdi:web"></iconify-icon>
                                @break
                                @default()
                                <iconify-icon class="text-2xl dark:text-slate-300" icon="lucide:app-window"></iconify-icon>
                            @endswitch
                        </div>
                        <div class="flex-1">
                        <span class="block text-slate-600 text-sm font-semibold dark:text-slate-300">
                            {{ $platformLink->title }}
                        </span>
                            <span class="block font-normal text-xs text-slate-500">
                            {{ __('for') . ' ' . $platformLink->os }}
                        </span>
                        </div>
                    </div>
                    <div class="flex-none">
                        <a href="{{ $platformLink->link }}" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                            <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal for Account details -->
    @include('frontend::.user.forex.modal.__trade')

    <!-- Modal for Account details -->
    @include('frontend::.user.forex.modal.__account_details')

    <!-- Modal for Account leverage -->
    @include('frontend::.user.forex.modal.__change_leverage')

    <!-- Modal for Demo deposit -->
    @include('frontend::.user.forex.modal.__deposit_demo_account')

    <!-- Modal for Account rename -->
    @include('frontend::.user.forex.modal.__account_rename')

    <!-- Modal for Account password -->
    @include('frontend::.user.forex.modal.__change_account_password')

    <!-- Modal for Account invest password -->
    @include('frontend::.user.forex.modal.__change_investor_password')

    <!-- Modal for Account archive -->
    @include('frontend::.user.forex.modal.__archive_account')

    <!-- Modal for Account unarchive -->
    @include('frontend::.user.forex.modal.__unarchive_account')

@endsection

@section('script')
    @include('frontend::.user.forex.fx-js')
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
