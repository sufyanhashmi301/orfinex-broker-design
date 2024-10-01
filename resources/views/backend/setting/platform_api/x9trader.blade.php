@extends('backend.setting.platform_api.index')
@section('title')
    {{ __('X9trader Settings') }}
@endsection
@section('platform-api-content')
    <div class="card">
        <div class="card-body p-6">
            <form action="{{ route('admin.settings.update') }}" method="post">
                @csrf
                <input type="hidden" name="section" value="x9_api">
                <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-5">
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('Name') }}
                        </label>
                        <input type="text" name="x9_name" class="form-control" placeholder="X9trader" value="{{ setting('x9_name','x9_api') }}">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label invisible">
                            {{ __('Demo server') }}
                        </label>
                        <div class="flex items-center space-x-7 flex-wrap">
                            <div class="form-switch ps-0" style="line-height: 0;">
                                <input
                                    class="form-check-input"
                                    type="hidden"
                                    value="0"
                                    name="x9_demo_server_enable"
                                />
                                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="x9_demo_server_enable"
                                        value="1"
                                        @if(setting('x9_demo_server_enable','x9_api')) checked @endif
                                        class="sr-only peer"
                                    />
                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                </label>
                            </div>
                            <label class="form-label !w-auto pt-0 !mb-0">
                                {{ __('Demo Server (If separate)') }}
                            </label>
                        </div>
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('Network Address') }}
                        </label>
                        <input type="text" name="x9_network_address" class="form-control" placeholder="https://your-platform.encrypted-gateway.com" value="{{ setting('x9_network_address','x9_api') }}">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('Login') }}
                        </label>
                        <input type="text" name="x9_login" class="form-control" placeholder="x-access-token" value="{{ setting('x9_login','x9_api') }}">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('Password') }}
                        </label>
                        <input type="password" name="x9_password" class="form-control" placeholder="*************" value="{{ setting('x9_password','x9_api') }}">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('Status') }}
                        </label>
                        <select name="x9_status" class="select2 form-control w-full">
                            <option value="1">{{ __('Enabled')}}</option>
                            <option value="0">{{ __('Disabled')}}</option>
                        </select>
                    </div>
                    <div>
                        <ul class="space-y-4">
                            <li class="flex items-center space-x-7 flex-wrap">
                                <span class="flex-1 text-slate-400 dark:text-slate-400">
                                    {{ __('ID:') }}
                                </span>
                                <span class="flex-1 text-slate-900 dark:text-white">
                                     {{ getSettingByColumn('x9_name','id') }}
                                </span>
                            </li>
                            <li class="flex items-center space-x-7 flex-wrap">
                                <span class="flex-1 text-slate-400 dark:text-slate-400">
                                    {{ __('Created:') }}
                                </span>
                                <span class="flex-1 text-slate-900 dark:text-white">
                                    {{ getSettingByColumn('x9_name','created_at') }}
                                </span>
                            </li>
                            <li class="flex items-center space-x-7 flex-wrap">
                                <span class="flex-1 text-slate-400 dark:text-slate-400">
                                    {{ __('Updated:') }}
                                </span>
                                <span class="flex-1 text-slate-900 dark:text-white">
                                    {{ getSettingByColumn('x9_name','updated_at') }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="mt-10">
                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                        {{ __('Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>


@endsection
