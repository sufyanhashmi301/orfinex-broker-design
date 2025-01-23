@extends('backend.setting.promotion.index')
@section('title')
    {{ __('Banners Settings') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>

        <a href="javascript:void(0)" data-bs-target="#new-banner-modal" data-bs-toggle="modal" class="btn btn-primary" >
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" style="position: relative; top: 4px" icon="lucide:plus"></iconify-icon>
            {{ __('Create New') }} 
        </a>
    </div>
    <div class="card p-4 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
            <li class="nav-item">
                <a href="javascript:;" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 active">
                    {{ __('User Dashboard') }}
                </a>
            </li>

            <li class="nav-item">
                <a href="javascript:void(0);" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300">
                    {{ __('Coming Soon') }}
                </a>
            </li>


        </ul>
    </div>
    @if (count($banners) > 0)
    <div class="grid grid-cols-3 gap-5">
        @foreach($banners as $banner)
            <div class="lg:col-span-1 col-span-3">
                <div class="h-full rounded transition-all duration-100 shadow-none bg-slate-200 dark:bg-slate-700">
                    <form action="{{ route('admin.banner.update', ['id' => $banner->id]) }}" method="post">
                        @csrf
                        <div class="relative flex justify-between items-center bg-white dark:bg-slate-800 rounded shadow-base px-6 py-5">
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-[2px] bg-primary"></span>
                            <h3 class="text-lg text-slate-900 dark:text-white font-medium capitalize">
                                {{ $banner->title }}
                            </h3>
                            <div class="form-switch ps-0" style="line-height: 0;">
                                <input type="hidden" value="disabled" name="status">
                                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-switch">
                                    <input type="checkbox" name="status" value="enabled" class="sr-only peer" {{ $banner->status == 'enabled' ? 'checked' : '' }}>
                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                </label>
                            </div>
                        </div>
                        <div class="px-2 py-4 h-full space-y-4 rounded-bl rounded-br">
                            <input type="hidden" name="type" value="user_dashboard">
                            <!-- BEGIN: Cards -->
                            <div class="card rounded-md bg-white dark:bg-slate-800 shadow-base custom-class card-body space-y-5 p-6">
                                <div class="input-area">
                                    <label for="" class="form-label">{{ __('Title') }}</label>
                                    <input type="text" name="title" class="form-control" value="{{ $banner->title }}">
                                </div>
                                <div class="input-area">
                                    <label for="" class="form-label">{{ __('Subtitle') }}</label>
                                    <input type="text" name="subtitle" class="form-control" value="{{ $banner->subtitle }}">
                                </div>
                                <div class="input-area">
                                    <label for="" class="form-label">{{ __('Description') }}</label>
                                    <textarea name="description" id="" cols="30" rows="2" class="form-control">{{ $banner->description }}</textarea>
                                </div>
                                <div class="input-area">
                                    <label for="" class="form-label" style="text-transform: none">{{ __('List') }} (Use linebreaks ↵ for mutliple rows)</label>
                                    <textarea name="list" id="" cols="30" rows="5" class="form-control">@foreach ($banner->list as $list_item){{ $list_item }}
@endforeach
                                    </textarea>
                                </div>
                                <div class="input-area">
                                    <label for="" class="form-label">{{ __('Button Text') }}</label>
                                    <input type="text" name="button_text" class="form-control" value="{{ $banner->button_text }}">
                                </div>
                                <div class="input-area">
                                    <label for="" class="form-label">{{ __('Button Link') }}</label>
                                    <input type="text" name="button_link" class="form-control" value="{{ $banner->button_link }}">
                                </div>
                                <div class="input-area">
                                    <button type="submit" class="btn btn-dark block-btn">
                                        <span class="flex items-center">
                                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                            <span>{{ __('Save Changes') }}</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <!-- END: Cards -->
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    @else
        <div class="card basicTable_wrapper items-center justify-center py-10 px-10">
            <div class="flex items-center justify-center flex-col gap-3">
                <img src="{{ asset('frontend/images/icon/danger.png') }}" alt="">
                <p class="text-lg text-center text-slate-600 dark:text-slate-100 mb-3">
                    {{ __("Nothing to see here.") }}
                </p>
            </div>
        </div>
    @endif

    @include('backend.setting.promotion.banner.includes.__new_banner_modal')
@endsection
