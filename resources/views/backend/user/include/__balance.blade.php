<div
    class="modal fade"
    id="addSubBal"
    tabindex="-1"
    aria-labelledby="addSubBalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content site-table-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubBalLabel">
                    {{ __('Balance Add or Subtract') }}
                </h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.user.balance-update',$user->id) }}" method="post">
                    @csrf
                    <input type="hidden" name="target_type" id="selectedAccountType" value="">

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="switch-field">
                                <input
                                    type="radio"
                                    id="addMon"
                                    name="type"
                                    value="add"
                                    checked
                                />
                                <label for="addMon">{{ __('Add') }}</label>
                                <input
                                    type="radio"
                                    id="addMon2"
                                    name="type"
                                    value="subtract"
                                />
                                <label for="addMon2">{{ __('Subtract') }}</label>
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <select class="form-select" name="target_id" id="tradingAccount">
                                @foreach($realForexAccounts as $forexAccount)
                                    <option value="{{$forexAccount->login}}" data-type="forex">
                                        {{ $forexAccount->login }} - {{ $forexAccount->account_name }}
                                        ({{ $forexAccount->equity }} {{$forexAccount->currency}})
                                    </option>
                                @endforeach
                                @if($user->ib_status == \App\Enums\IBStatus::APPROVED && isset($user->ib_login))
                                    <option value="{{ $user->ib_login }}" data-type="forex"
                                            data-type="forex">{{ $user->ib_login }}
                                        - {{ __('IB') }} ({{ $user->ib_balance }} {{$currency}})
                                    </option>
                                @endif
                            </select>

                        </div>


                        <div class="col-xl-12">
                            <div class="site-input-groups">
                                <div class="input-group joint-input">
                                    <span class="input-group-text">{{ setting('site_currency','global') }}</span>
                                    <input type="text" name="amount" oninput="this.value = validateDouble(this.value)"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="site-input-groups">
                                <label for="" class="box-input-label">{{ __('Comment Message') }}</label>
                                <textarea name="comment" class="form-textarea mb-0"
                                          placeholder="Comment Message"></textarea>
                            </div>
                        </div>

                        <div class="col-xl-12">
                            <button type="submit" class="site-btn primary-btn w-100">
                                {{ __('Apply Now') }}
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
