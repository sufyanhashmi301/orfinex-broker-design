<div class="bg-slate-900 py-4 text-center">
    <div class="max-w-screen-xl mx-auto">
        <p class="text-white text-sm">
            {{ __('Interested in New Updates & Market Analysis?') }}
        </p>
    </div>
</div>
<header class="header">
    <nav class="bg-white border-gray-200 py-5 dark:bg-gray-900">
        <div class="flex flex-wrap items-center justify-between max-w-screen-xl px-4 mx-auto">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset(setting('site_logo', 'global')) }}" width="192" alt=""/>
            </a>
            <div class="items-center justify-between hidden md:flex md:order-1" id="navbarSupportedContent">
                <ul class="flex flex-col font-medium md:flex-row md:space-x-6">
                    @foreach($navigations as $navigation)
                        @if($navigation->page->status || $navigation->page_id == null)
                            <li>
                                <a class="block py-2 pl-3 pr-4 lg:p-0 dark:text-white hover:text-primary @if(url($navigation->url) == Request::url() )text-primary @endif"
                                   href="{{ url($navigation->url) }}">{{ __($navigation->tname) }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <div class="flex items-center md:order-2">
                <select name="language" id="" class="language-nice-select site-nice-select mr-3" onchange="window.location.href=this.options[this.selectedIndex].value;">
                    @foreach(\App\Models\Language::where('status', true)->get() as $lang)
                        <option value="{{ route('language-update', ['name' => $lang->locale]) }}" @selected(app()->getLocale() == $lang->locale)>
                            {{ __($lang->name) }}
                        </option>
                    @endforeach
                </select>

                @auth('web')
                    <a href="{{ route('user.dashboard') }}" class="site-btn-sm grad-btn">
                        <i class="anticon anticon-dashboard"></i>
                        {{ __('Dashboard') }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn inline-flex justify-center btn-dark">
                        <span class="flex items-center">
                            <iconify-icon class="text-xl mr-2 rtl:ml-2" icon="solar:user-rounded-linear"></iconify-icon>
                            <span>{{ __('User Area') }}</span>
                        </span>
                    </a>
                @endauth
                <div class="color-switcher">
                    <i icon-name="moon" class="dark-icon" data-mode="dark"></i>
                    <i icon-name="sun" class="light-icon" data-mode="light"></i>
                </div>
            </div>
            <button
                class="navbar-toggler hidden"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
</header>
<div class="bg-slate-200 py-2 overflow-hidden">
    <ul class="flex items-center nowrap gap-5">
        <li class="text-xs">{{ __('Enjoy the Lowest Spreads & Direct Access to 2000+ Markets') }}</li>
        <li class="text-xs">{{ __('Enjoy the Lowest Spreads & Direct Access to 2000+ Markets') }}</li>
        <li class="text-xs">{{ __('Enjoy the Lowest Spreads & Direct Access to 2000+ Markets') }}</li>
    </ul>
</div>
@push('script')
    <script>
        // Color Switcher
        $(".color-switcher").on('click', function () {
            "use strict"
            $("body").toggleClass("dark-theme");
            var url = '{{ route("mode-theme") }}';
            $.get(url)
        });
    </script>
@endpush
