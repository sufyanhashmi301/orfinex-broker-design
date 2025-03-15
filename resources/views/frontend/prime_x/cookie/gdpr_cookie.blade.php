@if( setting('gdpr_status','gdpr') == true )

    <div class="caches-privacy cookiealert" hidden>
        <div class="content">
            <p>{{ __(':gdpr_text', ['gdpr_text' => setting('gdpr_text','gdpr')]) }} <a href="{{ url(setting('gdpr_button_url','gdpr')) }}"
                                                    target="_blank">{{ __(':gdpr_button_label', ['gdpr_button_label' => setting('gdpr_button_label','gdpr')]) }}</a></p>
        </div>
        <button class="site-btn blue-btn acceptcookies">{{ __('Accept All') }}</button>
    </div>

@endif
