{{-- Alpine.js Base Modal Component --}}
{{-- Usage: Include this in each modal file and customize the content --}}

<div x-show="$store.forexModals.{{ $modalName ?? 'modalName' }}" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-y-auto"
     x-on:keydown.escape.window="$store.forexModals.close('{{ $modalName ?? 'modalName' }}')"
     style="display: none;">
    
    {{-- Modal backdrop --}}
    <div class="fixed inset-0 bg-black bg-opacity-50" 
         x-on:click="$store.forexModals.close('{{ $modalName ?? 'modalName' }}')"></div>
    
    {{-- Modal container --}}
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl {{ $maxWidth ?? 'max-w-md' }} w-full dark:bg-gray-800"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95">
            
            {{-- Modal header --}}
            <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-gray-600">
                <div>
                    <h3 class="text-xl font-medium dark:text-white">
                        {{ $title ?? 'Modal Title' }}
                    </h3>
                    @if(isset($subtitle))
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $subtitle }}
                        </p>
                    @endif
                </div>
                <button x-on:click="$store.forexModals.close('{{ $modalName ?? 'modalName' }}')" 
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                    <span class="sr-only">{{ __('Close modal') }}</span>
                </button>
            </div>
            
            {{-- Modal body --}}
            <div class="p-6">
                {{-- Loading state --}}
                <div x-show="$store.forexModals.loading" class="text-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Loading...') }}</p>
                </div>
                
                {{-- Error state --}}
                <div x-show="$store.forexModals.error && !$store.forexModals.loading" 
                     class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 dark:bg-red-900 dark:border-red-700 dark:text-red-300">
                    <div class="flex items-center">
                        <i data-lucide="alert-circle" class="w-5 h-5 mr-2"></i>
                        <span x-text="$store.forexModals.error"></span>
                    </div>
                </div>
                
                {{-- Modal content --}}
                <div x-show="!$store.forexModals.loading">
                    {{ $slot ?? '' }}
                </div>
            </div>
            
            {{-- Modal footer (optional) --}}
            @if(isset($footer))
                <div class="flex items-center justify-end gap-3 p-5 border-t dark:border-gray-600">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>