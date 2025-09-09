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

    <div class="overflow-hidden">
        <div class="max-w-full overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-3">
                <!-- table body start -->
                <tbody class="">
                    <tr>
                        <td class="pe-5 pt-2 pb-1 sm:pe-6">
                            <div class="flex items-center">
                                <p class="text-nowrap text-gray-800 dark:text-white/90">
                                    {{ __('Account type')}}
                                </p>
                            </div>
                        </td>
                        <td class="px-5 pt-2 pb-1 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-nowrap text-gray-500 text-theme-xs dark:text-gray-400">
                                    {{ __('Min deposit') }}
                                </p>
                            </div>
                        </td>
                        <td class="px-5 pt-2 pb-1 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-nowrap text-gray-500 text-theme-xs dark:text-gray-400">
                                    {{ __('Min spread') }}
                                </p>
                            </div>
                        </td>
                        <td class="px-5 pt-2 pb-1 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-nowrap text-gray-500 text-theme-xs dark:text-gray-400">
                                    {{ __('Max leverage') }}
                                </p>
                            </div>
                        </td>
                        <td class="px-5 pt-2 pb-1 sm:px-6">
                            <div class="flex items-center">
                                <p class="text-nowrap text-gray-500 text-theme-xs dark:text-gray-400">
                                    {{ __('Commission') }}
                                </p>
                            </div>
                        </td>
                    </tr>
                    @foreach($schemas as $schema)
                        <tr class="outline outline-1 outline-gray-200 cursor-pointer dark:outline-gray-800 rounded-lg hover:shadow-md transition-all duration-300" 
                            x-data 
                            @click="window.location.href = '{{ route('user.schema.preview', the_hash($schema->id)) }}'">
                            <td class="px-5 py-4 sm:px-6">
                                <div class="flex gap-3">
                                    <div class="w-12 h-12 overflow-hidden">
                                        <img src="{{ asset($schema->icon) }}" alt="brand">
                                    </div>

                                    <div>
                                        <span class="block font-medium text-gray-800 text-theme-lg dark:text-white/90">
                                            {{ $schema->title }}
                                        </span>
                                        <span class="block text-gray-500 text-theme-sm dark:text-gray-400">
                                            {!! $schema->desc !!}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 sm:px-6">
                                <div class="flex items-center">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ isset($schema->first_min_deposit) ? $currencySymbol . $schema->first_min_deposit : $currencySymbol . 0 }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-5 py-4 sm:px-6">
                                <div class="flex items-center">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ $schema->spread }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-5 py-4 sm:px-6">
                                <div class="flex items-center">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ $schema->leverage }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-5 py-4 sm:px-6">
                                <div class="flex items-center">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ $schema->commission }}
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
