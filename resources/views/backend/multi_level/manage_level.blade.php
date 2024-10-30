@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Levels') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="card p-4 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
            <li class="nav-item">
                <a href="javascript:;" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 active">
                    {{ __('Metatrader') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="nav-link block font-medium font-Inter text-xs leading-tight capitalize rounded-md px-5 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300">
                    {{ __('X9 Trader') }}
                </a>
            </li>
        </ul>
    </div>
    <div class="grid md:grid-cols-3 grid-cols-1 gap-5">
        @foreach($schemas as $schema)
            <a href="{{route('admin.multi-level.view',$schema->id)}}" class="card h-full">
                <div class="card-header noborder">
                    <img src="{{ asset($schema->icon) }}" alt="{{ $schema->title }}" class="h-8">
                    <div @class([
                        'badge bg-opacity-30 capitalize', // common classes
                        'bg-success text-success' => $schema->status,
                        'bg-danger text-danger' => !$schema->status
                        ])>
                        {{ $schema->status ? 'Active' : 'Deactivated' }}
                    </div>
                </div>
                <div class="card-body p-6 pt-3">
                    <h4 class="text-base font-medium dark:text-white mb-3">{{ $schema->title }}</h4>
                    <ul class="space-y-3">
                        <li class="flex items-center justify-between text-sm">
                            <span class="text-slate-400 mr-1">{{ __('Leverage') }}</span>
                            <span class="capitalize">{{ $schema->leverage }}</span>
                        </li>
                        <li class="flex items-center justify-between text-sm">
                            <span class="text-slate-400 mr-1">{{ __('Country') }}</span>
                            <span>
                                @if( null != $schema->country) {{ implode(', ', json_decode($schema->country,true)) }} @endif
                            </span>
                        </li>
                        <li class="flex items-center justify-between text-sm">
                            <span class="text-slate-400 mr-1">{{ __('Tags') }}</span>
                            <span>
                                @if( null != $schema->tags) {{ implode(', ', json_decode($schema->tags,true)) }} @endif
                            </span>
                        </li>
                    </ul>
                </div>
            </a>
        @endforeach
    </div>

@endsection
