<section class="bg-white dark:bg-gray-900 py-20 px-5">
    <div class="mx-auto max-w-2xl">
        <div class="text-center space-y-5">
            <p class="subtitle text-primary-500 font-light uppercase">
                {{ __('Better and Secure') }}
            </p>
            <h2 class="text-6xl capitalize">
                {{ __('' . $data['hero_title'] . '') }}
            </h2>
            <p>{{ __('' . $data['hero_content'] . '') }}</p>
            <div class="space-x-3">
                <a href="{{ $data['hero_button1_url'] }}" class="btn inline-flex justify-center btn-dark" target="{{ $data['hero_button1_target'] }}">
                    <span class="flex items-center">
                        <iconify-icon icon="{{ $data['hero_button1_icon'] }}" class="text-xl ltr:mr-2 rtl:ml-2"></iconify-icon>
                        <span>{{ __('' . $data['hero_button1_level'] . '') }}</span>
                    </span>
                </a>
                <a href="{{ $data['hero_button2_url'] }}" class="btn inline-flex justify-center btn-outline-dark" target="{{ $data['hero_button2_target'] }}">
                    <span class="flex items-center">
                        <iconify-icon icon="{{ $data['hero_button2_icon'] }}" class="text-xl ltr:mr-2 rtl:ml-2"></iconify-icon>
                        <span>{{ __('' . $data['hero_button2_lavel'] . '') }}</span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</section>
<section class="banner light-blue-bg">
    <div class="slider-thumb"></div>
    <div class="container">
        <div class="row">
            <div class="col-xl-7 col-lg-7 col-md-12 col-12">
                <div class="banner-content">
                    <h2 data-aos="fade-right" data-aos-duration="1000">
                        {{ __('' . $data['hero_title'] . '') }}
                    </h2>
                    <p data-aos="fade-up" data-aos-duration="1500">
                        {{ __('' . $data['hero_content'] . '') }}
                    </p>
                    <div class="banner-anchors">
                        <a href="{{ $data['hero_button1_url'] }}" class="site-btn grad-btn mb-2" data-aos="fade-up"
                           target="{{ $data['hero_button1_target'] }}" data-aos-duration="2000"><i
                                class="anticon {{ $data['hero_button1_icon'] }}"></i>{{ __('' . $data['hero_button1_level'] . '') }}
                        </a>
                        <a href="{{ $data['hero_button2_url'] }}" class="site-btn white-btn" data-aos="fade-up"
                           target="{{ $data['hero_button2_target'] }}" data-aos-duration="2500"><i
                                class="anticon {{ $data['hero_button2_icon'] }}"></i>{{ __('' . $data['hero_button2_lavel'] . '') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-lg-5 col-md-12 col-12">
                <div class="banner-right">
                    <img src="{{ asset($data['hero_right_img']) }}" alt="" class="banner-img" data-aos="fade-left"
                         data-aos-duration="2000"/>
                    <div class="dots" style="background: url({{ asset($data['hero_right_top_img']) }}) repeat;"
                         data-aos="fade-down-left" data-aos-duration="1500"></div>
                </div>
            </div>
        </div>
    </div>
</section>
