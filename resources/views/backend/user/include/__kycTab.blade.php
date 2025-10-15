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
        <div class="card-body p-6">
            <div class="max-w-2xl w-full mx-auto">

                {{-- ✅ Display External KYC ID (Read-Only) --}}
                @if($user->external_kyc_id)
                    <div class="alert alert-info text-sm mb-5">
                        <strong>{{ __('External KYC ID:') }}</strong> {{ $user->external_kyc_id }}
                    </div>
                @endif

                <form action="{{ route('admin.kyc.submit', ['id' => $user->id]) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-5">
                        <div class="input-area relative">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="KYC level for the user">
                                    {{ __('KYC Level') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <select name="kyc_level" id="kycLevelSelect" class="select2 form-control w-full" data-placeholder="Select Level">
                                <option value="">{{ __('Select Level') }}</option>
                                <option value="1">{{ __('Level 1') }}</option>
                                <option value="3">{{ __('Level 2') }}</option>
                                <option value="5">{{ __('Level 3') }}</option>
                            </select>
                        </div>
                        <div class="input-area relative">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Verification type for the user">
                                    {{ __('Verification Type') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <select id="kycTypeSelect" name="kyc_id" class="select2 form-control" data-placeholder="Select Type">
                                <option value="">{{ __('Select Type') }}</option>
                            </select>
                        </div>
                        @if(Auth::user() && Auth::user()->getRoleNames()->contains('Super-Admin'))
                            <div class="input-area relative">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <label class="form-label !w-auto !mb-0">
                                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Auto approve the user's KYC">
                                            {{ __('Auto Approve') }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                        </span>
                                    </label>
                                    <div class="form-switch" style="line-height: 0;">
                                        <input class="form-check-input" type="hidden" value="0" name="is_auto_approve"/>
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="is_auto_approve" value="1" class="sr-only peer">
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="kycData"></div>
                    </div>
                    <div class="input-area relative text-right mt-10">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            <span class="flex items-center">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Save Changes') }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Display Level 2 KYC Images --}}
    @if ($user->kyc_credential)
        @php
            $kycCredentialLevel2 = json_decode($user->kyc_credential, true);
            $hasLevel2Images = false;
            if (is_array($kycCredentialLevel2)) {
                unset($kycCredentialLevel2['kyc_type_of_name']);
                unset($kycCredentialLevel2['kyc_time_of_time']);
                foreach ($kycCredentialLevel2 as $value) {
                    if (file_exists('assets/' . $value)) {
                        $hasLevel2Images = true;
                        break;
                    }
                }
            }

            // Determine Level 2 KYC Status
            $level2Status = '';
            $level2StatusBadge = '';
            $level2SubmittedDate = '';

            // Check all possible Level 2 related statuses
            if (
                in_array($user->kyc, [
                    \App\Enums\KYCStatus::Level2->value,
                    \App\Enums\KYCStatus::PendingLevel3->value,
                    \App\Enums\KYCStatus::RejectLevel3->value,
                    \App\Enums\KYCStatus::Level3->value,
                ])
            ) {
                // Level 2 is verified if user reached Level2 or beyond
                $level2Status = __('Verified');
                $level2StatusBadge = 'badge badge-success';
            } elseif ($user->kyc == \App\Enums\KYCStatus::Pending->value) {
                $level2Status = __('Pending');
                $level2StatusBadge = 'badge badge-warning';
            } elseif ($user->kyc == \App\Enums\KYCStatus::Rejected->value) {
                $level2Status = __('Rejected');
                $level2StatusBadge = 'badge badge-danger';
            } elseif ($user->kyc == \App\Enums\KYCStatus::Resubmit->value) {
                $level2Status = __('Resubmit Required');
                $level2StatusBadge = 'badge badge-warning';
            } elseif ($user->kyc == \App\Enums\KYCStatus::Level1->value) {
                $level2Status = __('Level 1');
                $level2StatusBadge = 'badge badge-info';
            }

            // Get submission date from credential if available and format it
            $originalCredential = json_decode($user->kyc_credential, true);
            if (isset($originalCredential['kyc_time_of_time'])) {
                try {
                    $level2SubmittedDate = \Carbon\Carbon::parse($originalCredential['kyc_time_of_time'])->format(
                        'F d Y h:i',
                    );
                } catch (\Exception $e) {
                    $level2SubmittedDate = $originalCredential['kyc_time_of_time'];
                }
            }
        @endphp

        @if ($hasLevel2Images)
            <div class="card basicTable_wrapper mt-5">
                <div class="card-header">
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="card-title flex items-center gap-2">
                                {{ __('Level 2 KYC Documents') }}
                                @if ($level2Status)
                                    <div class="{{ $level2StatusBadge }}">{{ $level2Status }}</div>
                                @endif
                            </h4>
                            <p class="card-text">
                                {{ __('Uploaded documents for Level 2 KYC verification') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-6">
                    {{-- Status Information Section --}}
                    @if ($level2SubmittedDate || $level2Status)
                        <div
                            class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4 mb-6 border border-slate-200 dark:border-slate-700">
                            <h5
                                class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3 flex items-center gap-2">
                                <iconify-icon icon="lucide:info" class="text-lg"></iconify-icon>
                                {{ __('Verification Status Details') }}
                            </h5>
                            <div class="grid md:grid-cols-2 grid-cols-1 gap-4">
                                @if ($level2Status)
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-slate-600 dark:text-slate-400 text-sm">{{ __('Current Status:') }}</span>
                                        <div class="{{ $level2StatusBadge }}">{{ $level2Status }}</div>
                                    </div>
                                @endif
                                @if ($level2SubmittedDate)
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-slate-600 dark:text-slate-400 text-sm">{{ __('Submitted On:') }}</span>
                                        <span
                                            class="text-slate-900 dark:text-slate-100 text-sm font-medium">{{ $level2SubmittedDate }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Documents Grid --}}
                    <ul class="grid md:grid-cols-2 grid-cols-1 gap-5">
                        @foreach ($kycCredentialLevel2 as $key => $value)
                            @if (file_exists('assets/' . $value))
                                <li
                                    class="dark:text-slate-300 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                    <span
                                        class="block mb-3 font-medium text-slate-700 dark:text-slate-300">{{ $key }}:</span>
                                    <div class="h-[260px] bg-no-repeat bg-contain bg-center bg-slate-100 dark:bg-slate-800 mb-3 rounded-lg"
                                        style="background-image: url('{{ asset($value) }}')"></div>
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ asset($value) }}" class="btn-link"
                                            download>{{ __('Download') }}</a>
                                        <a href="{{ asset($value) }}" class="btn-link"
                                            target="_blank">{{ __('View') }}</a>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    @endif

    {{-- Display Level 3 KYC Images --}}
    @if ($user->kyc_level3_credential)
        @php
            $kycCredentialLevel3 = json_decode($user->kyc_level3_credential, true);
            $hasLevel3Images = false;
            if (is_array($kycCredentialLevel3)) {
                unset($kycCredentialLevel3['kyc_type_of_name']);
                unset($kycCredentialLevel3['kyc_time_of_time']);
                foreach ($kycCredentialLevel3 as $value) {
                    if (file_exists('assets/' . $value)) {
                        $hasLevel3Images = true;
                        break;
                    }
                }
            }

            // Determine Level 3 KYC Status
            $level3Status = '';
            $level3StatusBadge = '';
            $level3SubmittedDate = '';

            // Check all possible Level 3 related statuses
            if ($user->kyc == \App\Enums\KYCStatus::Level3->value) {
                // Level 3 is verified/approved
                $level3Status = __('Verified');
                $level3StatusBadge = 'badge badge-success';
            } elseif ($user->kyc == \App\Enums\KYCStatus::PendingLevel3->value) {
                $level3Status = __('Pending');
                $level3StatusBadge = 'badge badge-warning';
            } elseif ($user->kyc == \App\Enums\KYCStatus::RejectLevel3->value) {
                $level3Status = __('Rejected');
                $level3StatusBadge = 'badge badge-danger';
            } elseif ($user->kyc == \App\Enums\KYCStatus::Resubmit->value) {
                $level3Status = __('Resubmit Required');
                $level3StatusBadge = 'badge badge-warning';
            }

            // Get submission date from credential if available and format it
            $originalCredential3 = json_decode($user->kyc_level3_credential, true);
            if (isset($originalCredential3['kyc_time_of_time'])) {
                try {
                    $level3SubmittedDate = \Carbon\Carbon::parse($originalCredential3['kyc_time_of_time'])->format(
                        'F d Y h:i',
                    );
                } catch (\Exception $e) {
                    $level3SubmittedDate = $originalCredential3['kyc_time_of_time'];
                }
            }
        @endphp

        @if ($hasLevel3Images)
            <div class="card basicTable_wrapper mt-5">
                <div class="card-header">
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="card-title flex items-center gap-2">
                                {{ __('Level 3 KYC Documents') }}
                                @if ($level3Status)
                                    <div class="{{ $level3StatusBadge }}">{{ $level3Status }}</div>
                                @endif
                            </h4>
                            <p class="card-text">
                                {{ __('Uploaded documents for Level 3 KYC verification') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-6">
                    {{-- Status Information Section --}}
                    @if ($level3SubmittedDate || $level3Status)
                        <div
                            class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4 mb-6 border border-slate-200 dark:border-slate-700">
                            <h5
                                class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3 flex items-center gap-2">
                                <iconify-icon icon="lucide:info" class="text-lg"></iconify-icon>
                                {{ __('Verification Status Details') }}
                            </h5>
                            <div class="grid md:grid-cols-2 grid-cols-1 gap-4">
                                @if ($level3Status)
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-slate-600 dark:text-slate-400 text-sm">{{ __('Current Status:') }}</span>
                                        <div class="{{ $level3StatusBadge }}">{{ $level3Status }}</div>
                                    </div>
                                @endif
                                @if ($level3SubmittedDate)
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-slate-600 dark:text-slate-400 text-sm">{{ __('Submitted On:') }}</span>
                                        <span
                                            class="text-slate-900 dark:text-slate-100 text-sm font-medium">{{ $level3SubmittedDate }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Documents Grid --}}
                    <ul class="grid md:grid-cols-2 grid-cols-1 gap-5">
                        @foreach ($kycCredentialLevel3 as $key => $value)
                            @if (file_exists('assets/' . $value))
                                <li
                                    class="dark:text-slate-300 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
                                    <span
                                        class="block mb-3 font-medium text-slate-700 dark:text-slate-300">{{ $key }}:</span>
                                    <div class="h-[260px] bg-no-repeat bg-contain bg-center bg-slate-100 dark:bg-slate-800 mb-3 rounded-lg"
                                        style="background-image: url('{{ asset($value) }}')"></div>
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ asset($value) }}" class="btn-link"
                                            download>{{ __('Download') }}</a>
                                        <a href="{{ asset($value) }}" class="btn-link"
                                            target="_blank">{{ __('View') }}</a>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    @endif
</div>
