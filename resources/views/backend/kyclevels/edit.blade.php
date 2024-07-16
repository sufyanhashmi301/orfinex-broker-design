@extends('backend.layouts.app')
@section('title')
{{ __('Update KYC Level') }}
@endsection
@section('content')
<div class="transition-all duration-150 rtl:mr-[200px] p-6">
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Update KYC Level') }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ url()->previous() }}" class="inline-flex items-center justify-center text-success-500">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                {{ __('Back') }}
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <form action="#" method="post" class="space-y-4">
                @method('PUT')
                @csrf
                <div class="input-area">
                    <label for="" class="form-label">{{ __('Name') }}</label>
                    <input type="text" class="form-control" required="" name="name" value="{{ $kycLevel->name }}"/>
                </div>
                <div class="card border">
                    <div class="card-header noborder">
                        <h3 class="card-title mb-0">{{ __('All Settings') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="role-cat-items">
                            <div class="accordion">
                                @php
                                    // Group settings by kyclevel and unique_code
                                    $groupedSettings = $kycLevelSettings->groupBy(['kyclevel.name', 'unique_code']);
                                @endphp
                                @foreach($groupedSettings as $kyclevelName => $settingsByCode)
                                    @php
                                        $isExpanded = true; // Expand the first accordion item
                                    @endphp
                                    <div class="accordion-item">
                                        <h2 class="accordion-header text-lg" id="heading{{ $kyclevelName }}">
                                            <button
                                                class="accordion-button @if(!$isExpanded) collapsed @endif flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-x-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3"
                                                type="button" data-bs-toggle="collapse"
                                                data-bs-target="#{{ str_replace(' ', '', $kyclevelName) }}"
                                                aria-expanded="true"
                                                aria-controls="{{ str_replace(' ', '', $kyclevelName) }}">
                                                <span class="flex items-center">
                                                    <span class="icon h-5 w-5 rounded-full bg-slate-100 flex items-center justify-center ltr:mr-2 rtl:ml-2">
                                                        <iconify-icon class="text-sm" icon="lucide:check"></iconify-icon>
                                                    </span>
                                                    {{ $kyclevelName }}
                                                </span>
                                                <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                                                </svg>
                                            </button>
                                        </h2>
                                        <div id="{{ str_replace(' ', '', $kyclevelName) }}"
                                             class="accordion-collapse collapse @if($isExpanded) show @endif p-5 border border-b-0 border-x-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900"
                                             aria-labelledby="heading{{ $kyclevelName }}">
                                            <div class="accordion-body">
                                                @foreach($settingsByCode as $uniqueCode => $settings)
                                                    <div class="mb-4">
                                                        <h4 class="text-lg font-medium mb-2">{{ $uniqueCode }}</h4>
                                                        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
                                                            @foreach($settings as $setting)
                                                                <div class="flex items-center justify-between p-5 border border-slate-100 dark:border-slate-700">
                                                                    <label class="switch-label text-sm" for="{{ $setting->title }}">
                                                                        {{ ucwords(str_replace('-', ' ', $setting->title)) }}
                                                                    </label>
                                                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                                        <input type="checkbox" class="sr-only peer" id="{{ $setting->title }}"
                                                                        name="permission[]"
                                                                        value="{{ $setting->id }}">
                                            
                                                                        <span class="w-9 h-5 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[3.5px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $isExpanded = false; // Collapse subsequent accordion items
                                    @endphp
                                @endforeach
                            </div>
                        </div>
                        <div class="text-right p-5">
                            <button class="btn btn-dark inline-flex items-center justify-center" type="submit">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
