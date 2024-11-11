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
            <a href="{{ url()->previous() }}" class="btn btn-primary inline-flex items-center justify-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                {{ __('Back') }}
            </a>
        </div>
    </div>
    <form action="{{route('admin.account-type.store')}}" method="post" enctype="multipart/form-data" id="accountTypeForm">
        @csrf
        <div class="grid grid-cols-12 gap-5 mb-6">
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
                    <div class="card-header noborder">
                        <div>
                            <h4 class="card-title">
                                {{ __('Account Type Filter') }}
                            </h4>
                            <p class="card-text">
                                {{ __('You can filter the account types based on users\' countries or tags.') }}
                            </p>
                        </div>
                    </div>
                    <div class="card-body p-6 pt-0 space-y-5">
                        <div class="input-area">
                            <label class="form-label"
                                   for="">{{ __('Select Specific Countries or Select "All"') }}</label>
                            <select name="countries[]" class="select2 form-control w-full h-9"
                                    placeholder="Manage Country" multiple>
                                <option value="All" {{ in_array('All', old('countries', [])) ? 'selected' : '' }}>
                                    {{ __('All') }}
                                </option>
                                @foreach(getCountries() as $country)
                                    <option
                                        value="{{ $country['name'] }}" {{ in_array($country['name'], old('countries', [])) ? 'selected' : '' }}>
                                        {{ $country['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-area">
                            <label class="form-label"
                                   for="">{{ __('Select User\'s Tags') }}</label>
                            <select name="tags[]" class="select2 form-control w-full h-9" placeholder="Manage Tags"
                                    multiple>
                                @foreach(getRiskProfileTag() as $tag)
                                    <option
                                        value="{{ $tag->name }}" {{ in_array($tag->name, old('tags', [])) ? 'selected' : '' }}>
                                        {{ $tag->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-6">
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

                    @php
                        use App\Enums\AccountType;

                        // Retrieve the type parameter and check if it’s one of the valid enum values
                        $type = request('type') && in_array(request('type'), [AccountType::CHALLENGE, AccountType::FUNDED, AccountType::AUTO_EXPIRE]) 
                                ? request('type') 
                                : AccountType::CHALLENGE;
                    @endphp

                    <div class="input-area">
                        <label class="form-label">{{ __('Account Type') }}</label>
                        <input type="text" name="type" id="account-type-type" class="form-control capitalize"
                                placeholder="Trader Type" value="{{ str_replace('_', ' ', $type) }}" readonly/>
                    </div>

                    <div class="input-area">
                        <label class="form-label">{{ __('Active Trader Type') }}</label>
                        <input type="text" name="trader_type" class="form-control uppercase"
                                placeholder="Trader Type" value="{{setting('active_trader_type', 'features')}}" readonly/>
                    </div>

                    @if (setting('active_trader_type', 'features') == \App\Enums\TraderType::MT5)
                        <div class="input-area">
                            <label class="form-label">{{ __('Platform Group') }}</label>
                            <input type="text" name="platform_group" class="form-control"
                                   placeholder="Platform Group" required value="{{ old('platform_group') }}" />
                        </div>
                    @elseif (setting('active_trader_type', 'features') == \App\Enums\TraderType::X9)
                        <div class="input-area">
                            <label class="form-label">{{ __('Platform Group') }}</label>
                            <select name="group" class="select2 form-control w-full">
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
                        </div>
                    @endif

                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Title') }}</label>
                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            placeholder="Account Title"
                            value="{{ old('title') }}"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Account Type Badge') }}</label>
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Account Type Badge"
                            name="badge"
                            value="{{ old('badge') }}"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Priority') }}</label>
                        <input
                            type="number"
                            name="priority"
                            oninput="this.value = validateDouble(this.value)"
                            class="form-control"
                            placeholder="Priority e.g 1,2,3.."
                            value="{{ old('priority') }}"
                            required
                            
                        />
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Account Creation Limit') }}</label>
                        <input
                            type="number"
                            name="accounts_limit"
                            oninput="this.value = validateDouble(this.value)"
                            class="form-control"
                            placeholder="Account Limit"
                            value="{{ old('accounts_limit') }}"
                            required
                            min="1"
                            max="50"
                        />
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Range Start (Min 6 digits)') }}</label>
                        <input
                            type="number"
                            name="accounts_range_start"
                            oninput="this.value = this.value.slice(0, 6); validateDouble(this.value);"
                            class="form-control"
                            placeholder="Start Range"
                            value="{{ old('accounts_range_start') }}"
                            required
                            min="100000"
                            max="999999"
                        />
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Range End (Min 6 digits)') }}</label>
                        <input
                            type="number"
                            name="accounts_range_end"
                            oninput="this.value = this.value.slice(0, 6); validateDouble(this.value);"
                            class="form-control"
                            placeholder="End Range"
                            value="{{ old('accounts_range_end') }}"
                            min="100000"
                            max="999999"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Minimum Trading Days') }}</label>
                        <input
                            type="number"
                            name="trading_days"
                            class="form-control"
                            placeholder="Minimum Trading Days"
                            value="{{ old('trading_days') }}"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Company Profit Share (%)') }}</label>
                        <input
                            type="number"
                            name="profit_share"
                            class="form-control"
                            placeholder="50"
                            value="{{ old('profit_share') }}"
                            min="1"
                            max="100"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Leverage') }}</label>
                        <input
                            type="number"
                            name="leverage"
                            oninput="this.value = validateDouble(this.value)"
                            class="form-control"
                            placeholder="Leverage"
                            value="{{ old('leverage') }}"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Commission') }}</label>
                        <input
                            type="number"
                            name="commission"
                            class="form-control"
                            placeholder="Commission"
                            value="{{ old('commission') }}"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Spread') }}</label>
                        <input
                            type="number"
                            name="spread"
                            class="form-control"
                            placeholder="Spread"
                            value="{{ old('spread') }}"
                            required
                        />
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="card mb-6">
            <div class="card-header noborder">
                <div>
                    <h4 class="card-title">
                        {{ __('Key Features (Upto)') }}
                    </h4>
                    <p class="card-text">
                        
                    </p>
                </div>
            </div>
            <div class="card-body p-6 pt-0">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Allotted Fund') }}</label>
                        <input
                            type="number"
                            class="form-control"
                            placeholder="$100,000"
                            name="upto_allotted_fund"
                            value="{{ old('upto_allotted_fund') }}"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Profit Target') }}</label>
                        <input
                            type="number"
                            class="form-control"
                            placeholder="$10,000"
                            name="upto_profit_target"
                            value="{{ old('upto_profit_target') }}"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Daily Max Loss') }}</label>
                        <input
                            type="number"
                            name="upto_daily_max_loss"
                            class="form-control"
                            placeholder="100"
                            value="{{ old('upto_daily_max_loss') }}"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Maximum Loss') }}</label>
                        <input
                            type="number"
                            name="upto_maximum_loss"
                            class="form-control"
                            placeholder="$10,000"
                            value="{{ old('upto_maximum_loss') }}"
                            required
                        />
                    </div>
                </div>
            </div>
        </div>

        {{-- Phases / Rules --}}
        @include('backend.account_types.include.__phases')
        @include('backend.account_types.modal.__control_room')

        {{-- More details --}}
        <div class="card">
            <div class="card-header noborder">
                <div>
                    <h4 class="card-title">
                        {{ __('More Details') }}
                    </h4>
                </div>
            </div>
            <div class="card-body p-6 pt-0">
                <div class="input-area mb-5">
                    <label for="" class="form-label">{{ __('Description') }}</label>
                    <div class="site-editor">
                        <textarea class="summernote" name="description">{{ old('desc') }}</textarea>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-5 items-center">
                    <div class="2xl:col-span-3 lg:col-span-4 col-span-12">
                        <div class="input-area">
                            <select name="status" class="form-control w-full" data-placeholder="Status">
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>
                                    {{ __('Active') }}
                                </option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>
                                    {{ __('Deactivate') }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="2xl:col-span-9 lg:col-span-8 col-span-12">
                        <div class="grid md:grid-cols-3 col-span-1 gap-5">
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <div class="form-switch ps-0" style="line-height:0;">
                                        <input type="hidden" name="is_weekend_holding" value="0">
                                        <label
                                            class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="is_weekend_holding" value="1"
                                                   class="sr-only peer" {{ old('is_weekend_holding') ? 'checked' : '' }}>
                                            <span
                                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                    <label class="form-label !w-auto pt-0 !mb-0">{{ __('Weekend Holding') }}</label>
                                </div>
                            </div>
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <div class="form-switch ps-0" style="line-height:0;">
                                        <input type="hidden" name="is_scalable" value="0">
                                        <label
                                            class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="is_scalable" value="1"
                                                   class="sr-only peer" {{ old('is_scalable') ? 'checked' : '' }}>
                                            <span
                                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                    <label class="form-label !w-auto pt-0 !mb-0">{{ __('Scalable') }}</label>
                                </div>
                            </div>
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <div class="form-switch ps-0" style="line-height:0;">
                                        <input type="hidden" name="is_refundable" value="0">
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="is_refundable" value="1" class="sr-only peer" {{ old('is_refundable') ? 'checked' : '' }}>
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                    <label class="form-label !w-auto pt-0 !mb-0">{{ __('Refundable') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--Modal for Control Room--}}
                <div class="mt-10 flex items-center gap-3">
                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center" id="submit-form">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                        {{ __('Add New') }}
                    </button>
                    {{-- <button type="button" class="btn btn-outline-secondary inline-flex items-center justify-center">
                        {{ __('Cancel') }}
                    </button> --}}
                </div>
            </div>
        </div>

    </form>
    <div id="notification-container" class="fixed top-0 right-0 mt-4 mr-4 space-y-2 z-50"></div>

@endsection
@section('script')
    <script>
        
        function showNotification(message, type) {
            const container = document.getElementById('notification-container');

            // Create a new notification element
            const notification = document.createElement('div');
            notification.className = `p-4 mb-2 rounded-md text-white text-sm ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
            notification.textContent = message;

            // Append the notification to the container
            container.appendChild(notification);

            // Automatically remove the notification after a few seconds
            setTimeout(() => {
                notification.remove();
            }, 5000); // 5 seconds
        }
        

    </script>
@endsection
