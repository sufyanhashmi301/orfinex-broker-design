@if(!blank($pendingPlans = data_get($investments, 'pending', [])))
    <div class="card mb-3">
        <header class="card-header noborder">
            <h4 class="card-title">{{ __('Pending Challenge (:count)',['count'=> count($pendingPlans) ]) }}</h4>
        </header>
        <div class="card-body p-6 pt-0">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                            <tr>
                                <th scope="col" class="table-th">{{ __('Fund Title') }}</th>
                                <th scope="col" class="table-th">{{ __('Prize') }}</th>
                                <th scope="col" class="table-th">{{ __('Activation Date') }}</th>
                                <th scope="col" class="table-th">{{ __('Returned Until Now') }}</th>
                                <th scope="col" class="table-th">{{ __('Detail') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($pendingPlans as $plan)
                                <tr>
                                    <td class="table-td">{{ data_get($plan->forexSchemaPhaseRule->forexSchemaPhase->forexSchema,'title') }}</td>
                                    <td class="table-td">{{ data_get($plan,'amount_allotted')}}</td>
                                    <td class="table-td">{{ data_get($plan,'term_start') ?? 'N/A'}}</td>
                                    <td class="table-td">{{ data_get($plan,'profit')}}</td>
                                    <td class="table-td">
                                        <a href="{{route('user.invest.details',$plan->id)}}" class="inline-flex justify-center">
                                        <span class="flex items-center">
                                            <span>{{ __('Fund Matrics') }}</span>
                                            <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2"
                                                          icon="lucide:chevron-right"></iconify-icon>
                                        </span>
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
@else
    <div class="card mb-3">
        <div class="card-body p-6">
            <div class="flex items-center justify-center flex-col gap-3">
                <img src="{{ asset('frontend/images/icon/danger.png') }}" alt="">
                <p class="text-lg text-slate-600 dark:text-slate-100">
                    {{ __("You don't have pending Challenge yet.") }}
                </p>
            </div>
        </div>
    </div>
@endif


