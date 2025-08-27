<div
    x-show="$store.modals.isOpen('changeInvestorPass')"
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
                    {{ __('Change Investor Password') }}
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
            <form @submit.prevent="$store.modals.updateInvestorPassword($refs)">
                @csrf
                <input type="hidden" x-ref="login" :value="$store.modals.data.login">

                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400" for="">
                    {{ __('Investor Password:') }}
                </label>
                <div class="relative"
                    x-data="{
                        password: $store.modals.data.investor_password || '',
                        rules: {
                            length: false,
                            upper: false,
                            lower: false,
                            number: false,
                            special: false,
                        },
                        validate() {
                            this.rules.length = this.password.length >= 8 && this.password.length <= 20
                            this.rules.upper = /[A-Z]/.test(this.password)
                            this.rules.lower = /[a-z]/.test(this.password)
                            this.rules.number = /\d/.test(this.password)
                            this.rules.special = /[!@#$%&*():{}|<>]/.test(this.password)
                            $store.modals.data.investor_password = this.password
                        },
                        init() {
                            this.validate()
                            this.$watch('password', () => this.validate())
                        }
                    }">
                    <div>
                        <input type="password" 
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" 
                            x-model="password"
                            x-ref="invest_password"
                            :value="$store.modals.data.invest_password"
                            @input="validate">
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
                    <div class="mt-4">
                        <button 
                            type="submit" 
                            :disabled="!rules.length || !rules.upper || !rules.lower || !rules.number || !rules.special"
                            :class="(rules.length && rules.upper && rules.lower && rules.number && rules.special) 
                                ? 'bg-brand-500 hover:bg-brand-600' 
                                : 'bg-gray-400 cursor-not-allowed'"
                            class="inline-flex items-center gap-2 rounded-lg px-5 py-3.5 text-sm font-medium text-white shadow-theme-xs transition mr-2" 
                            x-ref="submitBtn">
                            <i data-lucide="key" class="w-4 h-4"></i>
                            {{ __('Change Password') }}
                        </button>
                        <a href="#" @click="$store.modals.close()" class="inline-flex items-center gap-2 rounded-lg bg-white px-5 py-3.5 text-sm font-medium text-gray-700 shadow-theme-xs ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]" data-bs-dismiss="modal" aria-label="Close">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            {{ __('Close') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
