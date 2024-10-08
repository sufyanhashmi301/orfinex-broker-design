@extends('backend.layouts.app')
@section('title')
    {{ __('New Customer') }}
@endsection
@section('content')
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <form action="{{ route('admin.user.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="space-y-5">
            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                {{ __('Basic Info') }}
            </h4>
            <div class="card">
                <div class="card-body p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('First Name') }}
                            </label>
                            <input type="text" name="first_name" class="form-control" placeholder="" required>
                        </div>
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Last Name') }}
                            </label>
                            <input type="text" name="last_name" class="form-control" placeholder="" required>
                        </div>

                        @if(getPageSetting('country_show'))
                            <div class="input-area relative">
                                <label class="form-label">
                                    {{ __('Country') }}
                                </label>
                                <div class="relative">
                                    <select name="country" id="countrySelect" class="select2 form-control w-full" data-placeholder="Select Country">
                                        <option value="" disabled selected>Select Country</option> <!-- Placeholder option -->

                                        @foreach( getCountries() as $country)
                                            <option value="{{ $country['name'].':'.$country['dial_code'] }}"
                                                @if( old('country') == $country['name'].':'.$country['dial_code']) selected @endif
                                                class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                                {{ $country['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                    <div class="input-area relative">
                        <label class="form-label">
                            {{ __('Username') }}
                        </label>
                        <input type="text" name="username" class="form-control" placeholder="">
                    </div>
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Phone') }}
                            </label>
                            <input type="text" name="phone" class="form-control" placeholder="">
                        </div>
                        <div class="input-area relative">
                            <label class="form-label">{{ __('Email') }}</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="">
                            @error('email')
                                <small class="text-red-500">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Gender') }}
                            </label>
                            <select name="gender" class="select2 form-control w-full" data-placeholder="Select Gender">
                                <option value="">{{ __('Select Gender') }}</option>
                                <option value="male">{{ __('Male') }}</option>
                                <option value="female">{{ __('Female') }}</option>
                                <option value="other">{{ __('Other') }}</option>
                            </select>
                        </div>
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Date Of Birth') }}
                            </label>
                            <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                        </div>


                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('City') }}
                            </label>
                            <input type="text" name="city" class="form-control" placeholder="">
                        </div>

                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Zip Code') }}
                            </label>
                            <input type="text" name="zip_code" class="form-control" placeholder="">
                        </div>
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Address') }}
                            </label>
                            <input type="text" name="address" class="form-control" placeholder="">
                        </div>
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Attach to Risk Profile') }}
                            </label>
                            <select name="risk_profile_tags[]" class="select2 form-control w-full" multiple="multiple" data-placeholder="Select Tags">
                                @foreach($riskProfileTags as $tag)
                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-area relative lg:col-span-3">
                            <label class="form-label">
                                {{ __('Comment') }}
                            </label>
                            <textarea name="comment" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                {{ __('Security') }}
            </h4>
            <div class="card">
                <div class="card-body p-6">
                    <div class="grid grid-cols-12 gap-5 items-center">
                        <div class="lg:col-span-4 col-span-12">
                            <div class="input-area">
                                <label for="" class="form-label">
                                    {{ __('Password') }}
                                </label>
                                <input type="password" name="password" class="form-control" placeholder="********">
                            </div>
                        </div>
                        <div class="lg:col-span-8 col-span-12">
                            <div class="grid md:grid-cols-3 col-span-1 gap-5">
                                <div class="input-area">
                                    <label for="" class="form-label invisible">
                                        {{ __('Email Verified') }}
                                    </label>
                                    <div class="flex items-center space-x-7 flex-wrap">
                                        <div class="form-switch ps-0" style="line-height:0;">
                                            <input type="hidden" value="0" name="is_email_verified">
                                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                <input type="checkbox" name="is_email_verified" value="1" class="sr-only peer">
                                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                            </label>
                                        </div>
                                        <label class="form-label !w-auto pt-0 !mb-0">
                                            {{ __('Email Verified') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="input-area">
                                    <label for="" class="form-label invisible">
                                        {{ __('Phone Verified') }}
                                    </label>
                                    <div class="flex items-center space-x-7 flex-wrap">
                                        <div class="form-switch ps-0" style="line-height:0;">
                                            <input type="hidden" value="0" name="is_phone_verified">
                                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                <input type="checkbox" name="is_phone_verified" value="1" class="sr-only peer">
                                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                            </label>
                                        </div>
                                        <label class="form-label !w-auto pt-0 !mb-0">
                                            {{ __('Phone Verified') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="input-area">
                                    <label for="" class="form-label invisible">
                                        {{ __('Temporary Password') }}
                                    </label>
                                    <div class="flex items-center space-x-7 flex-wrap">
                                        <div class="form-switch ps-0" style="line-height:0;">
                                            <input type="hidden" value="0" name="temporary_password">
                                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                <input type="checkbox" name="temporary_password" value="1" class="sr-only peer">
                                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                            </label>
                                        </div>
                                        <label class="form-label !w-auto pt-0 !mb-0">
                                            {{ __('Temporary Password') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-12 gap-5">
                <div class="lg:col-span-4 col-span-12">
                    <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block mb-5">
                        {{ __('KYC Verification Status') }}
                    </h4>
                    <div class="card">
                        <div class="card-body space-y-5 p-6">
                            <div class="input-area relative">
                                <label for="status" class="form-label">{{ __('KYC Level:') }}</label>
                                <select name="kyc" id="status" class="form-control">
                                    <option value="">{{ __('Select') }}</option>

                                    {{-- Loop through KYC Levels --}}
                                    @foreach($kycLevels as $level)
                                        <option value="{{ $level->id }}">
                                            {{ $level->name }}
                                        </option>
                                    @endforeach

                                    {{-- Loop through KYC Statuses (Enum) --}}
                                    @foreach(App\Enums\KYCStatus::cases() as $status)
                                        <option value="kyc_{{ $status->value }}">
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="input-area relative">
                                <label for="" class="form-label">
                                    {{ __('Verified By') }}
                                </label>
                                <select name="" class="select2 form-control w-full" data-placeholder="Staff Name">
                                    <option value="">{{ __('Staff Name') }}</option>
                                </select>
                            </div>
                            <div class="input-area relative">
                                <label for="" class="form-label">
                                    {{ __('Comment') }}
                                </label>
                                <textarea name="" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-8 col-span-12">
                    <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block mb-5">
                        {{ __('Passport or Driving Licence') }}
                    </h4>
                    <div class="card">
                        <div class="card-body p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="input-area relative">
                                    <label for="" class="form-label">
                                        {{ __('ID Type') }}
                                    </label>
                                    <select name="" class="select2 form-control w-full">
                                        <option value="passport">{{ __('Passport') }}</option>
                                    </select>
                                </div>
                                <div class="input-area relative">
                                    <label for="" class="form-label">
                                        {{ __('Document No#') }}
                                    </label>
                                    <input type="text" name="" class="form-control">
                                </div>
                                <div class="input-area relative">
                                    <div class="wrap-custom-file">
                                        <input
                                            type="file"
                                            name="icon"
                                            id="front-page"
                                            accept=".gif, .jpg, .png"

                                        />
                                        <label for="front-page">
                                            <img
                                                class="upload-icon"
                                                src="{{asset('global/materials/upload.svg')}}"
                                                alt=""
                                            />
                                            <span>{{ __('Upload Avatar') }}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="input-area relative">
                                    <div class="wrap-custom-file">
                                        <input
                                            type="file"
                                            name="icon"
                                            id="back-page"
                                            accept=".gif, .jpg, .png"

                                        />
                                        <label for="back-page">
                                            <img
                                                class="upload-icon"
                                                src="{{asset('global/materials/upload.svg')}}"
                                                alt=""
                                            />
                                            <span>{{ __('Upload Avatar') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10">
            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                {{ __('Add New Customer') }}
            </button>
        </div>
    </form>
@endsection
