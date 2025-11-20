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
                        <button class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 transition-colors"
                            @click.prevent="$store.modals.open('accountDetails', {
                                method_name: '{{ $account->method_name }}',
                                method_currency: '{{ $account->method->currency }}',
                                status: '{{ $account->status_label }}',
                                credentials: @js($account->credentials),
                                created_at: '{{ $account->created_at ? $account->created_at->format('M d, Y h:i A') : 'N/A' }}',

                            })">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                        <button class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:text-error-600 hover:bg-error-50 dark:text-gray-400 dark:hover:text-error-400 dark:hover:bg-error-900/20 transition-colors"
                            @click="$store.modals.open('deleteAccount', {
                                id: '{{ the_hash($account->id) }}',
                            })">
                            <i data-lucide="trash" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Delete Account Modal -->
    @include('frontend::withdraw.account.modal.__delete_account')

    <!-- Account Details Modal -->
    @include('frontend::withdraw.account.modal.__account_details')

@endsection

@section('script')
    <script type="text/javascript">
        document.addEventListener('alpine:init', () => {
            Alpine.store('modals', {
                current: null,
                data: {},
                html: '',
                loading: false,

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
                },

                async deleteAccount(refs) {
                    const submitBtn = refs.submitBtn;
                    const accountId = refs.id.value;
                    
                    if (!accountId) {
                        if (typeof tNotify === 'function') {
                            tNotify('error', '{{ __("Invalid account ID") }}');
                        }
                        return;
                    }

                    // Store original button content
                    const originalContent = submitBtn.innerHTML;
                    
                    // Show loading state
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __("Deleting...") }}
                    `;

                    try {
                        const formData = new FormData();
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('_method', 'DELETE');

                        const response = await fetch(`{{ route('user.withdraw.account.index') }}/${accountId}`, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        let result;
                        const contentType = response.headers.get('content-type');
                        
                        // Handle JSON response
                        if (contentType && contentType.includes('application/json')) {
                            result = await response.json();
                        } else {
                            // Handle non-JSON response (redirect or HTML)
                            result = {
                                success: response.ok,
                                message: response.ok ? '{{ __("Withdraw account deleted successfully") }}' : '{{ __("Failed to delete account") }}'
                            };
                        }

                        if (response.ok && (result.success !== false)) {
                            // Close modal
                            this.close();

                            // Show success notification
                            if (typeof tNotify === 'function') {
                                tNotify('success', result.message || '{{ __("Withdraw account deleted successfully") }}');
                            }

                            // Reload page after a short delay
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            throw new Error(result.message || '{{ __("Failed to delete account") }}');
                        }
                    } catch (error) {
                        console.error('Delete error:', error);
                        
                        // Reset button state
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalContent;

                        // Show error notification
                        if (typeof tNotify === 'function') {
                            tNotify('error', error.message || '{{ __("Error deleting withdraw account") }}');
                        }
                    }
                },
            });
        });
    </script>
@endsection