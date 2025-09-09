<div x-data="{ activeTab: 'socialMediaMaterial' }" class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-5">
    <div class="flex flex-wrap flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex-shrink-0">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                {{ __('Choose advertisement materials for any purpose') }}
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('Explore available advertisement materials, preview the content, and download the assets you need for your marketing efforts.') }}
            </p>
        </div>
        <div class="flex flex-col sm:flex-row items-start w-full gap-3 sm:justify-end">
            <div class="inline-flex w-fit items-center gap-0.5 rounded-lg bg-gray-100 p-0.5 dark:bg-gray-900">
                <button @click="activeTab = 'socialMediaMaterial'" :class="activeTab === 'socialMediaMaterial' ? 'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800' : 'text-gray-500 dark:text-gray-400'" class="px-3 py-2 font-medium rounded-md text-theme-sm text-nowrap hover:text-gray-900 dark:hover:text-white transition-all duration-200">
                    {{ __('Social media material') }}
                </button>
                <button @click="activeTab = 'websiteBanners'" :class="activeTab === 'websiteBanners' ? 'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800' : 'text-gray-500 dark:text-gray-400'" class="px-3 py-2 font-medium rounded-md text-theme-sm text-nowrap hover:text-gray-900 dark:hover:text-white transition-all duration-200">
                    {{ __('Website banners') }}
                </button>
            </div>
            <div class="relative w-full sm:w-fit">
                <select class="w-full h-10 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs focus:outline-hidden focus:ring-0 focus-visible:outline-hidden dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400" name="language" id="language">
                    <option value="" selected="">{{ __('Choose Language') }}</option>
                    @foreach($languages as $language)
                        <option value="{{ $language->name }}">{{ $language->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    @include('frontend::.referral.include.__advertisement_material_partial')
</div>
