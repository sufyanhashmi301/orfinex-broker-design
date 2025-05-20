@extends('backend.layouts.app')
@section('title')
    {{ __('Edit Account Type') }}
@endsection
@section('style')
    <style>
        .title-placeholder {
            padding: 4px 6px;
            border-radius: 5px;
            background: #eee;
            color: #888;
            font-weight: 500;
            cursor: pointer;
            margin-right: 4px
        }
        .title-placeholder:hover {
            background: #1a1a1a;
            color: #fff;
            transition: background 0.4s
        }
    </style>
@endsection
@section('content')

    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4
            class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Edit Account Type') }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ url()->previous() }}" class="btn btn-primary inline-flex items-center justify-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                {{ __('Back') }}
            </a>
        </div>
    </div>

    <form action="{{ route('admin.account-type.update', $account_type->id) }}" method="post" enctype="multipart/form-data" id="accountTypeForm">
        @csrf
        @method('PUT')

        {{-- Image + Filter --}}
        <div class="grid grid-cols-12 gap-5 mb-6">
            <div class="2xl:col-span-3 lg:col-span-4 col-span-12">
                <div class="card h-full">
                    <div class="card-body p-6">
                        <div class="input-area">
                            <div class="wrap-custom-file">
                                <input type="file" name="icon" id="schema-icon" accept=".gif, .jpg, .png" />
                                <label for="schema-icon" class="file-ok" style="background-image: url({{ asset($account_type->icon) }})">
                                    <img class="upload-icon" src="{{ asset('global/materials/upload.svg') }}" alt="" />
                                    <span>{{ __('Update Icon') }}</span>
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
                                {{ __('Filter Settings') }}
                            </h4>
                            <p class="card-text">
                                {{ __('You can filter the account types based on users\' countries.') }}
                            </p>
                        </div>
                    </div>
                    <div class="card-body p-6 pt-0 space-y-5">
                        <div class="input-area">
                            <label class="form-label" for="">
                                {{ __('Select Specific Countries or Select "All"') }}
                            </label>
                            <select name="countries[]" class="select2 form-control w-full h-9" multiple>
                                <option value="All" @if (in_array('All', json_decode($account_type->countries, true))) selected @endif>
                                    {{ __('All') }}
                                </option>
                                @foreach (getCountries() as $country)
                                    <option value="{{ $country['name'] }}"
                                        @if (in_array($country['name'], json_decode($account_type->countries, true))) selected @endif>
                                        {{ $country['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="input-area">
                            <label class="form-label" for="">
                                {{ __('Choose the tags where you would like this account type to be shown') }}
                            </label>
                            <select name="tags[]" class="form-control w-full h-9" style="pointer-events: none" readonly> <!-- .select2 multiple -->
                                <option value="" disabled hidden selected>Available Soon</option>
                                @foreach (getRiskProfileTag() as $tag)
                                    <option value="{{ $tag->name }}" @if (in_array($tag->name, json_decode($account_type->tags ?? '[]', true))) selected @endif>
                                        {{ $tag->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- Type of Account --}}
        <div class="card mb-6">
            <div class="card-header noborder">
                <div>
                    <h4 class="card-title">
                        {{ __('Account Basics') }}
                    </h4>
                    {{-- <p class="card-text">
                        {{ __('Select all specifications and limits for account types you want clients to be able to open.') }}
                    </p> --}}
                </div>
            </div>
            <div class="card-body p-6 pt-0">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

                    <div class="input-area">
                        <label class="form-label">{{ __('Account Type') }}</label>
                        <input type="text" name="type" id="account-type-type" class="form-control capitalize"
                                placeholder="Trader Type" value="{{ $account_type->type }}" readonly/>
                    </div>

                    <div class="input-area">
                        <label class="form-label">{{ __('Active Trader Type') }}</label>
                        <input type="hidden" name="trader_type" value="{{ $account_type->trader_type }}">
                        <input type="text" name="" class="form-control capitalize" placeholder="Trader Type" value="{{ str_replace('_', ' ', $account_type->trader_type) }}" readonly/>
                    </div>

                    @if ($account_type->trader_type == \App\Enums\TraderType::MT5)
                        <div class="input-area">
                            <label class="form-label">{{ __('Platform Group') }}</label>
                            <input type="text" name="platform_group" class="form-control" placeholder="Platform Group"
                                required value="{{ old('platform_group', $account_type->platform_group) }}" />
                        </div>
                    @elseif ($account_type->trader_type == \App\Enums\TraderType::X9)
                        <div class="input-area">
                            <label class="form-label">{{ __('Platform Group') }}</label>
                            <select name="group" class="select2 form-control w-full">
                                <option value="">
                                    {{ __('Select Group') }}
                                </option>
                                @foreach (\App\Models\X9ClientGroup::where('client_group_type_id', 2)->get() as $group)
                                    <option value="{{ $group->id }}">
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- MetaTrader --}}
                    @if ($account_type->trader_type == \App\Enums\TraderType::MT)
                        <div class="input-area">
                            <label class="form-label">{{ __('Offer') }}</label>
                            <input type="hidden" name="system_uuid" id="mt-system-uuid" value="{{$account_type->system_uuid}}">
                            <select name="offer_uuid" class="select2 form-control w-full mt_offer" required>
                                <option value="">Select Offer</option>
                                @foreach($allOffers as $offer)
                                    <option value="{{$offer->uuid}}" {{ $account_type->offer_uuid == $offer->uuid ? 'selected' : '' }} data-group="{{ $offer->groupName }}" data-leverage="{{ $offer->leverage }}" data-system-uuid="{{ $offer->system->uuid }}">
                                        {{ $offer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-area">
                            <label class="form-label">{{ __('Platform Group') }}</label>
                            <input type="text" name="platform_group" class="form-control" id="mt-group" readonly placeholder="Platform Group" required value="{{$account_type->platform_group}}" />
                        </div>
                    @endif

                    <div class="input-area">
                        <label class="form-label">{{ __('Title') }}</label>
                        <input type="text" name="title" class="form-control" placeholder="Account Title"
                            value="{{ $account_type->title }}" required />
                    </div>
                    <div class="input-area">
                        <label class="form-label">{{ __('Account Type Badge') }}</label>
                        <input type="text" name="badge" class="form-control" placeholder="Account Type Badge"
                            value="{{ $account_type->badge }}" required />
                    </div>
                    <div class="input-area">
                        <label class="form-label">{{ __('Priority') }}</label>
                        <input type="number" name="priority" oninput="this.value = validateDouble(this.value)"
                            class="form-control" placeholder="Priority e.g 1,2,3.." value="{{ $account_type->priority }}"
                            required />
                    </div>
                    <div class="input-area">
                        <label class="form-label">{{ __('Account Creation Limit') }}</label>
                        <input type="number" name="accounts_limit" min="1" max="50" oninput="this.value = validateDouble(this.value)"
                            class="form-control" placeholder="Account Limit"
                            value="{{ $account_type->accounts_limit }}" />
                    </div>

                    @if (setting('is_group_range'))
                        <div class="input-area">
                            <label class="form-label">{{ __('Range Start (Min 6 digits)') }}</label>
                            <input type="text" name="accounts_range_start" class="form-control" placeholder="Start Range" oninput="this.value = this.value.slice(0, 6); validateDouble(this.value);" min="100000" max="999999" value="{{ $account_type->accounts_range_start }}" />
                        </div>
                        <div class="input-area">
                            <label class="form-label">{{ __('Range End (Min 6 digits)') }}</label>
                            <input type="text" name="accounts_range_end" class="form-control" placeholder="End Range" oninput="this.value = this.value.slice(0, 6); validateDouble(this.value);" min="100000" max="999999" value="{{ $account_type->accounts_range_end }}" />
                        </div>
                    @endif


                    {{-- @if (!empty($account_type->trading_days))
                        <div class="input-area">
                            <label class="form-label" for="">{{ __('Minimum Trading Days') }}</label>
                            <input
                                type="number"
                                name="trading_days"
                                class="form-control"
                                placeholder="Minimum Trading Days"
                                value="{{ $account_type->trading_days }}"
                            />
                        </div>
                    @endif --}}
                    
                    
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Company Profit Share (%)') }}</label>
                        <input
                            type="number"
                            name="profit_share"
                            class="form-control"
                            placeholder="50"
                            value="{{ $account_type->profit_share }}"
                            min="1"
                            max="100"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label class="form-label">{{ __('Leverage') }}</label>
                        <input type="number" name="leverage" class="form-control" placeholder="leverage e.g 100"
                            value="{{ $account_type->leverage }}" {{ $account_type->trader_type == \App\Enums\TraderType::MT ? 'readonly' : '' }} required />
                    </div>

                    {{-- <div class="input-area">
                        <label class="form-label">{{ __('Spread') }}</label>
                        <input type="number" name="spread" class="form-control" placeholder="Account Type Spread"
                            value="{{ $account_type->spread }}" required />
                    </div>
                    <div class="input-area">
                        <label class="form-label">{{ __('Commission') }}</label>
                        <input type="number" name="commission" class="form-control"
                            placeholder="Account Type Commission" value="{{ $account_type->commission }}" required />
                    </div> --}}
                </div>
            </div>
        </div>

        {{-- Key Features (Upto) --}}
        {{-- <div class="card mb-6">
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
                        <label class="form-label">{{ __('Allotted Fund') }}</label>
                        <input type="number" name="upto_allotted_fund" class="form-control" placeholder="10000"
                            value="{{ $account_type->upto_allotted_fund }}" required />
                    </div>
                    <div class="input-area">
                        <label class="form-label">{{ __('Profit Target') }}</label>
                        <input type="number" name="upto_profit_target" class="form-control" placeholder="200"
                            value="{{ $account_type->upto_profit_target }}" required />
                    </div>
                    <div class="input-area">
                        <label class="form-label">{{ __('Daily Max Loss') }}</label>
                        <input type="number" name="upto_daily_max_loss" class="form-control" placeholder="100"
                            value="{{ $account_type->upto_daily_max_loss }}" required />
                    </div>
                    <div class="input-area">
                        <label class="form-label">{{ __('Maximum Loss') }}</label>
                        <input type="number" name="upto_maximum_loss" class="form-control" placeholder="500"
                            value="{{ $account_type->upto_maximum_loss }}" />
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Phases / Rules -->
        @include('backend.account_types.include.__phases')
        @include('backend.account_types.modal.__control_room')

        <!-- More Details -->
        <div class="card">
            <div class="card-header noborder">
                <div>
                    <h4 class="card-title">
                        {{ __('More Details') }}
                    </h4>
                </div>
            </div>
            <div class="card-body pt-0 p-6">
                <div class="input-area mb-5">
                    <label for="desc" class="form-label">{{ __('Description') }}</label>
                    <textarea class="summernote" name="description">{{ $account_type->description }}</textarea>
                </div>

                <div class="input-area">
                    <label class="form-label">{{ __('Trading Platform Title Format') }}</label>
                    <label class="form-label" style="color: #777; cursor: default">Use placeholders for a dynamic account title: 
                        <span style="text-transform: none">
                            <span class="title-placeholder">[account_title]</span>
                            <span class="title-placeholder">[allotted_funds]</span>
                            <span class="title-placeholder">[user_first_name]</span>
                            <span class="title-placeholder">[user_last_name]</span>
                            <span class="title-placeholder">[phase_step]</span>
                            <span class="title-placeholder">[profit_target]</span>
                            <span class="title-placeholder">[daily_drawdown_limit]</span>
                            <span class="title-placeholder">[max_drawdown_limit]</span>
                        </span> 
                    </label>
                    <input type="text" class="form-control" name="trading_platform_title_format" id="title_format" value="{{ $account_type->trading_platform_title_format }}">
                </div>
                   
                <br>
                <div class="grid grid-cols-3 gap-5 items-center ">
                    <div class="">
                        <label for="" class="form-label">{{ __('Status') }}</label>
                        <div class="input-area">
                            <select name="status" class="form-control w-full" data-placeholder="Status">
                                <option value="1" {{ $account_type->status == 1 ? 'selected' : '' }}>
                                    {{ __('Active') }}</option>
                                <option value="0" {{ $account_type->status == 0 ? 'selected' : '' }}>
                                    {{ __('Deactivate') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="">
                        <label for="" class="form-label">{{ __('CTA Button') }}</label>
                        <div class="input-area">
                            <input type="text" name="cta_button_text" value="{{ $account_type->cta_button_text }}" class="form-control">
                        </div>
                    </div>

                    <div class="" style="position: relative; top: 14px">
                        <div class="input-area">
                            <div class="flex items-center space-x-3 flex-wrap">
                                <div class="form-switch ps-0" style="line-height:0;">
                                    <input type="hidden" name="is_trial" value="0">
                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                        <input type="checkbox" name="is_trial" value="1" class="sr-only peer" {{ $account_type->is_trial ? 'checked' : '' }}>
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                                <label class="form-label !w-auto pt-0 !mb-0">Allow Trial (Auto Expire after {{ setting('auto_expire_expiry_days') }} days)</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Button -->
        <div class="mt-10 flex items-center gap-3">
            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                {{ __('Update') }}
            </button>
            {{-- <button type="button" class="btn btn-outline-secondary">{{ __('Cancel') }}</button> --}}
        </div>
    </form>

    <div id="notification-container" class="fixed top-0 right-0 mt-4 mr-4 space-y-2 z-50"></div>

@endsection
@section('script')
    <script>
        $('.title-placeholder').on('click', function() {
            $('#title_format').val($('#title_format').val() + $(this).text())
            $('#title_format').focus()
        })
    </script>
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

        $(document).on('change', '.mt_offer', function() {
            var selectedOption = $(this).find('option:selected');
            var groupName = selectedOption.data('group');
            var leverage = selectedOption.data('leverage');
            var systemUuid = selectedOption.data('system-uuid');
            $('#mt-group').val(groupName)
            $('#mt-system-uuid').val(systemUuid)
            $('#leverage').val(leverage)
        });
        

    </script>
@endsection
