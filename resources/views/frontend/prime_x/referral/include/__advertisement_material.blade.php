<div class="card mb-3">
    <div class="card-body p-6">
        <h4 class="card-title mb-3">
            {{ __('Choose advertisement materials for any purpose') }}
        </h4>
        <p class="card-text mb-3">{{ __('Explore and access advertisement materials effortlessly. Simply check the available options, view the content, and download the materials for your intended purpose with ease') }}</p>
        <p class="card-text"></p>
    </div>
</div>
<div class="space-y-5">
    <div class="card mb-3">
        <div class="card-header items-center flex-wrap">
            <div class="flex items-center mb-4 sm:mb-0">
                <label for="" class="form-label me-3 mb-0">{{ __('Language:') }}</label>
                <select class="form-control" name="language" id="language">
                    <option value="" selected="">{{ __('Choose...') }}</option>
                    @foreach($languages as $language)
                        <option value="{{ $language->name }}">{{ $language->name }}</option>
                    @endforeach

                </select>
            </div>
            <div>
                <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4" id="tabs-tab"
                    role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#tabs-socialMediaMaterial"
                           class="nav-link block font-medium font-Inter text-sm leading-tight capitalize px-6 py-3 focus:outline-none focus:ring-0 active dark:bg-slate-900 dark:text-slate-300"
                           id="tabs-socialMediaMaterial-tab" data-bs-toggle="pill"
                           data-bs-target="#tabs-socialMediaMaterial" role="tab"
                           aria-controls="tabs-socialMediaMaterial" aria-selected="true">
                            {{ __('Social media material') }}
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tabs-websiteBanners"
                           class="nav-link block font-medium font-Inter text-sm leading-tight capitalize px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300"
                           id="tabs-websiteBanners-tab" data-bs-toggle="pill" data-bs-target="#tabs-websiteBanners"
                           role="tab"
                           aria-controls="tabs-websiteBanners" aria-selected="false">
                            {{ __('Website banners') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body p-6" id="advertisement-container">
            @include('frontend::.referral.include.__advertisement_material_partial')
        </div>
    </div>
</div>
