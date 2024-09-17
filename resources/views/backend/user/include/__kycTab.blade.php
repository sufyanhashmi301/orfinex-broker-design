<div class="tab-pane space-y-5 fade" id="pills-kyc" role="tabpanel" aria-labelledby="pills-kyc-tab">
    <div class="card basicTable_wrapper">
        <div class="card-header">
            <div>
                <h4 class="card-title">{{ __('Manage Client KYC') }}</h4>
                <p class="card-text">
                    {{ __('Ensure this client’s KYC status is up to date by adjusting their verification level based on the documentation received.') }}
                </p>
            </div>
        </div>
        <div class="card-body p-6 pt-3">
            <div class="max-w-2xl w-full mx-auto">
                <form action="" method="post" class="space-y-5">
                    <div class="input-area relative">
                        <label for="kyc_status" class="form-label">
                            {{ __('Current KYC Level: ') }}
                            @if (isset($user->kyc) && in_array($user->kyc,[4,5]))
                                {{ __(ucwords(str_replace('_', ' ', strtolower(App\Enums\KYCStatus::from($user->kyc)->name)))) }}
                            @else
                                {{ __('N/A') }}
                            @endif
                        </label>
                        <select name="kyc" id="kyc" class="form-control">
                            <option value="">Select</option>
                            @foreach (App\Enums\KYCStatus::cases() as $status)
                                <option value="{{ $status->value }}" {{ (isset($user->kyc) && $user->kyc == $status->value) ? 'selected' : '' }}>
                                    {{ __(ucwords(str_replace('_', ' ', strtolower($status->name)))) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-area relative text-right">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
