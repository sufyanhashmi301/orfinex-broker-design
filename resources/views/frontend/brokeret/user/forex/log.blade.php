@extends('frontend::layouts.user')
@section('title')
    {{ __('My Accounts') }}
@endsection
@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 pb-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
            @yield('title')
        </h2>
    </div>

    <div 
        x-data="{ 
            selectedTab: 'realAccounts', 
            viewMode: 'grid',
            init() {
                this.$watch('viewMode', (value) => {
                    this.$dispatch('view-mode-changed', { mode: value });
                });
            }
        }" 
        class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex flex-col items-center px-4 py-5 xl:px-6 xl:py-6">
            <div class="flex flex-col w-full gap-5 sm:justify-between xl:flex-row xl:items-center">
                <div x-on:keydown.right.prevent="$focus.wrap().next()" x-on:keydown.left.prevent="$focus.wrap().previous()" class="flex flex-wrap items-center gap-x-1 gap-y-2 rounded-lg bg-gray-100 p-0.5 dark:bg-gray-900" role="tablist" aria-label="tab options">
                    <button 
                        x-on:click="selectedTab = 'realAccounts'" 
                        x-bind:aria-selected="selectedTab === 'realAccounts'" 
                        x-bind:tabindex="selectedTab === 'realAccounts' ? '0' : '-1'" 
                        x-bind:class="selectedTab === 'realAccounts' ? 'text-gray-900 dark:text-white bg-white dark:bg-gray-800' : 'text-gray-500 dark:text-gray-400'" 
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md group hover:text-gray-900 dark:hover:text-white text-gray-500 dark:text-gray-400">
                        {{ __('Real') }}
                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium leading-normal group-hover:bg-brand-50 group-hover:text-brand-500 dark:group-hover:bg-brand-500/15 dark:group-hover:text-brand-400 text-brand-500 dark:text-brand-400 bg-brand-50 dark:bg-brand-500/15">
                            {{ $realForexAccounts->count() }}
                        </span>
                    </button>

                    <button 
                        x-on:click="selectedTab = 'demoAccounts'" 
                        x-bind:aria-selected="selectedTab === 'demoAccounts'" 
                        x-bind:tabindex="selectedTab === 'demoAccounts' ? '0' : '-1'" 
                        x-bind:class="selectedTab === 'demoAccounts' ? 'text-gray-900 dark:text-white bg-white dark:bg-gray-800' : 'text-gray-500 dark:text-gray-400'" 
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md group hover:text-gray-900 dark:hover:text-white text-gray-500 dark:text-gray-400">
                        {{ __('Demo') }}
                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium leading-normal group-hover:bg-brand-50 group-hover:text-brand-500 dark:group-hover:bg-brand-500/15 dark:group-hover:text-brand-400 bg-white dark:bg-white/[0.03]">
                            {{ $demoForexAccounts->count() }}
                        </span>
                    </button>

                    <button 
                        x-on:click="selectedTab = 'archivedAccounts'" 
                        x-bind:aria-selected="selectedTab === 'archivedAccounts'" 
                        x-bind:tabindex="selectedTab === 'archivedAccounts' ? '0' : '-1'" 
                        x-bind:class="selectedTab === 'archivedAccounts' ? 'text-gray-900 dark:text-white bg-white dark:bg-gray-800' : 'text-gray-500 dark:text-gray-400'" 
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md group hover:text-gray-900 dark:hover:text-white text-gray-500 dark:text-gray-400">
                        {{ __('Archived') }}
                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium leading-normal group-hover:bg-brand-50 group-hover:text-brand-500 dark:group-hover:bg-brand-500/15 dark:group-hover:text-brand-400 bg-white dark:bg-white/[0.03]">
                            {{ $archiveForexAccounts->count() }}
                        </span>
                    </button>
                </div>

                <div class="flex flex-wrap items-center gap-3 xl:justify-end">
                    <div class="inline-flex items-center shadow-theme-xs md:flex hidden">
                        <button 
                            type="button" 
                            x-on:click="viewMode = 'grid'"
                            x-bind:class="viewMode === 'grid' ? 'bg-brand-500 text-white border-brand-500' : 'text-gray-800 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-white/[0.03]'"
                            class="inline-flex items-center gap-2 px-4 py-3 text-sm font-medium ring-1 ring-inset ring-gray-300 transition first:rounded-l-lg last:rounded-r-lg dark:bg-white/[0.03] dark:ring-gray-700">
                            <i data-lucide="layout-grid" class="w-4 h-4"></i>
                        </button>
                        <button 
                            type="button" 
                            x-on:click="viewMode = 'list'"
                            x-bind:class="viewMode === 'list' ? 'bg-brand-500 text-white border-brand-500' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]'"
                            class="-ml-px inline-flex items-center gap-2 px-4 py-3 text-sm font-medium ring-1 ring-inset ring-gray-300 transition first:rounded-l-lg last:rounded-r-lg dark:bg-transparent dark:ring-gray-700">
                            <i data-lucide="layout-list" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <x-link-button href="{{route('user.schema')}}" variant="primary" icon="plus" iconPosition="left">
                        {{ __('Open New Account') }}
                    </x-link-button>
                </div>
            </div>
        </div>
        <div class="p-4 space-y-8 border-t border-gray-200 mt-7 dark:border-gray-800 sm:mt-0 xl:p-6 tab-content" id="trading-accounts" x-bind:class="viewMode === 'grid' ? 'grid-view' : 'list-view'">
            <div x-cloak x-show="selectedTab === 'realAccounts'" id="tabpanelRealAccounts" role="tabpanel" aria-label="realAccounts">
                @include('frontend::.user.forex.include.__real_accounts')
            </div>
            <div x-cloak x-show="selectedTab === 'demoAccounts'" id="tabpanelDemoAccounts" role="tabpanel" aria-label="demoAccounts">
                @include('frontend::.user.forex.include.__demo_accounts')
            </div>
            <div x-cloak x-show="selectedTab === 'archivedAccounts'" id="tabpanelArchivedAccounts" role="tabpanel" aria-label="archivedAccounts">
                @include('frontend::.user.forex.include.__archive_accounts')
            </div>
        </div>
    </div>

    <h4 class="font-medium text-xl capitalize text-slate-900 my-5">
        {{ __('Download Platform') }}
    </h4>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($platformLinks as $platformLink)
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-4">
                <div class="flex items-center space-x-2">
                    <div class="flex-1 flex items-center space-x-2">
                        <div class="flex-none inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-white/90">
                            @switch($platformLink->os)
                                @case('window')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" fill="none">
                                        <path fill="currentColor" stroke="currentColor" stroke-linejoin="round" stroke-width="1.5" d="M13.027 10.507V5.122l7.453-1.235v6.62zm7.453 9.606L13.027 18.9v-5.405h7.453zM9.633 10.505H3.565V6.622l6.068-1.005zm0 7.907l-6.068-.989v-3.928h6.068z"/>
                                    </svg>
                                @break
                                @case('mac')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" fill="none">
                                        <path fill="currentColor" d="M2 20q-.825 0-1.412-.587T0 18h4q-.825 0-1.412-.587T2 16V5q0-.825.588-1.412T4 3h16q.825 0 1.413.588T22 5v11q0 .825-.587 1.413T20 18h4q0 .825-.587 1.413T22 20zm10-1q.425 0 .713-.288T13 18t-.288-.712T12 17t-.712.288T11 18t.288.713T12 19m-8-3h16V5H4zm0 0V5z"/>
                                    </svg>
                                @break
                                @case('android')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" fill="none">
                                        <path fill="currentColor" d="m3.637 3.434l8.74 8.571l-8.675 8.65a2.1 2.1 0 0 1-.326-.613a2.5 2.5 0 0 1 0-.755V4.567c-.026-.395.065-.79.26-1.133m12.506 4.833l-2.853 2.826L4.653 2.6c.28-.097.58-.124.873-.078c.46.126.899.32 1.302.573l7.816 4.325c.508.273 1.003.56 1.498.847M13.29 12.93l2.839 2.788l-2.058 1.146l-6.279 3.49c-.52.287-1.042.561-1.55.874a1.8 1.8 0 0 1-1.472.195zm7.36-.925a1.92 1.92 0 0 1-.99 1.72l-2.346 1.302l-3.087-3.022l3.1-3.074c.795.443 1.577.886 2.358 1.303a1.89 1.89 0 0 1 .964 1.771"/>
                                    </svg>
                                @break
                                @case('ios')
                                <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" fill="none">
                                    <path fill="currentColor" d="M17.05 20.28c-.98.95-2.05.8-3.08.35c-1.09-.46-2.09-.48-3.24 0c-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8c1.18-.24 2.31-.93 3.57-.84c1.51.12 2.65.72 3.4 1.8c-3.12 1.87-2.38 5.98.48 7.13c-.57 1.5-1.31 2.99-2.54 4.09zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25c.29 2.58-2.34 4.5-3.74 4.25"/>
                                </svg>
                                @break
                                @case('android_apk')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="-3 -2 24 24" fill="none">
                                        <path fill="currentColor" d="M1.247 6.51h-.051c-.656 0-1.19.537-1.19 1.19v5.183c0 .656.534 1.19 1.19 1.19h.052c.655 0 1.19-.535 1.19-1.19V7.7a1.195 1.195 0 0 0-1.191-1.19m1.76 8.373c0 .602.492 1.092 1.094 1.092h1.17v2.8c0 .657.535 1.191 1.19 1.191h.05c.657 0 1.192-.535 1.192-1.192v-2.799h1.634v2.8c0 .657.538 1.191 1.192 1.191h.05c.657 0 1.191-.535 1.191-1.192v-2.799h1.17c.601 0 1.093-.49 1.093-1.092V6.701H3.007zm8.259-13.145l.929-1.433a.197.197 0 1 0-.33-.215l-.963 1.483a6.3 6.3 0 0 0-2.38-.462a6.3 6.3 0 0 0-2.382.462L5.179.09a.197.197 0 0 0-.275-.058a.197.197 0 0 0-.058.273l.93 1.433C4.1 2.56 2.97 4.107 2.97 5.882q0 .164.016.323h11.07a4 4 0 0 0 .014-.323c0-1.775-1.13-3.322-2.805-4.144zM5.955 4.305a.532.532 0 1 1-.002-1.064a.532.532 0 0 1 .002 1.064m5.132 0a.532.532 0 1 1-.003-1.064a.532.532 0 0 1 .003 1.064m4.758 2.205h-.05c-.655 0-1.191.537-1.191 1.19v5.183c0 .656.537 1.19 1.191 1.19h.05c.657 0 1.191-.535 1.191-1.19V7.7c0-.654-.535-1.19-1.191-1.19"/>
                                    </svg>
                                @break
                                @case('web')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" fill="none">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a9 9 0 0 1 7.843 4.582M12 3a9 9 0 0 0-7.843 4.582m15.686 0A11.95 11.95 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.96 8.96 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.9 17.9 0 0 1 12 16.5a17.9 17.9 0 0 1-8.716-2.247m0 0A9 9 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"/>
                                    </svg>
                                @break
                                @default()
                                    <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" fill="none">
                                        <path fill="currentColor" stroke="currentColor" stroke-linejoin="round" stroke-width="1.5" d="M13.027 10.507V5.122l7.453-1.235v6.62zm7.453 9.606L13.027 18.9v-5.405h7.453zM9.633 10.505H3.565V6.622l6.068-1.005zm0 7.907l-6.068-.989v-3.928h6.068z"/>
                                    </svg>
                            @endswitch
                        </div>
                        <div class="flex-1">
                            <span class="block text-slate-600 text-theme-sm font-semibold dark:text-slate-300">
                                {{ $platformLink->title }}
                            </span>
                            <span class="block font-normal text-theme-xs text-slate-500">
                                {{ __('for') . ' ' . $platformLink->os }}
                            </span>
                        </div>
                    </div>
                    <div class="flex-none leading-none">
                        <a href="{{ $platformLink->link }}" class="inline-flex items-center text-theme-sm dark:text-gray-300" target="_blank">
                            <i data-lucide="chevron-right" class="w-5"></i>
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
@endsection
@section('style')
    <style>
        /* Default state - Grid View */
        #trading-accounts .list-view-layout {
            display: none;
        }
        
        #trading-accounts .grid-view-layout {
            display: block;
        }

        /* List View Mode */
        #trading-accounts.list-view .grid-view-layout {
            display: none;
        }
        
        #trading-accounts.list-view .list-view-layout {
            display: block;
        }

        /* Grid View Mode (explicit) */
        #trading-accounts.grid-view .list-view-layout {
            display: none;
        }
        
        #trading-accounts.grid-view .grid-view-layout {
            display: block;
        }

        #trading-accounts.list-view .dropdown-menu .dropdown-header {
            display: none !important;
        }

        #trading-accounts.grid-view .list-view-dropdown-btn, #trading-accounts.list-view .grid-view-dropdown-btn {
            display: none !important;
        }

    </style>
@endsection