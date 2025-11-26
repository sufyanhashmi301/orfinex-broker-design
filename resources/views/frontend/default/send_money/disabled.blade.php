@extends('frontend::layouts.user')
@section('title')
    {{ __('Send Money Disabled') }}
@endsection
@section('content')
    <div class="min-h-[60vh] flex items-center justify-center">
        <div class="max-w-2xl mx-auto text-center px-4">
            <div class="card">
                <div class="card-body p-8">
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-danger-100 dark:bg-danger-900/20 mb-4">
                            <svg class="w-10 h-10 text-danger-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-semibold text-slate-800 dark:text-slate-200 mb-3">
                            {{ __('Send Money Disabled') }}
                        </h2>
                        <p class="text-slate-600 dark:text-slate-400 mb-6">
                            {{ $message ?? __('Send money functionality is currently disabled. Please contact our support team for assistance.') }}
                        </p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('home') }}" class="btn btn-dark dark:bg-slate-800">
                            {{ __('Return to Home') }}
                        </a>
                        @if(setting('support_email', 'global'))
                            <a href="mailto:{{ setting('support_email', 'global') }}" class="btn btn-primary">
                                {{ __('Contact Support') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

