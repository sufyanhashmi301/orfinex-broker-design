@extends('frontend::user.setting.index')
@section('title')
    {{ __('Settings') }}
@endsection
@section('settings-content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
        <div>
            <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90 mb-1">
                {{ __('Legal Agreements') }}
            </h2>
            <p class="text-gray-800 text-theme-sm dark:text-white/90">
                {{ __('Stay informed and compliant; review all legal agreements linked to your profile.') }}
            </p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
        @forelse($documentLinks as $documentLink)
            <div class="flex items-center gap-5 rounded-xl border border-gray-200 p-3 pr-5 dark:border-gray-800">
                <div class="inline-flex h-13 w-13 shrink-0 items-center justify-center rounded-lg border border-gray-200 dark:border-gray-800 text-gray-800 dark:text-white/90">
                    @switch($documentLink->slug)
                        @case('aml_policy')
                            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" fill="none">
                                <path fill="none" stroke="currentColor" stroke-width="1.5" d="M3 10.417c0-3.198 0-4.797.378-5.335c.377-.537 1.88-1.052 4.887-2.081l.573-.196C10.405 2.268 11.188 2 12 2s1.595.268 3.162.805l.573.196c3.007 1.029 4.51 1.544 4.887 2.081C21 5.62 21 7.22 21 10.417v1.574c0 5.638-4.239 8.375-6.899 9.536C13.38 21.842 13.02 22 12 22s-1.38-.158-2.101-.473C7.239 20.365 3 17.63 3 11.991z"/>
                            </svg>
                        @break
                        @case('client_agreement')
                            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 48 48" fill="none">
                                <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="4">
                                    <rect width="32" height="40" x="8" y="4" stroke-linejoin="round" rx="2"/>
                                    <path stroke-linejoin="round" d="M16 4h9v16l-4.5-4l-4.5 4z"/>
                                    <path d="M16 28h10m-10 6h16"/>
                                </g>
                            </svg>
                        @break
                        @case('complaints_handling_policy')
                            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" fill="none">
                                <path fill="currentColor" d="M12 2C6.486 2 2 6.486 2 12v4.143C2 17.167 2.897 18 4 18h1a1 1 0 0 0 1-1v-5.143a1 1 0 0 0-1-1h-.908C4.648 6.987 7.978 4 12 4s7.352 2.987 7.908 6.857H19a1 1 0 0 0-1 1V18c0 1.103-.897 2-2 2h-2v-1h-4v3h6c2.206 0 4-1.794 4-4c1.103 0 2-.833 2-1.857V12c0-5.514-4.486-10-10-10"/>
                            </svg>
                        @break
                        @case('cookies_policy')
                            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" fill="none">
                                <path fill="none" stroke="currentColor" stroke-width="1.5" d="M3 10.417c0-3.198 0-4.797.378-5.335c.377-.537 1.88-1.052 4.887-2.081l.573-.196C10.405 2.268 11.188 2 12 2s1.595.268 3.162.805l.573.196c3.007 1.029 4.51 1.544 4.887 2.081C21 5.62 21 7.22 21 10.417v1.574c0 5.638-4.239 8.375-6.899 9.536C13.38 21.842 13.02 22 12 22s-1.38-.158-2.101-.473C7.239 20.365 3 17.63 3 11.991z"/>
                            </svg>
                        @break
                        @case('ib_partner_agreement')
                            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" fill="none">
                                <path fill="currentColor" d="M12 1a2.5 2.5 0 0 0-2.5 2.5A2.5 2.5 0 0 0 11 5.79V7H7a2 2 0 0 0-2 2v.71A2.5 2.5 0 0 0 3.5 12A2.5 2.5 0 0 0 5 14.29V15H4a2 2 0 0 0-2 2v1.21A2.5 2.5 0 0 0 .5 20.5A2.5 2.5 0 0 0 3 23a2.5 2.5 0 0 0 2.5-2.5A2.5 2.5 0 0 0 4 18.21V17h4v1.21a2.5 2.5 0 0 0-1.5 2.29A2.5 2.5 0 0 0 9 23a2.5 2.5 0 0 0 2.5-2.5a2.5 2.5 0 0 0-1.5-2.29V17a2 2 0 0 0-2-2H7v-.71A2.5 2.5 0 0 0 8.5 12A2.5 2.5 0 0 0 7 9.71V9h10v.71A2.5 2.5 0 0 0 15.5 12a2.5 2.5 0 0 0 1.5 2.29V15h-1a2 2 0 0 0-2 2v1.21a2.5 2.5 0 0 0-1.5 2.29A2.5 2.5 0 0 0 15 23a2.5 2.5 0 0 0 2.5-2.5a2.5 2.5 0 0 0-1.5-2.29V17h4v1.21a2.5 2.5 0 0 0-1.5 2.29A2.5 2.5 0 0 0 21 23a2.5 2.5 0 0 0 2.5-2.5a2.5 2.5 0 0 0-1.5-2.29V17a2 2 0 0 0-2-2h-1v-.71A2.5 2.5 0 0 0 20.5 12A2.5 2.5 0 0 0 19 9.71V9a2 2 0 0 0-2-2h-4V5.79a2.5 2.5 0 0 0 1.5-2.29A2.5 2.5 0 0 0 12 1m0 1.5a1 1 0 0 1 1 1a1 1 0 0 1-1 1a1 1 0 0 1-1-1a1 1 0 0 1 1-1M6 11a1 1 0 0 1 1 1a1 1 0 0 1-1 1a1 1 0 0 1-1-1a1 1 0 0 1 1-1m12 0a1 1 0 0 1 1 1a1 1 0 0 1-1 1a1 1 0 0 1-1-1a1 1 0 0 1 1-1M3 19.5a1 1 0 0 1 1 1a1 1 0 0 1-1 1a1 1 0 0 1-1-1a1 1 0 0 1 1-1m6 0a1 1 0 0 1 1 1a1 1 0 0 1-1 1a1 1 0 0 1-1-1a1 1 0 0 1 1-1m6 0a1 1 0 0 1 1 1a1 1 0 0 1-1 1a1 1 0 0 1-1-1a1 1 0 0 1 1-1m6 0a1 1 0 0 1 1 1a1 1 0 0 1-1 1a1 1 0 0 1-1-1a1 1 0 0 1 1-1"/>
                            </svg>
                        @break
                        @case('order_execution_policy')
                            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" fill="none">
                                <g fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M16 4.002c2.175.012 3.353.109 4.121.877C21 5.758 21 7.172 21 10v6c0 2.829 0 4.243-.879 5.122C19.243 22 17.828 22 15 22H9c-2.828 0-4.243 0-5.121-.878C3 20.242 3 18.829 3 16v-6c0-2.828 0-4.242.879-5.121c.768-.768 1.946-.865 4.121-.877"/>
                                    <path stroke-linecap="round" d="M8 14h8m-9-3.5h10m-8 7h6"/>
                                    <path d="M8 3.5A1.5 1.5 0 0 1 9.5 2h5A1.5 1.5 0 0 1 16 3.5v1A1.5 1.5 0 0 1 14.5 6h-5A1.5 1.5 0 0 1 8 4.5z"/>
                                </g>
                            </svg>
                        @break
                        @case('privacy_policy')
                            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" fill="none">
                                <g fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M16 4.002c2.175.012 3.353.109 4.121.877C21 5.758 21 7.172 21 10v6c0 2.829 0 4.243-.879 5.122C19.243 22 17.828 22 15 22H9c-2.828 0-4.243 0-5.121-.878C3 20.242 3 18.829 3 16v-6c0-2.828 0-4.242.879-5.121c.768-.768 1.946-.865 4.121-.877"/>
                                    <path stroke-linecap="round" d="M8 14h8m-9-3.5h10m-8 7h6"/>
                                    <path d="M8 3.5A1.5 1.5 0 0 1 9.5 2h5A1.5 1.5 0 0 1 16 3.5v1A1.5 1.5 0 0 1 14.5 6h-5A1.5 1.5 0 0 1 8 4.5z"/>
                                </g>
                            </svg>
                        @break
                        @case('risk_disclosure')
                            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" fill="none">
                                <path fill="none" stroke="currentColor" stroke-width="1.5" d="M12 2C6.486 2 2 6.486 2 12v4.143C2 17.167 2.897 18 4 18h1a1 1 0 0 0 1-1v-5.143a1 1 0 0 0-1-1h-.908C4.648 6.987 7.978 4 12 4s7.352 2.987 7.908 6.857H19a1 1 0 0 0-1 1V18c0 1.103-.897 2-2 2h-2v-1h-4v3h6c2.206 0 4-1.794 4-4c1.103 0 2-.833 2-1.857V12c0-5.514-4.486-10-10-10"/>
                            </svg>
                        @break
                        @case('us_clients_policy')
                            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 32 32" fill="none">
                                <path fill="currentColor" d="M3 7v10h26v-2H17v-2h12v-2H17V9h12V7zm2 1a1 1 0 1 1 0 2a1 1 0 0 1 0-2m4 0a1 1 0 1 1 0 2a1 1 0 0 1 0-2m4 0a1 1 0 1 1 0 2a1 1 0 0 1 0-2m-6 3a1 1 0 1 1 0 2a1 1 0 0 1 0-2m4 0a1 1 0 1 1 0 2a1 1 0 0 1 0-2m4 0a1 1 0 1 1 0 2a1 1 0 0 1 0-2M5 14a1 1 0 1 1 0 2a1 1 0 0 1 0-2m4 0a1 1 0 1 1 0 2a1 1 0 0 1 0-2m4 0a1 1 0 1 1 0 2a1 1 0 0 1 0-2M3 19v2h26v-2zm0 4v2h26v-2z"/>
                            </svg>
                        @break
                        @default()
                            <svg xmlns="http://www.w3.org/2000/svg" width="2em" height="2em" viewBox="0 0 24 24" fill="none">
                                <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/>
                                    <path d="M14 2v4a2 2 0 0 0 2 2h4M10 9H8m8 4H8m8 4H8"/>
                                </g>
                            </svg>
                    @endswitch
                </div>
                <div>
                    <h3 class="mb-2 text-gray-800 dark:text-white/90">
                        {{ $documentLink->title }}
                    </h3>
                    <p class="text-sm font-normal text-gray-400 dark:text-gray-400">
                        {{ __('PDF') }}
                    </p>
                </div>
                <div class="flex-none ml-auto">
                    <a href="{{ $documentLink->link }}" class="inline-flex items-center text-sm dark:text-slate-300" target="_blank">
                        <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="lucide:chevron-right"></iconify-icon>
                        <i data-lucide="chevron-right" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>
        @empty
        <div class="col-span-full py-10 px-10">
            <div class="flex items-center justify-center flex-col gap-3">
                <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M26 19.875V30.9167" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M25.988 37.5417H26.0075" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="text-theme-sm text-center text-gray-600 dark:text-gray-100 mb-3">
                    {{ __('You don\'t have any Real account.') }}
                </p>
            </div>
        </div>
        @endforelse
    </div>
@endsection
