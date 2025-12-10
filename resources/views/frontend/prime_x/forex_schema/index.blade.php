@extends('frontend::layouts.user')
@section('title')
    @php
        $newAccountTitle = __('Open New Account');
        $requestedType = request()->get('type');
        if ($requestedType === 'real') {
            $newAccountTitle = __('Open New Real Account');
        } elseif ($requestedType === 'demo') {
            $newAccountTitle = __('Open New Demo Account');
        }
    @endphp
    {{ $newAccountTitle }}
@endsection
@section('content')
<div class="flex justify-between flex-wrap items-center mb-5">
    <h4 class="font-medium text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
        {{ $newAccountTitle }}
    </h4>
    <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
        <a href="{{ route('user.forex-account-logs') }}" class="btn btn-primary loaderBtn inline-flex items-center justify-center">
            {{ __('My Accounts') }}
        </a>
        {{-- <a href="{{ route('user.offers') }}" class="btn btn-sm btn-primary">{{ __('Get Bonus') }}</a> --}}
    </div>
</div>
@php 
    $reqType = request('type');
    $liveApproval = setting('live_account_creation','features');
    $demoApproval = setting('demo_account_creation','features');
@endphp
@if(($reqType === 'real' && $liveApproval) || ($reqType === 'demo' && $demoApproval))
<div class="py-3 px-4 rounded-md mb-5 bg-warning-500 bg-opacity-30 text-warning-900" style="background-color:#FEF3C7; color:#92400E;">
    <div class="flex items-center">
        <iconify-icon class="text-xl mr-2" icon="lucide:info"></iconify-icon>
        <span>
            @if($reqType === 'real')
                @php
                    $limits = [];
                    foreach($schemas as $s) {
                        $limit = $s->live_account_limit ?? 0;
                        if($limit > 0) {
                            $limits[] = $limit;
                        }
                    }
                    $hasLimit = !empty($limits);
                    $minLimit = $hasLimit ? min($limits) : 0;
                    $maxLimit = $hasLimit ? max($limits) : 0;
                    $uniqueLimits = array_unique($limits);
                @endphp
                @if($hasLimit)
                    @if(count($uniqueLimits) === 1)
                        {{ __('Real accounts will be auto-approved for the first :limit account(s). After reaching :limit account(s), additional accounts will require admin approval.', ['limit' => $minLimit]) }}
                    @else
                        {{ __('Real accounts will be auto-approved until you reach the account limit (varies by account type). After reaching the limit, accounts will require admin approval.') }}
                    @endif
                @else
                    {{ __('Real accounts require admin approval. Your request will be marked as Pending until approved.') }}
                @endif
            @else
                @php
                    $limits = [];
                    foreach($schemas as $s) {
                        $limit = $s->demo_account_limit ?? 0;
                        if($limit > 0) {
                            $limits[] = $limit;
                        }
                    }
                    $hasLimit = !empty($limits);
                    $minLimit = $hasLimit ? min($limits) : 0;
                    $maxLimit = $hasLimit ? max($limits) : 0;
                    $uniqueLimits = array_unique($limits);
                @endphp
                @if($hasLimit)
                    @if(count($uniqueLimits) === 1)
                        {{ __('Demo accounts will be auto-approved for the first :limit account(s). After reaching :limit account(s), additional accounts will require admin approval.', ['limit' => $minLimit]) }}
                    @else
                        {{ __('Demo accounts will be auto-approved until you reach the account limit (varies by account type). After reaching the limit, accounts will require admin approval.') }}
                    @endif
                @else
                    {{ __('Demo accounts require admin approval. Your request will be marked as Pending until approved.') }}
                @endif
            @endif
        </span>
    </div>
    </div>
@endif
<div class="grid xl:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 mb-10">
    @foreach($schemas as $schema)
    <div class="card relative border dark:border-slate-700">
        <div class="card-body h-full flex flex-col p-6">
            <div class="">
                <div class="flex items-center justify-between mb-1">
                    <h4>{{ $schema->title }}</h4>
                    @if($schema->badge)
                        <p class="badge badge-primary capitalize">
                            {{$schema->badge}}
                        </p>
                    @endif
                </div>
                {{-- @php
                    $countries = json_decode($schema->country, true);
                @endphp
                <p class="text-sm text-success mb-2">
                    {{ __('Available in countries: ') }}
                    @if(!empty($countries) && is_array($countries))
                        {{ implode(', ', $countries) }}
                    @elseif($schema->is_global)
                        {{ __('Global') }}
                    @endif
                </p> --}}
                <p class="text-slate-900 dark:text-white text-sm min-h-[3.75rem]">{!! $schema->desc !!}</p>
            </div>
            <div class="h-full space-y-3 bg-slate-50 dark:bg-dark rounded p-3 mb-5">
                <div class="flex items-center">
                    <span class="flex-1 text-sm font-medium text-slate-600 dark:text-slate-100">
                        {{ __('Initial Deposit') }}
                    </span>
                    <span class="flex-1 text-right">
                        <span class="bg-opacity-20 capitalize font-semibold text-sm leading-4 px-[10px] py-[2px] rounded-full inline-block bg-success text-success">
                            {{ isset($schema->first_min_deposit) ? $currencySymbol . $schema->first_min_deposit : $currencySymbol . 0 }}
                        </span>
                    </span>
                </div>
                <p class="text-sm font-medium text-slate-600 dark:text-slate-100">
                    {{ __('Key Features') }}
                </p>
                <ul class="space-y-2">
                    @if($schema->spread)
                        @php
                            $spreads = explode(',', $schema->spread);
                        @endphp

                        @foreach($spreads as $spread)
                            <li class="text-sm text-slate-900 dark:text-slate-100 flex space-x-2 items-center rtl:space-x-reverse pl-2">
                                <span class="h-[6px] w-[6px] bg-slate-900 dark:bg-slate-400 rounded-full inline-block"></span>
                                <span>{{ trim($spread) }}</span>
                            </li>
                        @endforeach
                    @else
                        <span class="text-sm text-slate-900 dark:text-slate-100 pl-2">{{ __('NA') }}</span>
                    @endif
                </ul>
            </div>
            <a href="{{ route('user.schema.preview', the_hash($schema->id)) }}@if(request('type'))?type={{ request('type') }}@endif" class="btn loaderBtn inline-flex justify-center btn-primary w-full mt-auto">
                {{ __('Create Account') }}
            </a>
        </div>
    </div>
    @endforeach
</div>

<h4 class="font-medium text-xl capitalize text-slate-900 mb-5">
    {{ __('Download Platform') }}
</h4>
<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    @foreach($platformLinks as $platformLink)
        <div class="card p-4">
            <div class="flex items-center space-x-2 rtl:space-x-reverse">
                <div class="flex-1 flex items-center space-x-2 rtl:space-x-reverse">
                    <div class="flex-none">
                        @switch($platformLink->os)
                            @case('window')
                            <iconify-icon class="text-2xl dark:text-slate-100" icon="material-symbols:window-sharp"></iconify-icon>
                            @break
                            @case('mac')
                            <iconify-icon class="text-2xl dark:text-slate-100" icon="fa6-brands:app-store-ios"></iconify-icon>
                            @break
                            @case('android')
                            <iconify-icon class="text-2xl dark:text-slate-100" icon="ion:logo-google-playstore"></iconify-icon>
                            @break
                            @case('ios')
                            <iconify-icon class="text-2xl dark:text-slate-100" icon="fa6-brands:apple"></iconify-icon>
                            @break
                            @case('android_apk')
                            <iconify-icon class="text-2xl dark:text-slate-100" icon="material-symbols:android"></iconify-icon>
                            @break
                            @case('web')
                            <iconify-icon class="text-2xl dark:text-slate-100" icon="mdi:web"></iconify-icon>
                            @break
                            @default()
                            <iconify-icon class="text-2xl dark:text-slate-100" icon="lucide:app-window"></iconify-icon>
                        @endswitch
                    </div>
                    <div class="flex-1">
                        <span class="block text-slate-600 text-sm font-semibold dark:text-slate-100">
                            {{ $platformLink->title }}
                        </span>
                        <span class="block font-normal text-xs text-slate-500 dark:text-slate-200">
                            {{ __('for') . ' ' . $platformLink->os }}
                        </span>
                    </div>
                </div>
                <div class="flex-none">
                    <a href="{{ $platformLink->link }}" class="inline-flex items-center text-sm dark:text-slate-100" target="_blank">
                        <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon>
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
