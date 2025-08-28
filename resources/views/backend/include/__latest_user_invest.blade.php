<div class="col-span-12">
    <div class="card enhanced-card">
        <div class="enhanced-card-header">
            <div class="flex justify-between items-center">
                <h3 class="enhanced-card-title">
                    <iconify-icon icon="lucide:users" class="text-slate-600 dark:text-slate-400"></iconify-icon>
                    {{ __('Latest Registered User') }}
                </h3>
                <a href="{{ route('admin.user.index') }}" class="inline-flex items-center text-sm font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 transition-colors duration-200">
                    {{ __('View All Users') }}
                    <iconify-icon class="text-base ltr:ml-2 rtl:mr-2" icon="lucide:arrow-right"></iconify-icon>
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                    {{ __('User') }}
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                    {{ __('Balance') }}
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                    {{ __('Profit') }}
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                    {{ __('KYC') }}
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                    {{ __('Status') }}
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                                    {{ __('Action') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($data['latest_user']->take(10) as $user)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.user.edit', $user->id) }}" class="flex items-center space-x-4 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                                            <div class="flex-shrink-0">
                                                <div class="h-10 w-10 rounded-full ring-2 ring-slate-200 dark:ring-slate-600 overflow-hidden">
                                                    <img src="{{ getFilteredPath($user->avatar, 'fallback/user.png') }}" alt="" class="h-full w-full object-cover">
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-sm font-medium text-slate-900 dark:text-white truncate">
                                                    {{ safe($user->full_name) }}
                                                </div>
                                                <div class="text-xs text-slate-500 dark:text-slate-400 truncate">
                                                    {{ safe($user->email) }}
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-slate-900 dark:text-white">
                                            {{ $currencySymbol . $user->balance }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                                            {{ $currencySymbol . $user->total_profit }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($user->kyc)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                <iconify-icon icon="lucide:check-circle" class="w-3 h-3 mr-1"></iconify-icon>
                                                {{ __('Verified') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">
                                                <iconify-icon icon="lucide:clock" class="w-3 h-3 mr-1"></iconify-icon>
                                                {{ __('Unverified') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($user->status)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full"></span>
                                                {{ __('Active') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                                <span class="w-1.5 h-1.5 mr-1.5 bg-red-500 rounded-full"></span>
                                                {{ __('Deactivated') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center items-center space-x-2">
                                            <a href="{{ route('admin.user.edit', $user->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 transition-colors duration-200 toolTip onTop" data-tippy-content="Edit">
                                                <iconify-icon icon="lucide:edit-3" class="w-4 h-4"></iconify-icon>
                                            </a>
                                            <button type="button" data-id="{{ $user->id }}" data-name="{{ $user->first_name.' '.$user->last_name }}" class="send-mail inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-400 dark:hover:bg-slate-600 transition-colors duration-200 toolTip onTop" data-tippy-content="Send Email">
                                                <iconify-icon icon="lucide:mail" class="w-4 h-4"></iconify-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center space-y-3">
                                            <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center">
                                                <iconify-icon icon="lucide:users" class="text-xl text-slate-400"></iconify-icon>
                                            </div>
                                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('No Data Found') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>