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
            <div class="flex items-center gap-2">
                <a href="{{ route('user.schema', ['type' => 'real']) }}" class="btn loaderBtn inline-flex justify-center btn-primary btn-sm">
                    <span class="flex items-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="bi:plus"></iconify-icon>
                        <span>{{ __('Open New Real Account') }}</span>
                    </span>
                </a>
                <a href="{{ route('user.schema', ['type' => 'demo']) }}" class="btn loaderBtn inline-flex justify-center btn-primary btn-sm">
                    <span class="flex items-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="bi:plus"></iconify-icon>
                        <span>{{ __('Open New Demo Account') }}</span>
                    </span>
                </a>
            </div>
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
                                <iconify-icon class="text-2xl dark:text-slate-100" icon="material-symbols:window-sharp"></iconify-icon>
                                @break
                                @case('mac')
                                <iconify-icon class="text-2xl dark:text-slate-100" icon="fa6-brands:app-store-ios"></iconify-icon>
                                @break
                                @case('android')
                                <iconify-icon class="text-2xl dark:text-slate-100" icon="ion:logo-google-playstore"></iconify-icon>
                                @break
                                @case('ios')
                                <iconify-icon class="text-2xl dark:text-slate-100" icon="fa6-brands:apple"></iconify-icon>
                                @break
                                @case('android_apk')
                                <iconify-icon class="text-2xl dark:text-slate-100" icon="material-symbols:android"></iconify-icon>
                                @break
                                @case('web')
                                <iconify-icon class="text-2xl dark:text-slate-100" icon="mdi:web"></iconify-icon>
                                @break
                                @default()
                                <iconify-icon class="text-2xl dark:text-slate-100" icon="lucide:app-window"></iconify-icon>
                            @endswitch
                        </div>
                        <div class="flex-1">
                        <span class="block text-slate-600 text-sm font-semibold dark:text-slate-100">
                            {{ $platformLink->title }}
                        </span>
                            <span class="block font-normal text-xs text-slate-500 dark:text-slate-200">
                            {{ __('for') . ' ' . $platformLink->os }}
                        </span>
                        </div>
                    </div>
                    <div class="flex-none">
                        <a href="{{ $platformLink->link }}" class="inline-flex items-center text-sm dark:text-slate-100" target="_blank">
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
           // Status details modal
        (function($){
            'use strict';
            $('body').on('click', '.dropdown-status-details', function(){
                var $el = $(this);
                $('#sdAccountName').text($el.data('account_name'));
                $('#sdLogin').text($el.data('login'));
                $('#sdSchema').text($el.data('schema_title'));
                $('#sdServer').text($el.data('server'));
                $('#sdType').text($el.data('account_type'));
                $('#sdLeverage').text($el.data('leverage'));
                $('#sdBalance').text($el.data('balance') + ' ');
                $('#sdEquity').text($el.data('equity'));
                $('#statusDetailsStatus').text($el.data('status'));
                var comment = $el.data('comment') || '';
                $('#statusDetailsComment').html(comment || '{{ __('No status message available.') }}');
                $('#statusDetailsModal').modal('show');
            });
        })(jQuery);

        $('body').on('click', '.toggle-password', function () {
            const passwordInput = $(this).prev('input');
            const icon = $(this).find('iconify-icon');
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.attr('icon', 'lucide:eye-off');
            } else {
                passwordInput.attr('type', 'password');
                icon.attr('icon', 'lucide:eye');
            }
        });
    </script>
     <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="statusDetailsModal" tabindex="-1" aria-labelledby="statusDetailsModal" aria-hidden="true">
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative max-w-2xl w-full pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
                <div class="relative bg-white rounded-lg shadow dark:bg-dark">
                    <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600">
                        <h3 class="text-xl font-medium dark:text-white capitalize">{{ __('Status details') }}</h3>
                        <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5 fill-black dark:fill-white" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">{{ __('Close modal') }}</span>
                        </button>
                    </div>
                    <div class="p-6 space-y-5">
                        <ul class="divide-y divide-slate-100 dark:divide-slate-700 border border-slate-100 dark:border-slate-700 rounded">
                            <li class="list-group-item dark:text-slate-300 block py-2 px-3">{{ __('Account Name:') }} <strong id="sdAccountName"></strong></li>
                            <li class="list-group-item dark:text-slate-300 block py-2 px-3">{{ __('Number:') }} <strong id="sdLogin"></strong></li>
                            <li class="list-group-item dark:text-slate-300 block py-2 px-3">{{ __('Platform:') }} <strong id="sdPlatform">MT5</strong></li>
                            <li class="list-group-item dark:text-slate-300 block py-2 px-3">{{ __('Schema:') }} <strong id="sdSchema"></strong></li>
                            <li class="list-group-item dark:text-slate-300 block py-2 px-3">{{ __('Server:') }} <strong id="sdServer"></strong></li>
                            <li class="list-group-item dark:text-slate-300 block py-2 px-3">{{ __('Account Type:') }} <strong id="sdType"></strong></li>
                            <li class="list-group-item dark:text-slate-300 block py-2 px-3">{{ __('Leverage:') }} <strong id="sdLeverage"></strong></li>
                            <li class="list-group-item dark:text-slate-300 block py-2 px-3">{{ __('Balance:') }} <strong id="sdBalance"></strong></li>
                            <li class="list-group-item dark:text-slate-300 block py-2 px-3">{{ __('Equity:') }} <strong id="sdEquity"></strong></li>
                            <li class="list-group-item dark:text-slate-300 block py-2 px-3">{{ __('Current status:') }} <strong id="statusDetailsStatus"></strong></li>
                        </ul>
                        <div class="input-area">
                            <label class="form-label">{{ __('Description') }}</label>
                            <div id="statusDetailsComment" class="prose dark:prose-invert"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
