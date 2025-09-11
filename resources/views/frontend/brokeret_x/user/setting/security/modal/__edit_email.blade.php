<div
    x-show="$store.modals.isOpen('emailEdit')"
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
                    {{ __('Update Email') }}
                </h4>
                <p class="block text-gray-500 dark:text-gray-400">
                    {{ __('Enter the email you want to link to your account.') }}
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
            <form action="{{ route('user.setting.info-update') }}" method="post" x-data="{ processing: false }" @submit="processing = true">
                @csrf
                <div class="input-area my-6">
                    <x-frontend::forms.input
                        type="email"
                        name="email"
                        value="{{ $user->email }}"
                        x-bind:value="$store.modals.data?.email || '{{ $user->email }}'"
                        placeholder="{{ __('Enter your email') }}"
                        required
                    />
                </div>
                <div class="flex items-center justify-end gap-3">
                    <x-frontend::forms.button type="submit" x-bind:disabled="processing" variant="primary" size="md" icon="send" iconPosition="left">
                        <span x-text="processing ? '{{ __('Processing...') }}' : '{{ __('Submit') }}'"></span>
                    </x-frontend::forms.button>
                    <x-frontend::forms.button type="button" @click="$store.modals.close()" variant="outline" size="md" icon="x" iconPosition="left">
                        {{ __('Cancel') }}
                    </x-frontend::forms.button>
                </div>
            </form>
        </div>
    </div>
</div>