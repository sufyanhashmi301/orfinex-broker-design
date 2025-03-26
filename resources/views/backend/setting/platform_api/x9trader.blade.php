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
                        <label for="" class="form-label !flex items-center">
                            {{ __('Server Name') }}
                            <iconify-icon class="toolTip onTop ml-1" icon="lucide:info" data-tippy-content="Specify a recognizable name for the server, such as ‘YourCompany-Live’ or ‘YourCompany-Demo,’ for easy identification and selection."></iconify-icon>
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
                                        id="demoServerToggle"
                                        name="x9_demo_server_enable"
                                        value="1"
                                        @if(setting('x9_demo_server_enable','x9_api')) checked @endif
                                        class="sr-only peer"
                                        disabled
                                    />
                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                </label>
                            </div>
                            <label class="form-label !w-auto !flex items-center !mb-0">
                                {{ __('Demo Server (If separate)') }}
                                <iconify-icon class="toolTip onTop ml-1" icon="lucide:info" data-tippy-content="Enable this option if the demo environment is hosted on a separately deployed server."></iconify-icon>
                            </label>
                        </div>
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label !flex items-center">
                            {{ __('Network Address') }}
                            <iconify-icon class="toolTip onTop ml-1" icon="lucide:info" data-tippy-content="This is the API Endpoint or URL to the network. It serves as the connection point for communicating with the server."></iconify-icon>
                        </label>
                        <input type="text" name="x9_network_address" class="form-control" placeholder="https://your-platform.encrypted-gateway.com" value="{{ setting('x9_network_address','x9_api') }}">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label !flex items-center">
                            {{ __('API Access Key') }}
                            <iconify-icon class="toolTip onTop ml-1" icon="lucide:info" data-tippy-content="The authentication key required to securely connect and access the platform through the API."></iconify-icon>
                        </label>
                        <input type="password" name="x9_API_access_key" class="form-control" placeholder="x-access-token" value="{{ setting('x9_API_access_key','x9_api') }}">
                    </div>

                    <div class="@if(!setting('x9_demo_server_enable','x9_api')) hidden @endif md:col-span-2" id="demo_server_form">
                        <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                            <div class="input-area">
                                <label for="" class="form-label !flex items-center">
                                    {{ __('Server Name - Demo') }}
                                    <iconify-icon class="toolTip onTop ml-1" icon="lucide:info" data-tippy-content="Specify a recognizable name for the server, such as ‘YourCompany-Live’ or ‘YourCompany-Demo,’ for easy identification and selection."></iconify-icon>
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="x9_demo_server"
                                    value=""
                                />
                            </div>
                            <div></div>
                            <div class="input-area">
                                <label for="" class="form-label !flex items-center">
                                    {{ __('Network Address - Demo') }}
                                    <iconify-icon class="toolTip onTop ml-1" icon="lucide:info" data-tippy-content="This is the API Endpoint or URL to the network. It serves as the connection point for communicating with the server."></iconify-icon>
                                </label>
                                <input
                                    type="password"
                                    class="form-control"
                                    name="x9_api_url_demo"
                                    value=""

                                />
                            </div>
                            <div class="input-area">
                                <label for="" class="form-label !flex items-center">
                                    {{ __('API Access Key - Demo') }}
                                    <iconify-icon class="toolTip onTop ml-1" icon="lucide:info" data-tippy-content="The authentication key required to securely connect and access the platform through the API."></iconify-icon>
                                </label>
                                <input
                                    type="password"
                                    class="form-control"
                                    name="x9_api_key_demo"
                                    value=""
                                />
                            </div>
                        </div>
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

                    <div class="input-area">
                        <label for="" class="form-label invisible">
                            Last update
                        </label>
                        <div class="flex item-center gap-3">
                            <span class="flex items-center text-slate-400 dark:text-slate-400">
                                {{ __('Updated') }}
                                <iconify-icon class="toolTip onTop ml-1" icon="lucide:info" data-tippy-content="Displays the date and time when the server settings were last modified or updated."></iconify-icon>
                            </span>
                            <span class="text-slate-900 dark:text-white">
                                 {{ getSettingByColumn('x9_name','updated_at') }}
                            </span>
                        </div>
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
@section('platform-script')
    <script>
        $(document).ready(function() {
            $('#demoServerToggle').change(function() {
                $('#demo_server_form').toggleClass('hidden', !this.checked);
            });
        });
    </script>
@endsection
