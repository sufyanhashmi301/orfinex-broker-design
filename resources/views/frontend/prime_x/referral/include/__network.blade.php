<div class="flex justify-between flex-wrap items-center mb-6">
    <h4 class="font-medium text-xl capitalize text-slate-700 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
        {{ __('Network Tree') }}
    </h4>
    <div class="flex items-center space-x-2 sm:rtl:space-x-reverse">
        <button type="button" class="btn btn-outline-secondary btn-sm inline-flex items-center justify-center changeTree__btn active" data-target="vertical">
            <iconify-icon class="text-lg" icon="iconoir:network-reverse"></iconify-icon>
        </button>
        <button type="button" class="btn btn-outline-secondary btn-sm inline-flex items-center justify-center changeTree__btn" data-target="horizontal">
            <iconify-icon class="text-lg" icon="iconoir:network-right"></iconify-icon>
        </button>
    </div>
</div>
<div class="card">
    <div class="card-body p-6">
        {{-- level referral tree --}}
        @if(setting('site_referral','global') == 'level' && auth()->user()->referrals->count() > 0)
            <section class="management-hierarchy mt-5">
                <div class="vertical-tree tree-view-block overflow-x-auto">
                    <div class="hv-container">
                        <div class="hv-wrapper">
                            <!-- tree component -->
                            @include('frontend::referral.include.__tree',['levelUser' => auth()->user(),'level' => $level,'depth' => 1, 'me' => true])
                        </div>
                    </div>
                </div>
                <div class="horizontal-tree tree-view-block pt-3 hidden">
                    <div class="mobile_treeview">
                        <ul>
                            <li>
                                @include('frontend::referral.include.__mobile_tree',['levelUser' => auth()->user(),'level' => $level,'depth' => 1, 'me' => true])
                            </li>
                        </ul>
                    </div>
                </div>
            </section>
        @endif
    </div>
</div>
@push('script')
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
