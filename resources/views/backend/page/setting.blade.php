@extends('backend.setting.customer.index')
@section('title')
    {{ __('Registration Settings') }}
@endsection
@section('customer-content')
    <div class="card">
        <div class="card-body p-6">
            <form action="{{ route('admin.page.setting.update') }}" method="post" enctype="multipart/form-data"">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="input-area flex items-center justify-between border border-slate-100 dark:border-slate-700 rounded px-3 py-2">
                        <label class="form-label !mb-0">
                            {{ __('Username:') }}
                        </label>
                        <div class="form-switch ps-0 leading-[0]">
                            <input class="form-check-input" type="hidden" value="0" name="username_show"/>
                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                <input type="checkbox" name="username_show" value="1" class="sr-only peer" @checked( getPageSetting('username_show'))>
                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                            </label>
                        </div>
                    </div>
                    <div class="input-area flex items-center justify-between border border-slate-100 dark:border-slate-700 rounded px-3 py-2">
                        <label class="form-label !mb-0">
                            {{ __('Phone Number:') }}
                        </label>
                        <div class="form-switch ps-0 leading-[0]">
                            <input class="form-check-input" type="hidden" value="0" name="phone_show"/>
                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                <input type="checkbox" name="phone_show" value="1" class="sr-only peer" @checked( getPageSetting('phone_show'))>
                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                            </label>
                        </div>
                    </div>
                    <div class="input-area flex items-center justify-between border border-slate-100 dark:border-slate-700 rounded px-3 py-2">
                        <label class="form-label !mb-0">
                            {{ __('Country:') }}
                        </label>
                        <div class="form-switch ps-0 leading-[0]">
                            <input class="form-check-input" type="hidden" value="0" name="country_show"/>
                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                <input type="checkbox" name="country_show" value="1" class="sr-only peer" @checked( getPageSetting('country_show'))>
                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                            </label>
                        </div>
                    </div>
                    <div class="input-area flex items-center justify-between border border-slate-100 dark:border-slate-700 rounded px-3 py-2">
                        <label class="form-label !mb-0">
                            {{ __('Referral Code:') }}
                        </label>
                        <div class="form-switch ps-0 leading-[0]">
                            <input class="form-check-input" type="hidden" value="0" name="referral_code_show"/>
                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                <input type="checkbox" name="referral_code_show" value="1" class="sr-only peer" @checked( getPageSetting('referral_code_show'))>
                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mt-10">
                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
