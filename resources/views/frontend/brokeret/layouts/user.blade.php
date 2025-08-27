<!doctype html>
<html lang="en">
@include('frontend::include.__head')
<body
    x-data="{ page: 'dashboard', 'loaded': true, 'darkMode': false, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    x-init="
         darkMode = JSON.parse(localStorage.getItem('darkMode'));
         $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{'dark bg-gray-900': darkMode === true}"
  >
    <div class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black"
        x-show="loaded"
        x-init="window.addEventListener('DOMContentLoaded', () => {setTimeout(() => loaded = false, 500)})">
            <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-brand-500 border-t-transparent"></div>
    </div>
    
    <div class="flex h-screen overflow-hidden">

        @include('frontend::include.__user_side_nav')

        <!-- ===== Content Area Start ===== -->
        <div class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto">
            <div @click="sidebarToggle = false" :class="sidebarToggle ? 'block lg:hidden' : 'hidden'" class="fixed w-full h-screen z-9 bg-gray-900/50"></div>

            <!-- ===== Header Start ===== -->
            @include('frontend::include.__user_header')
            <!-- ===== Header End ===== -->
        
            <!-- ===== Main Content Start ===== -->
            <main class="pb-20">
                <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
                    @yield('content')
                </div>
            </main>
            <!-- ===== Main Content End ===== -->

            <!-- ===== Footer Start ===== -->
            @include('frontend::include.__user_footer')
            <!-- ===== Footer End ===== -->
        </div>
        <!-- ===== Content Area End ===== -->
        
        <!-- Back to Top Button -->
        @if(setting('back_to_top','permission'))
        <div x-data="backToTop" x-show="show" x-transition class="fixed bottom-4 right-4 z-50">
            <button 
                @click="scrollToTop"
                class="flex items-center justify-center w-12 h-12 bg-brand-500 hover:bg-brand-600 text-white rounded-full shadow-lg transition-all duration-200 transform hover:scale-110"
            >
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