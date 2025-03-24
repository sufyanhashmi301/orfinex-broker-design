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
                <form action="{{route('admin.kyc.submit',['id'=>$user->id])}}" method="post">
                    @csrf
                    <div class="space-y-5">
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
                            <label for="" class="form-label">{{ __('KYC Level') }}</label>
                            <select name="kyc_level" id="kycLevelSelect" class="select2 form-control w-full" data-placeholder="Select Level">
                                <option value="">{{ __('Select Level') }}</option>
                                <option value="1">{{ __('Level 1') }}</option>
                                <option value="3">{{ __('Level 2') }}</option>
                                <option value="5">{{ __('Level 3') }}</option>
                            </select>
                        </div>
                        <div class="input-area relative">
                            <label for="" class="form-label">{{ __('Verification Type') }}</label>
                            <select id="kycTypeSelect" name="kyc_id" class="select2 form-control" data-placeholder="Select Type">
                                <option value="">{{ __('Select Type') }}</option>
                                <!-- Options will be dynamically populated based on KYC level -->
                            </select>
                        </div>
                        @if(Auth::user() && Auth::user()->getRoleNames()->contains('Super-Admin'))
                            <div class="input-area relative">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <label class="form-label !w-auto !mb-0">
                                        {{ __('Auto Approve') }}
                                    </label>
                                    <div class="form-switch" style="line-height: 0;">
                                        <input class="form-check-input" type="hidden" value="0" name="is_auto_approve"/>
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="is_auto_approve" value="1" class="sr-only peer" >
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
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
