@extends('backend.setting.platform_api.index')
@section('title')
    {{ __('MetaTrader API Settings') }}
@endsection
@section('title-desc')
    {{ __("Set up Trading Platform API's provided by your provider.") }}
@endsection
@section('platform-api-content')
    <div class="card">
        <div class="card-body p-6">
            <form action="{{ route('admin.settings.update') }}" method="post">
                @csrf
                <input type="hidden" name="section" value="platform_api">
                <div class="grid md:grid-cols-2 grid-cols-1 items-center gap-5">
                    <div class="input-area">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Specify a recognizable name for the server, such as ‘YourCompany-Live’ or ‘YourCompany-Demo,’ for easy identification and selection">
                                {{ __('Server Name') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            name="live_server"
                            value="{{ setting('live_server','platform_api') }}"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label invisible">
                            {{ __('Demo server') }}
                        </label>
                        <div class="flex items-center space-x-7 flex-wrap">
                            <div class="form-switch ps-0" style="line-height: 0">
                                <input class="form-check-input" type="hidden" value="0" name="demo_server_enable" >
                                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        id="demoServerToggle"
                                        name="demo_server_enable"
                                        value="1"
                                        @if(setting('demo_server_enable','platform_api')) checked @endif
                                        class="sr-only peer"
                                    >
                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                </label>
                            </div>
                            <label class="form-label !w-auto !mb-0">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enable this option if the demo environment is hosted on a separately deployed server">
                                    {{ __('Demo Server (If Separate)') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="This is the API Endpoint or URL to the network. It serves as the connection point for communicating with the server">
                                {{ __('Network Address') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            name="mt5_api_url_real"
                            value="{{ setting('mt5_api_url_real','platform_api') }}"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The authentication key required to securely connect and access the platform through the API">
                                {{ __('API Access Key') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <input
                            type="password"
                            class="form-control"
                            name="mt5_api_key_real"
                            value="{{ setting('mt5_api_key_real','platform_api') }}"
                            required
                        />
                    </div>
                </div>
                <div class="@if(!setting('demo_server_enable','platform_api')) hidden @endif mt-5" id="demo_server_form">
                    <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Specify a recognizable name for the server, such as ‘YourCompany-Live’ or ‘YourCompany-Demo,’ for easy identification and selection">
                                    {{ __('Server Name - Demo') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                name="demo_server"
                                value="{{ setting('demo_server','platform_api') }}"
                                required
                            />
                        </div>
                        <div></div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="This is the API Endpoint or URL to the network. It serves as the connection point for communicating with the server">
                                    {{ __('Network Address - Demo') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                name="mt5_api_url_demo"
                                value="{{ setting('mt5_api_url_demo','platform_api') }}"
                            />
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The authentication key required to securely connect and access the platform through the API">
                                    {{ __('API Access Key - Demo') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input
                                type="password"
                                class="form-control"
                                name="mt5_api_key_demo"
                                value="{{ setting('mt5_api_key_demo','platform_api') }}"
                            />
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 grid-cols-1 items-center gap-5 mt-5">
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
                        <label for="" class="form-label invisible">
                            {{ __('Last Update') }}
                        </label>
                        <div class="flex item-center gap-3">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Displays the date and time when the server settings were last modified or updated">
                                <span class="text-slate-400 dark:text-slate-400">{{ __('Updated:') }}</span>
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                            <span class="text-slate-900 dark:text-white">
                                 {{ getSettingByColumn('mt5_api_url_real','updated_at') }}
                            </span>
                        </div>
                    </div>
                </div>
                @can('meta-trader-edit')
                <div class="mt-10">
                    <button type="submit" class="btn btn-dark inline-flex ltr:mr-2 rtl:ml-2">
                        {{ __(' Save Changes') }}
                    </button>
                    @can('mt5-groups-delete')
                    <a href="javascript:;" class="btn btn-danger inline-flex" type="button" data-bs-toggle="modal" data-bs-target="#resetAllGroupsModal">
                        {{ __(' Reset All Groups') }}
                    </a>
                    @endcan
                </div>
                @endcan

            </form>
        </div>
    </div>

@endsection
@section('platform-script')
    <script>
        $(document).ready(function() {
            $('#demoServerToggle').change(function() {
                $('#demo_server_form').toggleClass('hidden', !this.checked);
            });
        });
    </script>
    @can('mt5-groups-delete')
        @include('backend.platform_group.modal.__reset_all')
    @endcan
@endsection
