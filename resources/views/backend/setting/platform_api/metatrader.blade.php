@extends('backend.setting.platform_api.index')
@section('title')
    {{ __('MetaTrader API Settings') }}
@endsection
@section('platform-content')
    <div class="card">
        <div class="card-body p-6">
            <form action="" method="" enctype="multipart/form-data">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('CTrader server API URL - Primary') }}
                        </label>
                        <input type="text" name="site_admin_prefix" class=" form-control " placeholder="http://9787.76867.34534.66">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('CTrader server API KEY - Demo') }}
                        </label>
                        <input type="text" name="site_admin_prefix" class=" form-control " placeholder="G2JHGJK43G2KLJ3GH2GHJKGDJK">
                    </div>
                    <div class="md:col-span-2">
                        <div class="flex items-center space-x-7 flex-wrap">
                            <div class="form-switch ps-0">
                                <input 
                                    class="form-check-input" 
                                    type="hidden" 
                                    value="0" 
                                    name="demo_server"
                                />
                                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="demo_server" 
                                        value="1" 
                                        class="sr-only peer"
                                    />
                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                </label>
                            </div>
                            <label class="form-label !w-auto pt-0">
                                {{ __('Demo Server (If separate)') }}
                            </label>
                        </div>
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('Name') }}
                        </label>
                        <select name="" class="select2 form-control w-full">
                            <option value="ctrader">{{ __('CTrader')}}</option>
                            <option value="metatrader">{{ __('MetaTrader')}}</option>
                        </select>
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('Status') }}
                        </label>
                        <select name="" class="select2 form-control w-full">
                            <option value="enabled">{{ __('Enabled')}}</option>
                            <option value="disabled">{{ __('Disabled')}}</option>
                        </select>
                    </div>
                    <div>
                        <ul class="space-y-4">
                            <li class="flex items-center space-x-7 flex-wrap">
                                <span class="flex-1 text-slate-400 dark:text-slate-400">
                                    {{ __('ID:') }}
                                </span>
                                <span class="flex-1 text-slate-900 dark:text-white">
                                    {{ __('4') }}
                                </span>
                            </li>
                            <li class="flex items-center space-x-7 flex-wrap">
                                <span class="flex-1 text-slate-400 dark:text-slate-400">
                                    {{ __('Created:') }}
                                </span>
                                <span class="flex-1 text-slate-900 dark:text-white">
                                    {{ __('Sep. 5, 2023 05:23:14') }}
                                </span>
                            </li>
                            <li class="flex items-center space-x-7 flex-wrap">
                                <span class="flex-1 text-slate-400 dark:text-slate-400">
                                    {{ __('Updated:') }}
                                </span>
                                <span class="flex-1 text-slate-900 dark:text-white">
                                    {{ __('Mar. 14, 2024 02:44:24') }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="mt-10">
                    <button type="submit" class="btn btn-dark inline-flex items-center">
                        {{ __('Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>


@endsection
