@if(setting('site_referral','global') == 'level' && $user->referrals->count() > 0)
    <div class="horizontal-tree tree-view-block pt-3">
        <div class="mobile_treeview">
            <ul>
                <li>
                    @include('frontend::referral.include.__mobile_tree',['levelUser' => $user,'level' => $level,'depth' => 1, 'me' => true])
                </li>
            </ul>
        </div>
    </div>
@else
    <p class="text-lg text-center text-slate-600 dark:text-slate-100">{{ __('No referral tree available.') }}</p>
@endif
