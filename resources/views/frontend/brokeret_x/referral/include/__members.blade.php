@php use App\Enums\TxnStatus; use App\Enums\TxnType; @endphp

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
    <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90">
        {{ __('Referral Members') }}
    </h2>

    <div class="flex-1 flex flex-col sm:flex-row sm:justify-end sm:items-center gap-3">
        <div class="input-area relative">
            <select id="member-status" class="dark:bg-dark-900 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                <option value="">{{ __('All Members') }}</option>
                <option value="active">{{ __('Active') }}</option>
                <option value="inactive">{{ __('Inactive') }}</option>
            </select>
        </div>
    </div>
</div>

@if(count($referrals) == 0)
    <x-frontend::empty-state icon="inbox">
        <x-slot name="title">
            {{ __("You don't have any referral members yet.") }}
        </x-slot>
    </x-frontend::empty-state>
@else
    <div class="desktop-screen-show md:block hidden">
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
    </div>

    <div class="md:hidden block mobile-screen-show">
        <div class="card all-feature-mobile mobile-members mb-3">
            <div class="card-body p-3">
                <div class="contents space-y-3" id="mobile-members-container">
                    @foreach($referrals as $referral)
                        <div class="single-member text-xs bg-slate-100 dark:bg-slate-900 rounded-md p-2 py-3">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-full max-w-8 items-center rounded-full">
                                    <img src="{{ getFilteredPath($referral->avatar, 'fallback/user.png') }}" alt="" class="w-full h-full rounded-full object-cover">
                                </div>
                                <div class="flex flex-col gap-1">
                                    <p class="text-sm font-semibold text-gray-800 dark:text-white/90">
                                        {{ $referral->full_name }}
                                    </p>
                                    <span class="block text-xs text-gray-500 dark:text-gray-400">
                                        {{ $referral->email }}
                                    </span>
                                </div>
                            </div>
                            <div class="member-info">
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
    </div>
@endif
