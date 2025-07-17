@extends('backend.layouts.app')
@section('title')
    {{ __('Update Lead') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="card">
        <form action="{{ route('admin.lead.update', $lead->id) }}" method="post" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')
            <div class="card !mt-0">
                <div class="card-header">
                    <h4 class="card-title">{{ __('Lead Contact Detail') }}</h4>
                </div>
                <div class="card-body p-6">
                    <div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Select the salutation">
                                    {{ __('Salutation') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    <span class="text-xs text-danger">*</span>
                                </span>
                            </label>
                            <select name="salutation" class="select2 form-control">
                                <option value="mr" @if($lead->salutation == 'mr') selected @endif>{{ __('Mr') }}</option>
                                <option value="mrs" @if($lead->salutation == 'mrs') selected @endif>{{ __('Mrs') }}</option>
                                <option value="miss" @if($lead->salutation == 'miss') selected @endif>{{ __('Miss') }}</option>
                                <option value="dr" @if($lead->salutation == 'dr') selected @endif>{{ __('Dr.') }}</option>
                                <option value="sir" @if($lead->salutation == 'sir') selected @endif>{{ __('Sir') }}</option>
                                <option value="madam" @if($lead->salutation == 'madam') selected @endif>{{ __('Madam') }}</option>
                            </select>
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the first name">
                                    {{ __('First Name') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    <span class="text-xs text-danger">*</span>
                                </span>
                            </label>
                            <input
                                type="text"
                                name="first_name"
                                class="form-control mb-0"
                                value="{{ old('first_name', $lead->first_name) }}"
                            />
                            @error('first_name')
                                <span class="error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the last name">
                                    {{ __('Last Name') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    <span class="text-xs text-danger">*</span>
                                </span>
                            </label>
                            <input
                                type="text"
                                name="last_name"
                                class="form-control mb-0"
                                value="{{ old('last_name', $lead->last_name) }}"
                            />
                            @error('last_name')
                                <span class="error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the email">
                                    {{ __('Email') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    <span class="text-xs text-danger">*</span>
                                </span>
                            </label>
                            <input
                                type="email"
                                name="client_email"
                                class="form-control mb-0"
                                value="{{ old('client_email', $lead->client_email) }}"
                            />
                            @error('client_email')
                                <span class="error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the phone">
                                    {{ __('Phone') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    <span class="text-xs text-danger">*</span>
                                </span>
                            </label>
                            <input
                                type="text"
                                name="phone"
                                class="form-control mb-0"
                                value="{{ old('phone', $lead->phone) }}"
                            />
                            @error('phone')
                                <span class="error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Select the lead source">
                                    {{ __('Lead Source') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    <span class="text-xs text-danger">*</span>
                                </span>
                            </label>
                            <select name="source_id" class="select2 form-control">
                                @foreach($sources as $source)
                                    <option value="{{ $source->id }}" {{ $source->id == old('source_id', $lead->source_id) ? 'selected' : '' }}>
                                        {{ $source->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('source_id')
                                <span class="error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Select the lead owner">
                                    {{ __('Lead Owner') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    <span class="text-xs text-danger">*</span>
                                </span>
                            </label>
                            <select name="lead_owner" id="leadOwner" class="form-control">
                                @foreach($staff as $staff)
                                    <option data-avatar="{{ asset($staff->avatar ?? 'global/materials/user.png') }}" data-role="{{ $staff->getRoleNames()->first() }}"
                                        {{ $staff->id == old('lead_owner', $lead->lead_owner) ? 'selected' : '' }}
                                        value="{{ $staff->id }}">
                                        {{ $staff->first_name .' '. $staff->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lead_owner')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('Company Details') }}</h4>
                </div>
                <div class="card-body p-6">
                    <div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
                        <div class="input-area">
                            <label class="form-label" for="">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the company name">
                                    {{ __('Company Name') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input
                                type="text"
                                name="company_name"
                                class="form-control"
                                value="{{ old('company_name', $lead->company_name) }}"
                                placeholder="e.g. Acme Corporation">
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the website">
                                    {{ __('Website') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input
                                type="text"
                                name="website"
                                class="form-control"
                                value="{{ old('website', $lead->website) }}"
                                placeholder="e.g. https://www.example.com">
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the office phone number">
                                    {{ __('Office Phone Number') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input
                                type="tel"
                                name="office_phone_number"
                                class="form-control"
                                value="{{ old('office_phone_number', $lead->office_phone_number) }}"
                                placeholder="e.g. 1234567890">
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Select the country">
                                    {{ __('Country') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <select name="country" class="select2 form-control" data-placeholder="Select Country">
                                <option value="" disabled selected>Select Country</option>
                                @foreach( getCountries() as $country)
                                    <option value="{{ $country['name'] }}" {{ $country['name'] == old('country', $lead->country) ? 'selected' : '' }}>
                                        {{ $country['name']  }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the state">
                                    {{ __('State') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input
                                type="text"
                                name="state"
                                class="form-control"
                                value="{{ old('state', $lead->state) }}"
                                placeholder="e.g. California, Rajasthan, Dubai">
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the city">
                                    {{ __('City') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input
                                type="text"
                                name="city"
                                class="form-control"
                                value="{{ old('city', $lead->city) }}"
                                placeholder="e.g. New York, Jaipur, Dubai">
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the postal code">
                                    {{ __('Postal code') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input
                                type="text"
                                name="postal_code"
                                class="form-control"
                                value="{{ old('postal_code', $lead->postal_code) }}"
                                placeholder="e.g. 90250">
                        </div>
                        <div class="lg:col-span-3">
                            <div class="input-area">
                                <label class="form-label" for="">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the address">
                                        {{ __('Address') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <textarea
                                    name="address"
                                    class="form-control"
                                    rows="3"
                                    placeholder="e.g. 132, My Street, Kingston, New York 12401">
                                    {{ old('address', $lead->address) }}
                                </textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="action-btns text-right p-6 pt-4">
                <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                    {{ __('Save Changes') }}
                </button>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script !src="">
        $(document).ready(function() {
            $('#leadOwner').select2({
                templateResult: function(data) {
                    if (!data.id) {
                        return data.text;
                    }

                    // Create a custom option template
                    var avatar = $(data.element).data('avatar');
                    var role = $(data.element).data('role');
                    var text = data.text;

                    // Return custom HTML for each option
                    var $container = $(
                        `<div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-[100%]">
                                <img src="${avatar}" alt="${text}" class="w-full h-full rounded-[100%] object-cover">
                            </div>
                            <span>${text}</span>
                            <span class="badge badge-primary">${role}</span>
                        </div>`
                    );
                    return $container;
                },
                templateSelection: function(data) {
                    // For the selected option, display the name and designation
                    var avatar = $(data.element).data('avatar');
                    var role = $(data.element).data('role');
                    var text = data.text;

                    // Return custom HTML for the selected option
                    var $container = $(
                        `<div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-[100%]">
                                <img src="${avatar}" alt="${text}" class="w-full h-full rounded-[100%] object-cover">
                            </div>
                            <span>${text}</span>
                            <span class="badge badge-primary">${role}</span>
                        </div>`
                    );
                    return $container;
                }
            });
        });
    </script>
@endsection
