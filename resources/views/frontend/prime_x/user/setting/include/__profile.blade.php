<div class="innerMenu">
    <div class="card overflow-hidden mb-5">
        <div class="card-body py-1">
            <div class="grid md:grid-cols-3 col-span-1 gap-px bg-slate-100 dark:bg-slate-700">
                <div class="bg-white dark:bg-dark">
                    <div class="flex flex-wrap justify-between items-baseline gap-y-2 gap-x-4 p-4">
                        <div class="text-slate-800 dark:text-slate-100 text-sm mb-1 font-medium">
                            {{ __('Total Balance') }}
                        </div>
                        <div class="w-full text-slate-900 dark:text-white text-xl font-medium">
                            {{ mt5_total_balance(auth()->user()->id) }}
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-dark">
                    <div class="flex flex-wrap justify-between items-baseline gap-y-2 gap-x-4 p-4">
                        <div class="text-slate-800 dark:text-slate-100 text-sm mb-1 font-medium">
                            {{ __('Current Equity') }}
                        </div>
                        <div class="w-full text-slate-900 dark:text-white text-xl font-medium">
                            {{ mt5_total_equity(auth()->user()->id) }}
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-dark">
                    <div class="flex flex-wrap justify-between items-baseline gap-y-2 gap-x-4 p-4">
                        <div class="text-slate-800 dark:text-slate-100 text-sm mb-1 font-medium">
                            {{ __('Wallet Balance') }}
                        </div>
                        <div class="w-full text-slate-900 dark:text-white text-xl font-medium">
                            {{ user_balance() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-6">
        <form action="{{ route('user.setting.profile-update') }}" id="profile-update-form" method="post"
            enctype="multipart/form-data">
            @csrf
            <div class="progress-steps-form">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="input-area relative">
                        <label for="exampleFormControlInput1" class="form-label">{{ __('First Name') }}</label>
                        <input type="text" class="form-control" name="first_name" value="{{ $user->first_name }}"
                            placeholder="{{ __('First Name') }}" @if ($user->last_name && !setting('customer_name_edit', 'customer_permission')) disabled @endif />
                    </div>
                    <div class="input-area relative">
                        <label for="exampleFormControlInput1" class="form-label">{{ __('Last Name') }}</label>
                        <input type="text" class="form-control" name="last_name" value="{{ $user->last_name }}"
                            placeholder="{{ __('Last Name') }}" @if ($user->last_name && !setting('customer_name_edit', 'customer_permission')) disabled @endif />
                    </div>
                    <div class="input-area relative">
                        <label for="exampleFormControlInput1" class="form-label">{{ __('Username') }}</label>
                        <input type="text" class="form-control" name="username" value="{{ $user->username }}"
                            placeholder="{{ __('Username') }}" @if ($user->username && !setting('customer_username_edit', 'customer_permission')) disabled @endif />
                    </div>
                    <div class="input-area relative">
                        <label for="exampleFormControlInput1" class="form-label">{{ __('Gender') }}</label>
                        <div class="input-group">
                            <select name="gender" id="kycTypeSelect" class="select2 form-control w-full mt-2 py-2"
                                required>
                                @foreach (['male', 'female', 'other'] as $gender)
                                    <option @if ($user->gender == $gender) selected @endif
                                        value="{{ $gender }}">
                                        {{ ucfirst($gender) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="input-area relative">
                        <label for="exampleFormControlInput1" class="form-label">{{ __('Date of Birth') }}</label>
                        <input type="date" name="date_of_birth" class="form-control"
                            value="{{ $user->date_of_birth }}" placeholder="{{ __('Date of Birth') }}"
                            @if ($user->date_of_birth && !setting('customer_dob_edit', 'customer_permission')) disabled @endif />
                    </div>

                    <div class="input-area relative">
                        <label for="exampleFormControlInput1" class="form-label">{{ __('Email Address') }}</label>
                        <input type="email" class="form-control" value="{{ $user->email }}"
                            placeholder="{{ __('Email Address') }}"
                            @if ($user->email && !setting('customer_email_edit', 'customer_permission')) disabled @endif />
                    </div>

                    <div class="input-area relative phone-input-wrapper">
                        <label for="exampleFormControlInput1" class="form-label">{{ __('Phone') }}</label>
                        <input type="text" class="form-control w-full" name="phone" id="phone"
                            value="{{ $user->phone }}" placeholder="{{ __('Phone') }}"
                            @if ($user->phone && !setting('customer_phone_edit', 'customer_permission')) disabled @endif />
                    </div>
                    <div class="input-area relative">
                        <label for="exampleFormControlInput1" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1"
                                data-tippy-content="Change the country if the user has relocated">
                                {{ __('Country') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline"
                                    class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <select class="select2 form-control w-full" name="country" placeholder="Countries"
                            @if ($user->country && !setting('customer_country_edit', 'customer_permission')) disabled @endif>
                            @foreach (getCountries() as $country)
                                <option value="{{ $country['name'] }}" @selected(null != $user->country && in_array($country['name'], [$user->country]))>
                                    {{ $country['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-area relative">
                        <label for="exampleFormControlInput1" class="form-label">{{ __('Timezone') }}</label>
                        @php
                            // Get user's timezone preference (priority: user timezone -> user country -> UTC)
                            $currentTimezone = 'UTC';
                            
                            // Priority 1: User's timezone column (if set and valid)
                            if (!empty($user->timezone)) {
                                $userTz = trim($user->timezone);
                                if (in_array($userTz, timezone_identifiers_list())) {
                                    $currentTimezone = $userTz;
                                }
                            }
                            
                            // Priority 2: User's country-based timezone (if timezone column is empty)
                            if ($currentTimezone === 'UTC' && !empty($user->country) && function_exists('getTimezoneByCountry')) {
                                $countryTimezone = getTimezoneByCountry($user->country);
                                if ($countryTimezone && in_array($countryTimezone, timezone_identifiers_list())) {
                                    $currentTimezone = $countryTimezone;
                                }
                            }
                            
                            // Use old value if form was submitted with errors
                            $currentTimezone = old('timezone', $currentTimezone);
                            
                            $allTimezones = function_exists('getAllTimezones') ? getAllTimezones() : [];
                            // Fallback: if function doesn't exist, create basic timezone list
                            if (empty($allTimezones)) {
                                foreach (timezone_identifiers_list() as $tz) {
                                    $allTimezones[] = ['id' => $tz, 'text' => $tz];
                                }
                            }
                        @endphp
                        <select name="timezone" class="select2 form-control w-full" id="user_timezone_select">
                            @foreach($allTimezones as $tz)
                                <option value="{{ $tz['id'] }}" {{ ($tz['id'] == $currentTimezone) ? 'selected' : '' }}>
                                    {{ $tz['text'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-area relative">
                        <label for="exampleFormControlInput1" class="form-label">{{ __('City') }}</label>
                        <input type="text" class="form-control" name="city" value="{{ $user->city }}"
                            placeholder="{{ __('City') }}" />
                    </div>
                    <div class="input-area relative">
                        <label for="exampleFormControlInput1" class="form-label">{{ __('Zip') }}</label>
                        <input type="text" class="form-control" name="zip_code" value="{{ $user->zip_code }}"
                            placeholder="{{ __('Zip') }}" />
                    </div>
                    <div class="input-area relative">
                        <label for="exampleFormControlInput1" class="form-label">{{ __('Address') }}</label>
                        <input type="text" class="form-control" name="address" value="{{ $user->address }}"
                            placeholder="{{ __('Address') }}" />
                    </div>
                    <div class="input-area relative">
                        <label for="exampleFormControlInput1" class="form-label">{{ __('Joining Date') }}</label>
                        <input type="text" class="form-control disabled"
                            value="{{ toSiteTimezone($user->created_at, 'D, M d, Y h:i A') }}"
                            placeholder="{{ __('Joining Date') }}" disabled />
                    </div>
                </div>

                <div class="text-right mt-10">
                    <button type="submit" class="btn btn-primary inline-flex items-center justify-center"
                        id="profile-update-save">{{ __('Save Changes') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
