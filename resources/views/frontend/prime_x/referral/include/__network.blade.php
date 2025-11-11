<div class="pageTitle flex justify-between flex-wrap items-center mb-6">
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
@if(setting('site_referral','global') == 'level' && auth()->user()->referrals->count() > 0)
    <div class="grid grid-cols-12 gap-5">
        <div class="lg:col-span-9 col-span-12">
            <div class="card h-full">
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
        </div>
        <div class="lg:col-span-3 col-span-12">
            <div class="card h-full">
                <div class="card-header noborder">
                    <h4 class="card-title">{{ __('Network Equity Summary') }}</h4>
                </div>
                <div class="card-body p-6 pt-0">
                    @if(setting('site_referral','global') == 'level' && auth()->user()->referrals->count() > 0)
                        @php
                            $equityDetails = get_recursive_equity_details(auth()->user());
                        @endphp
                        <div class="flex flex-col">
                            <!-- Personal Stats -->
                            <div class="border border-slate-100 dark:border-slate-700 p-4 rounded mb-4">
                                <div class="flex space-x-4 rtl:space-x-reverse">
                                    <div class="flex-none">
                                        <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-body dark:text-white">
                                            <iconify-icon icon="lucide:user"></iconify-icon>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                                            {{ __('Personal Equity') }}
                                        </div>
                                        <div class="text-slate-900 dark:text-white text-lg font-medium">
                                            {{ $currencySymbol.number_format($equityDetails['personal_equity'], 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border border-slate-100 dark:border-slate-700 p-4 rounded mb-4">
                                <div class="flex space-x-4 rtl:space-x-reverse">
                                    <div class="flex-none">
                                        <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-100 dark:bg-body dark:text-white">
                                            <iconify-icon icon="lucide:network"></iconify-icon>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                                            {{ __('Total Network Equity') }}
                                        </div>
                                        <div class="text-slate-900 dark:text-white text-lg font-medium">
                                            {{ $currencySymbol.number_format($equityDetails['total_equity'], 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Level-wise Equity Table -->
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="bg-slate-50 dark:bg-body">
                                            <th class="table-th">{{ __('Level') }}</th>
                                            <th class="table-th">{{ __('Total Equity') }}</th>
                                            <th class="table-th">{{ __('Referrals') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                        @foreach($equityDetails['levels_data'] as $level => $data)
                                            <tr>
                                                <td class="table-td">
                                                    <div class="w-6 h-6 inline-flex items-center justify-center rounded bg-slate-100 dark:bg-body text-slate-600 dark:text-slate-300 text-sm font-medium">
                                                        {{ $level }}
                                                    </div>
                                                </td>
                                                <td class="table-td">{{ $currencySymbol.number_format($data['level_equity'], 2) }}</td>
                                                <td class="table-td">{{ $data['referral_count'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-slate-50 dark:bg-body font-medium">
                                            <td class="table-td">
                                                <span class="font-medium">
                                                    {{ __('Total') }}
                                                </span>
                                            </td>
                                            <td class="table-td">
                                                <span class="font-medium">
                                                    {{ $currencySymbol.number_format($equityDetails['total_downline_equity'], 2) }}
                                                </span>
                                            </td>
                                            <td class="table-td">
                                                <span class="font-medium">
                                                    {{ $equityDetails['referral_count'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@else
    {{-- Single Empty State for Both Network and Statistics --}}
    <div class="card basicTable_wrapper items-center justify-center">
        <div class="flex items-center justify-center flex-col py-10 px-10">
            <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h4 class="text-xl font-medium text-slate-900 dark:text-white mb-2">
                {{ __("You don't have any referrals yet.") }}
            </h4>
        </div>
    </div>
@endif


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
                // Find and hide the level-summary within the same hv-item parent container
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
                        // Toggle the level-summary visibility
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
                    $level.find('.level-summary').hide();

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
                                $level.find('.level-summary').toggle(!isVisible);

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
