<!doctype html>
<html lang="en" 
    x-data="{ darkMode: {{ auth()->user()->user_theme == 'dark' ? 'true' : 'false' }} }"
    x-init="
        // Initialize darkMode from server-side user_theme preference
        darkMode = {{ auth()->user()->user_theme == 'dark' ? 'true' : 'false' }};
        
        // Sync localStorage with server preference
        localStorage.setItem('darkMode', JSON.stringify(darkMode));
        
        // Watch for changes and update localStorage
        $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)));
    "
    :class="{ 'dark': darkMode }">
@include('frontend::include.__head')
<body 
    x-data="{
        'loaded': true, 
        'stickyMenu': false, 
        'sidebarOpen': false, 
        'scrollTop': false
    }"
    x-init="
        window.addEventListener('scroll', () => scrollTop = window.pageYOffset > 100);
        setTimeout(() => loaded = false, 500);
    "
    class="text-base bg-white dark:bg-gray-900">

    <!-- ===== Preloader Start ===== -->
    <div
        x-show="loaded"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed left-0 top-0 z-[99999] flex h-screen w-screen items-center justify-center bg-white dark:bg-gray-900">
        <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-brand-500 border-t-transparent"></div>
    </div>
    <!-- ===== Preloader End ===== -->

    <div class="flex flex-col flex-grow">
        <!-- ===== Header Start ===== -->
        @include('frontend::include.__new_header')

        <!-- ===== Content Area Start ===== -->
        <div class="flex grow pt-(--header-height-mobile) lg:pt-(--header-height)">
            @include('frontend::include.__user_side_nav')
        
            <!-- ===== Main Content Start ===== -->
            <main class="flex-1 lg:ps-16 xl:ps-(--sidebar-width) overflow-y-auto transition-all duration-300">
                <!-- ===== KYC Info Start ===== -->
                @if(setting('kyc_verification','permission'))
                    @if(!Route::is(['webterminal', 'user.follower_access', 'user.provider_access', 'user.ratings', 'user.ticket*', 'user.kyc*']))
                        @include('frontend::user.include.__kyc_info')
                    @endif
                @endif

                <div class="p-4 mx-auto max-w-(--breakpoint-xl) w-full md:p-6">
                    @yield('content')
                </div>
            </main>
            <!-- ===== Main Content End ===== -->
        </div>
        <!-- ===== Content Area End ===== -->
        
        <!-- Back to Top Button -->
        @if(setting('back_to_top','permission'))
            <div x-show="scrollTop" x-transition class="fixed bottom-4 right-4 z-50">
                <button 
                    @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
                    class="flex items-center justify-center w-12 h-12 bg-brand-500 hover:bg-brand-600 text-white rounded-full shadow-lg transition-all duration-200 transform hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                </button>
            </div>
        @endif
    </div>
    
    @include('frontend::include.__script')
</body>
</html>