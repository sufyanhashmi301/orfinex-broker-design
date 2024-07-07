<div class="tab-content">
    <div class="tab-pane fade show active" id="tabs-socialMediaMaterial" role="tabpanel"
         aria-labelledby="tabs-socialMediaMaterial-tab">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            @if(isset($advertisements['social_media']))
                @foreach($advertisements['social_media'] as $advertisement)
                    <div class="card card-cover h-100 overflow-hidden relative rounded-4"
                         style="background-image: url({{ asset($advertisement->img) }});min-height: 275px;background-size: cover;">
                        <div class="absolute right-0">
                            <a href="{{route('user.image.download', ['filename' => $advertisement->img])}}"
                               class="btn inline-flex h-12 w-12 items-center justify-center btn-light rounded-none">
                                    <span class="flex items-center">
                                        <iconify-icon class="text-lg" icon="octicon:download-16"></iconify-icon>
                                    </span>
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
    </div>
    <div class="tab-pane fade" id="tabs-websiteBanners" role="tabpanel"
         aria-labelledby="tabs-websiteBanners-tab">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            @if(isset($advertisements['website_banner']))
                @foreach($advertisements['website_banner'] as $advertisement)
                    <div class="card card-cover h-100 overflow-hidden relative rounded-4"
                         style="background-image: url({{ asset($advertisement->img) }});min-height: 275px;background-size: cover;">
                        <div class="absolute right-0">
                            <a href="{{route('user.image.download', ['filename' => $advertisement->img])}}"

                               class="btn inline-flex h-12 w-12 items-center justify-center btn-light rounded-none">
                                    <span class="flex items-center">
                                        <iconify-icon class="text-lg" icon="octicon:download-16"></iconify-icon>
                                    </span>
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
    </div>
</div>
