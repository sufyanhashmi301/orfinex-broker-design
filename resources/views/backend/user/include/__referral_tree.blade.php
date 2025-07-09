<div
    class="tab-pane fade"
    id="pills-tree"
    role="tabpanel"
    aria-labelledby="pills-transactions-tab"
>
    <div class="card basicTable_wrapper">
        <div class="card-header">
            <h4 class="card-title">{{ __('Referral Tree') }}</h4>
            <div class="flex items-center space-x-2 sm:rtl:space-x-reverse">
                <a href="{{ route('admin.referral-network.report', ['email' => $user->email]) }}" target="_blank" class="btn btn-sm btn-dark inline-flex items-center justify-center">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:network"></iconify-icon>
                    <span>{{ __('Network stats') }}</span>
                </a>
                <div class="outline-buttons">
                    <div class="groupButtons">
                        <button type="button" class="btn btn-outline-dark btn-sm inline-flex items-center justify-center changeTree__btn active" data-target="vertical" style="min-width: auto;">
                            <iconify-icon class="text-lg" icon="iconoir:network-reverse"></iconify-icon>
                        </button>
                        <button type="button" class="btn btn-outline-dark btn-sm inline-flex items-center justify-center changeTree__btn" data-target="horizontal" style="min-width: auto;">
                            <iconify-icon class="text-lg" icon="iconoir:network-right"></iconify-icon>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive p-6">
{{--{{dd( $user->referrals->count(),setting('site_referral','global'),['levelUser' => $user,'level' => $level,'depth' => 1, 'me' => true])}}--}}
            {{-- level referral tree --}}
            @if(setting('site_referral','global') == 'level' && $user->referrals->count() > 0)
                <section class="management-hierarchy">
                    <div class="vertical-tree tree-view-block overflow-x-auto">
                        <div class="hv-container">
                            <div class="hv-wrapper">
                                <!-- tree component -->
                                @include('frontend::referral.include.__tree',['levelUser' => $user,'level' => $level,'depth' => 1, 'me' => true])
                            </div>
                        </div>
                    </div>
                    <div class="horizontal-tree tree-view-block pt-3 hidden">
                        <div class="mobile_treeview">
                            <ul>
                                <li>
                                    @include('frontend::referral.include.__mobile_tree',['levelUser' => $user,'level' => $level,'depth' => 1, 'me' => true])
                                </li>
                            </ul>
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

            $('.changeTree__btn').on('click', function () {
                const target = $(this).data('target');

                $('.changeTree__btn').removeClass('active');
                $(this).addClass('active');

                // Show the selected tree view, hide others
                $('.tree-view-block').hide();
                $(`.${target}-tree`).show();
            });

            // Hide all child containers and their .person blocks on load
            $('.hv-item-children').each(function () {
                $(this).hide();
                $(this).find('.person').hide();
                $(this).siblings('.hv-item-parent').addClass('hide-line');
                $(this).closest('.hv-item').find('.level-summary').hide();
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
                        $parent.find('.level-summary').toggle(!isVisible);

                        // Change the icon accordingly
                        var $icon = $(this).find('iconify-icon');
                        $icon.attr('icon', isVisible ? 'lucide:plus' : 'lucide:minus');
                    });
                }
            });

            initHorizontalTree();
            function initHorizontalTree() {
                $('.treeview__level').each(function () {
                    const $level = $(this);
                    const $nextUl = $level.next('ul');

                    if ($nextUl.length) {
                        $nextUl.hide();

                        // Avoid duplicate buttons
                        if (!$level.find('.horizontal-toggle-btn').length) {
                            const $toggleBtn = $(`
                                <button class="h-5 w-5 btn-primary rounded inline-flex items-center justify-center horizontal-toggle-btn">
                                    <iconify-icon icon="lucide:plus"></iconify-icon>
                                </button>
                            `);
                            $level.find('.text-start').append($toggleBtn);

                            $toggleBtn.on('click', function () {
                                const isVisible = $nextUl.is(':visible');
                                $nextUl.slideToggle(200);

                                var $icon = $(this).find('iconify-icon');
                                $icon.attr('icon', isVisible ? 'lucide:plus' : 'lucide:minus');
                            });
                        }
                    }
                });
            }
        });
    </script>
@endpush

