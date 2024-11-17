@extends('backend.setting.system.index')
@section('title')
    {{ __('Application Details') }}
@endsection
@section('system-content')
    <div class="card bg-dark p-6 mb-6">
        <h4 class="card-title">{{ setting('site_title', 'global') }}</h4>
        <p class="card-text my-2">{{ __('Enterprise CRM Platform') }}</p>
        <ul class="flex items-center gap-3">
            <li class="badge badge-secondary">
                {{ __('Version 3.0') }}
            </li>
            <li class="badge badge-secondary">
                {{ __('Enterprise License') }}
            </li>
        </ul>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <ul class="divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($applicationInfo as $key => $value)
                    <li class="block py-[8px]">
                        <div class="flex space-x-2 rtl:space-x-reverse">
                            <div class="flex-1 flex space-x-2 rtl:space-x-reverse">
                                <span class="block text-slate-600 text-sm dark:text-slate-300">
                                    {{  $key }}
                                </span>
                            </div>
                            <div class="flex-none">
                                <span class="badge bg-slate-900 text-white capitalize">
                                    {{ $value }}
                                </span>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
