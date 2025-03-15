@extends('backend.layouts.app')
@section('title')
    {{ __('Create New Lead') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="card">
        <form action="{{ route('admin.lead.store') }}" method="post" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="card !mt-0">
                <div class="card-header">
                    <h4 class="card-title">{{ __('Lead Contact Detail') }}</h4>
                </div>
                <div class="card-body p-6">
                    <div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
                        <div class="input-area">
                            <label for="" class="form-label">
                                {{ __('Salutation:') }}
                                <span class="text-xs text-danger">*</span>
                            </label>
                            <select name="salutation" class="select2 form-control">
                                <option value="mr">{{ __('Mr') }}</option>
                                <option value="mrs">{{ __('Mrs') }}</option>
                                <option value="miss">{{ __('Miss') }}</option>
                                <option value="dr">{{ __('Dr.') }}</option>
                                <option value="sir">{{ __('Sir') }}</option>
                                <option value="madam">{{ __('Madam') }}</option>
                            </select>
                            @error('salutation')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                {{ __('First Name:') }}
                                <span class="text-xs text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                name="first_name"
                                class="form-control mb-0"
                                placeholder="e.g. John"
                                value="{{ old('first_name') }}"
                            />
                            @error('first_name')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                {{ __('Last Name:') }}
                                <span class="text-xs text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                name="last_name"
                                class="form-control mb-0"
                                placeholder="e.g. Doe"
                                value="{{ old('last_name') }}"
                            />
                            @error('last_name')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                {{ __('Email:') }}
                                <span class="text-xs text-danger">*</span>
                            </label>
                            <input
                                type="email"
                                name="client_email"
                                class="form-control mb-0"
                                placeholder="e.g. johndoe@example.com"
                                value="{{ old('client_email') }}"
                            />
                            @error('client_email')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                {{ __('Phone:') }}
                                <span class="text-xs text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                name="phone"
                                class="form-control mb-0"
                                placeholder="e.g. 1234567890"
                                value="{{ old('phone') }}"
                            />
                            @error('phone')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                {{ __('Lead Source:') }}
                                <span class="text-xs text-danger">*</span>
                            </label>
                            <select name="source_id" class="select2 form-control">
                                @foreach($sources as $source)
                                    <option value="{{ $source->id }}">{{ $source->name }}</option>
                                @endforeach
                            </select>
                            @error('source_id')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                {{ __('Lead Owner:') }}
                                <span class="text-xs text-danger">*</span>
                            </label>
                            <select name="lead_owner" id="leadOwner" class="form-control">
                                @foreach($staff as $staff)
                                    <option data-avatar="{{ getFilteredPath($staff->avatar, 'global/materials/user.png') }}" data-role="{{ $staff->getRoleNames()->first() }}" value="{{ $staff->id }}">
                                        {{ $staff->first_name .' '. $staff->last_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lead_owner')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="lg:col-span-3">
                            <div class="input-area mt-3">
                                <div class="checkbox-area">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="toggleDealForm" class="hidden" name="create_deal" @if(old('create_deal') == 'on') checked @endif>
                                        <span class="h-4 w-4 border flex-none border-slate-400 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                            <img src="{{ asset('frontend/images/icon/ck-white.svg') }}" alt="" class="h-[10px] w-[10px] block m-auto opacity-0">
                                        </span>
                                        <span class="form-label text-slate-500 dark:text-slate-400 text-sm leading-6 !mb-0">
                                            {{ __('Create Deal') }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hidden card-body p-6" id="deal-form">
                <div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
                    <div class="input-area">
                        <label class="form-label" for="">
                            {{ __('Deal Name:') }}
                            <span class="text-xs text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="e.g. Acme Corporation"
                            value="{{ old('name') }}"
                        >
                        @error('name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">
                            {{ __('Pipeline:') }}
                            <span class="text-xs text-danger">*</span>
                        </label>
                        <select name="lead_pipeline_id" id="pipelineData" class="select2 form-control">
                            <option value="">{{ __('Select Pipeline') }}</option>
                            @foreach($pipelines as $pipeline)
                                <option value="{{ $pipeline->id }}">{{ $pipeline->name }}</option>
                            @endforeach
                        </select>
                        @error('lead_pipeline_id')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">
                            {{ __('Deal Stages:') }}
                            <span class="text-xs text-danger">*</span>
                        </label>
                        <select name="pipeline_stage_id" id="stages" class="select2 form-control">
                            <option value="">{{ __('select stage') }}</option>
                        </select>
                        @error('stages')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">
                            {{ __('Deal Value:') }}
                            <span class="text-xs text-danger">*</span>
                        </label>
                        <div class="joint-input relative">
                            <input type="text" class="form-control" name="value" value="0">
                            <span class="absolute right-0 top-1/2 -translate-y-1/2 w-auto h-full text-sm h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center px-2">
                                {{ setting('site_currency', 'global') }}
                            </span>
                        </div>
                        @error('value')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">
                            {{ __('Close Date') }}
                            <span class="text-xs text-danger">*</span>
                        </label>
                        <input type="text" name="close_date" class="form-control py-2 flatpickr flatpickr-input" value="{{ old('close_date') }}" readonly>
                        @error('close_date')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <a href="javascript:;" id="companyDetailsToggle" class="inline-flex items-center">
                            {{ __('Company Details') }}
                            <iconify-icon class="text-2xl ml-2" icon="lucide:chevron-down"></iconify-icon>
                        </a>
                    </h4>
                </div>
                <div class="hidden card-body p-6" id="company-details-form">
                    <div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
                        <div class="input-area">
                            <label class="form-label" for="">{{ __('Company Name:') }}</label>
                            <input type="text" name="company_name" class="form-control" placeholder="e.g. Acme Corporation" value="{{ old('company_name') }}">
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">{{ __('Website:') }}</label>
                            <input type="text" name="website" class="form-control" placeholder="e.g. https://www.example.com" value="{{ old('website') }}">
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">{{ __('Office Phone Number:') }}</label>
                            <input type="tel" name="office_phone_number" class="form-control" placeholder="e.g. 1234567890" value="{{ old('office_phone_number') }}">
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">{{ __('Country:') }}</label>
                            <select name="country" class="select2 form-control" data-placeholder="Select Country">
                                <option value="" disabled selected>Select Country</option>
                                @foreach( getCountries() as $country)
                                    <option value="{{ $country['name'] }}">
                                        {{ $country['name']  }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">{{ __('State:') }}</label>
                            <input type="text" name="state" class="form-control" placeholder="e.g. California, Rajasthan, Dubai" value="{{ old('state') }}">
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">{{ __('City:') }}</label>
                            <input type="text" name="city" class="form-control" placeholder="e.g. New York, Jaipur, Dubai" value="{{ old('city') }}">
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">{{ __('Postal code:') }}</label>
                            <input type="text" name="postal_code" class="form-control" placeholder="e.g. 90250" value="{{ old('postal_code') }}">
                        </div>
                        <div class="lg:col-span-3">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Address:') }}</label>
                                <textarea name="address" class="form-control" rows="3" placeholder="e.g. 132, My Street, Kingston, New York 12401">{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="action-btns text-right p-6 pt-4">
                <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                    {{ __('Add Lead') }}
                </button>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script !src="">
        $(document).ready(function() {
            if ($('#toggleDealForm').is(':checked')) {
                $('#deal-form').removeClass('hidden');
            } else {
                $('#deal-form').addClass('hidden');
            }

            // Toggle the deal form visibility when the checkbox is changed
            $('#toggleDealForm').change(function() {
                if ($(this).is(':checked')) {
                    $('#deal-form').removeClass('hidden');
                } else {
                    $('#deal-form').addClass('hidden');
                }
            });

            $('#companyDetailsToggle').click(function() {
                $('#company-details-form').toggleClass('hidden');
            });

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

            function getStages(pipelineId) {
                var url = "{{ route('admin.deal.get-stage', ':id') }}";
                url = url.replace(':id', pipelineId);

                $.get(url, function (response){
                    var options = [];

                    $.each(response.data, function (index, value) {
                        options.push({
                            id: value.id,
                            text: value.name,
                            label_color: value.label_color
                        });
                    });

                    $('#stages').empty();
                    $.each(options, function (index, item) {
                        var option = new Option(item.text, item.id, false, false);
                        $(option).attr('data-color', item.label_color);
                        $('#stages').append(option);
                    });

                    $('#stages').select2({
                        templateResult: function (data) {
                            var $result = $(`
                                <span class="flex space-x-2 rtl:space-x-reverse items-center">
                                    <span class="inline-flex h-[10px] w-[10px] rounded-full" style="background-color: ${$(data.element).attr('data-color')}"></span>
                                    <span>${data.text}</span>
                                </span>`
                            );
                            return $result;
                        },
                        templateSelection: function (data) {
                            var $result = $(`
                                <span class="flex space-x-2 rtl:space-x-reverse items-center">
                                    <span class="inline-flex h-[10px] w-[10px] rounded-full" style="background-color: ${$(data.element).attr('data-color')}"></span>
                                    <span>${data.text}</span>
                                </span>`
                            );
                            return $result;
                        }
                    });
                });
            }

            $('#pipelineData').on("change", function (e) {
                let pipelineId = $(this).val();
                getStages(pipelineId);
            });
        });
    </script>
@endsection
