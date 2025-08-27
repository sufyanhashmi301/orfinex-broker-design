<div x-data="{ open: false }" class="hv-item flex flex-col m-auto">
    <!-- Parent node with connecting line -->
    <div class="hv-item-parent mb-12 relative flex justify-center 
        after:absolute after:content-[''] after:w-0.5 after:h-6 after:bottom-0 after:left-1/2 
        after:bg-gray-500 after:transform after:translate-y-full after:-translate-x-1/2
        last:after:hidden"
        :class="{ 'hide-line': !open }">
        <div class="person flex flex-col items-center z-10 relative">
            <!-- Avatar with enhanced styling -->
            <div class="w-16 h-16 rounded-full bg-white border-2 {{ $me ? 'border-blue-400 ring-2 ring-blue-200' : 'border-gray-200' }} shadow-lg dark:bg-slate-800 dark:border-slate-600 transition-all duration-300 hover:scale-105 hover:shadow-xl">
                <img src="{{ getFilteredPath($levelUser->avatar, 'fallback/user.png') }}" alt="" class="w-full h-full rounded-full object-cover">
            </div>
            <!-- Enhanced user info card -->
            <div class="text-center inline-flex flex-col rounded-xl border {{ $me ? 'border-blue-200 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 dark:border-blue-700' : 'border-gray-200 bg-white dark:border-slate-700 dark:bg-slate-800' }} 
                p-4 mt-3 shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-1
                min-w-max backdrop-blur-sm group">
                @if($me)
                    <div class="flex items-center justify-center gap-2 mb-2">
                        <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                        <h4 class="text-sm font-bold text-blue-700 dark:text-blue-300 whitespace-nowrap">
                            {{ __("It's Me") }}
                        </h4>
                        <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                    </div>
                    <p class="text-xs font-medium text-slate-600 dark:text-slate-300">
                        {{ $levelUser->full_name }}
                    </p>
                @else
                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-200 whitespace-nowrap mb-2 group-hover:text-blue-600 transition-colors">
                        {{ $levelUser->full_name }}
                    </h4>
                    <div class="space-y-1.5 text-xs">
                        <!-- Deposit info with enhanced styling -->
                        <div class="flex items-center justify-center gap-1.5 bg-green-50 dark:bg-green-900/20 px-2 py-1 rounded-md">
                            <span class="text-xs font-semibold text-green-600 dark:text-green-400">{{ __('Deposit') }}:</span>
                            <span class="font-medium text-green-700 dark:text-green-300">
                                {{ $currencySymbol }}{{ number_format($levelUser->totalDeposit(), 2) }}
                            </span>
                        </div>
                        <!-- Balance info with enhanced styling -->
                        <div class="flex items-center justify-center gap-1.5 bg-blue-50 dark:bg-blue-900/20 px-2 py-1 rounded-md">
                            <span class="text-xs font-semibold text-blue-600 dark:text-blue-400">{{ __('Balance') }}:</span>
                            <span class="font-medium text-blue-700 dark:text-blue-300">
                                {{ $currencySymbol }}{{ number_format(mt5_total_balance($levelUser->id), 2) }}
                            </span>
                        </div>
                        <!-- Referral count badge -->
                        @if($levelUser->referrals->count() > 0)
                            <div class="flex items-center justify-center gap-1.5 bg-purple-50 dark:bg-purple-900/20 px-2 py-1 rounded-md">
                                <span class="text-xs font-semibold text-purple-600 dark:text-purple-400">{{ __('Referrals') }}:</span>
                                <span class="font-medium text-purple-700 dark:text-purple-300">
                                    {{ $levelUser->referrals->count() }}
                                </span>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
            @if($levelUser->referrals->count() > 0)
                <button type="button"
                    @click="open = !open"
                    class="inline-flex h-6 w-6 items-center justify-center rounded-full border-[0.5px] border-gray-200 bg-gray-50 text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400 my-1 toggle-btn">
                    <span x-text="open ? '-' : '+'"></span>
                </button>
            @endif
        </div>
    </div>

    @if($depth && $level > $depth && $levelUser->referrals->count() > 0)
        <!-- Children container with horizontal connecting lines -->
        <div class="hv-item-children flex justify-center" x-show="open" x-transition>
            @foreach($levelUser->referrals as $childUser)
                <div class="hv-item-child relative px-4 
                    before:absolute before:content-[''] before:left-1/2 before:top-0 before:transform before:-translate-y-full before:w-0.5 before:h-6 before:bg-gray-500
                    after:absolute after:content-[''] after:left-0 after:top-[-24px] after:transform after:-translate-y-full after:h-0.5 after:w-full after:bg-gray-500
                    first:after:left-1/2 first:after:w-1/2
                    last:after:w-[calc(50%+1px)] last:after:left-0
                    only:after:hidden">
                    <!-- Recursive tree component -->
                    @include('frontend::referral.include.__tree',['levelUser' => $childUser,'level' => $level,'depth' => $depth + 1,'me' => false])
                </div>
            @endforeach
        </div>
    @endif

</div>
