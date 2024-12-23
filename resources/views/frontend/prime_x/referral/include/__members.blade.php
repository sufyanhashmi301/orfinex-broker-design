@php use App\Enums\TxnStatus; use App\Enums\TxnType; @endphp

@extends('frontend::layouts.user')
@section('title')
    {{ __('Schema Logs') }}
@endsection
@section('content')
    <div class="space-y-5">
        <div class="card desktop-screen-show md:block hidden">
            <div class="card-body p-6 pb-0">
                <div class="innerMenu grid xl:grid-cols-2 grid-cols-1 gap-5 mb-6">
                    <div class="filter">
                        <form action="{{ route('user.referral.members') }}" method="get">
                            <div class="flex justify-between flex-wrap items-center mb-5">
                                <div class="search flex gap-3 items-center">
                                    <div class="py-6">
                                        <div class="input-area relative min-w-[184px]">
                                            <select name="level_order" class="select2 form-control w-full">
                                                @for ($i = 0; $i <= $maxLevelOrderCount; $i++)
                                                    <option
                                                        value="{{ $i }}" {{ $i == $selectedLevel ? 'selected' : '' }}>
                                                        {{ __('Level ' . $i) }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-dark btn-sm">
                                        <i icon-name="search"></i>
                                        {{ __('Search') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="overflow-x-auto -mx-6">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden basicTable_wrapper">
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                <thead class="border-t border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th scope="col" class="table-th">{{ __('User') }}</th>
                                    <th scope="col" class="table-th">{{ __('Level') }}</th>
                                    <th scope="col" class="table-th">{{ __('Type') }}</th>
                                    <th scope="col" class="table-th">{{ __('Schema') }}</th>
                                    <th scope="col" class="table-th">{{ __('Join') }}</th>
                                    {{--                                        <th scope="col" class="table-th">{{ __('Fee') }}</th>--}}
                                    {{--                                        <th scope="col" class="table-th">{{ __('Status') }}</th>--}}
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                    @forelse($referrals as $referral)
                                        <tr>
                                            <td class="table-td">
                                                <div class="flex items-center">
                                                    <div class="flex-1 text-start">
                                                        <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                            {{ $referral->user->full_name }}
                                                        </h4>
                                                        <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                                            {{ $referral->user->email }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="table-td">
                                                {{ $referral->multiLevel ? $referral->multiLevel->level_order : 'N/A' }}
                                            </td>
                                            <td class="table-td">
                                                {{ $referral->multiLevel ? $referral->multiLevel->type : 'N/A' }}
                                            </td>
                                            <td class="table-td">
                                                {{ $referral->multiLevel ? $referral->multiLevel->forexSchema->title : 'N/A' }}
                                            </td>
                                            <td class="table-td">
                                                {{ $referral->created_at }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center p-4 text-slate-600 dark:text-slate-400">
                                                {{ __("No referrals found for the selected level.") }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div
                                class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-3 mt-auto">
                                <div>
                                    {{--                                        @php--}}
                                    {{--                                            $from = $referrals->firstItem(); // The starting item number on the current page--}}
                                    {{--                                            $to = $referrals->lastItem(); // The ending item number on the current page--}}
                                    {{--                                            $total = $referrals->total(); // The total number of items--}}
                                    {{--                                        @endphp--}}

                                    {{--                                        <p class="text-sm text-gray-700 dark:text-slate-300 px-3">--}}
                                    {{--                                            {{ __('Showing') }}--}}
                                    {{--                                            <span class="font-medium">{{ $from }}</span>--}}
                                    {{--                                            {{ __('to') }}--}}
                                    {{--                                            <span class="font-medium">{{ $to }}</span>--}}
                                    {{--                                            {{ __('of') }}--}}
                                    {{--                                            <span class="font-medium">{{ $total }}</span>--}}
                                    {{--                                            {{ __('results') }}--}}
                                    {{--                                        </p>--}}
                                </div>
                                {{--                                    {{ $referrals->links() }}--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="md:hidden block mobile-screen-show">
        <!-- Transactions -->
        <div class="card all-feature-mobile mobile-transactions mb-3">
            <div class="card-header">
                <h4 class="card-title">{{ __('All Transactions') }}</h4>
            </div>
            <div class="card-body p-3 mobile-transaction-filter">
                <div class="filter mb-3">
                    <form action="{{ route('user.history.transactions') }}" method="get">
                        <div class="search flex items-center gap-2">
                            <input type="text" class="form-control" placeholder="{{ __('Search') }}"
                                   value="{{ request('query') }}" name="query"/>
                            <input type="date" class="form-control" name="date" value="{{ request()->get('date') }}"/>
                            <button type="submit" class="apply-btn h-10 btn btn-dark">
                                <iconify-icon icon="lucide:search"></iconify-icon>
                            </button>
                        </div>
                    </form>
                </div>
                {{--                <div class="contents space-y-3">--}}
                {{--                    @foreach($referrals as $referral )--}}
                {{--                        <div class="single-transaction flex justify-between text-xs bg-slate-100 dark:bg-slate-900 rounded-md p-2 py-3">--}}
                {{--                            <div class="transaction-left w-3/4">--}}
                {{--                                <div class="transaction-des">--}}
                {{--                                    <div class="transaction-title mb-1 dark:text-white">{{ $referral->description }}</div>--}}
                {{--                                    <div class="transaction-id mb-1 dark:text-white">{{ $referral->tnx }}</div>--}}
                {{--                                    <div class="transaction-date mb-1 dark:text-white">{{ $referral->created_at }}</div>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                            <div class="transaction-right text-right">--}}
                {{--                                <div class="transaction-amount {{ txn_type($referral->type->value,['add','sub']) }} mb-1 dark:text-white">--}}
                {{--                                    {{ txn_type($referral->type->value,['+','-']) }}{{ $referral->amount . ' ' . $currency }}</div>--}}
                {{--                                <div class="transaction-fee sub mb-1 dark:text-white">--}}
                {{--                                    -{{ $referral->charge . ' ' . $currency . ' ' . __('Fee') }} </div>--}}
                {{--                                <div class="transaction-gateway mb-1 dark:text-white">{{ $referral->method }}</div>--}}

                {{--                                @if($referral->status->value == App\Enums\TxnStatus::Pending->value)--}}
                {{--                                    <div class="transaction-status text-warning">{{ __('Pending') }}</div>--}}
                {{--                                @elseif($referral->status->value == App\Enums\TxnStatus::Success->value)--}}
                {{--                                    <div class="transaction-status text-success">{{ __('Success') }}</div>--}}
                {{--                                @elseif($referral->status->value == App\Enums\TxnStatus::Failed->value)--}}
                {{--                                    <div class="transaction-status text-danger">{{ __('Canceled') }}</div>--}}
                {{--                                @endif--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    @endforeach--}}
                {{--                </div>--}}
                {{--                {{ $referrals->onEachSide(1)->links() }}--}}
            </div>
        </div>
    </div>
@endsection
