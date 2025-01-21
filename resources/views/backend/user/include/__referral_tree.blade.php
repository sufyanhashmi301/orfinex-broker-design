<div
    class="tab-pane fade"
    id="pills-tree"
    role="tabpanel"
    aria-labelledby="pills-transactions-tab"
>
    <div class="card basicTable_wrapper">
        <div class="card-header">
            <h4 class="card-title">{{ __('Referral Tree') }}</h4>
        </div>
        <div class="card-body table-responsive p-6">
{{--{{dd( $user->referrals->count(),setting('site_referral','global'),['levelUser' => $user,'level' => $level,'depth' => 1, 'me' => true])}}--}}
            {{-- level referral tree --}}
            @if(setting('site_referral','global') == 'level' && $user->referrals->count() > 0)
                <section class="management-hierarchy">
                    <div class="hv-container">
                        <div class="hv-wrapper">
                            <!-- tree component -->
                            @include('frontend::referral.include.__tree',['levelUser' => $user,'level' => $level,'depth' => 1, 'me' => true])
                        </div>
                    </div>
                </section>
            @else
                <p>{{ __('No Referral user found') }}</p>
            @endif

        </div>
    </div>
</div>

