@extends('frontend::user.setting.index')
@section('title')
    {{ __('Legal Agreements') }}
@endsection
@section('settings-content')
    <div class="card">
        <div class="card-body p-6">
            <div class="mb-4">
                <h4 class="card-title mb-2">{{ __('Legal Agreements') }}</h4>
                <p class="block font-normal text-sm text-slate-500">
                    {{ __("Stay informed and compliant; review all legal agreements linked to your profile.") }}
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                @foreach($documentLinks as $documentLink)
                    <div class="border border-slate-100 dark:border-slate-700 rounded p-4">
                        <div class="h-12 w-12 flex flex-col items-center justify-center rounded bg-slate-50 dark:bg-body text-3xl mb-4">
                            @switch($documentLink->slug)
                                @case('aml_policy')
                                <iconify-icon class="dark:text-white" icon="solar:shield-minimalistic-linear"></iconify-icon>
                                @break
                                @case('client_agreement')
                                <iconify-icon class="dark:text-white" icon="icon-park-outline:agreement"></iconify-icon>
                                @break
                                @case('complaints_handling_policy')
                                <iconify-icon class="dark:text-white" icon="bx:support"></iconify-icon>
                                @break
                                @case('cookies_policy')
                                <iconify-icon class="dark:text-white" icon="solar:shield-minimalistic-linear"></iconify-icon>
                                @break
                                @case('ib_partner_agreement')
                                <iconify-icon class="dark:text-white" icon="mdi:family-tree"></iconify-icon>
                                @break
                                @case('order_execution_policy')
                                <iconify-icon class="dark:text-white" icon="fluent:document-copy-20-regular"></iconify-icon>
                                @break
                                @case('privacy_policy')
                                <iconify-icon class="dark:text-white" icon="solar:clipboard-linear"></iconify-icon>
                                @break
                                @case('risk_disclosure')
                                <iconify-icon class="dark:text-white" icon="tabler:info-hexagon"></iconify-icon>
                                @break
                                @case('us_clients_policy')
                                <iconify-icon class="dark:text-white" icon="la:flag-usa"></iconify-icon>
                                @break
                                @default()
                                    <iconify-icon icon="lucide:file-text"></iconify-icon>
                            @endswitch
                        </div>
                        <span class="block text-base text-slate-600 font-medium dark:text-white mb-1">
                            {{ $documentLink->title }}
                            <br>
                            <span class="text-slate-400 text-sm font-normal">{{ __('PDF') }}</span>
                        </span>
                        <div class="mt-5">
                            <a href="{{ $documentLink->link }}" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                                <span class="mr-1">{{ __('Read Now') }}</span>
                                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
@endsection
