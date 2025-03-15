@extends('frontend::layouts.partner')

@section('title')
    {{ __('User IB Rule Levels') }}
@endsection

@section('content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-700">
            {{ __('IB Distribution Rules') }}
        </h4>
        <!-- Update Shares Button -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateSharesModal">
            {{ __('Update IB Shares') }}
        </button>
    </div>

    <div class="card">


        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700">
                    <thead>
                    <tr>
                        <th class="table-th">{{ __('Level') }}</th>
                        <th class="table-th">{{ __('Total Share') }}</th>
                        <th class="table-th">{{ __('Master IB Share / Level 0') }}</th>
                        @foreach($levels as $level)
                            <th class="table-th">{{ __('Level ' . $level->level_order) }}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($levels as $level)
                        <tr>
                            <td class="table-td">{{ $level->level_order }}</td>
                            <td class="table-td">${{ $userIbRule->rebateRule->rebate_amount }}</td>

                            @php
                                $userIbRuleLevel = $userIbRuleLevels->where('level_id', $level->id)->first();
                                $shares = $userIbRuleLevel ? $userIbRuleLevel->userIbRuleLevelShares : collect([]);
                                $sumShares = $shares->isNotEmpty() ? $shares->sum('share') : 0;
                                $totalRebate = $userIbRule->rebateRule->rebate_amount;

                                $masterIbShare = $totalRebate - $sumShares;
                            @endphp
                            <td class="table-td">${{ number_format($masterIbShare, 2) }}</td>

                            @foreach($levels as $l)
                                @php
                                    $levelShare = $userIbRuleLevels->where('level_id', $level->level_order)->first();
                                @endphp
                                <td class="table-td">
                                    @if($levelShare && $l->id <= $level->id)
                                        @foreach($levelShare->userIbRuleLevelShares->where('level_id', $l->id) as $share)
                                            <p>${{ $share->share }}</p>
                                        @endforeach
                                    @else
                                        <p>--</p>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>


    <!-- Update Shares Modal -->
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="updateSharesModal" tabindex="-1" aria-labelledby="updateSharesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
                <div class="relative bg-white rounded-lg shadow dark:bg-dark">
                    <div class="flex items-center justify-between rounded-t p-5">
                        <h3 class="text-xl font-medium dark:text-white capitalize">{{ __('Update IB Level Shares') }}</h3>
                        <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                            <svg aria-hidden="true" class="w-5 h-5 fill-black dark:fill-white" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">{{ __('Close modal') }}</span>
                        </button>
                    </div>

                    <div class="p-6 space-y-4">
                        <form id="updateSharesForm">
                            @csrf
                            <input type="hidden" name="user_ib_rule_id" value="{{ $userIbRule->id }}">

                            <!-- Total Share -->
                            <div class="mb-3">
                                <label class="form-label">{{ __('Total Share') }}</label>
                                <input type="text" class="form-control" value="{{ $userIbRule->rebateRule->rebate_amount }}" disabled>
                            </div>

                            <!-- Dynamic Levels Input Fields -->
                            <div id="levelsContainer" class="space-y-4">
                                @foreach($levels as $level)
                                    <div class="input-area">
                                        <label class="form-label">{{ __('Level ' . $level->level_order . ' Shares') }}</label>
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
                                                    <input type="number" class="form-control level-share"
                                                           name="shares[{{ $level->id }}][{{ $i }}]"
                                                           data-level="{{ $i }}"
                                                           value="{{ $individualShare }}"
                                                           min="0" step="0.01"
                                                           placeholder="Level {{ $i }}">
                                                    <!-- Error message placeholder -->
                                                    <span class="text-red-500 text-sm error-message hidden"></span>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="text-right mt-10">
                                <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                    {{ __('Save Changes') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('script')
    <script>
        $(document).ready(function () {
            let totalShare = {{ $userIbRule->rebateRule->rebate_amount }};

            $('#updateSharesForm').on('submit', function (e) {
                e.preventDefault();

                let formData = $(this).serialize();
                let isValid = true;

                // Clear previous error messages
                $('.error-message').text('').addClass('hidden');

                // Validate each level independently
                $('#levelsContainer .mb-3').each(function () {
                    let levelSum = 0;
                    let levelInputs = $(this).find('.level-share');

                    levelInputs.each(function () {
                        let value = parseFloat($(this).val()) || 0;
                        levelSum += value;
                    });

                    if (levelSum > totalShare) {
                        isValid = false;
                        $(this).find('.error-message').text('Total share for this level cannot exceed ' + totalShare).removeClass('hidden');
                    }
                });

                if (!isValid) return;

                // AJAX request to save data
                $.ajax({
                    url: "{{ route('user.ib.rule.level.update') }}",
                    method: "POST",
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function (xhr) {
                        alert("An error occurred. Please try again.");
                    }
                });
            });
        });
    </script>
@endsection
