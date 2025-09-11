<div class="tab-content">
    <div 
        x-show="activeTab === 'socialMediaMaterial'" 
        x-transition:enter="transition ease-out duration-300" 
        x-transition:enter-start="opacity-0 transform scale-95" 
        x-transition:enter-end="opacity-100 transform scale-100">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            @if(isset($advertisements['social_media']) && count($advertisements['social_media']) > 0)
                @foreach($advertisements['social_media'] as $advertisement)
                    <div class="group flex items-center justify-center h-100 overflow-hidden relative rounded-lg border border-gray-200 dark:border-gray-800" style="background-image: url({{ asset($advertisement->img) }});min-height: 275px;background-size: cover;">
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
                <div class="col-span-full">
                    <x-frontend::empty-state icon="inbox">
                        <x-slot name="title">
                            {{ __('No social media materials available') }}
                        </x-slot>
                        <x-slot name="subtitle">
                            {{ __('No social media ads available right now. Please check back later.') }}
                        </x-slot>
                    </x-frontend::empty-state>
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
                    <div class="group flex items-center justify-center h-100 overflow-hidden relative rounded-lg border border-gray-200 dark:border-gray-800" style="background-image: url({{ asset($advertisement->img) }});min-height: 275px;background-size: cover;">
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
                <div class="col-span-full">
                    <x-frontend::empty-state icon="inbox">
                        <x-slot name="title">
                            {{ __('No website banners available') }}
                        </x-slot>
                        <x-slot name="subtitle">
                            {{ __('No website banners available right now. Please check back later.') }}
                        </x-slot>
                    </x-frontend::empty-state>
                </div>
            @endif
        </div>
    </div>
</div>
