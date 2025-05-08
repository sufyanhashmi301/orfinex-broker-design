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
@push('single-script')
    <script>
        $(document).ready(function () {
            // Hide all child containers and their .person blocks on load
            $('.hv-item-children').each(function () {
                $(this).hide();
                $(this).find('.person').hide();
                $(this).siblings('.hv-item-parent').addClass('hide-line');
            });

            // Add toggle button to each parent with children
            $('.hv-item').each(function () {
                var $children = $(this).children('.hv-item-children');
                var $parent = $(this).children('.hv-item-parent');

                if ($children.length) {
                    var $btn = $(`
                        <button class="h-5 w-5 btn-primary rounded-full inline-flex items-center justify-center mx-auto mt-1 toggle-btn">
                            <iconify-icon icon="lucide:plus"></iconify-icon>
                        </button>
                    `);

                    $parent.find('.person').append($btn);

                    // Toggle logic
                    $btn.on('click', function () {
                        var isVisible = $children.is(':visible');
                        $children.toggle();
                        $children.find('.person').toggle(!isVisible);
                        $parent.toggleClass('hide-line', isVisible);

                        // Change the icon accordingly
                        var $icon = $(this).find('iconify-icon');
                        $icon.attr('icon', isVisible ? 'lucide:plus' : 'lucide:minus');
                    });
                }
            });
        });
    </script>
@endpush

