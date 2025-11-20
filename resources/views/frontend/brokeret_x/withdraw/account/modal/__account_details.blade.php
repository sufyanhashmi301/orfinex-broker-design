<div
    x-show="$store.modals.isOpen('accountDetails')"
    x-transition
    @click.outside="$store.modals.close()"
    class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto modal z-99999"
    style="display: none;">
    <!-- Overlay -->
    <div @click="$store.modals.close()" class="fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[20px]"></div>

    <!-- Modal Content -->
    <div class="relative w-full max-w-[600px] rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h4 class="font-semibold text-gray-800 text-title-xs dark:text-white/90">
                    {{ __('Account Details') }}
                </h4>
                <p class="block text-gray-500 dark:text-gray-400">
                    <span x-text="$store.modals.data.method_name"></span>
                </p>
            </div>
            <!-- Close Button -->
            <button @click="$store.modals.close()" class="flex h-9.5 w-9.5 items-center justify-center rounded-full bg-gray-100 text-gray-400 transition-colors hover:bg-gray-200 hover:text-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white sm:right-6 sm:top-6 sm:h-11 sm:w-11">
                <!-- SVG Icon -->
                <svg class="fill-current" width="16" height="16" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.04289 16.5418C5.65237 16.9323 5.65237 17.5655 6.04289 17.956C6.43342 18.3465 7.06658 18.3465 7.45711 17.956L11.9987 13.4144L16.5408 17.9565C16.9313 18.347 17.5645 18.347 17.955 17.9565C18.3455 17.566 18.3455 16.9328 17.955 16.5423L13.4129 12.0002L17.955 7.45808C18.3455 7.06756 18.3455 6.43439 17.955 6.04387C17.5645 5.65335 16.9313 5.65335 16.5408 6.04387L11.9987 10.586L7.45711 6.04439C7.06658 5.65386 6.43342 5.65386 6.04289 6.04439C5.65237 6.43491 5.65237 7.06808 6.04289 7.4586L10.5845 12.0002L6.04289 16.5418Z" />
                </svg>
            </button>
        </div>
        <!-- Modal Content -->
        <div class="space-y-5">
            <div>
                <h4 class="font-semibold text-gray-800 text-lg dark:text-white/90 border-b border-gray-100 py-3 dark:border-gray-800">
                    {{ __('Method Details') }}
                </h4>
                <p class="flex items-center justify-between border-b border-gray-100 py-3 dark:border-gray-800">
                    <span class="text-theme-sm text-gray-500 dark:text-gray-400">{{ __('Method Name:')}}</span> 
                    <span class="text-right text-theme-sm text-gray-500 dark:text-gray-400" x-text="$store.modals.data.method_name"></span>
                </p>
                <p class="flex items-center justify-between border-b border-gray-100 py-3 dark:border-gray-800">
                    <span class="text-theme-sm text-gray-500 dark:text-gray-400">{{ __('Method Currency:')}}</span> 
                    <span class="text-right text-theme-sm text-gray-500 dark:text-gray-400" x-text="$store.modals.data.method_currency"></span>
                </p>
                <p class="flex items-center justify-between border-b border-gray-100 py-3 dark:border-gray-800">
                    <span class="text-theme-sm text-gray-500 dark:text-gray-400">{{ __('Status:')}}</span> 
                    <span class="inline-flex items-center justify-center gap-1 rounded-full font-medium px-2.5 py-1 text-xs"
                          :class="{
                              'bg-success-50 text-success-700 dark:bg-success-900/20 dark:text-success-400': $store.modals.data.status?.toLowerCase() === 'approved',
                              'bg-warning-50 text-warning-700 dark:bg-warning-900/20 dark:text-warning-400': $store.modals.data.status?.toLowerCase() === 'pending',
                              'bg-error-50 text-error-700 dark:bg-error-900/20 dark:text-error-400': $store.modals.data.status?.toLowerCase() === 'rejected',
                              'bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80': !$store.modals.data.status
                          }"
                          x-text="$store.modals.data.status || 'N/A'"></span>
                </p>
                <p class="flex items-center justify-between border-b border-gray-100 py-3 dark:border-gray-800">
                    <span class="text-theme-sm text-gray-500 dark:text-gray-400">{{ __('Created At:')}}</span> 
                    <span class="text-right text-theme-sm text-gray-500 dark:text-gray-400" x-text="$store.modals.data.created_at"></span>
                </p>
            </div>

            <div>
                <h4 class="font-semibold text-gray-800 text-lg dark:text-white/90 border-b border-gray-100 py-3 dark:border-gray-800">
                    {{ __('Account Details') }}
                </h4>
                
                <!-- Credentials List -->
                <template x-if="$store.modals.data.credentials && typeof $store.modals.data.credentials === 'object' && !Array.isArray($store.modals.data.credentials) && Object.keys($store.modals.data.credentials).length > 0">
                    <div>
                        <template x-for="([key, value], index) in Object.entries($store.modals.data.credentials)" :key="index">
                            <div class="flex items-center justify-between border-b border-gray-100 py-3 dark:border-gray-800">
                                <span class="text-theme-sm font-medium text-gray-600 dark:text-gray-300" 
                                      x-text="key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())"></span>
                                <div class="text-right text-gray-700 dark:text-gray-200">
                                    <template x-if="(() => {
                                        const val = (typeof value === 'object' && value !== null && value.value !== undefined) ? value.value : value;
                                        return val && typeof val === 'string' && (val.match(/\.(jpg|jpeg|png|gif|webp)$/i) || val.includes('/assets/') || val.includes('/storage/'));
                                    })()">
                                        <a :href="(typeof value === 'object' && value !== null && value.value !== undefined) ? value.value : value" 
                                           target="_blank" 
                                           class="inline-flex items-center gap-1 text-primary-600 hover:text-primary-700 dark:text-primary-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="text-theme-sm">{{ __('View Image') }}</span>
                                        </a>
                                    </template>
                                    <template x-if="!(() => {
                                        const val = (typeof value === 'object' && value !== null && value.value !== undefined) ? value.value : value;
                                        return val && typeof val === 'string' && (val.match(/\.(jpg|jpeg|png|gif|webp)$/i) || val.includes('/assets/') || val.includes('/storage/'));
                                    })()">
                                        <span class="text-theme-sm break-words max-w-xs inline-block" 
                                              x-text="(typeof value === 'object' && value !== null && value.value !== undefined) ? (value.value || '—') : (value || '—')"></span>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
                
                <!-- Empty State -->
                <template x-if="!$store.modals.data.credentials || typeof $store.modals.data.credentials !== 'object' || Array.isArray($store.modals.data.credentials) || Object.keys($store.modals.data.credentials).length === 0">
                    <div class="py-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            {{ __('No account details available') }}
                        </p>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>