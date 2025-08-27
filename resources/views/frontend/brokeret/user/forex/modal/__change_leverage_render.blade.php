<form @submit.prevent="$store.modals.updateLeverage($refs)">
    @csrf
    <input type="hidden" x-ref="login" :value="$store.modals.data.login">
    <div class="relative">
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400" for="">
            {{ __('Leverage:') }}
        </label>
        <select x-ref="leverage"
            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
            <option class="text-gray-700 dark:bg-gray-900 dark:text-gray-400" value="default_option">
                {{ __('Choose Leverage') }}
            </option>
            @foreach(explode(',', $forexTrading->schema->leverage) as $leverage)
                <option class="text-gray-700 dark:bg-gray-900 dark:text-gray-400" value="{{$leverage}}" @if($leverage == $forexTrading->leverage) selected @endif>
                    1:{{$leverage}}
                </option>
            @endforeach
        </select>
        <small class="text-gray-500 dark:text-gray-400 mt-1">
            {{ __('Choose the leverage ratio you want to adjust for this account.') }}
        </small>
    </div>
    <div class="flex items-center mt-4">
        <button type="submit" x-ref="submitBtn" class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-3.5 text-sm font-medium text-white shadow-theme-xs transition hover:bg-brand-600 mr-2" id="submit-leverage">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="check" class="lucide lucide-check w-4 h-4"><path d="M20 6 9 17l-5-5"></path></svg>
            {{ __('Set Leverage') }}
        </button>
        <a href="#" @click="$store.modals.close()" class="inline-flex items-center gap-2 rounded-lg bg-white px-5 py-3.5 text-sm font-medium text-gray-700 shadow-theme-xs ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]" data-bs-dismiss="modal" aria-label="Close">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="x" class="lucide lucide-x w-4 h-4"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
            {{ __('Close') }}
        </a>
    </div>
    <div class="border-b border-gray-100 dark:border-gray-800 my-5"></div>
    <div class="flex">
        <small class="text-gray-500 dark:text-gray-400 mb-0">
            {{ __('Disclaimer: The leverage you select is subject to market conditions and internal policies of '. setting('site_title', 'global') .'. Please be aware that leverage can increase both gains and losses. '. setting('site_title', 'global') .' will not be held responsible for any risks or financial losses incurred through leverage adjustments.') }}
        </small>
    </div>
</form>
