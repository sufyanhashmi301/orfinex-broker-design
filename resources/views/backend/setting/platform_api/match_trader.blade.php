@extends('backend.setting.platform_api.index')
@section('title')
    {{ __('MatchTrader API Settings') }}
@endsection
@section('platform-api-content')
    <div class="card">
        <div class="card-body p-6">
            <form action="{{ route('admin.settings.update') }}" method="post">
                @csrf
                <input type="hidden" name="section" value="match_trader_platform_api">
                <div class="grid md:grid-cols-2 grid-cols-1 items-center gap-5">
                    <div class="input-area">
                        <label for="" class="form-label !flex items-center">
                            {{ __('Server Name') }}
                            <iconify-icon class="toolTip onTop ml-1" icon="lucide:info" data-tippy-content="Specify a recognizable name for the server, such as ‘YourCompany-Live’ for easy identification and selection."></iconify-icon>
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            name="mt_live_server_real"
                            value="{{ setting('mt_live_server_real','match_trader_platform_api') }}"
                            required
                        />
                    </div>

                    <div class="input-area">
                        <label for="" class="form-label !flex items-center">
                            {{ __('Network Address') }}
                            <iconify-icon class="toolTip onTop ml-1" icon="lucide:info" data-tippy-content="This is the API Endpoint or URL to the network. It serves as the connection point for communicating with the server."></iconify-icon>
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            name="mt_network_address_real"
                            value="{{ setting('mt_network_address_real','match_trader_platform_api') }}"
                            required
                        />
                    </div>
                    <div class="input-area relative">
                      <label for="" class="form-label">
                          {{ __('API Version') }}
                      </label>
                      <select name="mt_api_version_real" class="select2 form-control w-full" required>
                          <option {{ setting('mt_api_version_real','match_trader_platform_api') == 'v1' ? 'selected' : '' }}  value="v1">v1</option>
                      </select>
                  </div>
                    <div class="input-area">
                        <label for="" class="form-label !flex items-center">
                            {{ __('API Access Key') }}
                            <iconify-icon class="toolTip onTop ml-1" icon="lucide:info" data-tippy-content="The authentication key required to securely connect and access the platform through the API."></iconify-icon>
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            name="mt_api_key_real"
                            value="{{ setting('mt_api_key_real','match_trader_platform_api') }}"
                            required
                        />
                    </div>
                </div>
                
                <div class="grid md:grid-cols-2 grid-cols-1 items-center gap-5 mt-5">
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('Status') }}
                        </label>
                        <select name="mt_server_enabled" class="select2 form-control w-full">
                            <option {{ setting('mt_server_enabled','match_trader_platform_api') == 'enabled' ? 'selected' : '' }} value="enabled">{{ __('Enabled')}}</option>
                            <option {{ setting('mt_server_enabled','match_trader_platform_api') == 'disabled' ? 'selected' : '' }} value="disabled">{{ __('Disabled')}}</option>
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
                                 {{ getSettingByColumn('mt_live_server_real','updated_at') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-10">
                    <button type="submit" class="btn btn-dark inline-flex">
                        {{ __(' Save Changes') }}
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
