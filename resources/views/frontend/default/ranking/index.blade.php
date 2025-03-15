@extends('frontend::layouts.user')
@section('title')
    {{ __('All The Badges') }}
@endsection
@section('content')
    <div class="mb-5">
        <ul class="m-0 p-0 list-none">
            <li class="inline-block relative top-[3px] text-base text-primary font-Inter ">
                <a href="{{route('user.dashboard')}}">
                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                </a>
            </li>
            <li class="inline-block relative text-sm text-primary font-Inter ">
                {{ __('Dashboard') }}
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ __('All The Badges') }}
            </li>
        </ul>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('All The Badges') }}</h3>
        </div>
        <div class="card-body p-6">
            <div class="grid grid-cols-12 justify-center gap-5">
                @foreach($rankings as $ranking)
                    <div class="lg:col-span-3 col-span-12">
                        <div class="single-badge @if(!in_array($ranking->id,$alreadyRank)) locked @endif">
                            <div class="badge">
                                <div class="img">
                                    <img src="{{ asset($ranking->icon) }}" alt="">
                                </div>
                            </div>
                            <div class="content">
                                <h3 class="title">{{ $ranking->ranking_name }}</h3>
                                <p class="description">{{ $ranking->description }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

@endsection

