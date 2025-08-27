@php use App\Enums\TxnStatus; use App\Enums\TxnType; @endphp

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="flex items-center justify-between border-b border-gray-200 px-4 py-5 xl:px-6 xl:py-6 dark:border-gray-800">
        <div class="flex-shrink-0">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                {{ __('Referral Members') }}
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('Manage and view your referred members') }}
            </p>
        </div>
        <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-3">
            <div class="input-area relative">
                <select id="member-status" class="dark:bg-dark-900 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                    <option value="">{{ __('All Members') }}</option>
                    <option value="active">{{ __('Active') }}</option>
                    <option value="inactive">{{ __('Inactive') }}</option>
                </select>
            </div>
        </div>
    </div>

    <div class="desktop-screen-show md:block hidden">
        @if(count($referrals) == 0)
            <div class="flex items-center justify-center flex-col py-10 px-10">
                <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="text-lg text-slate-600 dark:text-slate-100 mb-3">
                    {{ __("You don't have any referral members yet.") }}
                </p>
            </div>
        @else
            <div class="custom-scrollbar overflow-x-auto">
                <table class="w-full table-auto">
                    <thead class="border-b border-gray-100 dark:border-gray-800">
                        <tr>
                            <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('User') }}</span>
                            </th>
                            <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Phone') }}</span>
                            </th>
                            <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Balance') }}</span>
                            </th>
                            <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Equity') }}</span>
                            </th>
                            <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Credit') }}</span>
                            </th>
                            <th scope="col" class="px-5 py-3 text-left sm:px-6">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Join Date') }}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800" id="referrals-table-body">
                        @foreach($referrals as $referral)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center">
                                        <div class="flex-none">
                                            <div class="w-8 h-8 rounded-full ltr:mr-3 rtl:ml-3">
                                                <img src="{{ getFilteredPath($referral->avatar, 'fallback/user.png') }}" alt="" class="w-full h-full rounded-full object-cover">
                                            </div>
                                        </div>
                                        <div class="flex-1 text-start">
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                                {{ $referral->full_name }}
                                            </h4>
                                            <div class="text-xs font-normal text-gray-500 dark:text-gray-400">
                                                {{ $referral->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-sm text-gray-900 dark:text-white">
                                        {{ $referral->phone ? $referral->phone : 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ mt5_total_balance($referral->id) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ mt5_total_equity($referral->id) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ mt5_total_credit($referral->id) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $referral->created_at->format('M d, Y') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Pagination section if needed --}}
            <div class="flex flex-wrap justify-between items-center border-t border-gray-100 dark:border-gray-800 gap-3 px-4 py-3 mt-auto">
                <div>
                    <p class="text-sm text-gray-700 dark:text-slate-300 px-3">
                        {{ __('Showing') }}
                        <span class="font-medium">{{ count($referrals) }}</span>
                        {{ __('members') }}
                    </p>
                </div>
            </div>
        @endif
    </div>

    <div class="md:hidden block mobile-screen-show">
        @if(count($referrals) == 0)
            <div class="basicTable_wrapper card flex items-center justify-center flex-col p-4">
                <svg width="42" height="43" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="text-sm text-slate-600 dark:text-slate-100 my-3">
                    {{ __("You don't have any referral members yet.") }}
                </p>
            </div>
        @else
            <div class="card all-feature-mobile mobile-members mb-3">
                <div class="card-header">
                    <h4 class="card-title">{{ __('Referral Members') }}</h4>
                </div>
                <div class="card-body p-3">
                    <div class="contents space-y-3" id="mobile-members-container">
                        @foreach($referrals as $referral)
                            <div class="single-member flex items-center text-xs bg-slate-100 dark:bg-slate-900 rounded-md p-3">
                                <div class="member-avatar flex-none">
                                    <div class="w-10 h-10 rounded-full ltr:mr-3 rtl:ml-3">
                                        <img src="{{ getFilteredPath($referral->avatar, 'fallback/user.png') }}" alt="" class="w-full h-full rounded-full object-cover">
                                    </div>
                                </div>
                                <div class="member-info flex-1">
                                    <div class="member-name font-semibold dark:text-white mb-1">
                                        {{ $referral->full_name }}
                                    </div>
                                    <div class="member-email text-gray-500 dark:text-gray-400 mb-1">
                                        {{ $referral->email }}
                                    </div>
                                    <div class="member-stats grid grid-cols-2 gap-2 text-xs">
                                        <div class="stat-item">
                                            <span class="text-gray-500 dark:text-gray-400">{{ __('Balance') }}:</span>
                                            <span class="font-medium dark:text-white">{{ mt5_total_balance($referral->id) }}</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="text-gray-500 dark:text-gray-400">{{ __('Equity') }}:</span>
                                            <span class="font-medium dark:text-white">{{ mt5_total_equity($referral->id) }}</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="text-gray-500 dark:text-gray-400">{{ __('Credit') }}:</span>
                                            <span class="font-medium dark:text-white">{{ mt5_total_credit($referral->id) }}</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="text-gray-500 dark:text-gray-400">{{ __('Joined') }}:</span>
                                            <span class="font-medium dark:text-white">{{ $referral->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
