@extends('frontend::user.setting.index')
@section('title')
    {{ __('Create Withdraw Account') }}
@endsection
@section('settings-content')
    <div class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-5">
        <div class="flex flex-col gap-5 mb-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    {{ __('Create Withdraw Account') }}
                </h3>
                <p class="mt-1 text-gray-500 text-theme-sm dark:text-gray-400">
                    {{ __("Set up a new withdrawal payment method for your account.") }}
                </p>
            </div>
        </div>

        <div x-data="withdrawMethodSelector()">
            <form action="{{ route('user.withdraw.account.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                
                <!-- Form Fields -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 selectMethodRow">
                    <div class="input-area selectMethodCol">
                        <label for="selectMethod" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            {{ __('Choose Payment Method') }}
                        </label>
                        <select name="withdraw_method_id" 
                            id="selectMethod"
                            x-model="selectedMethodId"
                            @change="loadMethodFields"
                            :disabled="loading"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            :class="{ 'opacity-50 cursor-not-allowed': loading }">
                            <option value="">{{ __('Select Payment Method') }}</option>
                            @foreach($withdrawMethods as $method)
                                <option value="{{ $method->id }}">
                                    {{ $method->name }} ({{ ucwords($method->type) }})
                                </option>
                            @endforeach
                        </select>
                        
                        <!-- Loading indicator -->
                        <div x-show="loading" class="mt-2 flex items-center text-sm text-gray-500">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ __('Loading payment method details...') }}
                        </div>
                        
                        <!-- Error message -->
                        <div x-show="error" x-text="error" class="mt-2 text-sm text-red-500"></div>
                    </div>
                </div>
                
                <!-- Dynamic method fields container with proper grid layout -->
                <template x-if="methodFields">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mt-6" x-html="methodFields"></div>
                </template>

                <!-- Submit Button -->
                <div class="buttons text-right mt-6">
                    <x-forms.button type="submit" size="lg" variant="primary" icon="square-pen" icon-position="left">
                        {{ __('Create Withdraw Account') }}
                    </x-forms.button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function withdrawMethodSelector() {
            return {
                selectedMethodId: '',
                methodFields: null,
                loading: false,
                error: null,
                
                async loadMethodFields() {
                    // Clear previous state
                    this.methodFields = null;
                    this.error = null;
                    
                    // If no method selected, return early
                    if (!this.selectedMethodId) {
                        return;
                    }
                    
                    this.loading = true;
                    
                    try {
                        const url = '{{ route("user.withdraw.method", ":id") }}'.replace(':id', this.selectedMethodId);
                        const response = await fetch(url, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'text/html'
                            }
                        });
                        
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }
                        
                        const data = await response.text();
                        
                        if (data && data.trim()) {
                            this.methodFields = data;
                            
                            // Re-initialize file preview functionality after DOM update
                            this.$nextTick(() => {
                                if (typeof imagePreview === 'function') {
                                    imagePreview();
                                }
                            });
                        } else {
                            this.error = '{{ __("No payment method details available.") }}';
                        }
                        
                    } catch (error) {
                        console.error('Failed to load payment method fields:', error);
                        this.error = '{{ __("Failed to load payment method details. Please try again.") }}';
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
@endsection
