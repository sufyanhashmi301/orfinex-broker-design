<div x-data="{ activeTab: 'vertical' }">
    <div class="flex justify-between flex-wrap items-center gap-2 mb-6 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/3">
        <div>
            <h1 class="text-xl font-semibold text-gray-800 dark:text-white/90 mb-1">
                {{ __('Network Tree') }}
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('Visualize and manage your referral network hierarchy') }}
            </p>
        </div>
        <div class="inline-flex flex-wrap items-center gap-x-1 gap-y-2 rounded-lg bg-gray-100 p-0.5 dark:bg-gray-900">
            <button 
                type="button" 
                @click="activeTab = 'vertical'"
                :class="activeTab === 'vertical' ? 'text-gray-900 dark:text-white bg-white dark:bg-gray-800' : 'text-gray-500 dark:text-gray-400'"
                class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-md group hover:text-gray-900 dark:hover:text-white">
                <i class="w-4 h-4" data-lucide="network"></i>
                {{ __('Vertical') }}
            </button>
            <button 
                type="button" 
                @click="activeTab = 'horizontal'"
                :class="activeTab === 'horizontal' ? 'text-gray-900 dark:text-white bg-white dark:bg-gray-800' : 'text-gray-500 dark:text-gray-400'"
                class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-md group hover:text-gray-900 dark:hover:text-white">
                <i class="w-4 h-4 -rotate-90" data-lucide="network"></i>
                {{ __('Horizontal') }}
            </button>
        </div>
    </div>
    @if(setting('site_referral','global') == 'level' && auth()->user()->referrals->count() > 0)
        <div class="grid grid-cols-12 gap-5">
            <div class="lg:col-span-9 col-span-12">
                <div class="h-full rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/3">
                    {{-- level referral tree --}}
                    <section class="management-hierarchy mt-5">
                        <div x-show="activeTab === 'vertical'" class="vertical-tree tree-view-block overflow-x-auto">
                            <div class="hv-container">
                                <div class="hv-wrapper flex">
                                    <!-- tree component -->
                                    @include('frontend::referral.include.__tree',['levelUser' => auth()->user(),'level' => $level,'depth' => 1, 'me' => true])
                                </div>
                            </div>
                        </div>
                        <div x-show="activeTab === 'horizontal'" class="horizontal-tree tree-view-block pt-3">
                            <div class="mobile_treeview">
                                <ul>
                                    <li>
                                        @include('frontend::referral.include.__mobile_tree',['levelUser' => auth()->user(),'level' => $level,'depth' => 1, 'me' => true])
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <div class="lg:col-span-3 col-span-12">
                <div class="h-full rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/3">
                    @php
                        $equityDetails = get_recursive_equity_details(auth()->user());
                    @endphp
                    <div class="space-y-6">
                        <!-- Simple Header -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Network Equity Summary') }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Portfolio overview') }}</p>
                        </div>
                        
                        <!-- Simple Equity Cards -->
                        <div class="flex flex-col gap-4">
                            <!-- Personal Equity Card -->
                            <article class="flex items-center gap-5 rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/3">
                                <div class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-white/90">
                                    <i data-lucide="user"></i>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-semibold text-gray-800 dark:text-white/90">
                                        {{ $currencySymbol.number_format($equityDetails['personal_equity'], 2) }}
                                    </h3>
                                    <p class="flex items-center gap-3 text-gray-500 dark:text-gray-400">
                                        {{ __('Personal Equity') }}
                                    </p>
                                </div>
                            </article>

                            <!-- Total Network Equity Card -->
                            <article class="flex items-center gap-5 rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/3">
                                <div class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-white/90">
                                    <i data-lucide="network"></i>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-semibold text-gray-800 dark:text-white/90">
                                        {{ $currencySymbol.number_format($equityDetails['total_equity'], 2) }}  
                                    </h3>
                                    <p class="flex items-center gap-3 text-gray-500 dark:text-gray-400">
                                        {{ __('Total Network Equity') }}
                                    </p>
                                </div>
                            </article>
                            
                        </div>

                        <!-- Simple Level-wise Equity Table -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                                <div class="flex items-center space-x-2">
                                    <i class="w-4 h-4 text-gray-600 dark:text-gray-400" data-lucide="layers"></i>
                                    <h5 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Level Distribution') }}</h5>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="bg-gray-50 dark:bg-gray-900">
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                                {{ __('Level') }}
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                                {{ __('Total Equity') }}
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                                {{ __('Referrals') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                        @foreach($equityDetails['levels_data'] as $level => $data)
                                            <tr>
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center space-x-2">
                                                        <div class="w-6 h-6 bg-brand-500 rounded-full flex items-center justify-center text-xs font-bold text-white">{{ $level }}</div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ $currencySymbol.number_format($data['level_equity'], 2) }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center space-x-2">
                                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $data['referral_count'] }}</span>
                                                        <div class="flex-1 bg-gray-200 dark:bg-gray-600 rounded-full h-1.5 max-w-[60px]">
                                                            <div class="bg-brand-500 h-1.5 rounded-full" style="width: {{ min(100, ($data['referral_count'] / max($equityDetails['referral_count'], 1)) * 100) }}%"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-gray-100 border-t-2 border-gray-200 dark:border-gray-600 dark:bg-white/[0.03]">
                                            <td class="px-4 py-4">
                                                <span class="text-sm font-bold text-gray-900 dark:text-white">{{ __('Total') }}</span>
                                            </td>
                                            <td class="px-4 py-4 text-sm font-bold text-gray-900 dark:text-white">
                                                {{ $currencySymbol.number_format($equityDetails['total_downline_equity'], 2) }}
                                            </td>
                                            <td class="px-4 py-4 text-sm font-bold text-gray-900 dark:text-white">
                                                {{ $equityDetails['referral_count'] }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Single Empty State for Both Network and Statistics --}}
        <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
            <div class="flex items-center justify-center flex-col py-10 px-10">
                <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="text-lg text-slate-600 dark:text-slate-100 mb-3">
                    {{ __("You don't have any referrals yet.") }}
                </p>
            </div>
        </div>
    @endif
</div>
@section('style')
    <style>
        .mobile_treeview ul li ul li {
            padding: 50px 0px 0px 50px;
            position: relative;
        }

        .mobile_treeview ul li ul li:before {
            content: "";
            position: absolute;
            top: -28px;
            left: 22px;
            border-left: 2px dashed #a2a5b5;
            width: 1px;
            height: 100%;
        }

        .mobile_treeview ul li ul li:after {
            content: "";
            position: absolute;
            border-top: 2px dashed #a2a5b5;
            top: 68px;
            left: 22px;
            width: 35px;
        }
        .hv-item-parent.hide-line:after {
            display: none !important;
        }
    </style>
@endsection

@push('script')
    <script>
        
    </script>
@endpush
