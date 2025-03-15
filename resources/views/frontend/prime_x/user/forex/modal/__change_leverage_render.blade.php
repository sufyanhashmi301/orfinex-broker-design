<form action="" method="post">
    @csrf
    <input type="hidden" name="login" id="update-leverage-modal-login-id" class="form-control" value="{{$forexTrading->login}}">
    <div class="input-area relative">
        <label class="form-label" for="">{{ __('Leverage:') }}</label>
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
    <div class="flex items-center mt-4">
        <button type="submit" class="btn btn-primary mr-2" id="submit-leverage">
            <i icon-name="check"></i>
            {{ __('Set Leverage') }}
        </button>
        <a href="#" class="btn btn-outline-dark inline-flex" data-bs-dismiss="modal" aria-label="Close">
            <i icon-name="x"></i>
            {{ __('Close') }}
        </a>
    </div>
    <div class="divider border-b dark:border-slate-700 my-5"></div>
    <div class="flex">
        <p class="text-xs text-slate-400 dark:text-slate-300 mb-0">
            {{ __('Disclaimer: The leverage you select is subject to market conditions and internal policies of '. setting('site_title', 'global') .'. Please be aware that leverage can increase both gains and losses. '. setting('site_title', 'global') .' will not be held responsible for any risks or financial losses incurred through leverage adjustments.') }}
        </p>
    </div>
</form>
