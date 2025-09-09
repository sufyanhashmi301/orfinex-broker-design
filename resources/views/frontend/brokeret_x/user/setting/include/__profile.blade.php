<div class="grid rounded-2xl border border-gray-200 bg-white sm:grid-cols-2 xl:grid-cols-3 dark:border-gray-800 dark:bg-white/[0.03] mb-5">
    <div class="border-b border-gray-200 px-6 py-5 sm:border-r xl:border-b-0 dark:border-gray-800">
        <span class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('Balance') }}
        </span>
        <div class="mt-2 flex items-end gap-3">
            <h4 class="text-title-xs sm:text-title-sm font-bold text-gray-800 dark:text-white/90">
                ${{auth()->user()->totalForexBalance()}}
            </h4>
        </div>
    </div>
    <div class="border-b border-gray-200 px-6 py-5 xl:border-r xl:border-b-0 dark:border-gray-800">
        <span class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('Equity') }}
        </span>
        <div class="mt-2 flex items-end gap-3">
            <h4 class="text-title-xs sm:text-title-sm font-bold text-gray-800 dark:text-white/90">
                ${{auth()->user()->totalForexEquity()}}
            </h4>
        </div>
    </div>
    <div class="px-6 py-5">
        <span class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('Balance') }}
        </span>
        <div class="mt-2 flex items-end gap-3">
            <h4 class="text-title-xs sm:text-title-sm font-bold text-gray-800 dark:text-white/90">
                ${{ user_balance() }}
            </h4>
        </div>
    </div>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
    <form action="{{ route('user.setting.profile-update') }}" id="profile-update-form" method="post" enctype="multipart/form-data">
        @csrf
        <div class="progress-steps-form">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <x-forms.field
                    fieldId="first_name"
                    fieldLabel="{{ __('First Name') }}"
                    fieldName="first_name"
                    fieldPlaceholder="{{ __('First Name') }}"
                    fieldValue="{{ $user->first_name }}"
                    type="text"
                    :disabled="$user->last_name && !setting('customer_name_edit', 'customer_permission')"
                />
                <x-forms.field
                    fieldId="last_name"
                    fieldLabel="{{ __('Last Name') }}"
                    fieldName="last_name"
                    fieldPlaceholder="{{ __('Last Name') }}"
                    fieldValue="{{ $user->last_name }}"
                    type="text"
                    :disabled="$user->last_name && !setting('customer_name_edit', 'customer_permission')"
                />
                <x-forms.field
                    fieldId="username"
                    fieldLabel="{{ __('Username') }}"
                    fieldName="username"
                    fieldPlaceholder="{{ __('Username') }}"
                    fieldValue="{{ $user->username }}"
                    type="text"
                    :disabled="$user->username && !setting('customer_username_edit', 'customer_permission')"
                />
                <x-forms.select-field
                    fieldId="gender"
                    fieldLabel="{{ __('Gender') }}"
                    fieldName="gender"
                    fieldValue="{{ $user->gender }}"
                    :fieldRequired="true"
                >
                    @foreach(['male','female','other'] as $gender)
                        <option @if($user->gender == $gender) selected @endif value="{{$gender}}">
                            {{ ucfirst($gender) }}
                        </option>
                    @endforeach
                </x-forms.select-field>

                <x-forms.field
                    fieldId="date_of_birth"
                    fieldLabel="{{ __('Date of Birth') }}"
                    fieldName="date_of_birth"
                    fieldPlaceholder="{{ __('Date of Birth') }}"
                    fieldValue="{{ $user->date_of_birth }}"
                    type="date"
                    :disabled="$user->date_of_birth && !setting('customer_dob_edit', 'customer_permission')"
                />

                <x-forms.field
                    fieldId="email"
                    fieldLabel="{{ __('Email Address') }}"
                    fieldName="email"
                    fieldPlaceholder="{{ __('Email Address') }}"
                    fieldValue="{{ $user->email }}"
                    type="email"
                    :disabled="$user->email && !setting('customer_email_edit', 'customer_permission')"
                />
                <x-forms.field
                    fieldId="phone"
                    fieldLabel="{{ __('Phone') }}"
                    fieldName="phone"
                    fieldPlaceholder="{{ __('Phone') }}"
                    fieldValue="{{ $user->phone }}"
                    type="text"
                    containerClass="phone-input-wrapper"
                    :disabled="$user->phone && !setting('customer_phone_edit', 'customer_permission')"
                />
                <x-forms.field
                    fieldId="country"
                    fieldLabel="{{ __('Country') }}"
                    fieldName="country"
                    fieldPlaceholder="{{ __('Country') }}"
                    fieldValue="{{ $user->country }}"
                    type="text"
                    :disabled="$user->country && !setting('customer_country_edit', 'customer_permission')"
                />

                <x-forms.field
                    fieldId="city"
                    fieldLabel="{{ __('City') }}"
                    fieldName="city"
                    fieldPlaceholder="{{ __('City') }}"
                    fieldValue="{{ $user->city }}"
                    type="text"
                />

                <x-forms.field
                    fieldId="zip_code"
                    fieldLabel="{{ __('Zip') }}"
                    fieldName="zip_code"
                    fieldPlaceholder="{{ __('Zip') }}"
                    fieldValue="{{ $user->zip_code }}"
                    type="text"
                />

                <x-forms.field
                    fieldId="address"
                    fieldLabel="{{ __('Address') }}"
                    fieldName="address"
                    fieldPlaceholder="{{ __('Address') }}"
                    fieldValue="{{ $user->address }}"
                    type="text"
                />

                <x-forms.field
                    fieldId="joining_date"
                    fieldLabel="{{ __('Joining Date') }}"
                    fieldName="joining_date"
                    fieldPlaceholder="{{ __('Joining Date') }}"
                    fieldValue="{{ $user->created_at }}"
                    type="text"
                    disabled
                />
            </div>

            <div class="text-right mt-10">
                <x-forms.button type="submit" size="lg" variant="primary" icon="save" icon-position="left">  
                    {{ __('Save Changes') }}
                </x-forms.button>
            </div>
        </div>
    </form>
</div>
