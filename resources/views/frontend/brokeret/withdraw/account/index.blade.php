@extends('frontend::user.setting.index')
@section('title')
    {{ __('Withdraw Accounts') }}
@endsection
@section('settings-content')
    
    @if(count($accounts) == 0)
        <!-- Empty State -->
        <div class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-5">
            <div class="text-center py-12">
                <div class="inline-flex h-20 w-20 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="2.5em" height="2.5em" viewBox="0 0 24 24" fill="none" class="text-gray-400 dark:text-gray-500">
                        <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <path d="M19 7V6a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v1m0 0v3a2 2 0 0 1-2 2H9.5a1 1 0 0 1-.8-.4L6 12"/>
                            <path d="M3 14v1a2 2 0 0 0 2 2h2"/>
                            <circle cx="7" cy="7" r="1"/>
                        </g>
                    </svg>
                </div>
                <h4 class="text-xl font-semibold text-gray-800 dark:text-white/90 mb-3">
                    {{ __("You're almost ready to withdraw!") }}
                </h4>
                <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto">
                    {{ __('To make a withdraw, please add a withdraw account from your profile (withdraw accounts).') }}
                </p>
                <a href="{{ route('user.withdraw.account.create') }}" class="inline-flex items-center justify-center rounded-lg bg-brand-600 px-6 py-3 text-sm font-medium text-white transition-colors hover:bg-brand-700 dark:bg-brand-600 dark:hover:bg-brand-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m-7-7h14"/>
                    </svg>
                    {{ __('Add Withdraw Account') }}
                </a>
            </div>
        </div>
    @else
        <div class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-5">
            <div class="flex flex-col gap-5 mb-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                        {{ __('Withdraw Accounts') }}
                    </h3>
                    <p class="mt-1 text-gray-500 text-theme-sm dark:text-gray-400">
                        {{ __("Manage your withdrawal payment methods and account details.") }}
                    </p>
                </div>
                @if(count($accounts) > 0)
                    <div class="flex items-center gap-3">
                        <x-link-button href="{{ route('user.withdraw.account.create') }}" variant="primary" icon="plus" icon-position="left">
                            {{ __('Add New') }}
                        </x-link-button>
                    </div>
                @endif
            </div>

            <!-- Accounts List -->
            <div class="space-y-2">
                @foreach($accounts as $account)
                    <div class="flex items-center gap-4 rounded-lg border border-gray-100 bg-white p-4 hover:border-gray-200 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800/50 dark:hover:border-gray-600 dark:hover:bg-gray-800 transition-all duration-200">
                        <!-- Account Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3">
                                <h3 class="text-gray-800 dark:text-white/90 font-medium truncate">
                                    {{ $account->method_name }}
                                </h3>
                                <!-- Category Badge -->
                                <x-badge variant="light" style="light" size="sm">
                                    {{ $account->method->currency }}
                                </x-badge>
                            </div>
                        </div>

                        <!-- Account Info -->
                        <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                            <!-- Date Added (placeholder) -->
                            <div class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 2v4m8-4v4M3 10h18M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2"/>
                                </svg>
                                <span>{{ $account->created_at ? $account->created_at->format('M d, Y') : 'Today' }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-2">
                            <a href="{{ route('user.withdraw.account.edit', the_hash($account->id)) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 transition-colors">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </a>
                            <button class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 dark:text-gray-400 dark:hover:text-red-400 dark:hover:bg-red-900/20 transition-colors">
                                <i data-lucide="trash" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection
