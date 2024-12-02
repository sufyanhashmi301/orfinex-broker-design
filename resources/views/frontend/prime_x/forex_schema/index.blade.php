@extends('frontend::layouts.user')
@section('title')
    {{ __('Open New Account') }}
@endsection
@section('content')
<div class="flex justify-between flex-wrap items-center mb-5">
    <h4 class="font-medium text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
        {{ __('Open New Account') }}
    </h4>
    <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
        <a href="{{ route('user.forex-account-logs') }}" class="btn btn-primary loaderBtn inline-flex items-center justify-center">
            {{ __('My Accounts') }}
        </a>
        {{-- <a href="{{ route('user.offers') }}" class="btn btn-sm btn-primary">{{ __('Get Bonus') }}</a> --}}
    </div>
</div>
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
                <p class="text-sm text-success mb-2">
                    {{ __('Available in countries: ') }} {{ implode(', ', json_decode($schema->country, true)) }}
                </p>
                <p class="text-slate-900 dark:text-white text-sm min-h-[3.75rem]">{{ $schema->desc }}</p>
            </div>
            <div class="bg-slate-50 dark:bg-dark rounded px-3 mb-5">
                <div class="flex items-center py-3">
                    <span class="flex-1 text-sm font-medium text-slate-600 dark:text-slate-300">
                        {{ __('Initial Deposit') }}
                    </span>
                    <span class="flex-1 text-right">
                        <span class="bg-opacity-20 capitalize font-semibold text-sm leading-4 px-[10px] py-[2px] rounded-full inline-block bg-success text-success">
                            {{ isset($schema->first_min_deposit) ? $currencySymbol . $schema->first_min_deposit : $currencySymbol . 0 }}
                        </span>
                    </span>
                </div>
                <p class="text-sm font-medium text-slate-600 dark:text-slate-300 mb-3">
                    {{ __('Key Features') }}
                </p>
                <ul class="space-y-2">
                    @if($schema->spread)
                        @php
                            $spreads = explode(',', $schema->spread);
                        @endphp

                        @foreach($spreads as $spread)
                            <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse pl-2">
                                <span class="h-[6px] w-[6px] bg-slate-900 dark:bg-slate-400 rounded-full inline-block"></span>
                                <span>{{ trim($spread) }}</span>
                            </li>
                        @endforeach
                    @else
                        <span class="text-sm text-slate-900 dark:text-slate-300 pl-2">{{ __('NA') }}</span>
                    @endif
                </ul>
            </div>
            <a href="{{ route('user.schema.preview', $schema->id) }}" class="btn loaderBtn inline-flex justify-center btn-primary w-full mt-auto">
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
                            <iconify-icon class="text-2xl dark:text-slate-300" icon="material-symbols:window-sharp"></iconify-icon>
                            @break
                            @case('mac')
                            <iconify-icon class="text-2xl dark:text-slate-300" icon="fa6-brands:app-store-ios"></iconify-icon>
                            @break
                            @case('android')
                            <iconify-icon class="text-2xl dark:text-slate-300" icon="ion:logo-google-playstore"></iconify-icon>
                            @break
                            @case('ios')
                            <iconify-icon class="text-2xl dark:text-slate-300" icon="fa6-brands:apple"></iconify-icon>
                            @break
                            @case('android_apk')
                            <iconify-icon class="text-2xl dark:text-slate-300" icon="material-symbols:android"></iconify-icon>
                            @break
                            @case('web')
                            <iconify-icon class="text-2xl dark:text-slate-300" icon="mdi:web"></iconify-icon>
                            @break
                            @default()
                            <iconify-icon class="text-2xl dark:text-slate-300" icon="lucide:app-window"></iconify-icon>
                        @endswitch
                    </div>
                    <div class="flex-1">
                        <span class="block text-slate-600 text-sm font-semibold dark:text-slate-300">
                            {{ $platformLink->title }}
                        </span>
                        <span class="block font-normal text-xs text-slate-500">
                            {{ __('for') . ' ' . $platformLink->os }}
                        </span>
                    </div>
                </div>
                <div class="flex-none">
                    <a href="{{ $platformLink->link }}" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                        <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon>
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
