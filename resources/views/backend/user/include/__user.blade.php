@if ($row->in_grace_period)
    <div class="flex items-center opacity-50 cursor-not-allowed" title="User is in grace period - editing disabled">
        <div class="flex-none">
            <div class="w-8 h-8 rounded-full ltr:mr-3 rtl:ml-3">
                <img src="{{ getFilteredPath($row->avatar, 'fallback/user.png') }}" alt=""
                    class="w-full h-full rounded-full object-cover">
            </div>
        </div>
        <div class="flex-1 text-start">
            @php
                $isCompanyApproved = \App\Models\CompanyFormSubmission::where('user_id', $row->id)->where('status','approved')->exists();
            @endphp
            <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap flex items-center gap-1">
                {{ safe($row->full_name) }}
                @if($isCompanyApproved)
                    <span class="badge bg-success-500 text-white text-[9px] leading-3 px-1 py-[1px] ml-1 uppercase">{{ __('C') }}</span>
                @endif
            </h4>
            <div class="text-xs font-normal lowercase text-slate-600 dark:text-slate-400">
                {{ safe($row->email) }}
            </div>
        </div>
    </div>
@else
    <a href="{{ route('admin.user.edit', $row->id) }}" class="flex items-center">
        <div class="flex-none">
            <div class="w-8 h-8 rounded-full ltr:mr-3 rtl:ml-3">
                <img src="{{ getFilteredPath($row->avatar, 'fallback/user.png') }}" alt=""
                    class="w-full h-full rounded-full object-cover">
            </div>
        </div>
        <div class="flex-1 text-start">
            @php
                $isCompanyApproved = \App\Models\CompanyFormSubmission::where('user_id', $row->id)->where('status','approved')->exists();
            @endphp
            <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap flex items-center gap-1">
                {{ safe($row->full_name) }}
                @if($isCompanyApproved)
                    <span class="badge bg-success-500 text-white text-[9px] leading-3 px-1 py-[1px] ml-1 uppercase">{{ __('Company') }}</span>
                @endif
            </h4>
            <div class="text-xs font-normal lowercase text-slate-600 dark:text-slate-400">
                {{ safe($row->email) }}
            </div>
        </div>
        </div>
@endif
