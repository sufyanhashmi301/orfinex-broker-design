<!-- Desktop View -->
<div class="desktop-screen-show md:block hidden">
    @if($userIbRules->isEmpty())
        <div class="flex items-center justify-center flex-col py-10 px-10">
            <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="text-lg text-slate-600 dark:text-slate-100 mb-3">
                {{ __("No IB distribution rules found.") }}
            </p>
        </div>
    @else
        <div class="custom-scrollbar overflow-x-auto">
            <table class="w-full table-auto">
                <thead class="border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th scope="col" class="px-5 py-3 text-left sm:px-6">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Rebate Rule') }}</span>
                        </th>
                        <th scope="col" class="px-5 py-3 text-left sm:px-6">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Symbols') }}</span>
                        </th>
                        <th scope="col" class="px-5 py-3 text-left sm:px-6">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Total Rebate') }}</span>
                        </th>
                        <th scope="col" class="px-5 py-3 text-left sm:px-6">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Master IB Share') }}</span>
                        </th>
                        <th scope="col" class="px-5 py-3 text-left sm:px-6">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Action') }}</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800" id="rules-table-body">
                    @foreach($userIbRules as $userIbRule)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <!-- Rebate Rule -->
                            <td class="px-5 py-4 sm:px-6">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $userIbRule->rebateRule->title }}</span>
                            </td>
                            <!-- Symbols -->
                            <td class="px-5 py-4 sm:px-6">
                                <div class="flex flex-wrap items-center gap-2">
                                    @foreach($userIbRule->rebateRule->symbolGroups->flatMap->symbols as $symbol)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 uppercase">{{ $symbol->symbol }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <!-- Rebate Amount -->
                            <td class="px-5 py-4 sm:px-6">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">${{ $userIbRule->rebateRule->rebate_amount }}</span>
                            </td>

                            <!-- Master IB Share -->
                            <td class="px-5 py-4 sm:px-6">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">${{$userIbRule->rebateRule->rebate_amount - $userIbRule->sub_ib_share }}</span>
                            </td>
                            <!-- Action -->
                            <td class="px-5 py-4 sm:px-6">
                                <a href="{{ route('user.ib.rule.levels', ['id' => $userIbRule->id]) }}" class="inline-flex items-center justify-center w-8 h-8 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Mobile View -->
<div class="md:hidden block mobile-screen-show">
    @if($userIbRules->isEmpty())
        <div class="basicTable_wrapper card flex items-center justify-center flex-col p-4">
            <svg width="42" height="43" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="text-sm text-slate-600 dark:text-slate-100 my-3">
                {{ __("No IB distribution rules found.") }}
            </p>
        </div>
    @else
        <div class="card all-feature-mobile mobile-rules mb-3">
            <div class="card-body p-3">
                <div class="contents space-y-3" id="mobile-rules-container">
                    @foreach($userIbRules as $userIbRule)
                        <div class="single-rule text-xs bg-slate-100 dark:bg-slate-900 rounded-md p-3">
                            <div class="rule-header mb-3">
                                <div class="rule-title font-semibold dark:text-white mb-2">
                                    {{ $userIbRule->rebateRule->title }}
                                </div>
                                <div class="rule-symbols flex flex-wrap gap-1 mb-2">
                                    @foreach($userIbRule->rebateRule->symbolGroups->flatMap->symbols as $symbol)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-200 uppercase">{{ $symbol->symbol }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="rule-details grid grid-cols-2 gap-3 mb-3">
                                <div class="detail-item">
                                    <span class="text-gray-500 dark:text-gray-400 block">{{ __('Total Rebate') }}:</span>
                                    <span class="font-medium dark:text-white">${{ $userIbRule->rebateRule->rebate_amount }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="text-gray-500 dark:text-gray-400 block">{{ __('Master IB Share') }}:</span>
                                    <span class="font-medium dark:text-white">${{$userIbRule->rebateRule->rebate_amount - $userIbRule->sub_ib_share }}</span>
                                </div>
                            </div>
                            <div class="rule-action">
                                <x-link-button href="{{ route('user.ib.rule.levels', ['id' => $userIbRule->id]) }}" variant="outline" icon="pencil" icon-position="left" class="w-full">
                                    {{ __('Edit') }}
                                </x-link-button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
