<div class="tab-content">
    <div 
        x-show="activeTab === 'socialMediaMaterial'" 
        x-transition:enter="transition ease-out duration-300" 
        x-transition:enter-start="opacity-0 transform scale-95" 
        x-transition:enter-end="opacity-100 transform scale-100">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            @if(isset($advertisements['social_media']) && count($advertisements['social_media']) > 0)
                @foreach($advertisements['social_media'] as $advertisement)
                    <div class="group flex items-center justify-center h-100 overflow-hidden relative rounded-4" style="background-image: url({{ asset($advertisement->img) }});min-height: 275px;background-size: cover;">
                        <div class="absolute w-full h-full inset-0 bg-slate-900/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-2">
                            <a href="{{ route('user.image.download', ['filename' => $advertisement->img]) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full border-[0.5px] border-gray-200 bg-gray-50 text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400">
                                <i data-lucide="eye" class="text-lg"></i>
                            </a>
                            <a href="{{ route('user.image.download', ['filename' => $advertisement->img]) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full border-[0.5px] border-gray-200 bg-gray-50 text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400">
                                <i data-lucide="download" class="text-lg"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-span-full py-10 px-10">
                    <div class="flex items-center justify-center flex-col gap-3">
                        <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M26 19.875V30.9167" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M25.988 37.5417H26.0075" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('No social media materials') }}</h3>
                        <p class="text-sm text-center text-slate-600 dark:text-slate-100">
                            {{ __('There are no social media advertisement materials available at the moment. Please check back later.') }}
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div 
        x-show="activeTab === 'websiteBanners'" 
        x-transition:enter="transition ease-out duration-300" 
        x-transition:enter-start="opacity-0 transform scale-95" 
        x-transition:enter-end="opacity-100 transform scale-100">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            @if(isset($advertisements['website_banner']) && count($advertisements['website_banner']) > 0)
                @foreach($advertisements['website_banner'] as $advertisement)
                    <div class="group flex items-center justify-center h-100 overflow-hidden relative rounded-4" style="background-image: url({{ asset($advertisement->img) }});min-height: 275px;background-size: cover;">
                        <div class="absolute w-full h-full inset-0 bg-slate-900/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-2">
                            <a href="{{ route('user.image.download', ['filename' => $advertisement->img]) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full border-[0.5px] border-gray-200 bg-gray-50 text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400">
                                <i data-lucide="eye" class="text-lg"></i>
                            </a>
                            <a href="{{ route('user.image.download', ['filename' => $advertisement->img]) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-full border-[0.5px] border-gray-200 bg-gray-50 text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400">
                                <i data-lucide="download" class="text-lg"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-span-full py-10 px-10">
                    <div class="flex items-center justify-center flex-col gap-3">
                        <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M26 19.875V30.9167" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M25.988 37.5417H26.0075" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('No website banners') }}</h3>
                        <p class="text-sm text-center text-slate-600 dark:text-slate-100">
                            {{ __('There are no website banner materials available at the moment. Please check back later.') }}
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
