@extends('frontend::user.setting.index')
@section('title')
    {{ __('Settings') }}
@endsection
@section('settings-content')
    @if(count($accounts) == 0)
        <!-- Empty State -->
        <x-frontend::empty-state icon="inbox">
            <x-slot name="title">
                {{ __("You're almost ready to withdraw!") }}
            </x-slot>
            <x-slot name="subtitle">
                {{ __('To make a withdraw, please add a withdraw account from your profile (withdraw accounts).') }}
            </x-slot>
            <x-slot name="actions">
                <x-frontend::link-button href="{{ route('user.withdraw.account.create') }}" variant="primary" size="md" icon="plus" icon-position="left">
                    {{ __('Add Withdraw Account') }}
                </x-frontend::link-button>
            </x-slot>
        </x-frontend::empty-state>
    @else
        <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
            <div>
                <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90 mb-1">
                    {{ __('Withdraw Accounts') }}
                </h2>
                <p class="text-gray-800 text-theme-sm dark:text-white/90">
                    {{ __('Manage your withdrawal payment methods and account details.') }}
                </p>
            </div>
            @if(count($accounts) > 0)
                <div class="flex items-center gap-3">
                    <x-frontend::link-button href="{{ route('user.withdraw.account.create') }}" variant="secondary" icon="plus" icon-position="left">
                        {{ __('Add New') }}
                    </x-frontend::link-button>
                </div>
            @endif
        </div>
        <div class="rounded-lg border border-gray-200 dark:border-gray-800">
            <!-- Accounts List -->
            @foreach($accounts as $account)
                <div class="flex items-center justify-between gap-3 border-b border-gray-100 px-4 py-5 last:border-b-0 dark:border-gray-800">
                    <!-- Account Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3">
                            <h3 class="text-gray-800 dark:text-white/90 font-medium truncate">
                                {{ $account->method_name }}
                            </h3>
                            <!-- Category Badge -->
                            <x-frontend::badge variant="light" style="light" size="sm">
                                {{ $account->method->currency }}
                            </x-frontend::badge>
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
                        <button class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-error-600 hover:bg-error-50 dark:text-gray-400 dark:hover:text-error-400 dark:hover:bg-error-900/20 transition-colors">
                            <i data-lucide="trash" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
