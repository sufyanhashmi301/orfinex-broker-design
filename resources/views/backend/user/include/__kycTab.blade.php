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
                <form action="{{route('admin.user.kyc',['id'=>$user->id])}}" method="post">
                    @csrf
                    <div class="input-area relative">
{{--                        <label for="kyc_status" class="form-label">--}}
{{--                            {{ __('Current KYC Level: ') }}--}}
{{--                            @if (isset($user->kyc) && in_array($user->kyc,[4,5]))--}}
{{--                                {{ __(ucwords(str_replace('_', ' ', strtolower(App\Enums\KYCStatus::from($user->kyc)->name)))) }}--}}
{{--                            @else--}}
{{--                                {{ __('N/A') }}--}}
{{--                            @endif--}}
{{--                        </label>--}}
                        {{-- Dropdown to show KYC statuses --}}
                        <select name="kyc" class="select2 form-control w-full">
                            @foreach($kycLevels as $kycLevel)
                                {{-- Get the status mapping for each enabled KYC Level --}}
                                @if(isset($statusLabels[$kycLevel->id]))
                                    @foreach($statusLabels[$kycLevel->id]['statuses'] as $statusValue)
                                        @php
                                            $status = \App\Enums\KYCStatus::from($statusValue);
                                            $label = $kycLevel->name; // Get the dynamic name from the KycLevel model

                                            // Override label for Pending/Reject statuses with dynamic level name
                                            if (isset($statusLabels[$kycLevel->id]['additionalLabels'][$statusValue])) {
                                                $label = strtolower($statusLabels[$kycLevel->id]['additionalLabels'][$statusValue]) . '-' . str_replace(' ', '-', strtolower($kycLevel->name));
                                            }
                                        @endphp

                                        <option value="{{ $status->value }}"
                                            {{ isset($user) && $user->kyc == $status->value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <br>
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
