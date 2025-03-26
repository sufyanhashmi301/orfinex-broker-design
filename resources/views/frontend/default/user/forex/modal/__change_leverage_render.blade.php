<form action="" method="post">
    @csrf
    <input type="hidden" name="login" id="update-leverage-modal-login-id"  class="form-control" value="{{$forexTrading->login}}"  >
    <div class="input-area relative">
        <label class="form-label" for="">{{ __('Leverage') }}</label>
        <div class="select2-lg">
            <select name="leverage" class="select2 form-control !text-lg w-full mt-2 py-2" id="update-leverage-modal-leverage">
                <option value="default_option">Choose Leverage</option>
                @foreach(explode(',',$forexTrading->schema->leverage) as $leverage)
                    <option value="{{$leverage}}" @if($leverage == $forexTrading->leverage) selected @endif>1:{{$leverage}}</option>
                @endforeach
            </select>
            <small class="mt-1">The leverage range you want to adjust in account.</small>
        </div>
    </div>
    <div class="flex items-center mt-4">
        <button type="submit" class="btn btn-dark mr-2" id="submit-leverage">
            <i icon-name="check"></i>
            {{ __('Set Leverage') }}
        </button>
        <a href="#" class="btn btn-danger inline-flex" data-bs-dismiss="modal" aria-label="Close">
            <i icon-name="x"></i>
            {{ __('Close') }}
        </a>
    </div>
    <div class="divider border-b my-5"></div>
    <div class="flex">
        <p class="text-xs mb-0">
            Leverage of 1:2000 is only available when your equity is less than 5,000 USD. Your actual leverage could be lower depending on
            <a href="#" class="text-gray">various conditions</a>.
        </p>
    </div>
</form>
