<!DOCTYPE html>
<html lang="en">
@include('frontend::include.__head')
<body
    x-data="{
        'loaded': true, 
        'darkMode': false, 
        'stickyMenu': false, 
        'sidebarOpen': false, 
        'scrollTop': false
    }"
    x-init="
        darkMode = JSON.parse(localStorage.getItem('darkMode')) || false; 
        $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)));
        window.addEventListener('scroll', () => scrollTop = window.pageYOffset > 100);
        setTimeout(() => loaded = false, 500);
    "
    :class="{ 'dark': darkMode }"
    class="flex h-full text-base text-foreground dark:bg-gray-900">

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

    <div class="flex flex-grow">
        <!-- ===== Header Start ===== -->
        @include('frontend::include.__new_header')

        <!-- ===== Content Area Start ===== -->
        <div class="flex grow pt-(--header-height-mobile) lg:pt-(--header-height)">
            @include('frontend::include.__partner_side_nav')
            
            <!-- ===== Main Content Start ===== -->
            <main class="lg:ps-16 xl:ps-(--sidebar-width) transition-all duration-300 grow">
                <div class="container-fluid">
                    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
                        @yield('content')
                    </div>
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