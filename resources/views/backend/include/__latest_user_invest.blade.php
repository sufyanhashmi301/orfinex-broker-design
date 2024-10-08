<div class="col-span-12">
    <div class="card">
        <div class="card-header noborder">
            <h3 class="card-title">{{ __('Latest Registered User') }}</h3>
        </div>
        <div class="card-body px-6 pb-6">
            <div class="flex justify-between flex-wrap items-center mb-6 mt-3">
                <div class="inline-flex ltr:pr-4 rtl:pl-4 mb-2 sm:mb-0">
                    <div class="input-area">
                        <div class="relative">
                            <input type="text" class="form-control !pr-9" placeholder="Search">
                            <butt class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-none flex items-center justify-center">
                                <iconify-icon icon="heroicons-solid:search"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                    <button class="btn btn-sm inline-flex justify-center bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                        <span class="flex items-center">
                            <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="heroicons-outline:filter"></iconify-icon>
                            <span>Filter</span>
                        </span>
                    </button>
                    <div class="relative">
                        <button class="btn btn-sm inline-flex justify-center bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white" type="button" data-bs-toggle="dropdown" data-bs-auto-close="false" aria-expanded="false">
                            <span class="flex items-center">
                                <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:columns-2"></iconify-icon>
                                <span>Columns</span>
                            </span>
                        </button>
                        <div class="dropdown-menu z-10 hidden bg-white space-y-3 divide-slate-100 shadow w-[250px] dark:bg-slate-800 border dark:border-slate-700 !top-[14px] p-4 rounded-md overflow-hidden">
                            <div class="flex items-center justify-between">
                                <div class="checkbox-area">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="hidden" name="user" checked>
                                        <span class="h-3 w-3 border flex-none border-slate-100 dark:border-slate-800 rounded-sm inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                            <img src="assets/images/icon/ck-white.svg" alt="" class="h-[8px] w-[8px] block m-auto opacity-0"></span>
                                        <span class="text-slate-500 dark:text-slate-400 text-sm leading-6">User</span>
                                    </label>
                                </div>
                                <div>
                                    <a href="javascript:;">
                                        <iconify-icon class="text-xl font-light" icon="mdi:drag"></iconify-icon>
                                    </a>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="checkbox-area">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="hidden" name="email" checked>
                                        <span class="h-3 w-3 border flex-none border-slate-100 dark:border-slate-800 rounded-sm inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                            <img src="assets/images/icon/ck-white.svg" alt="" class="h-[8px] w-[8px] block m-auto opacity-0"></span>
                                        <span class="text-slate-500 dark:text-slate-400 text-sm leading-6">Email</span>
                                    </label>
                                </div>
                                <div>
                                    <a href="javascript:;">
                                        <iconify-icon class="text-xl font-light" icon="mdi:drag"></iconify-icon>
                                    </a>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="checkbox-area">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="hidden" name="balance" checked>
                                        <span class="h-3 w-3 border flex-none border-slate-100 dark:border-slate-800 rounded-sm inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                            <img src="assets/images/icon/ck-white.svg" alt="" class="h-[8px] w-[8px] block m-auto opacity-0"></span>
                                        <span class="text-slate-500 dark:text-slate-400 text-sm leading-6">Balance</span>
                                    </label>
                                </div>
                                <div>
                                    <a href="javascript:;">
                                        <iconify-icon class="text-xl font-light" icon="mdi:drag"></iconify-icon>
                                    </a>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="checkbox-area">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="hidden" name="profit" checked>
                                        <span class="h-3 w-3 border flex-none border-slate-100 dark:border-slate-800 rounded-sm inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                            <img src="assets/images/icon/ck-white.svg" alt="" class="h-[8px] w-[8px] block m-auto opacity-0"></span>
                                        <span class="text-slate-500 dark:text-slate-400 text-sm leading-6">Profit</span>
                                    </label>
                                </div>
                                <div>
                                    <a href="javascript:;">
                                        <iconify-icon class="text-xl font-light" icon="mdi:drag"></iconify-icon>
                                    </a>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="checkbox-area">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="hidden" name="kyc" checked>
                                        <span class="h-3 w-3 border flex-none border-slate-100 dark:border-slate-800 rounded-sm inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                            <img src="assets/images/icon/ck-white.svg" alt="" class="h-[8px] w-[8px] block m-auto opacity-0"></span>
                                        <span class="text-slate-500 dark:text-slate-400 text-sm leading-6">KYC</span>
                                    </label>
                                </div>
                                <div>
                                    <a href="javascript:;">
                                        <iconify-icon class="text-xl font-light" icon="mdi:drag"></iconify-icon>
                                    </a>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="checkbox-area">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="hidden" name="status" checked>
                                        <span class="h-3 w-3 border flex-none border-slate-100 dark:border-slate-800 rounded-sm inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                            <img src="assets/images/icon/ck-white.svg" alt="" class="h-[8px] w-[8px] block m-auto opacity-0"></span>
                                        <span class="text-slate-500 dark:text-slate-400 text-sm leading-6">Status</span>
                                    </label>
                                </div>
                                <div>
                                    <a href="javascript:;">
                                        <iconify-icon class="text-xl font-light" icon="mdi:drag"></iconify-icon>
                                    </a>
                                </div>
                            </div>
                            <div class="text-right">
                                <button class="btn btn-dark btn-sm inline-flex items-center justify-center" type="button">
                                    Apply
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="border-t border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Avatar') }}</th>
                                    <th scope="col" class="table-th">{{ __('User') }}</th>
                                    <th scope="col" class="table-th">{{ __('Email') }}</th>
                                    <th scope="col" class="table-th">{{ __('Balance') }}</th>
                                    <th scope="col" class="table-th">{{ __('Profit') }}</th>
                                    <th scope="col" class="table-th">{{ __('KYC') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach($data['latest_user'] as $user)
                                <tr>
                                    <td class="table-td">
                                        <div class="w-8 h-8 rounded-[100%] bg-slate-100 text-slate-900 dark:bg-slate-600 dark:text-slate-200 flex flex-col items-center justify-center font-normal capitalize ltr:mr-3 rtl:ml-3">
                                            @if(null != $user->avatar)
                                                <img src="{{ asset($user->avatar)}}" alt="" class="w-full h-full rounded-[100%] object-cover">
                                            @else
                                                {{ $user->first_name[0] }}{{ $user->last_name[0] }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="table-td">
                                        {{ safe($user->username) }}
                                    </td>
                                    <td class="table-td">
                                        {{ safe($user->email) }}
                                    </td>
                                    <td class="table-td">
                                        <strong>{{ $currencySymbol . $user->balance }}</strong>
                                    </td>
                                    <td class="table-td">
                                        <strong>{{ $currencySymbol . $user->total_profit }}</strong>
                                    </td>
                                    <td class="table-td">
                                        @if($user->kyc == 1)
                                            <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">{{ __('Verified') }}</div>
                                        @else
                                            <div class="badge bg-warning-500 text-warning-500 bg-opacity-30 capitalize">{{ __('Unverified') }}</div>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        @if($user->status == 1)
                                        <span class="block text-left">
                                            <span class="inline-block text-center mx-auto py-1">
                                                <span class="flex items-center space-x-3 rtl:space-x-reverse">
                                                    <span class="h-[6px] w-[6px] bg-success-500 rounded-full inline-block ring-4 ring-opacity-30 ring-success-500"></span>
                                                        <span>{{ __('Active') }}</span>
                                                    </span>
                                                </span>
                                            </span>
                                        @else
                                        <span class="block text-left">
                                            <span class="inline-block text-center mx-auto py-1">
                                                <span class="flex items-center space-x-3 rtl:space-x-reverse">
                                                    <span class="h-[6px] w-[6px] bg-danger-500 rounded-full inline-block ring-4 ring-opacity-30 ring-danger-500"></span>
                                                    <span>{{ __('DeActivated') }}</span>
                                                </span>
                                                </span>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <a href="{{route('admin.user.edit',$user->id)}}" class="toolTip onTop action-btn" data-tippy-theme="dark" data-tippy-content="Edit User">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </a>
                                            <span type="button" data-id="{{$user->id}}" data-name="{{ $user->first_name.' '. $user->last_name }}" class="send-mail">
                                                <button class="toolTip onTop action-btn" data-tippy-theme="dark" data-tippy-content="Send Email">
                                                    <iconify-icon icon="lucide:mail"></iconify-icon>
                                                </button>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                <tr class="centered">
                                    <td class="table-td" colspan="7">
                                        @if($data['latest_user']->isEmpty())
                                            {{ __('No Data Found') }}
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--<div class="row">--}}
{{--    <div class="col-xl-12">--}}
{{--        <div class="site-card">--}}
{{--            <div class="site-card-header">--}}
{{--                <h3 class="title">{{ __('Latest Investment') }}</h3>--}}
{{--            </div>--}}
{{--            <div class="site-card-body table-responsive">--}}
{{--                <div class="site-datatable">--}}
{{--                    <table class="data-table mb-0">--}}
{{--                        <thead>--}}
{{--                        <tr>--}}
{{--                            <th>{{ __('Avatar') }}</th>--}}
{{--                            <th>{{ __('User') }}</th>--}}
{{--                            <th>{{ __('Schema') }}</th>--}}
{{--                            <th>{{ __('ROI') }}</th>--}}
{{--                            <th>{{ __('Profit') }}</th>--}}
{{--                            <th>{{ __('Capital Back') }}</th>--}}
{{--                            <th>{{ __('Timeline') }}</th>--}}
{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody>--}}
{{--                        @foreach($data['latest_invest'] as $invest)--}}

{{--                            @php--}}
{{--                                $calculateInterest = ($invest->interest*$invest->invest_amount)/100;--}}
{{--                                $interest = $invest->interest_type != 'percentage' ? $invest->interest : $calculateInterest;--}}
{{--                            @endphp--}}


{{--                            <tr>--}}
{{--                                <td>--}}
{{--                                    @if(null != $invest->user->avatar)--}}
{{--                                        <img class="avatar" src="{{ asset($invest->user->avatar)}}" alt=""--}}
{{--                                             height="40" width="40">--}}
{{--                                    @else--}}
{{--                                        <span--}}
{{--                                            class="avatar-text">{{ $invest->user->first_name[0] }}{{ $invest->user->last_name[0] }}</span>--}}
{{--                                    @endif--}}
{{--                                </td>--}}
{{--                                <td><a href="{{ route('admin.user.edit',$invest->user_id) }}"--}}
{{--                                       class="link">{{ safe($invest->user->username) }}</a></td>--}}
{{--                                <td>--}}

{{--                                    <strong> {{ $invest->schema->name }} <i--}}
{{--                                            icon-name="arrow-big-right"></i> {{ $currencySymbol.$invest->invest_amount }}--}}
{{--                                    </strong>--}}

{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    <strong>{{ $invest->interest_type == 'percentage' ? $invest->interest.'%' : $currencySymbol.$invest->interest }}</strong>--}}
{{--                                </td>--}}

{{--                                <td>--}}
{{--                                    <strong>{{ $invest->already_return_profit .' x '.$interest .' = '. ($invest->already_return_profit*$interest).' '. $currency }}</strong>--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    <div--}}
{{--                                        class="site-badge {{ $invest->capital_back ? 'success' : 'pending' }}">{{ $invest->capital_back ? 'Yes' : 'No' }}</div>--}}
{{--                                </td>--}}
{{--                                <td>--}}

{{--                                    @if($invest->status == App\Enums\InvestStatus::Ongoing)--}}

{{--                                        <div>--}}
{{--                                            <strong><span id="days{{ $invest->id }}"></span>D : <span--}}
{{--                                                    id="hours{{ $invest->id }}"></span>H : <span--}}
{{--                                                    id="minutes{{ $invest->id }}"></span>M : <span--}}
{{--                                                    id="seconds{{ $invest->id }}"></span>S</strong>--}}
{{--                                            <span class="site-badge primary-bg ms-2"--}}
{{--                                                  id="percentage{{ $invest->id }}"></span>--}}
{{--                                        </div>--}}
{{--                                        <div class="progress investment-timeline">--}}
{{--                                            <div--}}
{{--                                                class="progress-bar progress-bar-striped progress-bar-animated"--}}
{{--                                                id="time-progress{{ $invest->id }}" role="progressbar"--}}
{{--                                                aria-valuenow="75" aria-valuemin="0"--}}
{{--                                                aria-valuemax="100"></div>--}}
{{--                                        </div>--}}

{{--                                        @push('single-script')--}}
{{--                                            <script>--}}
{{--                                                (function ($) {--}}
{{--                                                    "use strict";--}}
{{--                                                    // Countdown--}}
{{--                                                    const second = 1000,--}}
{{--                                                        minute = second * 60,--}}
{{--                                                        hour = minute * 60,--}}
{{--                                                        day = hour * 24;--}}
{{--                                                    let timezone = @json(setting('site_timezone','global'));--}}

{{--                                                    let countDown = new Date('{{$invest->next_profit_time}}').getTime()--}}
{{--                                                    var start = new Date('{{ $invest->last_profit_time ?? $invest->created_at}}').getTime()--}}
{{--                                                    setInterval(function () {--}}

{{--                                                        let utc_datetime_str = new Date().toLocaleString("en-US", {timeZone: timezone});--}}
{{--                                                        let now = new Date(utc_datetime_str).getTime();--}}
{{--                                                        let distance = countDown - now;--}}


{{--                                                        var progress = (((now - start) / (countDown - start)) * 100).toFixed(2);--}}


{{--                                                        $("#time-progress{{ $invest->id }}").css("width", progress + '%');--}}

{{--                                                        $("#percentage{{ $invest->id }}").text(progress >= 100 ? 100 + '%' : progress + '%');--}}

{{--                                                        document.getElementById('days{{ $invest->id }}').innerText = Math.floor(distance < 0 ? 0 : distance / (day)),--}}
{{--                                                            document.getElementById('hours{{ $invest->id }}').innerText = Math.floor(distance < 0 ? 0 : (distance % (day)) / (hour)),--}}
{{--                                                            document.getElementById('minutes{{$invest->id }}').innerText = Math.floor(distance < 0 ? 0 : (distance % (hour)) / (minute)),--}}
{{--                                                            document.getElementById('seconds{{ $invest->id }}').innerText = Math.floor(distance < 0 ? 0 : (distance % (minute)) / second);--}}

{{--                                                    }, second)--}}

{{--                                                })(jQuery)--}}
{{--                                            </script>--}}
{{--                                        @endpush--}}

{{--                                    @elseif($invest->status == App\Enums\InvestStatus::Completed)--}}
{{--                                        <div class="site-badge success">{{ __('Completed') }}</div>--}}
{{--                                        <div class="progress investment-timeline">--}}
{{--                                            <div--}}
{{--                                                class="progress-bar progress-bar-striped progress-bar-animated"--}}
{{--                                                role="progressbar" aria-valuenow="75" aria-valuemin="0"--}}
{{--                                                aria-valuemax="100" style="width: 100%"></div>--}}
{{--                                        </div>--}}
{{--                                    @elseif($invest->status == App\Enums\InvestStatus::Pending)--}}
{{--                                        <div class="site-badge pending">{{ __('Pending') }}</div>--}}
{{--                                    @else--}}
{{--                                        <div class="site-badge pending">{{ __('Canceled') }}</div>--}}
{{--                                    @endif--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
{{--                        <tr class="centered">--}}
{{--                            <td colspan="7">--}}
{{--                                @if($data['latest_invest']->isEmpty())--}}
{{--                                    {{ __('No Data Found') }}--}}
{{--                                @endif--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                        </tbody>--}}
{{--                    </table>--}}

{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
