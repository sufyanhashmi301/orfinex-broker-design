@extends('frontend::layouts.user')
@section('title')
    {{ __('My Accounts') }}
@endsection
@section('content')

    <!-- Banner Section -->
    @if($banners->count() > 0)
    <div x-data="{swiper: null}"
        x-init="swiper = new Swiper($refs.container, {
            loop: true,
            slidesPerView: 1,
            spaceBetween: 10,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
        })"
        class="banner-swiper relative w-full max-w-full mb-12">
        
        <div class="swiper w-full" x-ref="container">
            <div class="swiper-wrapper items-stretch">
                @foreach($banners as $banner)
                    <div class="swiper-slide flex-grow !flex !h-auto">
                        <div class="flex gap-2 border border-gray-100 rounded bg-success-50/50 dark:border-gray-800 w-full h-full">
                            <div class="flex-grow-1 flex flex-col gap-1 p-4">
                                <h4 class="text-theme-sm font-medium">
                                    {{ $banner->title }}
                                </h4>
                                <p class="text-theme-xs">
                                    {{ $banner->subtitle }}
                                </p>
                            </div>
                            <div class="w-[100px] flex-shrink-0 flex-grow-0 bg-no-repeat bg-cover bg-center" style="background-image: url({{ asset($banner->image) }})"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
        <!-- Add Navigation -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>
    @endif

    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90">
            @yield('title')
        </h2>

        <x-frontend::link-button href="{{route('user.schema')}}" variant="secondary" icon="plus" iconPosition="left">
            {{ __('Open New Account') }}
        </x-frontend::link-button>
    </div>

    <div x-data="{ 
            selectedTab: 'realAccounts', 
            viewMode: 'grid',
            init() {
                this.$watch('viewMode', (value) => {
                    this.$dispatch('view-mode-changed', { mode: value });
                });
            }
        }">
        <div class="border-b border-gray-200 dark:border-gray-800 mb-6">
            <nav class="-mb-px flex" role="tablist" aria-label="tab options">
                <button type="button" 
                    class="inline-flex items-center justify-center text-base border-b-3 px-4 py-3 transition-colors duration-200"
                    x-on:click="selectedTab = 'realAccounts'" 
                    x-bind:aria-selected="selectedTab === 'realAccounts'" 
                    x-bind:tabindex="selectedTab === 'realAccounts' ? '0' : '-1'" 
                    x-bind:class="selectedTab === 'realAccounts'
                        ? 'dark:text-white/90 border-brand-500 dark:border-brand-400' 
                        : 'text-gray-500 border-transparent hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'">
                    {{ __('Real') }}            
                </button>
                <button type="button" 
                    class="inline-flex items-center justify-center text-base border-b-3 px-4 py-3 transition-colors duration-200"
                    x-on:click="selectedTab = 'demoAccounts'" 
                    x-bind:aria-selected="selectedTab === 'demoAccounts'" 
                    x-bind:tabindex="selectedTab === 'demoAccounts' ? '0' : '-1'" 
                    x-bind:class="selectedTab === 'demoAccounts'
                        ? 'dark:text-white/90 border-brand-500 dark:border-brand-400' 
                        : 'text-gray-500 border-transparent hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'">
                    {{ __('Demo') }}
                </button>
                @if($archiveForexAccounts->count() > 0)
                    <button type="button" 
                        class="inline-flex items-center justify-center text-base border-b-3 px-4 py-3 transition-colors duration-200"
                        x-on:click="selectedTab = 'archivedAccounts'" 
                        x-bind:aria-selected="selectedTab === 'archivedAccounts'" 
                        x-bind:tabindex="selectedTab === 'archivedAccounts' ? '0' : '-1'" 
                        x-bind:class="selectedTab === 'archivedAccounts'
                            ? 'dark:text-white/90 border-brand-500 dark:border-brand-400' 
                            : 'text-gray-500 border-transparent hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'">
                        {{ __('Archived') }}
                    </button>
                @endif
            </nav>
        </div>
        <div class="flex flex-row gap-5 justify-between items-center mb-6">
            <div class="relative">
                <x-frontend::forms.select name="" size="sm" class="min-w-[200px]" :options="['newest' => __('Newest'), 'oldest' => __('Oldest'), 'free-margin' => __('Free margin'), 'nickname' => __('Nickname')]" />
            </div>
            <div class="inline-flex items-center shadow-theme-xs px-1 py-0.5 border border-gray-300 rounded-lg">
                <button 
                    type="button" 
                    x-on:click="viewMode = 'grid'"
                    x-bind:class="viewMode === 'grid' ? 'bg-gray-100 text-gray-700' : 'text-gray-800 hover:bg-gray-50 dark:text-gray-200 dark:hover:bg-white/[0.03]'"
                    class="inline-flex items-center gap-2 p-2 text-sm font-medium transition rounded-lg dark:bg-white/[0.03]">
                    <i data-lucide="layout-grid" class="w-4 h-4"></i>
                </button>
                <button 
                    type="button" 
                    x-on:click="viewMode = 'list'"
                    x-bind:class="viewMode === 'list' ? 'bg-gray-100 text-gray-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]'"
                    class="-ml-px inline-flex items-center gap-2 p-2 text-sm font-medium transition rounded-lg dark:bg-transparent">
                    <i data-lucide="layout-list" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
        
        <div class="space-y-8 mt-7 tab-content" id="trading-accounts" x-bind:class="viewMode === 'grid' ? 'grid-view' : 'list-view'">
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
        .banner-swiper .swiper-button-next {
            right: -10px !important;
        }
        .banner-swiper .swiper-button-prev {
            left: -10px !important;
        }
        .banner-swiper .swiper-button-next, .banner-swiper .swiper-button-prev {
            width: 32px !important;
            height: 32px !important;
        }

        .banner-swiper .swiper-pagination {
            bottom: -20px !important;
        }

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