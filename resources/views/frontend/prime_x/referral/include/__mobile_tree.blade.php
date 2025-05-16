<div class="treeview__level relative z-[1]" data-level="{{ $level }}">
    <div class="flex items-center">
        <div class="flex-none">
            <div class="w-8 h-8 rounded-[100%] ltr:mr-3 rtl:ml-3">
                <img src="{{ getFilteredPath($levelUser->avatar, 'fallback/user.png') }}" alt="" class="w-full h-full rounded-[100%] object-cover">
            </div>
        </div>
        <div class="flex-1 text-start">
            @if($me)
                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                    {{ __("It's Me") }}( {{ $levelUser->full_name }} )
                </h4>
            @else
                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                    {{ $levelUser->full_name }}
                </h4>
                <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                    {{ __('Deposit') }} {{ $currencySymbol.$levelUser->totalDeposit() }},
                    {{ __('Accounts Balance') }} {{ mt5_total_balance($levelUser->id) }}
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
