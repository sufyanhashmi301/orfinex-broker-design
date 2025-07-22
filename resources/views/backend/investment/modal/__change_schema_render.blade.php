<form action="" method="post">
    @csrf
    <input type="hidden" name="login" id="update-forex-schema-modal-login-id" class="form-control" value="{{ $forexTrading->login }}">
    <input type="hidden" name="user_id" id="update-forex-schema-modal-user-id" value="7222">

    <div class="input-area relative">
        <label class="form-label" for="forex-schema-id">
            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Select the account schema you want to assign for this account.">
                {{ __('Account Schema') }}
                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
            </span>
        </label>
        <div class="select2-lg">
            <select name="forex_schema_id" class="select2 form-control !text-lg w-full mt-2 py-2" id="forex-schema-id">
                <option value="">{{ __('Choose Account Schema') }}</option>
                @foreach($schemas as $schema)
                    <option value="{{ $schema->id }}" @if($schema->id == $forexTrading->forex_schema_id) selected @endif>
                       {{ $schema->title }}
                    </option>
                @endforeach
            </select>
            <small class="dark:text-slate-300 mt-1">
                {{ __('Choose the account schema you want to assign for this account.') }}
            </small>
        </div>
    </div>

    <div class="flex items-center justify-end mt-10">
        <button type="submit" class="btn btn-primary inline-flex items-center justify-center mr-2" id="submit-forex-schema">
            <span class="flex items-center">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                {{ __('Set Account Schema') }}
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
            {{ __('Disclaimer: Please note that any changes will also update the account group on the platform.')}}
        </p>
    </div>
</form>
