<div class="card mb-3">
    <div class="card-body p-6">
        <h4 class="card-title mb-3">
            {{ __('Choose advertisement materials for any purpose') }}
        </h4>
        <p class="card-text mb-3">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Exercitationem tempora, esse
            totam assumenda enim amet blanditiis cupiditate! Error quibusdam sit quam laborum ipsam! In, consectetur
            animi expedita vero soluta fuga.</p>
        <p class="card-text">Lorem, ipsum dolor sit amet consectetur adipisicing elit.</p>
    </div>
</div>
<div class="space-y-5">
    <div class="card mb-3">
        <div class="card-header items-center flex-wrap">
            <div class="flex items-center mb-4 sm:mb-0">
                <label for="" class="form-label me-3 mb-0">Language:</label>
                <select class="form-control" name="language" id="language">
                    <option value="" selected="">Choose...</option>
                    @foreach($languages as $language)
                        <option value="{{$language->name}}">{{$language->name}}</option>
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
            @include('frontend.default.referral.include.__advertisement_material_partial')
        </div>
    </div>
</div>


