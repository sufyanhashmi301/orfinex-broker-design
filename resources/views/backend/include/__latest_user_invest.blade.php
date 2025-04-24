<div class="col-span-12">
    <div class="card">
        <div class="card-header noborder flex justify-between items-center">
            <h3 class="card-title text-slate-800 font-semibold text-base">
                {{ __('Latest Registered User') }}
            </h3>
            <a href="{{ route('admin.user.index') }}" class="text-sm text-blue-600 hover:underline">
                {{ __('View All Users') }}
            </a>
        </div>

        <div class="card-body px-6 pb-4">
            <!-- Table -->
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden rounded-md">
                        <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700 text-sm text-slate-700 dark:text-slate-300">
                            <thead class="bg-slate-50 dark:bg-slate-800">
                                <tr class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                    <th class="table-th">{{ __('User') }}</th>
                                    <th class="table-th">{{ __('Balance') }}</th>
                                    <th class="table-th">{{ __('Profit') }}</th>
                                    <th class="table-th">{{ __('KYC') }}</th>
                                    <th class="table-th">{{ __('Status') }}</th>
                                    <th class="table-th text-center">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @forelse($data['latest_user']->take(10) as $user)
                                    <tr>
                                        <td class="table-td">
                                            <a href="{{ route('admin.user.edit', $user->id) }}" class="flex items-center">
                                                <div class="flex-none">
                                                    <div class="w-8 h-8 rounded-[100%] ltr:mr-3 rtl:ml-3">
                                                        <img src="{{ getFilteredPath($user->avatar, 'global/materials/user.png') }}" alt="" class="w-full h-full rounded-[100%] object-cover">
                                                    </div>
                                                </div>
                                                <div class="flex-1 text-start">
                                                    <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                        {{ safe($user->full_name) }}
                                                    </h4>
                                                    <div class="text-xs font-normal lowercase text-slate-600 dark:text-slate-400">
                                                        {{ safe($user->email) }}
                                                    </div>
                                                </div>
                                            </a>
                                        </td>
                                        <td class="table-td font-medium">{{ $currencySymbol . $user->balance }}</td>
                                        <td class="table-td font-medium">{{ $currencySymbol . $user->total_profit }}</td>
                                        <td class="table-td">
                                            <span class="badge {{ $user->kyc ? 'badge-success' : 'badge-warning' }}">
                                                {{ $user->kyc ? __('Verified') : __('Unverified') }}
                                            </span>
                                        </td>
                                        <td class="table-td">
                                            <span class="badge {{ $user->status ? 'badge-success' : 'badge-danger' }}">
                                                {{ $user->status ? __('Active') : __('Deactivated') }}
                                            </span>
                                        </td>
                                        <td class="table-td text-center">
                                            <div class="flex justify-center items-center gap-3">
                                                <a href="{{ route('admin.user.edit', $user->id) }}" class="action-btn toolTip onTop" data-tippy-content="Edit">
                                                    <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                                </a>
                                                <button type="button" data-id="{{ $user->id }}" data-name="{{ $user->first_name.' '.$user->last_name }}" class="send-mail action-btn toolTip onTop" data-tippy-content="Send Email">
                                                    <iconify-icon icon="lucide:mail"></iconify-icon>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="table-td text-center">
                                            {{ __('No Data Found') }}
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
</div>
