@extends('backend.setting.platform_api.index')
@section('title')
    {{ __('CTrader API Settings') }}
@endsection
@section('title-desc')
    {{ __("Set up Trading Platform API's provided by your provider.") }}
@endsection
@section('platform-api-content')
    <div class="card">
        <div class="card-body p-6">
            <form action="" method="" enctype="multipart/form-data">
                <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-5">
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Specify a recognizable name for the server, such as ‘YourCompany-Live’ or ‘YourCompany-Demo,’ for easy identification and selection">
                                {{ __('Server Name') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <input type="text" name="name" class=" form-control " placeholder="CTrader">
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
                            <label class="form-label !w-auto !mb-0">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enable this option if the demo environment is hosted on a separately deployed server">
                                    {{ __('Demo Server (If separate)') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="This is the API Endpoint or URL to the network. It serves as the connection point for communicating with the server">
                                {{ __('Network Address') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <input type="text" name="" class="form-control" placeholder="https://your-platform.encrypted-gateway.com">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="This is the login credential required to authenticate and connect to the platform through the API">
                                {{ __('Login') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <input type="text" name="" class="form-control" placeholder="45423">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The password associated with the login credential for secure access to the platform">
                                {{ __('Password') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <input type="password" name="" class="form-control" placeholder="*************">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Set the connection as Enabled or Disabled">
                                {{ __('Status') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <select name="" class="select2 form-control w-full">
                            <option value="enabled">{{ __('Enabled')}}</option>
                            <option value="disabled">{{ __('Disabled')}}</option>
                        </select>
                    </div>
                    <div class="input-area">
                        <div class="flex items-center gap-3">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Displays the date and time when the server settings were last modified or updated">
                                <span class="text-slate-400 dark:text-slate-400">{{ __('Updated:') }}</span>
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                            <span class="text-slate-900 dark:text-white">
                                {{ __('Mar. 14, 2024 02:44:24') }}
                            </span>
                        </div>
                    </div>
                </div>
                @can('c-trader-edit')
                <div class="mt-10">
                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                        {{ __('Save') }}
                    </button>
                </div>
                @endcan
            </form>
        </div>
    </div>


@endsection
