<form @submit.prevent="$store.modals.updateLeverage($refs)">
    @csrf
    <input type="hidden" x-ref="login" :value="$store.modals.data.login">
    <div class="relative">
        <x-frontend::forms.label
            fieldId="leverage"
            fieldLabel="{{ __('Leverage') }}"
            fieldRequired="true"
        />
        <x-frontend::forms.select
            x-ref="leverage"
            :options="explode(',', $forexTrading->schema->leverage)"
            :selected="$forexTrading->leverage"
            fieldId="leverage"
            fieldName="leverage"
            fieldRequired="true"
        />
        <small class="text-gray-500 dark:text-gray-400 mt-1">
            {{ __('Choose the leverage ratio you want to adjust for this account.') }}
        </small>
    </div>
    <div class="space-x-2 mt-4">
        <x-frontend::forms.button type="submit" x-ref="submitBtn" variant="primary" size="md" icon="check" icon-position="left" id="submit-leverage">
            {{ __('Set Leverage') }}
        </x-frontend::forms.button>
        <x-frontend::forms.button type="button" @click="$store.modals.close()" variant="outline" size="md" icon="x" icon-position="left" data-bs-dismiss="modal" aria-label="Close">
            {{ __('Close') }}
        </x-frontend::forms.button>
    </div>
    <div class="border-b border-gray-100 dark:border-gray-800 my-5"></div>
    <div class="flex">
        <small class="text-gray-500 dark:text-gray-400 mb-0">
            {{ __('Disclaimer: The leverage you select is subject to market conditions and internal policies of '. setting('site_title', 'global') .'. Please be aware that leverage can increase both gains and losses. '. setting('site_title', 'global') .' will not be held responsible for any risks or financial losses incurred through leverage adjustments.') }}
        </small>
    </div>
</form>
