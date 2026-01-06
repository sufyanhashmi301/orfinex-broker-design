<div class="tab-pane space-y-5 fade show active" id="pills-informations" role="tabpanel"
    aria-labelledby="pills-informations-tab">
    @can('customer-edit')
        <div class="card">
            <div class="card-body p-5">
                <form action="{{ route('admin.user.update', $user->id) }}" method="post">
                    @method('PUT')
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                        <div class="input-area relative">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="Update the user's first name if needed">
                                    {{ __('First Name') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" class="form-control" value="{{ $user->first_name }}" name="first_name"
                                required="">
                        </div>
                        <div class="input-area relative">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="Update the user's last name if needed">
                                    {{ __('Last Name') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" class="form-control" value="{{ $user->last_name }}" required=""
                                name="last_name">
                        </div>
                        <div class="input-area relative">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="Change the country if the user has relocated">
                                    {{ __('Country') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <select class="select2 form-control w-full" name="country" placeholder="Countries">
                                @foreach (getCountries() as $country)
                                    <option value="{{ $country['name'] }}" @selected(null != $user->country && in_array($country['name'], [$user->country]))>
                                        {{ $country['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input-area relative phone-input-wrapper">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="Edit the user's phone number">
                                    {{ __('Phone') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="tel" name="phone" class="form-control" value="{{ safe($user->phone) }}"
                                id="phone" placeholder="{{ __('Phone Number') }}">
                            <input type="hidden" name="formatted_phone" id="formatted_phone"
                                value="{{ safe($user->phone) }}">
                        </div>
                        <div class="input-area relative">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="Modify the username (used for login)">
                                    {{ __('Username') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" class="form-control" name="username" value="{{ safe($user->username) }}"
                                required="">
                        </div>
                        <div class="input-area relative">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="Update the email address for notifications and login">
                                    {{ __('Email') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="email" name="email" class="form-control" value="{{ safe($user->email) }}">
                        </div>
                        <div class="input-area relative">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="Edit the gender selection">
                                    {{ __('Gender') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <select name="gender" class="select2 form-control w-full" required>
                                @foreach (['male', 'female', 'other'] as $gender)
                                    <option @if ($user->gender == $gender) selected @endif value="{{ $gender }}">
                                        {{ $gender }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-area relative">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="Update the user's date of birth">
                                    {{ __('Date of Birth') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="date" name="date_of_birth" class="form-control "
                                value="{{ safe($user->date_of_birth) }}">
                        </div>
                        <div class="input-area relative">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="Update the user's city">
                                    {{ __('City') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" name="city" class="form-control" value="{{ safe($user->city) }}">
                        </div>
                        <div class="input-area relative">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="Update the user's zip code">
                                    {{ __('Zip Code') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" class="form-control" name="zip_code" value="{{ $user->zip_code }}">
                        </div>
                        <div class="input-area relative">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="Update the user's address">
                                    {{ __('Address') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" class="form-control" name="address" value="{{ $user->address }}">
                        </div>
                        <div class="input-area relative">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="Update the user's joining date">
                                    {{ __('Joining Date') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" class="form-control"
                                value="{{ toSiteTimezone($user->created_at, 'D, M d, Y h:i A') }}" required=""
                                disabled>
                        </div>
                        @if ($user->notes)
                            <div class="input-area relative lg:col-span-3">
                                <label for="" class="form-label">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="Update the user's notes">
                                        {{ __('Notes') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <textarea type="text" name="notes" class="form-control"> {{ $user->notes }}</textarea>
                            </div>
                        @endif

                        {{-- Branch Assignment --}}
                        @canany(['staff-branch-assign', 'staff-branch-view'])
                            <div class="input-area relative">
                                <label for="" class="form-label">
                                    <span class="shift-Away inline-flex items-center gap-1"
                                        data-tippy-content="Assign user to a branch">
                                        {{ __('Assign Branch') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline"
                                            class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                @can('staff-branch-assign')
                                    <select name="branch_id" class="form-control">
                                        <option value="">{{ __('Select Branch') }}</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ user_meta('branch_id', null, $user) == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }} ({{ $branch->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <div class="form-control bg-slate-50 dark:bg-slate-700">
                                        @php $userBranchId = user_meta('branch_id', null, $user); @endphp
                                        @if ($userBranchId && isset($branches))
                                            @php $userBranch = $branches->where('id', $userBranchId)->first(); @endphp
                                            @if ($userBranch)
                                                <span class="badge badge-secondary">{{ $userBranch->name }}
                                                    ({{ $userBranch->code }})
                                                </span>
                                            @else
                                                <span class="text-slate-500">{{ __('No branch assigned') }}</span>
                                            @endif
                                        @else
                                            <span class="text-slate-500">{{ __('No branch assigned') }}</span>
                                        @endif
                                    </div>
                                @endcan
                            </div>
                        @endcanany

                        <div class="input-area relative">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="Update the user's group">
                                    {{ __('Assign Group') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <select name="group_id" class="form-control">
                                <option value="">Select</option>
                                @foreach ($customerGroups as $group)
                                    <option value="{{ $group->id }}"
                                        {{ $user->customerGroups->pluck('id')->contains($group->id) ? 'selected' : '' }}>
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-area relative">
                            <label for="risk_profile_tags" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="Update the user's risk profile tags">
                                    {{ __('Risk Profile Tags') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <select name="risk_profile_tags[]" class="select2 form-control w-full" multiple="multiple"
                                data-placeholder="Select Tags" id="riskProfileTagsSelect">
                                @foreach ($riskProfileTags as $tag)
                                    <option value="{{ $tag->id }}" @if ($user->riskProfileTags->pluck('id')->contains($tag->id)) selected @endif>
                                        {{ $tag->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-area relative">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="Update the user's lead campaign">
                                    {{ __('Lead Campaign') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" class="form-control" name="lead_campaign" value="">
                        </div>

                        <div class="input-area relative">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="Update the user's lead source">
                                    {{ __('Lead Source') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" class="form-control" name="lead_source" value="">
                        </div>

                        <div class="input-area relative lg:col-span-3">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1"
                                    data-tippy-content="Update the user's comment">
                                    {{ __('Comment') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline"
                                        class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <textarea type="text" name="comment" class="form-control basicTinymce" rows="5"> {{ $user->comment }}</textarea>
                        </div>
                        @can('customer-overview-update')
                            <div class="input-area relative text-right lg:col-span-3">
                                <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                    {{ __('Save Changes') }}
                                </button>
                            </div>
                        @endcan
                    </div>
                </form>
            </div>
        </div>
    @endcan
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.querySelector("#phone");
            if (phoneInput) {
                // Initialize intlTelInput
                const iti = window.intlTelInput(phoneInput, {
                    initialCountry: "auto",
                    separateDialCode: true,
                    showSelectedDialCode: true,
                    formatOnDisplay: true,
                    autoPlaceholder: 'polite',
                    utilsScript: "{{ asset('frontend/js/utils.js') }}"
                });

                // Set the initial value
                const userPhone = '{{ $user->phone }}';
                if (userPhone) {
                    try {
                        iti.setNumber(userPhone);
                    } catch (e) {
                        console.log('Error setting phone number:', e);
                    }
                }

                // Intercept form submission to ensure phone number with country code is captured
                const form = phoneInput.closest('form');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        try {
                            // Get the full formatted phone number with country code
                            const formattedNumber = iti.getNumber();
                            const isValid = iti.isValidNumber();

                            if (formattedNumber && isValid) {
                                // Update the phone input with the full formatted number
                                phoneInput.value = formattedNumber;

                                // Also update the hidden formatted_phone field if it exists
                                const formattedPhoneField = document.getElementById('formatted_phone');
                                if (formattedPhoneField) {
                                    formattedPhoneField.value = formattedNumber;
                                }
                            }
                        } catch (error) {
                            // Continue with form submission even if there's an error
                        }
                    });
                }
            }

        });
    </script>

</div>
