<div class="treeview__level relative z-[1]" data-level="{{ $level }}">
    <div class="flex items-center" style="padding-left: 20px;">
        <div class="flex-none absolute left-0">
            <div class="w-12 h-12 rounded-[100%] bg-white border border-slate-100 dark:bg-slate-800 dark:border-slate-700">
                <img src="{{ getFilteredPath($levelUser->avatar, 'fallback/user.png') }}" alt="" class="w-full h-full rounded-[100%] object-cover">
            </div>
        </div>
        <div class="text-start inline-flex items-center rounded border border-slate-100 dark:border-slate-700 gap-3 p-2 py-1 pl-8">
            @if($me)
                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap py-2">
                    {{ __("It's Me") }}( {{ $levelUser->full_name }} )
                </h4>
            @else
                <div>
                    <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                        {{ $levelUser->full_name }}
                    </h4>
                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                        {{ __('Deposit') }} {{ $currencySymbol.$levelUser->totalDeposit() }},
                        {{ __('Accounts Balance') }} {{ mt5_total_balance($levelUser->id) }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@if($depth && $level > $depth && $levelUser->referrals->count() > 0)
    <ul>
        @foreach($levelUser->referrals as $levelUser)
            <li>
                @include('frontend::referral.include.__mobile_tree',['levelUser' => $levelUser,'level' => $level,'depth' => $depth + 1,'me' => false])
            </li>
        @endforeach
    </ul>
@endif
