<form action="" method="post">
    @csrf
    <input type="hidden" name="login" id="update-forex-schema-modal-login-id" class="form-control" value="{{ $forexTrading->login }}">
    <input type="hidden" name="user_id" id="update-forex-schema-modal-user-id" value="7222">
    
    <div class="input-area relative">
        <label class="form-label" for="forex-schema-id">{{ __('Forex Schema:') }}</label>
        <div class="select2-lg">
            <select name="forex_schema_id" class="select2 form-control !text-lg w-full mt-2 py-2" id="forex-schema-id">
                <option value="">{{ __('Choose Forex Schema') }}</option>
                @foreach($schemas as $schema)
                    <option value="{{ $schema->id }}" @if($schema->id == $forexTrading->forex_schema_id) selected @endif>
                       {{ $schema->title }}
                    </option>
                @endforeach
            </select>
            <small class="dark:text-slate-300 mt-1">
                {{ __('Choose the forex schema you want to assign for this account.') }}
            </small>
        </div>
    </div>
    
    <div class="flex items-center mt-4">
        <button type="submit" class="btn btn-primary inline-flex items-center justify-center mr-2" id="submit-forex-schema">
            <i icon-name="check"></i>
            {{ __('Set Forex Schema') }}
        </button>
        <a href="#" class="btn btn-outline-dark inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
            <i icon-name="x"></i>
            {{ __('Close') }}
        </a>
    </div>
    
    <div class="divider border-b dark:border-slate-700 my-5"></div>
    
    <div class="flex">
        <p class="text-xs text-slate-400 dark:text-slate-300 mb-0">
            {{ __('Disclaimer: The forex schema you select is subject to market conditions and internal policies of ' . setting('site_title', 'global') . '. Please be aware that changes may affect account parameters. ' . setting('site_title', 'global') . ' is not liable for any discrepancies.') }}
        </p>
    </div>
</form>
