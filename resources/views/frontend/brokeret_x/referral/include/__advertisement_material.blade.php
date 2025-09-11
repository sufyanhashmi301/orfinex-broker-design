<div x-data="{ activeTab: 'socialMediaMaterial' }">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90">
            {{ __('Advertisement materials') }}
        </h2>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div class="h-11 items-center gap-0.5 rounded-lg bg-gray-100 p-0.5 lg:inline-flex dark:bg-gray-900">
            <button @click="activeTab = 'socialMediaMaterial'" :class="activeTab === 'socialMediaMaterial' ? 'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800' : 'text-gray-500 dark:text-gray-400'" class="px-3 py-2 font-medium rounded-md text-theme-sm text-nowrap hover:text-gray-900 dark:hover:text-white transition-all duration-200">
                {{ __('Social media material') }}
            </button>
            <button @click="activeTab = 'websiteBanners'" :class="activeTab === 'websiteBanners' ? 'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800' : 'text-gray-500 dark:text-gray-400'" class="px-3 py-2 font-medium rounded-md text-theme-sm text-nowrap hover:text-gray-900 dark:hover:text-white transition-all duration-200">
                {{ __('Website banners') }}
            </button>
        </div>
        <div class="relative w-full sm:w-fit">
            <select class="dark:bg-dark-900 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" name="language" id="language">
                <option value="" selected="">{{ __('Choose Language') }}</option>
                @foreach($languages as $language)
                    <option value="{{ $language->name }}">{{ $language->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @include('frontend::referral.include.__advertisement_material_partial')
    
</div>
