@php use App\Enums\TxnStatus; use App\Enums\TxnType; @endphp

@extends('frontend::layouts.user')
@section('title')
    {{ __('Schema Logs') }}
@endsection
@section('content')
    <div class="pageTitle flex flex-col md:flex-row justify-between md:items-center flex-wrap mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-700">
            {{ __('All Referrals') }}
        </h4>
    </div>
    <div class="card desktop-screen-show md:block hidden">
        <div class="card-body px-6 pb-0">
{{--                <div class="innerMenu grid xl:grid-cols-2 grid-cols-1 gap-5 mb-6">--}}
{{--                    <div class="filter">--}}
{{--                        <form action="{{ route('user.referral.members') }}" method="get">--}}
{{--                            <div class="flex justify-between flex-wrap items-center mb-5">--}}
{{--                                <div class="search flex gap-3 items-center">--}}
{{--                                    <div class="py-6">--}}
{{--                                        <div class="input-area relative min-w-[184px]">--}}
{{--                                            <select name="level_order" class="select2 form-control w-full">--}}
{{--                                                @for ($i = 0; $i <= $maxLevelOrderCount; $i++)--}}
{{--                                                    <option--}}
{{--                                                        value="{{ $i }}" {{ $i == $selectedLevel ? 'selected' : '' }}>--}}
{{--                                                        {{ __('Level ' . $i) }}--}}
{{--                                                    </option>--}}
{{--                                                @endfor--}}
{{--                                            </select>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <button type="submit" class="btn btn-dark btn-sm">--}}
{{--                                        <i icon-name="search"></i>--}}
{{--                                        {{ __('Search') }}--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                </div>--}}
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('User') }}</th>
                                    <th scope="col" class="table-th">{{ __('Phone') }}</th>
                                    <th scope="col" class="table-th">{{ __('Balance') }}</th>
                                    <th scope="col" class="table-th">{{ __('Equity') }}</th>
                                    <th scope="col" class="table-th">{{ __('Credit') }}</th>
                                    {{--<th scope="col" class="table-th">{{ __('Schema') }}</th>--}}
                                    <th scope="col" class="table-th">{{ __('Join') }}</th>
                                    {{--<th scope="col" class="table-th">{{ __('Fee') }}</th>--}}
                                    {{--<th scope="col" class="table-th">{{ __('Status') }}</th>--}}
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @forelse($referrals as $referral)
                                    <tr>
                                        <td class="table-td">
                                            <span class="flex items-center">
                                                <div class="flex-none">
                                                    <div class="w-8 h-8 rounded-[100%] ltr:mr-3 rtl:ml-3">
                                                        <img src="{{ getFilteredPath($referral->avatar, 'fallback/user.png') }}" alt="" class="w-full h-full rounded-[100%] object-cover">
                                                    </div>
                                                </div>
                                                <div class="flex-1 text-start">
                                                    <h4 class="text-sm font-medium text-slate-600 dark:text-white whitespace-nowrap">
                                                        {{ $referral->full_name }}
                                                    </h4>
                                                    <div class="text-xs font-normal text-slate-600 dark:text-slate-200">
                                                        {{ $referral->email }}
                                                    </div>
                                                </div>
                                            </span>
                                        </td>

                                        <td class="table-td">
                                            {{ $referral->phone ? $referral->phone : 'N/A' }}
                                        </td>
                                        <td class="table-td">
                                            {{ mt5_total_balance($referral->id) }}
                                        </td>
                                        <td class="table-td">
                                            {{ mt5_total_equity($referral->id) }}
                                        </td>
                                        <td class="table-td">
                                            {{ mt5_total_credit($referral->id) }}
                                        </td>
                                        <td class="table-td">
                                            {{ $referral->created_at }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="table-td text-center">
                                            {{ __("No referrals found for the selected level.") }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="md:hidden block mobile-screen-show">
    <div class="contents space-y-3">
        <div class="grid sm:grid-cols-2 grid-cols-1 gap-5">
            @foreach($referrals as $referral )
                <div class="card rounded-md bg-white dark:bg-slate-800 shadow-base">
                    <header class="card-header noborder">
                        <div class="flex items-center">
                            <div class="flex-none">
                                <div class="w-8 h-8 rounded-[100%] ltr:mr-3 rtl:ml-3">
                                    <img src="{{ getFilteredPath($referral->avatar, 'fallback/user.png') }}" alt="" class="w-full h-full rounded-[100%] object-cover">
                                </div>
                            </div>
                            <div class="flex-1 text-start">
                                <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                    {{ $referral->full_name }}
                                </h4>
                                <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                    {{ $referral->email }}
                                </div>
                            </div>
                        </div>
                    </header>
                    <div class="card-body p-6 pt-0">
                        <ul class="divide-y divide-slate-100 dark:divide-slate-700">
                            <li class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-100 py-2 first:uppercase">
                                <div class="flex justify-between">
                                    <span>{{ __('Phone') }}</span>
                                    <span>{{ $referral->phone ? $referral->phone : 'N/A' }}</span>
                                </div>
                            </li>
                            <li class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-100 py-2 first:uppercase">
                                <div class="flex justify-between">
                                    <span>{{ __('Balance') }}</span>
                                    <span>{{ mt5_total_balance($referral->id) }}</span>
                                </div>
                            </li>
                            <li class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-100 py-2 first:uppercase">
                                <div class="flex justify-between">
                                    <span>{{ __('Equity') }}</span>
                                    <span>{{ mt5_total_equity($referral->id) }}</span>
                                </div>
                            </li>
                            <li class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-100 py-2 first:uppercase">
                                <div class="flex justify-between">
                                    <span>{{ __('Credit') }}</span>
                                    <span>{{ mt5_total_credit($referral->id) }}</span>
                                </div>
                            </li>
                            <li class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-100 py-2 first:uppercase">
                                <div class="flex justify-between">
                                    <span>{{ __('Join') }}</span>
                                    <span>{{ $referral->created_at }}</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    {{--{{ $referrals->onEachSide(1)->links() }}--}}
@endsection
