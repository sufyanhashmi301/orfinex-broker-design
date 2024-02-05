@extends('frontend::layouts.user')
@section('title')
    {{ __('All Account Type') }}
@endsection
@section('content')
<div class="mb-5">
    <ul class="m-0 p-0 list-none">
        <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter ">
            <a href="{{route('user.dashboard')}}">
                <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
            </a>
        </li>
        <li class="inline-block relative text-sm text-primary-500 font-Inter ">
            {{ __('Dashboard') }}
            <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
        </li>
        <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
            {{ __('All Plans') }}
        </li>
    </ul>
</div>
<div class="card">
    <div class="card-header flex-wrap">
        <h4 class="card-title mb-4 sm:mb-0">
            {{ __('Open New Account') }}
        </h4>
        <div class="space-x-2 rtl:space-x-reverse">
            <a href="{{ route('user.forex-account-logs') }}" class="btn btn-outline-dark">My Accounts</a>
{{--            <a href="{{ route('user.offers') }}" class="btn btn-dark">Get Bonus</a>--}}
        </div>
    </div>
    <div class="card-body p-6">
        <div class="grid xl:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
            @foreach($schemas as $schema)
            <div class="card border bg-white dark:bg-slate-800 hover:bg-slate-100">
                @if($schema->badge)
                    <div class="flex justify-end p-3">
                        <p class="badge bg-slate-900 text-white capitalize">
                            {{$schema->badge}}
                        </p>
                    </div>
                @endif
                <div class="card-body p-6 pt-0">
                    <h4 class="text-center mb-2">{{$schema->title}}</h4>
                    <p class="text-slate-900 dark:text-white text-sm text-center my-3 min-h-[3.75rem]">{{$schema->desc }}</p>
                    <ul class="divide-y divide-slate-100 dark:divide-slate-700">
                        <li class="flex items-center py-3">
                            <span class="flex-1 text-sm text-slate-600 dark:text-slate-300">
                                {{ __('Initial Deposit') }}
                            </span>
                            <span class="flex-1 text-right">
                                <span class="bg-opacity-20 capitalize font-semibold text-sm leading-4 px-[10px] py-[2px] rounded-full inline-block bg-success-500 text-success-500">
                                    {{ isset($schema->first_min_deposit) ? $currencySymbol . $schema->first_min_deposit  : $currencySymbol . 0 }}
                                </span>
                            </span>
                        </li>
                        <li class="flex items-center py-3">
                            <span class="flex-1 text-sm text-slate-600 dark:text-slate-300">
                                {{ __('Spread') }}
                            </span>
                            <span class="flex-1 text-sm text-right text-slate-600 dark:text-slate-300">
                                {{ $schema->spread ? $schema->spread : 'NA' }}
                            </span>
                        </li>
                        <li class="flex items-center py-3">
                            <span class="flex-1 text-sm text-slate-600 dark:text-slate-300">
                                {{ __('Commission') }}
                            </span>
                            <span class="flex-1 text-sm text-right text-slate-600 dark:text-slate-300">
                                {{ $schema->commission ? $schema->commission : 'NA' }}
                            </span>
                        </li>
                    </ul>
                    <p class="text-slate-900 dark:text-white text-xs text-center mt-2">
                        <span class="star">*</span>
                        {{ __('Available in countries:  ') }}  {{implode(', ', json_decode($schema->country,true)) }}
                    </p>
                    <a href="{{route('user.schema.preview',$schema->id)}}" class="btn inline-flex justify-center btn-dark w-full mt-3">
                        {{ __('Create Account') }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
