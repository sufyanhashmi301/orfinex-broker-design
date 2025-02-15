@if($userIbRules->isEmpty())
    <p class="text-center text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-slate-900 text-sm rounded py-5">
        {{ __('No Data Found') }}
    </p>
@else
    <div class="grid grid-cols-12 gap-5">
        <div class="col-span-12">
            <div class="card">
                <div class="card-body px-6 pb-6">
                    <div class="overflow-x-auto -mx-6">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class=" border-t border-slate-100 dark:border-slate-800">
                            <tr>
{{--                                <th class="table-th">{{ __('Schema') }}</th>--}}
                                <th class="table-th">{{ __('Rebate Rule') }}</th>
                                <th class="table-th">{{ __('Symbols') }}</th>
                                <th class="table-th">{{ __('Total Rebate') }}</th>
                                <th class="table-th">{{ __('Master IB Share') }}</th>
{{--                                <th class="table-th">{{ __('Sub IB Share') }}</th>--}}
                                <th class="table-th">{{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($userIbRules as $userIbRule)
                                <tr>
                                    <!-- Schema -->
{{--                                    <td class="table-td">--}}
{{--                                        @foreach($userIbRule->rebateRule->ibGroups->flatMap->forexSchemas as $schema)--}}
{{--                                            <p>{{ $schema->title }}</p>--}}
{{--                                        @endforeach--}}
{{--                                    </td>--}}

                                    <!-- Rebate Rule -->
                                    <td class="table-td">
                                        <p>{{ $userIbRule->rebateRule->title }}</p>
                                    </td>
                                    <!-- Symbols -->
                                    <td class="table-td">
                                        @foreach($userIbRule->rebateRule->symbolGroups->flatMap->symbols as $symbol)
                                            <p>{{ $symbol->symbol }}</p>
                                        @endforeach
                                    </td>
                                    <!-- Rebate Amount -->
                                    <td class="table-td">
                                        <p>${{ $userIbRule->rebateRule->rebate_amount }}</p>
                                    </td>


                                    <td class="table-td">
                                        <p>${{$userIbRule->rebateRule->rebate_amount - $userIbRule->sub_ib_share }}</p>
                                    </td>
{{--                                    <!-- Sub IB Share -->--}}
{{--                                    <td class="table-td">--}}
{{--                                        <p>${{ $userIbRule->sub_ib_share }}</p>--}}
{{--                                    </td>--}}



                                    <!-- Action -->
                                    <td class="table-td">
                                        <a href="{{ route('user.ib.rule.levels', ['id' => $userIbRule->id]) }}" class="action-btn">
                                            <iconify-icon icon="heroicons:pencil"></iconify-icon>
                                        </a>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
