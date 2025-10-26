@extends('admin.layouts.integrations')

@section('title', 'Configure JenaPay')

@section('content')
    @php
        $integration = 'jenapay';
        $isActive = gateway_status('jenapay');
    @endphp

    <!-- Page Header -->
    <div
        class="flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl dark:bg-neutral-800 dark:border-neutral-700 mb-6">
        <div class="p-4 md:p-6">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-x-4">
                    @php
                        $moduleIcon = gateway_data('jenapay', 'module_icon');
                        $isImagePath =
                            $moduleIcon && (str_starts_with($moduleIcon, '/') || str_starts_with($moduleIcon, 'http'));
                    @endphp

                    @if ($isImagePath)
                        <!-- Image Logo -->
                        <div
                            class="shrink-0 flex items-center justify-center h-20 w-48 p-3 rounded-lg bg-white dark:bg-white/10 border border-gray-200 dark:border-neutral-700/50">
                            <img src="{{ $moduleIcon }}" alt="Integration Logo"
                                class="max-h-full max-w-full object-contain dark:brightness-0 dark:invert">
                        </div>
                    @else
                        <!-- SVG Icon -->
                        <div class="shrink-0 p-3 rounded-lg bg-blue-100 dark:bg-blue-900/20">
                            @if ($moduleIcon)
                                <div class="size-8 text-blue-600 dark:text-blue-400">
                                    {!! $moduleIcon !!}
                                </div>
                            @else
                                <svg class="size-8 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="20" height="14" x="2" y="5" rx="2" />
                                    <line x1="2" x2="22" y1="10" y2="10" />
                                </svg>
                            @endif
                        </div>
                    @endif
                    <div>
                        <div class="flex items-center gap-x-3 mb-2">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Configure JenaPay</h1>
                            @if ($isActive)
                                <span
                                    class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                    Active
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-neutral-700 dark:text-neutral-300">
                                    Inactive
                                </span>
                            @endif
                        </div>
                        <p class="text-gray-600 dark:text-neutral-400">Checkout integration with support for multiple
                            payment methods and secure processing</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button"
                        class="inline-flex items-center gap-x-2 text-sm font-medium text-gray-600 hover:text-gray-800 dark:text-neutral-400 dark:hover:text-neutral-200 py-2 px-4 rounded-lg border border-gray-200 dark:border-neutral-700 hover:bg-gray-50 dark:hover:bg-neutral-800">
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M22 12h-2.48a2 2 0 0 0-1.93 1.46l-2.35 8.36a.25.25 0 0 1-.48 0L9.24 2.18a.25.25 0 0 0-.48 0l-2.35 8.36A2 2 0 0 1 4.49 12H2" />
                        </svg>
                        Test Connection
                    </button>
                    <a href="{{ route('admin.gateway.index') }}"
                        class="inline-flex items-center gap-x-2 text-sm font-medium text-gray-600 hover:text-gray-800 dark:text-neutral-400 dark:hover:text-neutral-200">
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M9 18l6-6-6-6" />
                        </svg>
                        Back to Payment Gateways
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuration Form -->
    <div
        class="flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
        <div class="p-4 md:p-6 border-b border-gray-200 dark:border-neutral-700">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">JenaPay Settings</h2>
                    <p class="text-sm text-gray-500 dark:text-neutral-400">Configure your JenaPay merchant credentials and
                        URLs</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="https://docs.jenapay.com/docs/guides/checkout_integration" target="_blank"
                        class="inline-flex items-center gap-x-2 text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14,2 14,8 20,8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                            <polyline points="10,9 9,9 8,9" />
                        </svg>
                        Documentation
                    </a>
                </div>
            </div>
        </div>

        <div class="p-4 md:p-6">
            <form method="POST" action="{{ route('admin.gateway.update', 'jenapay') }}" class="space-y-6" id="configForm"
                autocomplete="new-password" data-lpignore="true">
                @csrf
                @method('PUT')

                <!-- Merchant Key -->
                <div>
                    <label for="merchant_key" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
                        Merchant Key <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="merchant_key" name="merchant_key"
                        value="{{ old('merchant_key', gateway_data('jenapay', 'merchant_key', '')) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-800 dark:border-neutral-600 dark:text-white dark:focus:ring-blue-400 dark:focus:border-blue-400"
                        placeholder="Enter your JenaPay Merchant Key" autocomplete="off" required>
                    <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">Your JenaPay merchant key from the
                        dashboard</p>
                    @error('merchant_key')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Merchant Pass -->
                <div>
                    <label for="merchant_pass" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
                        Merchant Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="merchant_pass" name="merchant_pass"
                        value="{{ old('merchant_pass', gateway_data('jenapay', 'merchant_pass', '')) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-800 dark:border-neutral-600 dark:text-white dark:focus:ring-blue-400 dark:focus:border-blue-400"
                        placeholder="Enter your Merchant Password" autocomplete="new-password" required>
                    <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">Your JenaPay merchant password used for
                        signing requests (keep this secure)</p>
                    @error('merchant_pass')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- API URL -->
                <div>
                    <label for="api_url" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
                        API URL
                    </label>
                    <input type="url" id="api_url" name="api_url"
                        value="{{ old('api_url', gateway_data('jenapay', 'api_url', 'https://checkout.jenapay.com')) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-800 dark:border-neutral-600 dark:text-white dark:focus:ring-blue-400 dark:focus:border-blue-400"
                        placeholder="https://checkout.jenapay.com" autocomplete="off">
                    <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">Base URL for JenaPay API (leave default
                        unless specified)</p>
                    @error('api_url')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- URL Information -->
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">URL Configuration</h3>
                    <div class="space-y-2 text-sm text-blue-700 dark:text-blue-300">
                        <div><strong>Success URL:</strong> <code
                                class="bg-blue-100 dark:bg-blue-800 px-2 py-1 rounded">{{ url('/status/success') }}</code>
                        </div>
                        <div><strong>Cancel URL:</strong> <code
                                class="bg-blue-100 dark:bg-blue-800 px-2 py-1 rounded">{{ url('/status/cancel') }}</code>
                        </div>
                        <div><strong>Callback URL:</strong> <code
                                class="bg-blue-100 dark:bg-blue-800 px-2 py-1 rounded">{{ url('/ipn/jenapay') }}</code>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-blue-600 dark:text-blue-400">These URLs are automatically generated and
                        configured in the system.</p>
                </div>

                <!-- Enable Integration -->
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                        {{ old('is_active', $isActive) ? 'checked' : '' }}
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-neutral-800 dark:border-neutral-600 dark:focus:ring-blue-400">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-neutral-300">
                        Enable JenaPay integration
                    </label>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-neutral-700">
                    <a href="{{ route('admin.gateway.index') }}"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="m12 19-7-7 7-7" />
                            <path d="M19 12H5" />
                        </svg>
                        Cancel
                    </a>
                    <button type="submit"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:ring-2 focus:ring-blue-600 focus:border-blue-600 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-400">
                        <i data-lucide="save" class="size-4"></i>
                        Save Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
