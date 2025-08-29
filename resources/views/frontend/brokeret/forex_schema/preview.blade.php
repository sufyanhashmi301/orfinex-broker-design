@extends('frontend::layouts.user')
@section('title')
    {{ __('Schema Preview') }}
@endsection
@section('content')
<div x-data="accountForm()" x-init="init()">
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6 mb-5 space-y-3">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            {{ __('Choose Account Type') }}
        </h3>
        <ul class="flex items-center flex-wrap list-none pl-0 space-x-4" id="account-type-tabs">
            @if($schema->real_swap_free || $schema->real_islamic)
                <li class="nav-item">
                    <a href="javascript:;"
                        :class="accountType === 'real' 
                            ? 'bg-brand-500 text-white ring-1 ring-brand-300' 
                            : 'bg-white text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]'"
                        @click="setAccountType('real')"
                        class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium shadow-theme-xs transition"
                        data-type="real" id="real-tab">
                        {{ __('Real') }}
                    </a>
                </li>
            @endif

            @if($schema->demo_swap_free || $schema->demo_islamic)
                <li class="nav-item">
                    <a href="javascript:;"
                        :class="accountType === 'demo' 
                            ? 'bg-brand-500 text-white ring-1 ring-brand-300' 
                            : 'bg-white text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]'"
                        @click="setAccountType('demo')"
                        class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium shadow-theme-xs transition"
                        data-type="demo" id="demo-tab">
                        {{ __('Demo') }}
                    </a>
                </li>
            @endif
        </ul>

        <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="description"></p>
    </div>
    <div class="grid grid-cols-12 gap-5 mb-5">
        <div class="col-span-12 sm:col-span-6 xl:col-span-7 h-full">
            <h4 class="text-xl font-semibold text-gray-800 dark:text-white/90 mb-3">
                {{ __('Account Options') }}
            </h4>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6 h-auto">
                <form class="space-y-5" action="{{route('user.forex-account-create-now')}}" method="post"
                        enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="account_type" id="account-type" value="real">
                    <div class="input-area">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400" for="">
                            {{ __('Account Type:') }}
                        </label>
                        <input type="hidden" value="{{the_hash($schema->id)}}"
                                aria-label="{{ __('Nickname') }}" name="schema_id" id="select-schema" aria-describedby="basic-addon1"
                                data-is_real_islamic="{{$schema->is_real_islamic}}"
                                data-is_demo_islamic="{{$schema->is_demo_islamic}}"
                                data-first-min-deposit="{{$schema->first_min_deposit}}"
                                data-leverage="{{$schema->leverage}}" required readonly>
                        <input type="text" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" value="{{$schema->title}}"
                                aria-label="{{ __('Nickname') }}" aria-describedby="basic-addon1" required readonly>
                    </div>
                    <div class="input-area" x-show="isIslamic">
                        <label class="flex cursor-pointer items-center gap-3 text-sm font-medium text-gray-700 select-none dark:text-gray-400">
                            <div class="relative">
                                <input type="checkbox" 
                                        x-model="islamicSelected" 
                                        name="is_islamic" 
                                        value="1" 
                                        class="sr-only" 
                                        id="islamic-checkbox">
                                <div class="block h-6 w-11 rounded-full transition-colors duration-300" 
                                        :class="islamicSelected ? 'bg-brand-500' : 'bg-gray-200 dark:bg-white/10'"></div>
                                <div class="shadow-theme-sm absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white transition-transform duration-300 ease-linear" 
                                        :class="islamicSelected ? 'translate-x-full' : 'translate-x-0'"></div>
                            </div>
                            {{ __('Swap-Free Account') }}
                        </label>
                    </div>
                    <div class="input-area">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400" for="select-leverage">
                            {{ __('Select Leverage:') }}
                        </label>
                        <select x-model="selectedLeverage"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            id="select-leverage" name="leverage" required>
                            @foreach(explode(',', $schema->leverage) as $leverage)
                                <option value="{{$leverage}}">{{$leverage}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-area">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400" for="enter-nickname">
                            {{ __('Account Nickname:') }}
                        </label>
                        <input type="text" 
                            x-model="accountNickname"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            placeholder="{{ __('Enter Nickname') }}" aria-label="{{ __('Nickname') }}"
                            name="account_name" id="enter-nickname" aria-describedby="basic-addon1" required>
                    </div>
                    <div class="input-area">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400" for="enter-main-password">
                            {{ __('Main Password:') }}
                        </label>
                        <input type="password" 
                            x-model="password"
                            @input="checkPasswordRules()"
                            :class="{ 'border-error-500': !passwordChecks.length || !passwordChecks.upperLower || !passwordChecks.number || !passwordChecks.special }"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            placeholder="{{ __('Enter Main Password') }}" aria-label="{{ __('Main Password') }}"
                            name="main_password" id="enter-main-password" aria-describedby="basic-addon1"
                            required>
                        <ul class="mt-2 space-y-1">
                            <li class="text-xs" :class="passwordChecks.length ? 'text-success-600' : 'text-error-500'">
                                {{ __('Use from 8 to 20 characters') }}
                            </li>
                            <li class="text-xs" :class="passwordChecks.upperLower ? 'text-success-600' : 'text-error-500'">
                                {{ __('Use both uppercase and lowercase letters') }}
                            </li>
                            <li class="text-xs" :class="passwordChecks.number ? 'text-success-600' : 'text-error-500'">
                                {{ __('At least one number') }}
                            </li>
                            <li class="text-xs" :class="passwordChecks.special ? 'text-success-600' : 'text-error-500'">
                                {{ __('At least one special character(!@#$%&*():{}|<>)') }}
                            </li>
                        </ul>
                    </div>
                    <div class="flex gap-3 mt-4">
                        <x-forms.button type="submit" size="md" variant="primary" icon="plus" icon-position="left"
                            x-bind:disabled="!isFormValid"
                            x-bind:class="{ 'opacity-50 cursor-not-allowed': !isFormValid, 'hover:bg-brand-600': isFormValid }"
                            id="create-forex-account">
                            {{ __('Create Account') }}
                        </x-forms.button>
                        <x-link-button href="{{route('user.schema')}}" size="md" variant="secondary" icon="x" icon-position="left">
                            {{ __('Cancel') }}
                        </x-link-button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-span-12 sm:col-span-6 xl:col-span-5 h-full">
            <h4 class="text-xl font-semibold text-gray-800 dark:text-white/90 mb-3">
                {{ __('Account Specifications and Features') }}
            </h4>
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6 h-auto">
                <div class="mb-6 flex items-center justify-between">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        {{ __('Standard') }}
                    </h3>
                </div>
                <ul class="space-y-3.5">
                    <li class="flex items-center gap-2.5">
                        <span class="text-sm text-gray-500 sm:w-1/3 dark:text-gray-400">{{ __('Spread from') }}</span>
                        <span id="display-spread" class="text-sm font-medium text-gray-700 sm:w-2/3 dark:text-gray-400">
                            {{ $schema->spread }}
                        </span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="text-sm text-gray-500 sm:w-1/3 dark:text-gray-400">{{ __('Commission') }}</span>
                        <span id="display-commission" class="text-sm font-medium text-gray-700 sm:w-2/3 dark:text-gray-400">
                            {{ $schema->commission == 0 ? __('No Commission') : $schema->commission }}
                        </span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="text-sm text-gray-500 sm:w-1/3 dark:text-gray-400">{{ __('Leverage') }}</span>
                        <span id="display-leverage" class="text-sm font-medium text-gray-700 sm:w-2/3 dark:text-gray-400" x-text="selectedLeverage">
                        </span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <span class="text-sm text-gray-500 sm:w-1/3 dark:text-gray-400">{{ __('Initial Deposit Limit') }}</span>
                        <span id="initial-deposit" class="text-sm font-medium text-gray-700 sm:w-2/3 dark:text-gray-400">
                            {{ $schema->first_min_deposit }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <x-alert type="info" class="mb-5">
        {{ __('Comprehensive details on our instruments and trading conditions can be found on the') }} 
        <x-text-link href="" variant="warning">
            {{ __('Customer Contract') }}
        </x-text-link> {{ __('page.') }}
    </x-alert>
</div>
@endsection
@section('script')
    <script>
        function accountForm() {
            return {
                accountType: '{{ $schema->real_swap_free || $schema->real_islamic ? "real" : "demo" }}',
                schema: {
                    real_swap_free: @json($schema->real_swap_free),
                    real_islamic: @json($schema->real_islamic),
                    demo_swap_free: @json($schema->demo_swap_free),
                    demo_islamic: @json($schema->demo_islamic),
                    commission: @json($schema->commission),
                    spread: @json($schema->spread),
                    leverage: @json(explode(',', $schema->leverage)),
                    first_min_deposit: @json($schema->first_min_deposit),
                    is_real_islamic: @json($schema->is_real_islamic),
                    is_demo_islamic: @json($schema->is_demo_islamic),
                },
                selectedLeverage: '{{ explode(",", $schema->leverage)[0] }}',
                isIslamic: false,
                islamicSelected: false,
                description: '',
                password: '',
                accountNickname: '',
                passwordChecks: {
                    length: false,
                    upperLower: false,
                    number: false,
                    special: false,
                },
                get isFormValid() {
                    return this.passwordChecks.length && 
                           this.passwordChecks.upperLower && 
                           this.passwordChecks.number && 
                           this.passwordChecks.special &&
                           this.accountNickname.trim().length > 0;
                },
                init() {
                    this.setDescription();
                    this.setIslamicCheckbox();
                    // Set initial account type in hidden field
                    document.getElementById('account-type').value = this.accountType;
                },
                setAccountType(type) {
                    this.accountType = type;
                    document.getElementById('account-type').value = type;
                    this.setDescription();
                    this.setIslamicCheckbox();
                },
                setDescription() {
                    this.description = this.accountType === 'demo'
                        ? '{{ __("Risk-Free Account: Trade using virtual money") }}'
                        : '{{ __("Live Account: Trade with real money and real profits") }}';
                },
                setIslamicCheckbox() {
                    // Check if Islamic option is available for current account type
                    const islamicAvailable = this.accountType === 'real'
                        ? this.schema.is_real_islamic
                        : this.schema.is_demo_islamic;
                    
                    // Show/hide the Islamic option section
                    this.isIslamic = islamicAvailable;
                    
                    // Reset Islamic selection when switching account type
                    this.islamicSelected = false;
                },
                checkPasswordRules() {
                    this.passwordChecks.length = this.password.length >= 8 && this.password.length <= 20;
                    this.passwordChecks.upperLower = /[a-z]/.test(this.password) && /[A-Z]/.test(this.password);
                    this.passwordChecks.number = /[0-9]/.test(this.password);
                    this.passwordChecks.special = /[!@#$%&*():{}|<>]/.test(this.password);
                }
            }
        }

    </script>
@endsection
