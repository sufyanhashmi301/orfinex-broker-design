@extends('frontend::layouts.partner')

@section('title')
    {{ __('User IB Rule Levels') }}
@endsection

@section('content')
<div
    x-data="ibRuleLevels({
        totalShare: {{ $userIbRule->rebateRule->rebate_amount }},
        updateUrl: '{{ route('user.ib.rule.level.update') }}',
        csrf: '{{ csrf_token() }}'
    })">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90">
            {{ __('IB Level Distribution') }}
        </h2>

        <div class="flex-1 flex flex-col sm:flex-row sm:justify-end sm:items-center gap-3">
            <x-frontend::forms.button type="button" @click="openModal()" variant="outline" icon="pencil" icon-position="left" class="w-fit">
                {{ __('Update IB Shares') }}
            </x-frontend::forms.button>
        </div>
    </div>
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="min-w-full">
                <thead class="border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="text-start px-6 py-3 whitespace-nowrap">
                            <span class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                {{ __('Level') }}
                            </span>
                        </th>
                        <th class="text-start px-6 py-3 whitespace-nowrap">
                            <span class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                {{ __('Total Share') }}
                            </span>
                        </th>
                        <th class="text-start px-6 py-3 whitespace-nowrap">
                            <span class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                {{ __('Master IB Share / Level 0') }}
                            </span>
                        </th>
                        @foreach($levels as $level)
                            <th class="text-start px-6 py-3 whitespace-nowrap">
                                <span class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                    {{ __('Level ' . $level->level_order) }}
                                </span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($levels as $level)
                        <tr>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <span class="block font-medium text-gray-700 text-theme-sm dark:text-gray-400">
                                    {{ __('Level ') }} {{ $level->level_order }}
                                </span>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <span class="text-gray-700 text-theme-sm dark:text-gray-400">
                                    ${{ $userIbRule->rebateRule->rebate_amount }}
                                </span>
                            </td>

                            @php
                                $userIbRuleLevel = $userIbRuleLevels->where('level_id', $level->id)->first();
                                $shares = $userIbRuleLevel ? $userIbRuleLevel->userIbRuleLevelShares : collect([]);
                                $sumShares = $shares->isNotEmpty() ? $shares->sum('share') : 0;
                                $totalRebate = $userIbRule->rebateRule->rebate_amount;

                                $masterIbShare = $totalRebate - $sumShares;
                            @endphp
                            <td class="px-6 py-3 whitespace-nowrap">
                                <span class="text-gray-700 text-theme-sm dark:text-gray-400">
                                    ${{ number_format($masterIbShare, 2) }}
                                </span>
                            </td>

                            @foreach($levels as $l)
                                @php
                                    $levelShare = $userIbRuleLevels->where('level_id', $level->level_order)->first();
                                @endphp
                                <td class="px-6 py-3 whitespace-nowrap">
                                    @if($levelShare && $l->id <= $level->id)
                                        @foreach($levelShare->userIbRuleLevelShares->where('level_id', $l->id) as $share)
                                            <p class="text-gray-700 text-theme-sm dark:text-gray-400">${{ $share->share }}</p>
                                        @endforeach
                                    @else
                                        <p class="text-gray-700 text-theme-sm dark:text-gray-400">--</p>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>


    <!-- Update Shares Modal -->
    <div
        x-show="showModal"
        x-transition
        @click.outside="closeModal()"
        class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto modal z-99999"
        style="display: none;">
        <!-- Overlay -->
        <div @click="closeModal()" class="fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[20px]"></div>

        <!-- Modal Content -->
        <div @click.away="closeModal()" class="relative w-full max-w-3xl rounded-3xl bg-white dark:bg-gray-900">
            <div class="flex items-center justify-between p-6 lg:p-10">
                <h4 class="font-semibold text-gray-800 text-title-xs dark:text-white/90">
                    {{ __('Update IB Level Shares') }}
                </h4>
                <!-- Close Button -->
                <button @click="closeModal()" class="flex h-9.5 w-9.5 items-center justify-center rounded-full bg-gray-100 text-gray-400 transition-colors hover:bg-gray-200 hover:text-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white sm:right-6 sm:top-6 sm:h-11 sm:w-11">
                    <!-- SVG Icon -->
                    <svg class="fill-current" width="16" height="16" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.04289 16.5418C5.65237 16.9323 5.65237 17.5655 6.04289 17.956C6.43342 18.3465 7.06658 18.3465 7.45711 17.956L11.9987 13.4144L16.5408 17.9565C16.9313 18.347 17.5645 18.347 17.955 17.9565C18.3455 17.566 18.3455 16.9328 17.955 16.5423L13.4129 12.0002L17.955 7.45808C18.3455 7.06756 18.3455 6.43439 17.955 6.04387C17.5645 5.65335 16.9313 5.65335 16.5408 6.04387L11.9987 10.586L7.45711 6.04439C7.06658 5.65386 6.43342 5.65386 6.04289 6.04439C5.65237 6.43491 5.65237 7.06808 6.04289 7.4586L10.5845 12.0002L6.04289 16.5418Z" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="">
                <form id="updateSharesForm">
                    <div class="max-h-[70vh] overflow-y-auto space-y-4 px-6 lg:px-10">
                        @csrf
                        <input type="hidden" name="user_ib_rule_id" value="{{ $userIbRule->id }}">

                        <!-- Total Share -->
                         <x-frontend::forms.field
                            fieldLabel="{{ __('Total Share') }}"
                            type="text"
                            value="{{ $userIbRule->rebateRule->rebate_amount }}"
                            disabled
                         />

                        <!-- Dynamic Levels Input Fields -->
                        <div id="levelsContainer" class="space-y-4">
                            @foreach($levels as $level)
                                <div class="input-area">
                                    <x-frontend::forms.label fieldLabel="{{ __('Level ' . $level->level_order . ' Shares') }}" />
                                    <div class="grid md:grid-cols-5 grid-cols-1 gap-2">
                                        @for($i = 1; $i <= $level->level_order; $i++)
                                            @php
                                                // Retrieve individual share record instead of sum
                                                $userIbRuleLevel = $userIbRuleLevels->where('level_id', $level->id)->first();
                                                $shareCollection = optional($userIbRuleLevel)->userIbRuleLevelShares ?? collect([]);
                                                $shareRecord = $shareCollection->where('level_id', $i)->first();
                                                $individualShare = $shareRecord ? $shareRecord->share : '';
                                            @endphp
                                            <div>
                                                <x-frontend::forms.input
                                                    type="number"
                                                    name="shares[{{ $level->id }}][{{ $i }}]"
                                                    data-level="{{ $i }}"
                                                    value="{{ $individualShare }}"
                                                    min="0" step="0.01"
                                                    placeholder="Level {{ $i }}">
                                                </x-frontend::forms.input>
                                                <!-- Error message placeholder -->
                                                <span class="text-red-500 text-sm error-message hidden"></span>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="text-right space-x-2 p-6 lg:p-10">
                        <x-frontend::forms.button type="submit" @click="submitForm()" variant="primary" icon="check" icon-position="left">
                            {{ __('Save Changes') }}
                        </x-frontend::forms.button>
                        <x-frontend::forms.button type="button" @click="closeModal()" variant="outline" icon="x" icon-position="left">
                            {{ __('Close') }}
                        </x-frontend::forms.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        function ibRuleLevels({ totalShare, updateUrl, csrf }) {
            return {
                showModal: false,
                totalShare,
                updateUrl,
                csrf,
                shares: {},
                errors: {},

                openModal() { this.showModal = true },
                closeModal() { this.showModal = false },

                validate() {
                    this.errors = {};
                    let valid = true;

                    for (const [levelId, levels] of Object.entries(this.shares)) {
                        let sum = Object.values(levels).reduce((a, b) => a + (parseFloat(b) || 0), 0);
                        if (sum > this.totalShare) {
                            valid = false;
                            for (const i in levels) {
                                this.errors[`${levelId}_${i}`] = `Total share for this level cannot exceed ${this.totalShare}`;
                            }
                        }
                    }
                    return valid;
                },

                async submitForm() {
                    if (!this.validate()) return;

                    let formData = new FormData();
                    formData.append('_token', this.csrf);
                    formData.append('user_ib_rule_id', document.querySelector('[name="user_ib_rule_id"]').value);
                    for (const [levelId, levels] of Object.entries(this.shares)) {
                        for (const i in levels) {
                            formData.append(`shares[${levelId}][${i}]`, levels[i] || '');
                        }
                    }

                    try {
                        let res = await fetch(this.updateUrl, { method: 'POST', body: formData });
                        let data = await res.json();
                        if (data.success) {
                            this.closeModal();
                            notify().success(data.message);
                            window.location.reload();
                        } else {
                            this.closeModal();
                            notify().warning(data.message || 'An error occurred');
                        }
                    } catch (e) {
                        this.closeModal();
                        notify().warning('An error occurred. Please try again.');
                    }
                }
            }
        }
    </script>
@endsection
