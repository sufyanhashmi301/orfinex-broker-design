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
                            <select name="salutation" class="select2 form-control" required>
                                <option value="mr">{{ __('Mr') }}</option>
                                <option value="mrs">{{ __('Mrs') }}</option>
                                <option value="miss">{{ __('Miss') }}</option>
                                <option value="dr">{{ __('Dr.') }}</option>
                                <option value="sir">{{ __('Sir') }}</option>
                                <option value="madam">{{ __('Madam') }}</option>
                            </select>
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
                                required
                            />
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
                                required
                            />
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
                                required
                            />
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
                                required
                            />
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                {{ __('Lead Source:') }}
                                <span class="text-xs text-danger">*</span>
                            </label>
                            <select name="source_id" class="select2 form-control" required>
                                @foreach($sources as $source)
                                    <option value="{{ $source->id }}">{{ $source->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                {{ __('Lead Stage:') }}
                                <span class="text-xs text-danger">*</span>
                            </label>
                            <select name="stage_id" class="select2 form-control" required>
                                @foreach($stages as $stage)
                                    <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                {{ __('Lead Owner:') }}
                                <span class="text-xs text-danger">*</span>
                            </label>
                            <select name="lead_owner" id="leadOwner" class="form-control" required>
                                @foreach($staff as $staff)
                                    <option data-avatar="{{ getFilteredPath($staff->avatar, 'global/materials/user.png') }}" data-role="{{ $staff->getRoleNames()->first() }}" value="{{ $staff->id }}">
                                        {{ $staff->first_name .' '. $staff->last_name }}
                                    </option>
                                @endforeach
                            </select>
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
                            <label class="form-label" for="">{{ __('Company Name:') }}</label>
                            <input type="text" name="company_name" class="form-control" placeholder="e.g. Acme Corporation">
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">{{ __('Website:') }}</label>
                            <input type="text" name="website" class="form-control" placeholder="e.g. https://www.example.com">
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">{{ __('Office Phone Number:') }}</label>
                            <input type="tel" name="office_phone_number" class="form-control" placeholder="e.g. 1234567890">
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
                            <input type="text" name="state" class="form-control" placeholder="e.g. California, Rajasthan, Dubai">
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">{{ __('City:') }}</label>
                            <input type="text" name="city" class="form-control" placeholder="e.g. New York, Jaipur, Dubai">
                        </div>
                        <div class="input-area">
                            <label class="form-label" for="">{{ __('Postal code:') }}</label>
                            <input type="text" name="postal_code" class="form-control" placeholder="e.g. 90250">
                        </div>
                        <div class="lg:col-span-3">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Address:') }}</label>
                                <textarea name="address" class="form-control" rows="3" placeholder="e.g. 132, My Street, Kingston, New York 12401"></textarea>
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
