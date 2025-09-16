@php
    $user = \App\Models\User::find($user_id);
@endphp
@if ($user->in_grace_period)
    <div class="flex items-center opacity-50 cursor-not-allowed" title="User is in grace period - editing disabled">
        <div class="flex-none">
            <div class="w-8 h-8 rounded-[100%] ltr:mr-3 rtl:ml-3">
                <img src="{{ getFilteredPath($user->avatar, 'fallback/user.png') }}" alt=""
                    class="w-full h-full rounded-[100%] object-cover">
            </div>
        </div>
        <div class="flex-1 text-start">
            <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                {{ $user->full_name }}
            </h4>
            <div class="text-xs font-normal lowercase text-slate-600 dark:text-slate-400">
                {{ safe($user->email) }}
            </div>
        </div>
    </div>
@else
    <a href="{{ route('admin.user.edit', $user->id) }}" class="flex items-center">
        <div class="flex-none">
            <div class="w-8 h-8 rounded-[100%] ltr:mr-3 rtl:ml-3">
                <img src="{{ getFilteredPath($user->avatar, 'fallback/user.png') }}" alt=""
                    class="w-full h-full rounded-[100%] object-cover">
            </div>
        </div>
        <div class="flex-1 text-start">
            <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                {{ $user->full_name }}
            </h4>
            <div class="text-xs font-normal lowercase text-slate-600 dark:text-slate-400">
                {{ safe($user->email) }}
            </div>
        </div>
    </a>
@endif
