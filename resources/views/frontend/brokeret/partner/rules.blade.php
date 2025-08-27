@extends('frontend::layouts.partner')
@section('title')
    {{ __('IB Distribution Rules') }}
@endsection
@section('content')
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center justify-between border-b border-gray-200 px-4 py-5 xl:px-6 xl:py-6 dark:border-gray-800">
            <div class="flex-shrink-0">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    {{ __('IB Distribution Rules') }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Manage your introducing broker distribution rules and rebate shares') }}
                </p>
            </div>
            <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-3">
                <div class="input-area relative">
                    <select id="rule-filter" class="dark:bg-dark-900 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800">
                        <option value="">{{ __('All Rules') }}</option>
                        <option value="active">{{ __('Active Rules') }}</option>
                        <option value="inactive">{{ __('Inactive Rules') }}</option>
                    </select>
                </div>
            </div>
        </div>

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
