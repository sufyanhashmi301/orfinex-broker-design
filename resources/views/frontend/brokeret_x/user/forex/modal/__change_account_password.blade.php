<div
    x-show="$store.modals.isOpen('changeAccountPass')"
    x-transition
    @click.outside="$store.modals.close()"
    class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto modal z-99999"
    style="display: none;">
    <!-- Overlay -->
    <div @click="$store.modals.close()" class="fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[20px]"></div>

    <!-- Modal Content -->
    <div @click.away="$store.modals.close()" class="relative w-full max-w-[600px] rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h4 class="font-semibold text-gray-800 text-title-xs dark:text-white/90">
                    {{ __('Change Trading Password') }}
                </h4>
                <p class="block text-gray-500 dark:text-gray-400">
                    {{ __('Account:') }} <span x-text="$store.modals.data.login"></span>
                </p>
            </div>
            <!-- Close Button -->
            <button @click="$store.modals.close()" class="flex h-9.5 w-9.5 items-center justify-center rounded-full bg-gray-100 text-gray-400 transition-colors hover:bg-gray-200 hover:text-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white sm:right-6 sm:top-6 sm:h-11 sm:w-11">
                <!-- SVG Icon -->
                <svg class="fill-current" width="16" height="16" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.04289 16.5418C5.65237 16.9323 5.65237 17.5655 6.04289 17.956C6.43342 18.3465 7.06658 18.3465 7.45711 17.956L11.9987 13.4144L16.5408 17.9565C16.9313 18.347 17.5645 18.347 17.955 17.9565C18.3455 17.566 18.3455 16.9328 17.955 16.5423L13.4129 12.0002L17.955 7.45808C18.3455 7.06756 18.3455 6.43439 17.955 6.04387C17.5645 5.65335 16.9313 5.65335 16.5408 6.04387L11.9987 10.586L7.45711 6.04439C7.06658 5.65386 6.43342 5.65386 6.04289 6.04439C5.65237 6.43491 5.65237 7.06808 6.04289 7.4586L10.5845 12.0002L6.04289 16.5418Z" />
                </svg>
            </button>
        </div>

        <!-- Modal Content -->
        <div>
            <form @submit.prevent="$store.modals.updateMainPassword($refs)">
                @csrf
                <input type="hidden" x-ref="login" :value="$store.modals.data.login">
                <x-frontend::forms.label
                    fieldId="main_password"
                    fieldLabel="{{ __('Main Password') }}"
                    fieldRequired="true"
                />
                <div class="relative"
                    x-data="{
                        password: $store.modals.data.main_password || '',
                        rules: {
                            length: false,
                            upper: false,
                            lower: false,
                            number: false,
                            special: false
                        },
                        validate() {
                            this.rules.length = this.password.length >= 8 && this.password.length <= 20
                            this.rules.upper = /[A-Z]/.test(this.password)
                            this.rules.lower = /[a-z]/.test(this.password)
                            this.rules.number = /\d/.test(this.password)
                            this.rules.special = /[!@#$%&*():{}|<>]/.test(this.password)
                            $store.modals.data.main_password = this.password
                        },
                        init() {
                            this.validate()
                            this.$watch('password', () => this.validate())
                        }
                    }">
                    <div>
                        <x-frontend::forms.input
                            x-ref="main_password"
                            x-model="password"
                            @input="validate"
                            type="password"
                            fieldId="main_password"
                            fieldName="main_password"
                            fieldPlaceholder="{{ __('Enter Main Password') }}"
                            fieldValue="$store.modals.data.main_password"
                            fieldRequired="true"
                        />
                        <ul class="mt-1">
                            <li class="text-xs mb-1" :class="rules.length ? 'text-success-500' : 'text-error-500'">
                                {{ __('Use from 8 to 20 characters') }}
                            </li>
                            <li class="text-xs mb-1" :class="rules.upper && rules.lower ? 'text-success-500' : 'text-error-500'">
                                {{ __('Use both uppercase and lowercase letters') }}
                            </li>
                            <li class="text-xs mb-1" :class="rules.number ? 'text-success-500' : 'text-error-500'">
                                {{ __('At least one number') }}
                            </li>
                            <li class="text-xs mb-1" :class="rules.special ? 'text-success-500' : 'text-error-500'">
                                {{ __('At least one special character(!@#$%&*():{}|<>)') }}
                            </li>
                        </ul>
                    </div>
                    <div class="space-x-2 mt-4">
                        <x-frontend::forms.button
                            type="submit"
                            x-ref="submitBtn"
                            variant="primary"
                            size="md"
                            icon="check"
                            icon-position="left"
                            x-bind:disabled="!rules.length || !rules.upper || !rules.lower || !rules.number || !rules.special"
                            x-bind:class="(rules.length && rules.upper && rules.lower && rules.number && rules.special) 
                                ? 'bg-brand-500 hover:bg-brand-600' 
                                : 'bg-gray-400 cursor-not-allowed'"
                        >
                            {{ __('Change Password') }}
                        </x-frontend::forms.button>
                        <x-frontend::forms.button
                            type="button"
                            @click="$store.modals.close()"
                            variant="outline"
                            size="md"
                            icon="x"
                            icon-position="left"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        >
                            {{ __('Close') }}
                        </x-frontend::forms.button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> 