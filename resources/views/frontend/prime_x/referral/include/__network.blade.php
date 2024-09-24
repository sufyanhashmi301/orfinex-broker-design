<div class="card">
    <div class="card-body p-6">
        {{-- level referral tree --}}
        @if(setting('site_referral','global') == 'level' && auth()->user()->referrals->count() > 0)
            <section class="management-hierarchy mt-5">
                <div class="hv-container">
                    <div class="hv-wrapper">
                        <!-- tree component -->
                        @include('frontend::referral.include.__tree',['levelUser' => auth()->user(),'level' => $level,'depth' => 1, 'me' => true])
                    </div>
                </div>
            </section>
        @endif
    </div>
</div>