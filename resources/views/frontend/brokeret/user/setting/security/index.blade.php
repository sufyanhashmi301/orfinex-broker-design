@extends('frontend::user.setting.index')
@section('title')
    {{ __('Security Settings') }}
@endsection
@section('settings-content')

    <div class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6">
        <div class="flex flex-col gap-5 mb-6 sm:flex-row sm:justify-between">
            <div class="w-full">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">{{ __('Security Settings') }}</h4>
                <p class="mt-1 text-gray-500 text-theme-sm dark:text-gray-400">
                    {{ __("Strengthen Your Online Security: It's your primary defense.") }}
                </p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/5 flex flex-col h-full">
                <div>
                    <p class="font-normal text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Security') }}</p>
                    <h4 class="text-lg font-semibold text-gray-800 mb-3 dark:text-white/90">{{ __('Authorization') }}</h4>
                </div>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Information for logging in to :site.', ['site' => setting('site_title', 'global')]) }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Change your password whenever you think it might have been compromised.') }}
                        </p>
                    </div>
                    <div class="input-area w-full mb-3">
                        <div class="relative">
                            <input type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ $user->email }}" disabled>
                            <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                <button type="button"
                                @click="$store.modals.open('emailEdit', {email: '{{ $user->email }}'})" 
                                class="text-sm text-brand-500 hover:text-brand-600 dark:text-brand-400">
                                    {{ __('Change') }}
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="input-area w-full">
                        <div class="relative">
                            <input type="password" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="12345678" disabled>
                            <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                <button type="button"
                                @click="$store.modals.open('passwordChange')" 
                                class="text-sm text-brand-500 hover:text-brand-600 dark:text-brand-400">
                                    {{ __('Change') }}
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-auto w-full">
                    <x-link-button href="" class="w-full mt-5" variant="primary" size="lg">
                        {{ __('Update') }}
                    </x-link-button>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-theme-sm dark:border-gray-800 dark:bg-white/5 flex flex-col h-full">
                <div>
                    <p class="font-normal text-sm text-gray-500 dark:text-gray-400 mb-1">{{ __('Security') }}</p>
                    <h4 class="text-lg font-semibold text-gray-800 mb-3 dark:text-white/90">{{ __('2-Step verification') }}</h4>
                </div>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('2-step verification ensures that all sensitive transactions are authorized by you.') }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('We encourage you to enter verification codes to confirm these transactions.') }}
                        </p>
                    </div>
                    <div class="input-area w-full">
                        <label for="" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            {{ __('Security type') }}
                        </label>
                        <div class="relative">
                            <input type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{ $user->email }}">
                            <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                <a href="" class="text-sm text-brand-500 hover:text-brand-600 dark:text-brand-400">
                                    {{ __('Change') }}
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-auto w-full">
                    <x-link-button href="{{ route('user.change.password') }}" class="w-full mt-5" variant="primary" size="lg">
                        {{ __('Update') }}
                    </x-link-button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for email change -->
    @include('frontend::user.setting.security.modal.__edit_email')

    <!-- Modal for password change -->
    @include('frontend::user.setting.security.modal.__change_password')

@endsection

@section('script')
<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        // Initialize modals store if not already exists
        if (!Alpine.store('modals')) {
            Alpine.store('modals', {
                current: null,
                data: {},
                
                open(modalName, payload = {}) {
                    this.current = modalName;
                    this.data = payload;
                    
                    Alpine.nextTick(() => {
                        if (window.lucide) lucide.createIcons();
                    });
                },
                
                close() {
                    this.current = null;
                    this.data = {};
                },
                
                isOpen(modalName) {
                    return this.current === modalName;
                }
            });
        }
    });
</script>
@endsection
