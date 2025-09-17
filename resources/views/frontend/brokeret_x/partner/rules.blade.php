@extends('frontend::layouts.partner')
@section('title')
    {{ __('IB Distribution Rules') }}
@endsection
@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90">
            @yield('title')
        </h2>

        <div class="flex-1 flex flex-col sm:flex-row sm:justify-end sm:items-center gap-3">
            <div class="input-area relative">
                <select id="rule-filter" class="dark:bg-dark-900 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                    <option value="">{{ __('All Rules') }}</option>
                    <option value="active">{{ __('Active Rules') }}</option>
                    <option value="inactive">{{ __('Inactive Rules') }}</option>
                </select>
            </div>
        </div>
    </div>
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div id="schemes">
            @include('frontend::partner.include.__scheme_rules')
        </div>
    </div>

    @include('frontend::partner.include.__share_modal')

@endsection
@section('script')
    <script>
        
    </script>
@endsection
