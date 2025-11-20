@extends('frontend::layouts.user')
@section('title')
    {{ __('Open account') }}
@endsection
@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('user.forex-account-logs') }}" class="size-10 relative inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded border border-transparent text-gray-800 hover:bg-gray-100 hover:border-gray-600 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                <i data-lucide="arrow-left" class="shrink-0 size-5"></i>
                <span class="sr-only">{{ __('Back to accounts') }}</span>
            </a>
            <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90">
                @yield('title')
            </h2>
        </div>
    </div>

    <!-- Desktop Table View (hidden on mobile) -->
    <div class="hidden md:block overflow-hidden">
        @if($schemas->isEmpty())
            <x-frontend::empty-state icon="inbox">
                <x-slot name="subtitle">
                    {{ __('No account type found') }}
                </x-slot>
                <x-slot name="actions">
                    <x-frontend::link-button href="{{ route('user.ticket.index') }}" variant="primary" size="md" icon="headset" icon-position="left">
                        {{ __('Contact Support') }}
                    </x-frontend::link-button>
                </x-slot>
            </x-frontend::empty-state>
        @else
            <div class="max-w-full overflow-x-auto">
                <table class="min-w-full border-separate border-spacing-3">
                    <!-- table body start -->
                    <tbody class="">
                        <tr>
                            <td class="pe-5 pt-2 pb-1 sm:pe-6">
                                <div class="min-w-max text-nowrap text-gray-800 dark:text-white/90">
                                    {{ __('Account type')}}
                                </div>
                            </td>
                            <td class="px-5 pt-2 pb-1 sm:px-6">
                                <div class="min-w-max text-nowrap text-gray-500 text-theme-xs dark:text-gray-400">
                                    {{ __('Min deposit') }}
                                </div>
                            </td>
                            <td class="px-5 pt-2 pb-1 sm:px-6">
                                <div class="min-w-max text-nowrap text-gray-500 text-theme-xs dark:text-gray-400">
                                    {{ __('Min spread') }}
                                </div>
                            </td>
                            <td class="px-5 pt-2 pb-1 sm:px-6">
                                <div class="min-w-max text-nowrap text-gray-500 text-theme-xs dark:text-gray-400">
                                    {{ __('Max leverage') }}
                                </div>
                            </td>
                            <td class="px-5 pt-2 pb-1 sm:px-6">
                                <div class="min-w-max text-nowrap text-gray-500 text-theme-xs dark:text-gray-400">
                                    {{ __('Commission') }}
                                </div>
                            </td>
                        </tr>
                        @foreach($schemas as $schema)
                            <tr class="outline outline-1 outline-gray-200 cursor-pointer dark:outline-gray-800 rounded-lg hover:shadow-md transition-all duration-300" 
                                x-data 
                                @click="window.location.href = '{{ route('user.schema.preview', the_hash($schema->id)) }}'">
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex gap-3">
                                        <div class="flex-shrink-0 w-12 h-12 overflow-hidden">
                                            <img src="{{ asset($schema->icon) }}" alt="brand">
                                        </div>

                                        <div class="flex flex-col gap-1 max-w-[356px]">
                                            <span class="font-medium text-gray-800 text-theme-lg dark:text-white/90">
                                                {{ $schema->title }}
                                            </span>
                                            <span class="w-full max-w-max text-gray-500 text-theme-sm dark:text-gray-400">
                                                {!! $schema->desc !!}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ isset($schema->first_min_deposit) ? $currencySymbol . $schema->first_min_deposit : $currencySymbol . 0 }}
                                    </p>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ $schema->spread }}
                                    </p>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ $schema->leverage }}
                                    </p>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ $schema->commission }}
                                    </p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Mobile Carousel View (visible only on mobile) -->
    <div x-data="{swiper: null}"
        x-init="swiper = new Swiper($refs.container, {
            loop: true,
            slidesPerView: 1,
            spaceBetween: 10,
            
            pagination: {
                el: $refs.pagination,
                clickable: true,
            },
        })"
        class="md:hidden schema-swiper relative w-full max-w-full mb-5">
        
        <div class="swiper w-full" x-ref="container">
            <div class="swiper-wrapper">
                @if($schemas->isEmpty())
                    <div class="swiper-slide">
                        <x-frontend::empty-state icon="inbox">
                            <x-slot name="subtitle">
                                {{ __('No account type found') }}
                            </x-slot>
                        </x-frontend::empty-state>
                    </div>
                @else
                @foreach($schemas as $schema)
                    <div class="swiper-slide">
                        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/3" 
                            x-data 
                            @click="window.location.href = '{{ route('user.schema.preview', the_hash($schema->id)) }}'">
                            <div class="text-center mb-5">
                                <div class="flex items-center justify-center mb-5">
                                    <img src="{{ asset($schema->icon) }}" alt="{{ $schema->title }}" class="h-32">
                                </div>
                                <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90 mb-2">
                                    {{ $schema->title }}
                                </h2>
                                <x-frontend::badge variant="light" style="light" size="sm" class="mb-2">
                                    {{ $schema->badge }}
                                </x-frontend::badge>
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400 text-center">
                                    {!! $schema->desc !!}
                                </p>
                            </div>
                            <div class="flex flex-col flex-auto self-stretch">
                                <div class="grid [grid-template-columns:1fr_min-content]">
                                    <div class="flex align-items-baseline text-theme-sm text-gray-600 py-2 dark:text-gray-300 after:content-[''] after:flex-auto after:min-w-2 after:border-b after:border-dashed after:border-gray-300">
                                        {{ __('Min deposit') }}
                                    </div>
                                    <div class="whitespace-nowrap text-right text-gray-600 text-theme-sm py-2 dark:text-gray-300">
                                        {{ isset($schema->first_min_deposit) ? $currencySymbol . $schema->first_min_deposit : $currencySymbol . 0 }}
                                    </div>
                                </div>
                                <div class="grid [grid-template-columns:1fr_min-content]">
                                    <div class="flex align-items-baseline text-theme-sm text-gray-600 py-2 dark:text-gray-300 after:content-[''] after:flex-auto after:min-w-2 after:border-b after:border-dashed after:border-gray-300">
                                        {{ __('Min spread') }}
                                    </div>
                                    <div class="whitespace-nowrap text-right text-gray-600 text-theme-sm py-2 dark:text-gray-300">
                                        {{ $schema->spread }}
                                    </div>
                                </div>
                                <div class="grid [grid-template-columns:1fr_min-content]">
                                    <div class="flex align-items-baseline text-theme-sm text-gray-600 py-2 dark:text-gray-300 after:content-[''] after:flex-auto after:min-w-2 after:border-b after:border-dashed after:border-gray-300">
                                        {{ __('Max leverage') }}
                                    </div>
                                    <div class="whitespace-nowrap text-right text-gray-600 text-theme-sm py-2 dark:text-gray-300">
                                        {{ $schema->leverage }}
                                    </div>
                                </div>
                                <div class="grid [grid-template-columns:1fr_min-content]">
                                    <div class="flex align-items-baseline text-theme-sm text-gray-600 py-2 dark:text-gray-300 after:content-[''] after:flex-auto after:min-w-2 after:border-b after:border-dashed after:border-gray-300">
                                        {{ __('Commission') }}
                                    </div>
                                    <div class="whitespace-nowrap text-right text-gray-600 text-theme-sm py-2 dark:text-gray-300">
                                        {{ $schema->commission }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination" x-ref="pagination"></div>
    </div>
@endsection
@section('style')
    <style>
        .schema-swiper .swiper-pagination {
            bottom: -20px !important;
        }
    </style>
@endsection