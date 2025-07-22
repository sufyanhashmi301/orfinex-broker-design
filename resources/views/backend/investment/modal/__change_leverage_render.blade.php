<form action="" method="post">
    @csrf
    <input type="hidden" name="login" id="update-leverage-modal-login-id" class="form-control" value="{{$forexTrading->login}}">
    <input type="hidden" name="user_id" id="update-leverage-modal-user-id" value="7222">
    <div class="input-area relative">
        <label class="form-label" for="">
            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Select the leverage ratio you want to adjust for this account.">
                {{ __('Leverage') }}
                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
            </span>
        </label>
        <div class="select2-lg">
            <select name="leverage" class="select2 form-control !text-lg w-full mt-2 py-2" id="update-leverage-modal-leverage">
                <option value="default_option">{{ __('Choose Leverage') }}</option>
                @foreach(explode(',', $forexTrading->schema->leverage) as $leverage)
                    <option value="{{$leverage}}" @if($leverage == $forexTrading->leverage) selected @endif>1:{{$leverage}}</option>
                @endforeach
            </select>
            <small class="dark:text-slate-300 mt-1">
                {{ __('Choose the leverage ratio you want to adjust for this account.') }}
            </small>
        </div>
    </div>
    <div class="flex items-center justify-end mt-10">
        <button type="submit" class="btn btn-primary inline-flex items-center justify-center mr-2" id="submit-leverage">
            <span class="flex items-center">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                {{ __('Set Leverage') }}
            </span>
        </button>
        <a href="#" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
            <span class="flex items-center">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                {{ __('Close') }}
            </span>
        </a>
    </div>
    <div class="divider border-b dark:border-slate-700 my-5"></div>
    <div class="flex">
        <p class="text-xs text-slate-400 dark:text-slate-300 mb-0">
            {{ __('Disclaimer: The leverage you select is subject to market conditions and internal policies of '. setting('site_title', 'global') .'. Please be aware that leverage can increase both gains and losses. '. setting('site_title', 'global') .' will not be held responsible for any risks or financial losses incurred through leverage adjustments.') }}
        </p>
    </div>
</form>
