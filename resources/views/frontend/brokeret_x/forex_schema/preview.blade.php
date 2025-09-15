@extends('frontend::layouts.user')
@section('title')
    {{ __('Set up your account') }}
@endsection
@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('user.schema') }}" class="size-10 relative inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded border border-transparent text-gray-800 hover:bg-gray-100 hover:border-gray-600 focus:outline-hidden focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                <i data-lucide="arrow-left" class="shrink-0 size-5"></i>
                <span class="sr-only">{{ __('Back to accounts') }}</span>
            </a>
            <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90">
                @yield('title')
            </h2>
        </div>
    </div>
    
    <div class="grid grid-cols-12 gap-12 mb-5" x-data="accountForm()" x-init="init()">
        <div class="col-span-12 lg:col-span-7">
            <div class="lg:max-w-xl">
                <div class="mb-5">
                    <div class="flex items-center gap-0.5 rounded-lg border border-gray-200 dark:border-gray-800 p-0.5 mb-2" id="account-type-tabs">
                        @if($schema->real_swap_free || $schema->real_islamic)
                            <button type="button"
                                :class="accountType === 'real' ? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' : 'text-gray-500 dark:text-gray-400'"
                                @click="setAccountType('real')"
                                class="flex-1 px-4 py-2.5 text-theme-sm rounded-lg gap-2 h-10 hover:text-gray-900 dark:hover:text-white text-gray-500 dark:text-gray-400"
                                data-type="real" id="real-tab">
                                {{ __('Real') }}
                            </button>
                        @endif

                        @if($schema->demo_swap_free || $schema->demo_islamic)
                            <button type="button"
                                :class="accountType === 'demo' ? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' : 'text-gray-500 dark:text-gray-400'"
                                @click="setAccountType('demo')"
                                class="flex-1 px-4 py-2.5 text-theme-sm rounded-lg gap-2 h-10 hover:text-gray-900 dark:hover:text-white text-gray-500 dark:text-gray-400"
                                data-type="demo" id="demo-tab">
                                {{ __('Demo') }}
                            </button>
                        @endif
                    </div>
                    <p class="text-gray-500 text-theme-sm dark:text-gray-400" x-text="description"></p>
                </div>

                <form action="{{route('user.forex-account-create-now')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="account_type" id="account-type" value="real">
                    <div class="space-y-5">
                        <div>
                            <x-frontend::forms.label
                                fieldId="account-type"
                                fieldLabel="{{ __('Account Type:') }}"
                                fieldRequired="true"
                            />
                            <input type="hidden" value="{{the_hash($schema->id)}}"
                                aria-label="{{ __('Nickname') }}" name="schema_id" id="select-schema" aria-describedby="basic-addon1"
                                data-is_real_islamic="{{$schema->is_real_islamic}}"
                                data-is_demo_islamic="{{$schema->is_demo_islamic}}"
                                data-first-min-deposit="{{$schema->first_min_deposit}}"
                                data-leverage="{{$schema->leverage}}" required readonly>

                            <x-frontend::forms.input
                                type="text"
                                fieldValue="{{$schema->title}}"
                                fieldRequired="true"
                                fieldReadOnly="true"
                                disabled="true"
                            />
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
                                    <div class="block h-6 w-11 rounded-full transition-colors duration-300" :class="islamicSelected ? 'bg-brand-500' : 'bg-gray-200 dark:bg-white/10'"></div>
                                    <div class="shadow-theme-sm absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white transition-transform duration-300 ease-linear" :class="islamicSelected ? 'translate-x-full' : 'translate-x-0'"></div>
                                </div>
                                {{ __('Swap-Free Account') }}
                            </label>
                        </div>

                        <x-frontend::forms.select-field
                            x-model="selectedLeverage"
                            fieldId="select-leverage"
                            fieldName="leverage"
                            fieldLabel="{{ __('Select Leverage') }}"
                            fieldValue="{{explode(',', $schema->leverage)[0]}}"
                            fieldRequired="true">
                            @foreach(explode(',', $schema->leverage) as $leverage)
                                <option value="{{$leverage}}">{{$leverage}}</option>
                            @endforeach
                        </x-frontend::forms.select-field>
                        
                        <x-frontend::forms.field
                            x-model="accountNickname"
                            fieldId="enter-nickname"
                            fieldName="account_name"
                            fieldLabel="{{ __('Account Nickname') }}"
                            fieldValue=""
                            fieldRequired="true"
                        />

                        <div>
                            <x-frontend::forms.password-field
                                x-model="password"
                                @input="checkPasswordRules()"
                                fieldId="enter-main-password"
                                fieldName="main_password"
                                fieldLabel="{{ __('Main Password') }}"
                                fieldRequired="true"
                            />

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
                    </div>
                    <div class="flex gap-3 mt-10">
                        <x-frontend::forms.button type="submit" class="w-full" size="md" variant="primary" x-bind:disabled="!isFormValid">
                            {{ __('Create Account') }}
                        </x-frontend::forms.button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-5 hidden lg:block">
            <div class="border-l border-gray-200 dark:border-gray-800 ps-5">
                <h3 class="text-lg font-medium text-gray-800 dark:text-white/90 mb-3">
                    {{ __('Standard') }}
                </h3>
                <ul class="space-y-3.5">
                    <li class="flex flex-col gap-1">
                        <span class="text-sm text-gray-500 sm:w-1/3 dark:text-gray-400">{{ __('Spread from') }}</span>
                        <span id="display-spread" class="text-sm text-gray-700 sm:w-2/3 dark:text-gray-400">
                            {{ $schema->spread }}
                        </span>
                    </li>
                    <li class="flex flex-col gap-1">
                        <span class="text-sm text-gray-500 sm:w-1/3 dark:text-gray-400">{{ __('Commission') }}</span>
                        <span id="display-commission" class="text-sm text-gray-700 sm:w-2/3 dark:text-gray-400">
                            {{ $schema->commission == 0 ? __('No Commission') : $schema->commission }}
                        </span>
                    </li>
                    <li class="flex flex-col gap-1">
                        <span class="text-sm text-gray-500 sm:w-1/3 dark:text-gray-400">{{ __('Leverage') }}</span>
                        <span id="display-leverage" class="text-sm text-gray-700 sm:w-2/3 dark:text-gray-400" x-text="selectedLeverage">
                        </span>
                    </li>
                    <li class="flex flex-col gap-1">
                        <span class="text-sm text-gray-500 sm:w-1/3 dark:text-gray-400">{{ __('Initial Deposit Limit') }}</span>
                        <span id="initial-deposit" class="text-sm text-gray-700 sm:w-2/3 dark:text-gray-400">
                            {{ $schema->first_min_deposit }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
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
