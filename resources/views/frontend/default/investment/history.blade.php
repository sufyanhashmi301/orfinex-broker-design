@extends('frontend::layouts.user')

@section('title', __('Funded History'))

@section('content')

<div class=" md:flex justify-between items-center mb-5">
    <!-- BEGIN: Breadcrumb -->
    <div class="mb-5 md:mb-0">
        <ul class="m-0 p-0 list-none">
            <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter ">
                <a href="{{route('user.dashboard')}}">
                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                </a>
            </li>
            <li class="inline-block relative text-sm text-primary-500 font-Inter ">
                {{ __("Funded") }}
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ __("Funded History") }}
            </li>
        </ul>
    </div>
    <!-- END: BreadCrumb -->
    <div class="flex flex-wrap ">
        <a href="{{ route('user.pricing.plans') }}" class="btn btn-dark inline-flex items-center">
            <span>{{ __('Funded More') }}</span>
        </a>
    </div>
</div>

<div class="card p-4 mb-5">
    <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4">
        <li class="nav-item">
            <a href="{{ route('user.pricing.history') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 dark:bg-slate-900 dark:text-slate-300 {{ ($listing == 'all' || empty($listing)) ? ' active' : '' }}">
                {{ __('History') }}
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('user.pricing.history', 'active') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 dark:bg-slate-900 dark:text-slate-300 {{ ($listing == 'active') ? ' active' : '' }}">
                {{ __("Active") }} 
                <span class="mr-1">
                    &#10088;{{ data_get($investCount, 'active') }}&#10089;
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('user.pricing.history', 'pending') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 dark:bg-slate-900 dark:text-slate-300 {{ ($listing == 'pending') ? ' active' : '' }}">
                {{ __("Pending") }} 
                <span class="mr-1">
                    &#10088;{{ data_get($investCount, 'pending') }}&#10089;
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('user.pricing.history', 'completed') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 dark:bg-slate-900 dark:text-slate-300 {{ ($listing == 'completed') ? ' active' : '' }}">
                {{ __("Completed") }}
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('user.pricing.history', 'violated') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 dark:bg-slate-900 dark:text-slate-300 {{ ($listing == 'violated') ? ' active' : '' }}">
                {{ __("Violated") }}
            </a>
        </li>
    </ul>
</div>

<div class="card">
    <div class="card-header noborder">
        <h4 class="card-title">{{ __("All Plans") }}</h4>
    </div>
    <div class="card body px-6 pb-6">
        @if(filled($investments))
        <div class="overflow-x-auto -mx-6">
            <div class="inline-block min-w-full align-middle">
                <div class="overflow-hidden ">
                    <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 table-plans{{ user_meta('iv_history_display') != 'compact' ? ' table-lg': '' }}">
                        <thead class=" border-t border-slate-100 dark:border-slate-800">
                            <th scope="col" class="table-th">{{ __("ID") }}</th>
                            <th scope="col" class="table-th">{{ __("Plan") }}</th>
                            <th scope="col" class="table-th">{{ __("Date") }}</th>
                            <th scope="col" class="table-th">{{ __("Service Fee") }}</th>
                            <th scope="col" class="table-th">{{ __("Alloted Amount") }}</th>
                            <th scope="col" class="table-th">{{ __("Status") }}</th>
                            <th scope="col" class="table-th">&nbsp;</th>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                            @foreach($investments as $invest)
                                <tr>
                                    <td class="table-td">
                                        <a href="{{ route('user.pricing.details', ['id' => the_hash($invest->id)]) }}" class="text-secondary fw-bold">
                                            {{ the_inv(data_get($invest, 'pvx')) }}
                                        </a>
                                    </td>
                                    <td class="table-td">
                                        {{ data_get($invest, 'scheme.name') }}
                                    </td>
                                    <td class="table-td">
                                        {{ show_date(data_get($invest, 'term_start'), true) }}
                                    </td>
                                    <td class="table-td">
                                        {{ money(data_get($invest, 'total'), data_get($invest, 'currency')) }}
                                    </td>
                                    <td class="table-td">
                                        {{ money(data_get($invest, 'amount_allotted'), data_get($invest, 'currency')) }}
                                    </td>
                                    <td class="table-td">
                                        <span class="badge {{ the_state(data_get($invest, 'status'), ['prefix' => 'bg']) }}-500 {{ the_state(data_get($invest, 'status'), ['prefix' => 'text']) }}-500 bg-opacity-30 capitalize rounded-3xl">
                                            {{ __(ucfirst(data_get($invest, 'status'))) }}
                                        </span>
                                    </td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <a class="action-btn btn-trans" href="{{ route('user.pricing.details', ['id' => the_hash($invest->id)]) }}">
                                                <iconify-icon icon="lucide:chevron-right"></iconify-icon>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if(filled($investments) && $investments->hasPages())
            <div class="card-inner pt-3 pb-3">
                {{ $investments->appends(request()->all())->links('misc.pagination') }}
            </div>
            @endif
        @else
            <p class="text-center">{{ __('No Funded plan found.') }}</p>
        @endif
    </div>
</div>
@endsection

@push('modal')
<div class="modal fade" tabindex="-1" role="dialog" id="ajax-modal"></div>
@endpush
