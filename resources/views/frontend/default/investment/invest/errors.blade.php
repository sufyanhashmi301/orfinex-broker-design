@extends('frontend::layouts.user')

@section('title', __("Invest & Earn"))

@section('content')

<div class="mb-5">
    <ul class="m-0 p-0 list-none">
        <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter ">
            <a href="{{route('user.dashboard')}}">
                <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
            </a>
        </li>
        <li class="inline-block relative text-sm text-primary-500 font-Inter ">
            {{ __('Pricing') }}
            <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
        </li>
        <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
            {{ __('Plans') }}
        </li>
    </ul>
</div>

<div class="grid grid-cols-12 gap-5">
    <div class="col-span-12">
        <div class="max-w-xl text-center py-10 mx-auto space-y-5">
            <div class="w-20 h-20 bg-danger-500 text-white rounded-full inline-flex items-center justify-center">
                <iconify-icon icon="fa6-solid:box-open" class="text-5xl"></iconify-icon>
            </div>

            @if(isset($title) && $title)
                <h4 class="text-3xl text-slate-900 dark:text-white">{{ $title }}</h4>
            @endif

            @if(isset($notice) && (data_get($notice, 'caption') || data_get($notice, 'note')))
                <div class="nk-pps-text {{ the_data($notice, 'class', 'md') }}{{ (!$title) ? ' mt-5' : '' }}">
                    @if(data_get($notice, 'caption'))
                        <p class="text-slate-600 dark:text-slate-100">{{ data_get($notice, 'caption') }}</p>
                    @endif
                    @if(data_get($notice, 'note'))
                        <p class="sub-text-sm">{{ data_get($notice, 'note') }}</p>
                    @endif
                </div>
            @endif

            @if((isset($button) && $button) || (isset($link) && $link))
                <div class="nk-pps-action">
                    <ul class="btn-group-vertical align-center gy-3">
                        @if(data_get($button, 'text') && data_get($button, 'url'))
                            <li>
                                <a href="{{ data_get($button, 'url') }}" class="btn btn-lg inline-flex items-center btn-mw {{ the_data($button, 'class', 'btn-dark') }}">
                                    {{ data_get($button, 'text') }}
                                </a>
                            </li>
                        @endif
                        @if(data_get($link, 'text') && data_get($link, 'url'))
                            <li>
                                <a href="{{ data_get($link, 'url') }}" class="link {{ the_data($link, 'class', 'link-primary') }}">
                                    {{ data_get($link, 'text') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif

            @if(isset($help) && $help)
                <div class="nk-pps-notes text-center">
                    {!! $help !!}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
