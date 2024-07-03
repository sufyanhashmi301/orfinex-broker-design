@extends('backend.layouts.app')
@section('title')
    {{ __('Application Details') }}
@endsection
@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('Application Details') }}</h4>
            </div>
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
    </div>
@endsection