<div class="border-b border-gray-200 dark:border-gray-800">
    <nav aria-label="Tabs" class="-mb-px flex">
        <a href="{{ route('login') }}"
            class="flex-1 inline-flex items-center justify-center text-theme-base tracking-wide border-b-3 px-2.5 py-2 transition-colors duration-200 
            {{ request()->routeIs('login') 
                ? 'text-brand-500 dark:text-brand-400 border-brand-500 dark:border-brand-400' 
                : 'text-gray-500 border-transparent hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
            {{ __('Sign In') }}            
        </a>
        <a href="{{ route('register') }}"
            class="flex-1 inline-flex items-center justify-center text-theme-base tracking-wide border-b-3 px-2.5 py-2 transition-colors duration-200
            {{ request()->routeIs('register') 
                ? 'text-brand-500 dark:text-brand-400 border-brand-500 dark:border-brand-400' 
                : 'text-gray-500 border-transparent hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }}">
            {{ __('Create an account') }}
        </a>
    </nav>
</div>