@extends('backend.setting.platform_api.index')
@section('title')
    {{ __('MetaTrader API Settings') }}
@endsection
@section('platform-content')
    <div class="card">
        <div class="card-body p-6">
            <form action="{{ route('admin.settings.update') }}" method="post">
                @csrf
                <input type="hidden" name="section" value="platform_api">
                <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('MT5 Server APi URL - Primary') }}</label>
                        <input
                            type="text"
                            class="form-control"
                            name="mt5_api_url_real"
                            value="{{ setting('mt5_api_url_real','platform_api') }}"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('MT5 Server APi KEY - Primary') }}</label>
                        <input
                            type="text"
                            class="form-control"
                            name="mt5_api_key_real"
                            value="{{ setting('mt5_api_key_real','platform_api') }}"
                            required
                        />
                    </div>
                </div>
                <div class="input-area mt-5 mb-10">
                    <div class="flex items-center space-x-7 flex-wrap">
                        <label class="form-label !w-auto pt-0 !mb-0">
                            {{ __('Demo Server (If Separate)') }}
                        </label>
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
                    </div>
                </div>
                <div class="@if(!setting('demo_server_enable','platform_api')) hidden @endif mb-5" id="demo_server_form">
                    <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                        <div class="input-area">
                            <label for="" class="form-label">{{ __('MT5 Server APi URL - Demo') }}</label>
                            <input
                                type="text"
                                class="form-control"
                                name="mt5_api_url_demo"
                                value="{{ setting('mt5_api_url_demo','platform_api') }}"

                            />
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">{{ __('MT5 Server APi KEY - Demo') }}</label>
                            <input
                                type="text"
                                class="form-control"
                                name="mt5_api_key_demo"
                                value="{{ setting('mt5_api_key_demo','platform_api') }}"
                            />
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 grid-cols-1 gap-5 mb-5">
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Live Server Name') }}</label>
                        <input
                            type="text"
                            class="form-control"
                            name="live_server"
                            value="{{ setting('live_server','platform_api') }}"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Demo Server Name') }}</label>
                        <input
                            type="text"
                            class="form-control"
                            name="demo_server"
                            value="{{ setting('demo_server','platform_api') }}"
                            required
                        />
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
                </div>

                <div class="max-w-full w-1/2">
                    <ul class="space-y-4">
                        <li class="flex items-center space-x-7 flex-wrap">
                                <span class="flex-1 text-slate-400 dark:text-slate-400">
                                    {{ __('ID:') }}
                                </span>
                            <span class="flex-1 text-slate-900 dark:text-white">
                               {{ getSettingByColumn('mt5_api_url_real','id') }}

                                </span>
                        </li>
                        <li class="flex items-center space-x-7 flex-wrap">
                                <span class="flex-1 text-slate-400 dark:text-slate-400">
                                    {{ __('Created:') }}
                                </span>
                            <span class="flex-1 text-slate-900 dark:text-white">
                                    {{ getSettingByColumn('mt5_api_url_real','created_at') }}
                                </span>
                        </li>
                        <li class="flex items-center space-x-7 flex-wrap">
                                <span class="flex-1 text-slate-400 dark:text-slate-400">
                                     {{ __('Updated:') }}
                                </span>
                            <span class="flex-1 text-slate-900 dark:text-white">
                                     {{ getSettingByColumn('mt5_api_url_real','updated_at') }}
                                </span>
                        </li>
                    </ul>
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
@section('script')
    <script>
        $(document).ready(function() {
            $('#demoServerToggle').change(function() {
                $('#demo_server_form').toggleClass('hidden', !this.checked);
            });
        });
    </script>
@endsection
