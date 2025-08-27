<!-- ===== Footer Start ===== -->
<footer :class="sidebarToggle ? 'lg:left-[90px]' : 'lg:left-[290px]'" class="fixed bottom-0 left-0 right-0 z-10 border-t border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
    <div class="flex grow flex-col items-center justify-between lg:flex-row lg:px-6">
        <div class="flex w-full items-center justify-center lg:justify-between gap-2 px-3 py-3 sm:gap-4 lg:px-0 lg:py-4">
            <div class="text-center lg:text-left">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    &copy; {{ date('Y') }} {{ setting('site_title', 'global') }}. {{ __('All rights reserved.') }}
                </p>
            </div>
            <div class="hidden lg:flex items-center gap-4">
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Version') }} 1.0
                </span>
            </div>
        </div>
    </div>
</footer>
<!-- ===== Footer End ===== -->