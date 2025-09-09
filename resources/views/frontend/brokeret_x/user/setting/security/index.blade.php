@extends('frontend::user.setting.index')
@section('title')
    {{ __('Settings') }}
@endsection
@section('settings-content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
        <div>
            <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90 mb-4">
                {{ __('Authorization') }}
            </h2>
            <p class="text-gray-800 text-theme-sm dark:text-white/90">
                {{ __('Information for logging in to :site.', ['site' => setting('site_title', 'global')]) }}
            </p>
            <p class="mt-0.5 text-gray-800 text-theme-sm dark:text-white/90">
                {{ __('Change your password whenever you think it might have been compromised.') }}
            </p>
        </div>
    </div>
    <div class="border border-gray-200 dark:border-gray-800 mb-10">
        <div class="flex items-center justify-between border-b border-gray-100 px-4 py-5 last:border-b-0 dark:border-gray-800">
            <p class="text-gray-800 text-theme-sm dark:text-white/90">
                {{ __('Login') }}
            </p>
            <p class="text-gray-800 text-theme-sm font-medium dark:text-white/90">
                {{ $user->email }}
            </p>
            <x-frontend::forms.button type="button" variant="secondary" size="md" @click="$store.modals.open('emailEdit', {email: '{{ $user->email }}'})">
                {{ __('Change') }}
            </x-frontend::forms.button>
        </div>
        <div class="flex items-center justify-between border-b border-gray-100 px-4 py-5 last:border-b-0 dark:border-gray-800">
            <p class="text-gray-800 text-theme-sm dark:text-white/90">
                {{ __('Password') }}
            </p>
            <p class="text-gray-800 text-theme-sm font-medium dark:text-white/90">
                {{ __('********') }}
            </p>
            <x-frontend::forms.button type="button" variant="secondary" size="md" @click="$store.modals.open('passwordChange')">
                {{ __('Change') }}
            </x-frontend::forms.button>
        </div>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
        <div>
            <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90 mb-4">
                {{ __('2-Step verification') }}
            </h2>
            <p class="text-gray-800 text-theme-sm dark:text-white/90">
                {{ __('2-step verification ensures that all sensitive transactions are authorized by you.') }}
            </p>
            <p class="mt-0.5 text-gray-800 text-theme-sm dark:text-white/90">
                {{ __('We encourage you to enter verification codes to confirm these transactions.') }}
            </p>
        </div>
    </div>
    <div class="border border-gray-200 dark:border-gray-800 mb-10">
        <div class="flex items-center justify-between border-b border-gray-100 px-4 py-5 last:border-b-0 dark:border-gray-800">
            <p class="text-gray-800 text-theme-sm dark:text-white/90">
                {{ __('Security type') }}
            </p>
            <p class="text-gray-800 text-theme-sm font-medium dark:text-white/90">
                {{ $user->email }}
            </p>
            <x-frontend::forms.button type="button" variant="secondary" size="md" @click="$store.modals.open('emailEdit', {email: '{{ $user->email }}'})">
                {{ __('Change') }}
            </x-frontend::forms.button>
        </div>
    </div>
    
    <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
        <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90">
            {{ __('Account security and termination') }}
        </h2>
    </div>
    <div class="border border-gray-200 dark:border-gray-800 mb-10">
        <div class="flex items-center justify-between border-b border-gray-100 px-4 py-5 last:border-b-0 dark:border-gray-800">
            <p class="text-gray-800 text-theme-sm dark:text-white/90">
                {{ __('Log out from all other devices except this one to secure your account.') }}
            </p>
            <x-frontend::forms.button type="button" variant="secondary" size="md" icon="logout" iconPosition="left">
                {{ __('Log out from other devices') }}
            </x-frontend::forms.button>
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
