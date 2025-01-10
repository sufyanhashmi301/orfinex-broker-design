@extends('backend.layouts.app')
@section('title')
    {{ __('New Customer') }}
@endsection
@section('content')
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
                            <input type="text" class="form-control" name="first_name" value="" required placeholder="First Name" />
                        </div>
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Last Name') }}
                            </label>
                            <input type="text" class="form-control" name="last_name" value="" required placeholder="Last Name" />
                        </div>
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Username') }}
                            </label>
                            <input type="text" name="username" class="form-control" required placeholder="Username">
                        </div>
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Country') }}
                            </label>
                            <select name="country" class="select2 form-control" required data-placeholder="Select Country">
                                <option value="">{{ __('Select Country') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->name }}" data-phone>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Phone') }}
                            </label>
                            <input type="text" name="phone" class="form-control" placeholder="Phone">
                        </div>
                        
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Email') }}
                            </label>
                            <input type="email" name="email" required class="form-control" placeholder="Email">
                        </div>
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Gender') }}
                            </label>
                            <select name="gender" class="select2 form-control w-full" data-placeholder="Select Gender">
                                <option value="">{{ __('Select Gender') }}</option>
                                <option value="male">{{ __('Male') }}</option>
                                <option value="female">{{ __('Female') }}</option>
                            </select>
                        </div>
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('City') }}
                            </label>
                            <input type="text" name="city"  class="form-control" placeholder="City">
                        </div>
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Zip Code') }}
                            </label>
                            <input type="text" name="zip_code" class="form-control" placeholder="Zip Code">
                        </div>
                        <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Address') }}
                            </label>
                            <input type="text" name="address" class="form-control" placeholder="Address">
                        </div>
                        {{-- <div class="input-area relative">
                            <label class="form-label">
                                {{ __('Attach to Risk Profile') }}
                            </label>
                            <select name="" class="select2 form-control w-full">
                                <option value="vip trader">{{ __('VIP Trader') }}</option>
                            </select>
                        </div> --}}
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
                                <input type="password" name="password" class="form-control" required placeholder="********">
                            </div>
                        </div>
                        {{-- <div class="lg:col-span-8 col-span-12">
                            <div class="grid md:grid-cols-3 col-span-1 gap-5">
                                <div class="input-area">
                                    <label for="" class="form-label invisible">
                                        {{ __('Email Verified') }}
                                    </label>
                                    <div class="flex items-center space-x-7 flex-wrap">
                                        <div class="form-switch ps-0" style="line-height:0;">
                                            <input
                                                class="form-check-input"
                                                type="hidden"
                                                value="0"
                                                name="is_email_verified"
                                            >
                                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                <input
                                                    type="checkbox"
                                                    name="is_email_verified"
                                                    value="1"
                                                    class="sr-only peer"
                                                >
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
                                            <input
                                                class="form-check-input"
                                                type="hidden"
                                                value="0"
                                                name="is_phone_verified"
                                            >
                                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                <input
                                                    type="checkbox"
                                                    name="is_phone_verified"
                                                    value="1"
                                                    class="sr-only peer"
                                                >
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
                                            <input
                                                class="form-check-input"
                                                type="hidden"
                                                value="0"
                                                name="temporsry_password"
                                            >
                                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                <input
                                                    type="checkbox"
                                                    name="temporsry_password"
                                                    value="1"
                                                    class="sr-only peer"
                                                >
                                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                            </label>
                                        </div>
                                        <label class="form-label !w-auto pt-0 !mb-0">
                                            {{ __('Temporary Password') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>

            {{-- <div class="grid grid-cols-12 gap-5">
                <div class="lg:col-span-4 col-span-12">
                    <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block mb-5">
                        {{ __('KYC Verification Status') }}
                    </h4>
                    <div class="card">
                        <div class="card-body space-y-5 p-6">
                            <div class="input-area relative">
                                <label for="" class="form-label">
                                    {{ __('Verification Status') }}
                                </label>
                                <select name="" class="select2 form-control w-full">
                                    <option value="level 1">{{ __('Level 1') }}</option>
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
                                            required
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
                                            required
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
            </div> --}}
        </div>
        <div class="mt-10">
            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                {{ __('Add New Customer') }}
            </button>
        </div>
    </form>
@endsection
