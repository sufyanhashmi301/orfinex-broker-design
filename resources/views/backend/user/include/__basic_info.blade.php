<div
    class="tab-pane space-y-5 fade show active"
    id="pills-informations"
    role="tabpanel"
    aria-labelledby="pills-informations-tab"
>
    @can('customer-edit')
    <div class="card">
        <div class="card-body p-5">
            <form action="{{ route('admin.user.update',$user->id) }}" method="post">
                @method('PUT')
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Update the user's first name if needed">
                                {{ __('First Name') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <input type="text" class="form-control" value="{{$user->first_name}}"
                               name="first_name" required="">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Update the user's last name if needed">
                                {{ __('Last Name') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <input type="text" class="form-control" value="{{$user->last_name}}" required=""
                               name="last_name">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Change the country if the user has relocated">
                                {{ __('Country') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <select class="select2 form-control w-full" name="country" placeholder="Countries" >
                            @foreach( getCountries() as $country)
                                <option value="{{$country['name']}}" @selected( null != $user->country && in_array($country['name'],[$user->country]))>{{$country['name']}}</option>
                            @endforeach
                        </select>
                    </div>

                  <div class="input-area relative phone-input-wrapper">
    <label for="" class="form-label">
        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Edit the user's phone number">
            {{ __('Phone') }}
            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
        </span>
    </label>
    <input type="tel" name="phone" class="form-control" value="{{ safe($user->phone) }}" id="phone" placeholder="{{ __('Phone Number') }}">
    <input type="hidden" name="formatted_phone" id="formatted_phone" value="{{ safe($user->phone) }}">
</div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Modify the username (used for login)">
                                {{ __('Username') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <input type="text" class="form-control" name="username" value="{{ safe($user->username) }}"
                               required="">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Update the email address for notifications and login">
                                {{ __('Email') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <input type="email" name="email" class="form-control" value="{{ safe($user->email) }}" >
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Edit the gender selection">
                                {{ __('Gender') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <select name="gender" class="select2 form-control w-full" required>
                            @foreach(['male','female','other'] as $gender)
                                <option @if($user->gender == $gender) selected @endif value="{{$gender}}">{{$gender}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Update the user's date of birth">
                                {{ __('Date of Birth') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <input type="date" name="date_of_birth" class="form-control " value="{{safe($user->date_of_birth) }}">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Update the user's city">
                                {{ __('City') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <input type="text" name="city" class="form-control" value="{{safe($user->city)}}">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Update the user's zip code">
                                {{ __('Zip Code') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <input type="text" class="form-control" name="zip_code" value="{{$user->zip_code}}">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Update the user's address">
                                {{ __('Address') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <input type="text" class="form-control" name="address" value="{{$user->address}}">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Update the user's joining date">
                                {{ __('Joining Date') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <input type="text" class="form-control"
                               value="{{ carbonInstance($user->created_at)->toDayDateTimeString() }}"
                               required="" disabled>
                    </div>
                    @if($user->notes)
                        <div class="input-area relative lg:col-span-3">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Update the user's notes">
                                    {{ __('Notes') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <textarea type="text"  name="notes" class="form-control"
                            > {{ $user->notes }}</textarea>
                        </div>
                    @endif
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Update the user's group">
                                {{ __('Assign Group') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <select name="group_id" class="form-control">
                            <option value="">Select</option>
                            @foreach($customerGroups as $group)
                                <option value="{{ $group->id }}" {{ $user->customerGroups->pluck('id')->contains($group->id) ? 'selected' : '' }}>
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-area relative">
                        <label for="risk_profile_tags" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Update the user's risk profile tags">
                                {{ __('Risk Profile Tags') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <select name="risk_profile_tags[]" class="select2 form-control w-full" multiple="multiple" data-placeholder="Select Tags" id="riskProfileTagsSelect">
                            @foreach($riskProfileTags as $tag)
                                <option value="{{ $tag->id }}"
                                    @if($user->riskProfileTags->pluck('id')->contains($tag->id))
                                        selected
                                    @endif>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Update the user's lead campaign">
                                {{ __('Lead Campaign') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <input type="text" class="form-control" name="lead_campaign" value="">
                    </div>

                    <div class="input-area relative">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Update the user's lead source">
                                {{ __('Lead Source') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <input type="text" class="form-control" name="lead_source" value="">
                    </div>

                    <div class="input-area relative lg:col-span-3">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Update the user's comment">
                                {{ __('Comment') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <textarea type="text"  name="comment" class="form-control basicTinymce" rows="5"> {{ $user->comment }}</textarea>
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
                showSelectedDialCode: true,
                utilsScript: "{{ asset('frontend/js/utils.js') }}",
                separateDialCode: false,
                formatOnDisplay: true
            });

            // Set the initial value
            const userPhone = '{{ $user->phone }}';
            if (userPhone) {
                iti.setNumber(userPhone);
            }

            // Function to update the country dropdown based on phone input country
            function updateCountryDropdownFromPhone(countryCode) {
                const countrySelect = document.querySelector('select[name="country"]');
                if (countrySelect) {
                    const options = countrySelect.querySelectorAll('option');
                    let found = false;
                    
                    // Try to find exact country code match first
                    options.forEach(option => {
                        if (option.value && option.value.toLowerCase().includes(countryCode.toLowerCase())) {
                            countrySelect.value = option.value;
                            found = true;
                        }
                    });
                    
                    // If no exact match, try to find by country name
                    if (!found) {
                        const countryName = iti.getSelectedCountryData().name;
                        options.forEach(option => {
                            if (option.value && option.value.toLowerCase().includes(countryName.toLowerCase())) {
                                countrySelect.value = option.value;
                                found = true;
                            }
                        });
                    }
                    
                    // Trigger change event for Select2 if it's initialized
                    if (window.jQuery && countrySelect.classList.contains('select2-hidden-accessible')) {
                        jQuery(countrySelect).trigger('change');
                    }
                }
            }

            // Listen for country changes in the phone input
            phoneInput.addEventListener('countrychange', function() {
                const countryData = iti.getSelectedCountryData();
                if (countryData && countryData.iso2) {
                    updateCountryDropdownFromPhone(countryData.iso2);
                }
            });

            // Listen for country dropdown changes to update phone input country
            const countrySelect = document.querySelector('select[name="country"]');
            if (countrySelect) {
                countrySelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption && selectedOption.value) {
                        const countryName = selectedOption.value;
                        
                        // Map country names to ISO codes for intlTelInput
                        // This mapping should match the countries in your CountryCodes.json
                        const countryCodeMap = {
                            'Afghanistan': 'af', 'Albania': 'al', 'Algeria': 'dz', 'Andorra': 'ad', 'Angola': 'ao',
                            'Antigua and Barbuda': 'ag', 'Argentina': 'ar', 'Armenia': 'am', 'Australia': 'au',
                            'Austria': 'at', 'Azerbaijan': 'az', 'Bahamas': 'bs', 'Bahrain': 'bh', 'Bangladesh': 'bd',
                            'Barbados': 'bb', 'Belarus': 'by', 'Belgium': 'be', 'Belize': 'bz', 'Benin': 'bj',
                            'Bhutan': 'bt', 'Bolivia': 'bo', 'Bosnia and Herzegovina': 'ba', 'Botswana': 'bw',
                            'Brazil': 'br', 'Brunei': 'bn', 'Bulgaria': 'bg', 'Burkina Faso': 'bf', 'Burundi': 'bi',
                            'Cambodia': 'kh', 'Cameroon': 'cm', 'Canada': 'ca', 'Cape Verde': 'cv', 'Central African Republic': 'cf',
                            'Chad': 'td', 'Chile': 'cl', 'China': 'cn', 'Colombia': 'co', 'Comoros': 'km',
                            'Congo': 'cg', 'Costa Rica': 'cr', 'Croatia': 'hr', 'Cuba': 'cu', 'Cyprus': 'cy',
                            'Czech Republic': 'cz', 'Democratic Republic of the Congo': 'cd', 'Denmark': 'dk',
                            'Djibouti': 'dj', 'Dominica': 'dm', 'Dominican Republic': 'do', 'East Timor': 'tl',
                            'Ecuador': 'ec', 'Egypt': 'eg', 'El Salvador': 'sv', 'Equatorial Guinea': 'gq',
                            'Eritrea': 'er', 'Estonia': 'ee', 'Ethiopia': 'et', 'Fiji': 'fj', 'Finland': 'fi',
                            'France': 'fr', 'Gabon': 'ga', 'Gambia': 'gm', 'Georgia': 'ge', 'Germany': 'de',
                            'Ghana': 'gh', 'Greece': 'gr', 'Grenada': 'gd', 'Guatemala': 'gt', 'Guinea': 'gn',
                            'Guinea-Bissau': 'gw', 'Guyana': 'gy', 'Haiti': 'ht', 'Honduras': 'hn', 'Hungary': 'hu',
                            'Iceland': 'is', 'India': 'in', 'Indonesia': 'id', 'Iran': 'ir', 'Iraq': 'iq',
                            'Ireland': 'ie', 'Israel': 'il', 'Italy': 'it', 'Ivory Coast': 'ci', 'Jamaica': 'jm',
                            'Japan': 'jp', 'Jordan': 'jo', 'Kazakhstan': 'kz', 'Kenya': 'ke', 'Kiribati': 'ki',
                            'Kuwait': 'kw', 'Kyrgyzstan': 'kg', 'Laos': 'la', 'Latvia': 'lv', 'Lebanon': 'lb',
                            'Lesotho': 'ls', 'Liberia': 'lr', 'Libya': 'ly', 'Liechtenstein': 'li', 'Lithuania': 'lt',
                            'Luxembourg': 'lu', 'Macedonia': 'mk', 'Madagascar': 'mg', 'Malawi': 'mw', 'Malaysia': 'my',
                            'Maldives': 'mv', 'Mali': 'ml', 'Malta': 'mt', 'Marshall Islands': 'mh', 'Mauritania': 'mr',
                            'Mauritius': 'mu', 'Mexico': 'mx', 'Micronesia': 'fm', 'Moldova': 'md', 'Monaco': 'mc',
                            'Mongolia': 'mn', 'Montenegro': 'me', 'Morocco': 'ma', 'Mozambique': 'mz', 'Myanmar': 'mm',
                            'Namibia': 'na', 'Nauru': 'nr', 'Nepal': 'np', 'Netherlands': 'nl', 'New Zealand': 'nz',
                            'Nicaragua': 'ni', 'Niger': 'ne', 'Nigeria': 'ng', 'North Korea': 'kp', 'Norway': 'no',
                            'Oman': 'om', 'Pakistan': 'pk', 'Palau': 'pw', 'Panama': 'pa', 'Papua New Guinea': 'pg',
                            'Paraguay': 'py', 'Peru': 'pe', 'Philippines': 'ph', 'Poland': 'pl', 'Portugal': 'pt',
                            'Qatar': 'qa', 'Romania': 'ro', 'Russia': 'ru', 'Rwanda': 'rw', 'Saint Kitts and Nevis': 'kn',
                            'Saint Lucia': 'lc', 'Saint Vincent and the Grenadines': 'vc', 'Samoa': 'ws', 'San Marino': 'sm',
                            'Sao Tome and Principe': 'st', 'Saudi Arabia': 'sa', 'Senegal': 'sn', 'Serbia': 'rs',
                            'Seychelles': 'sc', 'Sierra Leone': 'sl', 'Singapore': 'sg', 'Slovakia': 'sk', 'Slovenia': 'si',
                            'Solomon Islands': 'sb', 'Somalia': 'so', 'South Africa': 'za', 'South Korea': 'kr', 'South Sudan': 'ss',
                            'Spain': 'es', 'Sri Lanka': 'lk', 'Sudan': 'sd', 'Suriname': 'sr', 'Swaziland': 'sz',
                            'Sweden': 'se', 'Switzerland': 'ch', 'Syria': 'sy', 'Taiwan': 'tw', 'Tajikistan': 'tj',
                            'Tanzania': 'tz', 'Thailand': 'th', 'Togo': 'tg', 'Tonga': 'to', 'Trinidad and Tobago': 'tt',
                            'Tunisia': 'tn', 'Turkey': 'tr', 'Turkmenistan': 'tm', 'Tuvalu': 'tv', 'Uganda': 'ug',
                            'Ukraine': 'ua', 'United Arab Emirates': 'ae', 'United Kingdom': 'gb', 'United States': 'us',
                            'Uruguay': 'uy', 'Uzbekistan': 'uz', 'Venezuela': 've', 'Vietnam': 'vn', 'Yemen': 'ye',
                            'Zambia': 'zm', 'Zimbabwe': 'zw'
                        };
                        
                        const countryCode = countryCodeMap[countryName];
                        if (countryCode) {
                            iti.setCountry(countryCode);
                        }
                    }
                });
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
                        } else {
                            // Fallback: try to construct the phone number from country and input
                            const countrySelect = document.querySelector('select[name="country"]');
                            if (countrySelect && countrySelect.value) {
                                const selectedCountry = countrySelect.value;
                                const inputValue = phoneInput.value.replace(/^\+/, ''); // Remove + if present
                                
                                // Try to find the dial code for the selected country
                                const countryCodeMap = {
                                    'United States': '+1', 'United Kingdom': '+44', 'Canada': '+1', 'Australia': '+61',
                                    'Germany': '+49', 'France': '+33', 'Italy': '+39', 'Spain': '+34', 'Netherlands': '+31',
                                    'Belgium': '+32', 'Switzerland': '+41', 'Austria': '+43', 'Sweden': '+46', 'Norway': '+47',
                                    'Denmark': '+45', 'Finland': '+358', 'Poland': '+48', 'Czech Republic': '+420', 'Hungary': '+36',
                                    'Romania': '+40', 'Bulgaria': '+359', 'Greece': '+30', 'Portugal': '+351', 'Ireland': '+353',
                                    'New Zealand': '+64', 'Japan': '+81', 'South Korea': '+82', 'China': '+86', 'India': '+91',
                                    'Brazil': '+55', 'Mexico': '+52', 'Argentina': '+54', 'Chile': '+56', 'Colombia': '+57',
                                    'Peru': '+51', 'Venezuela': '+58', 'Uruguay': '+598', 'Paraguay': '+595', 'Ecuador': '+593',
                                    'Bolivia': '+591', 'Guyana': '+592', 'Suriname': '+597', 'French Guiana': '+594'
                                };
                                
                                const dialCode = countryCodeMap[selectedCountry];
                                if (dialCode && inputValue) {
                                    const fallbackPhone = dialCode + ' ' + inputValue;
                                    phoneInput.value = fallbackPhone;
                                    
                                    const formattedPhoneField = document.getElementById('formatted_phone');
                                    if (formattedPhoneField) {
                                        formattedPhoneField.value = fallbackPhone;
                                    }
                                }
                            }
                        }
                    } catch (error) {
                        // Continue with form submission even if there's an error
                    }
                });
            }

            // Initialize country dropdown based on current phone number
            if (userPhone && iti.isValidNumber()) {
                const countryCode = iti.getSelectedCountryData().iso2;
                if (countryCode) {
                    updateCountryDropdownFromPhone(countryCode);
                }
            }
        }
        
    });
</script>
  
</div>
