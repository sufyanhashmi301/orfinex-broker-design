@extends('backend.layouts.app')
@section('title')
    {{ __('Add New Account Type') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Add New Account Type') }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary inline-flex items-center justify-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                {{ __('Back') }}
            </a>
        </div>
    </div>
    <form action="{{route('admin.accountType.store')}}" method="post" enctype="multipart/form-data" class="account_form space-y-5">
        @csrf
        <div class="grid grid-cols-12 gap-5">
            <div class="2xl:col-span-3 lg:col-span-4 col-span-12">
                <div class="card h-full">
                    <div class="card-body p-6">
                        <div class="input-area">
                            <div class="wrap-custom-file">
                                <input
                                    type="file"
                                    name="icon"
                                    id="schema-icon"
                                    accept=".gif, .jpg, .png"
                                />
                                <label for="schema-icon">
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
            <div class="2xl:col-span-9 lg:col-span-8 col-span-12">
                <div class="card h-full">
                    <div class="card-body p-6 space-y-5">
                        <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                            <div class="input-area">
                                <label class="form-label" for="">
                                    {{ __('Account Type Category') }}
                                    <span class="text-xs font-Inter font-normal text-slate-600 block">
                                        {{ __('Choose how this account type should be categorized.') }}
                                    </span>
                                </label>
                                <select name="account_category_id" id="accountTypeCategory" class="form-control w-full" data-placeholder="Select Account Type Category">
                                    @foreach($accountTypeCategories as $category)
                                        <option value="{{ $category->id }}" data-slug="{{ $category->slug }}" data-description="{{ $category->description }}">
                                            {{ $category->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="global_account" class="input-area flex items-center hidden">
                            <input class="form-check-input" type="hidden" value="0" name="is_global">
                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                <input type="radio" name="is_global" value="1" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></div>
                            </label>
                            <div class="flex flex-col ml-5">
                                <span class="text-slate-500 dark:text-slate-400 text-sm leading-6 font-medium">
                                    {{ __('Global Account') }}
                                </span>
                                <span class="text-xs font-Inter font-normal text-slate-600">
                                    {{ __('This will override the country and tag restrictions and will be shown to all users.') }}
                                </span>
                            </div>
                        </div>
                        <div id="ib_rebate_rules" class="hidden">
                            <div class="input-area">
                                <label class="form-label" for="">
                                    {{ __('Select rebate rules') }}
                                    <span class="text-xs font-Inter font-normal text-slate-600 block">
                                        {{ __('Visible to users based on IB Group rebate rule configuration.') }}
                                    </span>
                                </label>
                                <select name="rebate_rules[]" id="rebateRuleSelect" class="select2 form-control w-full h-9" placeholder="Manage Country" multiple>
                                    @foreach( $rebateRules as $rule)
                                        <option  value="{{ $rule['id'] }}" class="inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $rule['title']  }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="country_and_tags" class="grid grid-cols-1 md:grid-cols-2 gap-5 hidden">
                            <div class="input-area">
                                <label class="form-label" for="">
                                    {{ __('Select countries') }}
                                    <span class="text-xs font-Inter font-normal text-slate-600 block">
                                        {{ __('Choose countries to display this forex scheme. Select "All" to show it globally.') }}
                                    </span>
                                </label>
                                <select name="country[]" id="countrySelect" class="select2 form-control w-full h-9" placeholder="Manage Country" multiple>
                                    <option value="All" >
                                        {{ __('All') }}
                                    </option>
                                    @foreach( getCountries() as $country)
                                        <option  value="{{ $country['name'] }}" class="inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $country['name']  }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="input-area">
                                <label class="form-label" for="">
                                    {{ __('Select tags') }}
                                    <span class="text-xs font-Inter font-normal text-slate-600 block">
                                        {{ __('Choose the tags where you would like this account type to be shown') }}
                                    </span>
                                </label>
                                <select name="tags[]" id="tagSelect" class="select2 form-control w-full h-9" placeholder="Manage Tags" multiple>
                                    @foreach( getRiskProfileTag() as $tag)
                                        <option  value="{{ $tag->name }}" class="inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{  $tag->name  }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header noborder">
                <div>
                    <h4 class="card-title">
                        {{ __('Type of Account') }}
                    </h4>
                    <p class="card-text">
                        {{ __('Select all specifications and limits for account types you want clients to be able to open.') }}
                    </p>
                </div>
            </div>
            <div class="card-body p-6 pt-0">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div class="input-area">
                        <label class="form-label">{{ __('Active Trader Type') }}</label>
                        <input
                            type="text"
                            name="trader_type"
                            class="form-control"
                            placeholder="Platform Group"
                            value="{{setting('active_trader_type', 'features')}}"
                            readonly
                        />
                        @error('trader_type')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Title:') }}</label>
                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            placeholder="Account Title"
                        />
                        @error('title')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Account Type Badge:') }}</label>
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Account Type Badge"
                            name="badge"
                        />
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Priority:') }}</label>
                        <input
                            type="text"
                            name="priority"
                            oninput="this.value = validateDouble(this.value)"
                            class="form-control"
                            placeholder="Priority e.g 1,2,3.."
                        />
                        @error('priority')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Account Creation Limit:') }}</label>
                        <input
                            type="text"
                            name="account_limit"
                            oninput="this.value = validateDouble(this.value)"
                            class="form-control"
                            placeholder="Account Limit"

                        />
                        @error('account_limit')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-area @if(!setting('is_forex_group_range', 'global')) hidden @endif">
                        <label class="form-label" for="">{{ __('Range Start(Min 5 digits):') }}</label>
                        <input
                            type="text"
                            name="start_range"
                            oninput="this.value = validateDouble(this.value)"
                            class="form-control"
                            placeholder="Start Range"

                        />
                        @error('start_range')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-area @if(!setting('is_forex_group_range', 'global')) hidden @endif">
                        <label class="form-label" for="">{{ __('Range End(Min 5 digits):') }}</label>
                        <input
                            type="text"
                            name="end_range"
                            oninput="this.value = validateDouble(this.value)"
                            class="form-control"
                            placeholder="End Range"

                        />
                        @error('end_range')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Key Features') }}
        </h4>
        <div class="card">
            <div class="card-body p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Account Type Spread:') }}</label>
                        <input
                            type="text"
                            class="form-control keyFeatureInput"
                            placeholder="Account Type Spread"
                            name="spread"
                        />
                        @error('spread')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Account Type Commission:') }}</label>
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Account Type Commission"
                            name="commission"
                        />
                        @error('commission')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Leverage:') }}</label>
                        <input
                            type="text"
                            name="leverage"
                            class="form-control"
                            placeholder="leverage e.g 10,20,50"
                        />
                        @error('leverage')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('First Min Deposit:') }}</label>
                        <input
                            type="text"
                            name="first_min_deposit"
                            oninput="this.value = validateDouble(this.value)"
                            class="form-control"
                            placeholder="Min deposit"

                        />
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Min Amount in wallet(On Creation):') }}</label>
                        <input
                            type="text"
                            name="min_amount"
                            oninput="this.value = validateDouble(this.value)"
                            class="form-control"
                            placeholder="Min Amount"

                        />
                        @error('min_amount')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-5">
                    {{ __('Live Account') }}
                </h4>
                <div class="card">
                    <div class="card-body p-6 space-y-5">
                        @if (setting('active_trader_type', 'features') == \App\Enums\TraderType::MT5)
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Platform Group') }}</label>
                                <select name="real_swap_free" id="" class="select2 form-control w-full" data-placeholder="Group">
                                    <option value="">{{ __('Select Group')}}</option>
                                    @foreach(\App\Models\PlatformGroup::all() as $group)
                                        <option value="{{$group->group}}">{{ $group->group}}</option>
                                    @endforeach
                                </select>
                                @error('real_swap_free')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        @elseif (setting('active_trader_type', 'features') == \App\Enums\TraderType::X9)
                            <div class="input-area">
                                <label class="form-label">{{ __('Platform Group: ') }}</label>
                                <select name="real_swap_free" class="select2 form-control w-full">
                                    <option
                                        value="" >
                                        {{ __('Select Group')}}
                                    </option>
                                    @foreach(\App\Models\X9ClientGroup::where('client_group_type_id',2)->get() as $group)
                                        <option
                                            value="{{$group->id}}" >
                                            {{ $group->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('real_swap_free')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
{{--                        <div class="input-area">--}}
{{--                            <input--}}
{{--                                type="text"--}}
{{--                                name="real_swap_free"--}}
{{--                                class="form-control"--}}
{{--                                placeholder="Platform Group"--}}
{{--                            />--}}
{{--                        </div>--}}
                        <div class="input-area !mb-7">
                            <div class="flex items-center space-x-5 flex-wrap">
                                <div class="form-switch ps-0" style="line-height:0;">
                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-checkbox" data-target="#live-islamic-group">
                                        <input
                                            type="checkbox"
                                            name="is_real_islamic"
                                            value="1"
                                            class="sr-only peer"
                                        >
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                                <label class="form-label !w-auto pt-0 !mb-0">
                                    {{ __('Enable Separate Swap-Free (Islamic) Account Type') }}
                                </label>
                            </div>
                        </div>
                        <div id="live-islamic-group" class="hidden">
{{--                            <div class="input-area">--}}
{{--                                <label class="form-label" for="">{{ __('Platform Group (Islamic):') }}</label>--}}
{{--                                <input--}}
{{--                                    type="text"--}}
{{--                                    name="real_islamic"--}}
{{--                                    class="form-control"--}}
{{--                                    placeholder="Platform Group (Islamic)"--}}
{{--                                />--}}
{{--                            </div>--}}
                            @if (setting('active_trader_type', 'features') == \App\Enums\TraderType::MT5)
                                <div class="input-area">
                                    <label class="form-label" for="">{{ __('Platform Group') }}</label>
                                    <select name="real_islamic" id="" class="select2 form-control w-full" data-placeholder="Group">
                                        <option value="">{{ __('Select Group')}}</option>
                                        @foreach(\App\Models\PlatformGroup::all() as $group)
                                            <option value="{{$group->group}}">{{ $group->group}}</option>
                                        @endforeach
                                    </select>
                                    @error('real_islamic')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            @elseif (setting('active_trader_type', 'features') == \App\Enums\TraderType::X9)
                                <div class="input-area">
                                    <label class="form-label">{{ __('Platform Group: ') }}</label>
                                    <select name="real_islamic" class="select2 form-control w-full">
                                        <option
                                            value="" >
                                            {{ __('Select Group')}}
                                        </option>
                                        @foreach(\App\Models\X9ClientGroup::where('client_group_type_id',2)->get() as $group)
                                            <option
                                                value="{{$group->id}}" >
                                                {{ $group->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('real_islamic')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                        </div>
                            @if (setting('active_trader_type', 'features') == \App\Enums\TraderType::MT5)
                                <div class="input-area relative">
                                    <label for="" class="form-label">
                                        {{ __('Trading Server') }}
                                    </label>
                                    <input type="text" class="form-control" name="demo_server" placeholder="Trading Server" value="{{ setting('live_server','platform_api') }}" readonly>
                                </div>
                            @elseif (setting('active_trader_type', 'features') == \App\Enums\TraderType::X9)
                                <div class="input-area relative">
                                    <label for="" class="form-label">
                                        {{ __('Trading Server') }}
                                    </label>
                                    <input type="text" class="form-control" name="demo_server" placeholder="Trading Server" value="{{ setting('x9_name','x9_api') }}" readonly>
                                </div>
                            @endif
                    </div>
                </div>
            </div>
            <div>
                <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-5">
                    {{ __('Demo Account') }}
                </h4>
                <div class="card">
                    <div class="card-body p-6 space-y-5">
{{--                        <div class="input-area">--}}
{{--                            <label class="form-label" for="">{{ __('Platform Group') }}</label>--}}
{{--                            <select name="demo_swap_free" id="" class="select2 form-control w-full" data-placeholder="Group">--}}
{{--                                <option value="">{{ __('Select Group')}}</option>--}}

{{--                                @foreach(\App\Models\PlatformGroup::all() as $group)--}}
{{--                                    <option value="{{$group->group}}">{{ $group->group}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                            <input--}}
{{--                                type="text"--}}
{{--                                name="demo_swap_free"--}}
{{--                                class="form-control"--}}
{{--                                placeholder="Platform Group"--}}
{{--                            />--}}
{{--                        </div>--}}
                        @if (setting('active_trader_type', 'features') == \App\Enums\TraderType::MT5)
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Platform Group') }}</label>
                                <select name="demo_swap_free" id="" class="select2 form-control w-full" data-placeholder="Group">
                                    <option value="">{{ __('Select Group')}}</option>

                                    @foreach(\App\Models\PlatformGroup::all() as $group)
                                        <option value="{{$group->group}}">{{ $group->group}}</option>
                                    @endforeach
                                </select>
                                @error('demo_swap_free')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        @elseif (setting('active_trader_type', 'features') == \App\Enums\TraderType::X9)
                            <div class="input-area">
                                <label class="form-label">{{ __('Platform Group: ') }}</label>
                                <select name="demo_swap_free" class="select2 form-control w-full">
                                    <option
                                        value="" >
                                        {{ __('Select Group')}}
                                    </option>
                                    @foreach(\App\Models\X9ClientGroup::where('client_group_type_id',2)->get() as $group)
                                        <option
                                            value="{{$group->id}}" >
                                            {{ $group->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('demo_swap_free')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                        <div class="input-area !mb-7">
                            <div class="flex items-center space-x-5 flex-wrap">
                                <div class="form-switch ps-0" style="line-height:0;">
                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-checkbox" data-target="#demo-islamic-group">
                                        <input
                                            type="checkbox"
                                            name="is_demo_islamic"
                                            value="1"
                                            class="sr-only peer"
                                        >
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                                <label class="form-label !w-auto pt-0 !mb-0">
                                    {{ __('Enable Separate Swap-Free (Islamic) Account Type') }}
                                </label>
                            </div>
                        </div>
                        <div id="demo-islamic-group" class="hidden">
{{--                            <div class="input-area">--}}
{{--                                <label class="form-label" for="">{{ __('Platform Group (Islamic):') }}</label>--}}
{{--                                <input--}}
{{--                                    type="text"--}}
{{--                                    name="demo_islamic"--}}
{{--                                    class="form-control"--}}
{{--                                    placeholder="Platform Group (Islamic)"--}}
{{--                                />--}}
{{--                            </div>--}}
                            @if (setting('active_trader_type', 'features') == \App\Enums\TraderType::MT5)
                                <div class="input-area">
                                    <label class="form-label" for="">{{ __('Platform Group') }}</label>
                                    <select name="demo_islamic" id="" class="select2 form-control w-full" data-placeholder="Group">
                                        <option value="">{{ __('Select Group')}}</option>

                                        @foreach(\App\Models\PlatformGroup::all() as $group)
                                            <option value="{{$group->group}}">{{ $group->group}}</option>
                                        @endforeach
                                    </select>
                                    @error('demo_islamic')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            @elseif (setting('active_trader_type', 'features') == \App\Enums\TraderType::X9)
                                <div class="input-area">
                                    <label class="form-label">{{ __('Platform Group: ') }}</label>
                                    <select name="demo_islamic" class="select2 form-control w-full">
                                        <option
                                            value="" >
                                            {{ __('Select Group')}}
                                        </option>
                                        @foreach(\App\Models\X9ClientGroup::where('client_group_type_id',2)->get() as $group)
                                            <option
                                                value="{{$group->id}}" >
                                                {{ $group->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('demo_islamic')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                        </div>
                        @if (setting('active_trader_type', 'features') == \App\Enums\TraderType::MT5)
                            <div class="input-area relative">
                                <label for="" class="form-label">
                                    {{ __('Trading Server (Demo) ') }}
                                </label>
                                <input type="text" class="form-control" name="demo_server" placeholder="Trading Server Demo" value="{{ setting('demo_server','platform_api') }}" readonly>
                            </div>
                        @elseif (setting('active_trader_type', 'features') == \App\Enums\TraderType::X9)
                            <div class="input-area relative">
                                <label for="" class="form-label">
                                    {{ __('Trading Server (Demo) ') }}
                                </label>
                                <input type="text" class="form-control" name="x9_name" placeholder="Trading Server Demo" value="{{ setting('x9_name','x9_api') }}" readonly>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('More Details') }}
        </h4>
        <div class="card">
            <div class="card-body p-6">
                <div class="input-area mb-5">
                    <label for="" class="form-label">{{ __('Detail:') }}</label>
                    <div class="site-editor">
                        <textarea class="summernote"></textarea>
                    </div>
                    <input type="hidden" name="desc">
                </div>
                <div class="grid grid-cols-12 gap-5 items-center">
                    <div class="2xl:col-span-12 lg:col-span-12 col-span-12">
                        <div class="grid md:grid-cols-4 col-span-1 gap-5">
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <label class="form-label !w-auto pt-0 !mb-0">
                                        {{ __('Withdraw') }}
                                    </label>
                                    <div class="form-switch ps-0" style="line-height:0;">
                                        <input
                                            class="form-check-input"
                                            type="hidden"
                                            value="0"
                                            name="is_withdraw"
                                        >
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input
                                                type="checkbox"
                                                name="is_withdraw"
                                                value="1"
                                                class="sr-only peer"
                                            >
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <label class="form-label !w-auto pt-0 !mb-0">
                                        {{ __('Internal Transfer') }}
                                    </label>
                                    <div class="form-switch ps-0" style="line-height:0;">
                                        <input
                                            class="form-check-input"
                                            type="hidden"
                                            value="0"
                                            name="is_internal_transfer"
                                        >
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input
                                                type="checkbox"
                                                name="is_internal_transfer"
                                                value="1"
                                                class="sr-only peer"
                                            >
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <label class="form-label !w-auto pt-0 !mb-0">
                                        {{ __('External Transfer') }}
                                    </label>
                                    <div class="form-switch ps-0" style="line-height:0;">
                                        <input
                                            class="form-check-input"
                                            type="hidden"
                                            value="0"
                                            name="is_external_transfer"
                                        >
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input
                                                type="checkbox"
                                                name="is_external_transfer"
                                                value="1"
                                                class="sr-only peer"
                                            >
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <label class="form-label !w-auto pt-0 !mb-0">
                                        {{ __('Cent Account') }}
                                    </label>
                                    <div class="form-switch ps-0" style="line-height:0;">
                                        <input
                                            class="form-check-input"
                                            type="hidden"
                                            value="0"
                                            name="is_cent_account"
                                        >
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input
                                                type="checkbox"
                                                name="is_cent_account"
                                                value="1"
                                                class="sr-only peer"
                                                {{ old('is_cent_account', $forexSchema->is_cent_account ?? false) ? 'checked' : '' }}
                                            >
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[\'\'] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="grid grid-cols-12 gap-5 items-center">
                    <div class="2xl:col-span-3 lg:col-span-4 col-span-12">
                        <div class="input-area">
                            <select name="status" id="" class="select2 form-control w-full" data-placeholder="Status">
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Deactivate') }}</option>
                            </select>
                        </div>
                    </div>


                </div>
                <div class="mt-10">
                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                        {{ __('Add New') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('global/css/bootstrap-tagsinput.css') }}">
    <style>
        .bootstrap-tagsinput {
            width: 100%;
            border-radius: 0.25rem;
            border-width: 1px;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
        }
        .bootstrap-tagsinput .tag.label-info{
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
            font-family: Inter, sans-serif;
            font-size: 0.75rem;
            line-height: 1rem;
            font-weight: 400;
            border-radius: 4px;
        }
    </style>
@endsection
@section('script')
    <script src="{{ asset('global/js/bootstrap-tagsinput.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            function formatState(state) {
                if (!state.id) {
                    return state.text;
                }

                const description = $(state.element).data('description') || '';
                const text = state.text || '';

                return $(`
                    <span class="leading-none">
                        <span class="block">${text}</span>
                        <span class="text-xs font-Inter font-normal text-slate-600">${description}</span>
                    </span>
                `);
            }

            $("#accountTypeCategory").select2({
                templateResult: formatState
            });

            const categorySelect = $('#accountTypeCategory');
            const countrySelect = $('#countrySelect');
            const tagSelect = $('#tagSelect');
            const rebateRuleSelect = $('#rebateRuleSelect');
            const allCategoryBlocks = ['#global_account', '#ib_rebate_rules', '#country_and_tags'];
            function updateAccountTypeCategory(selectedValue) {
                // Hide all blocks
                allCategoryBlocks.forEach(selector => $(selector).addClass('hidden'));

                // Show the selected block
                const selectedBlock = `#${selectedValue}`;
                if (allCategoryBlocks.includes(selectedBlock)) {
                    $(selectedBlock).removeClass('hidden');
                }
            }

            // Initial show/hide on page load (without reset)
            updateAccountTypeCategory(categorySelect.find('option:selected').data('slug'));

            // Add change listener
            categorySelect.on('change', function () {
                const slug = $(this).find('option:selected').data('slug');
                // Reset field values only on change
                countrySelect.val(null).trigger('change');
                tagSelect.val(null).trigger('change');
                rebateRuleSelect.val(null).trigger('change');

                // Then update visible block
                updateAccountTypeCategory(slug);
            });

            var elt = $('.keyFeatureInput');
            elt.tagsinput({
                maxTags: 5
            });

            $('.toggle-checkbox').change(function() {
                var target = $(this).data('target');
                $(target).toggleClass('hidden');
            });

            $('.account_form').on('keypress', function(event) {
                if (event.which === 13) { // 13 is the keycode for Enter
                    event.preventDefault();
                }
            });

        });
    </script>
@endsection
