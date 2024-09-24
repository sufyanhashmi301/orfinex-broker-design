@extends('backend.setting.website.index')
@section('title')
    {{ __('Banners Settings') }}
@endsection
@section('website-content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="card p-4 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open">
            <li class="nav-item">
                <a href="javascript:;" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-6 py-3 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 active">
                    {{ __('Dashboard') }}
                </a>
            </li>
        </ul>
    </div>
    <div class="grid grid-cols-3 gap-5">
        @foreach($banners as $banner)
            <div class="lg:col-span-1 col-span-3">
                <div class="h-full rounded transition-all duration-100 shadow-none bg-slate-200 dark:bg-slate-700">
                    <form action="{{ route('admin.banner.update',$banner->id) }}" method="post">
                        @method('put')
                        @csrf
                        <div class="relative flex justify-between items-center bg-white dark:bg-slate-800 rounded shadow-base px-6 py-5">
                            <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-[2px] bg-primary"></span>
                            <h3 class="text-lg text-slate-900 dark:text-white font-medium capitalize">
                                {{ $banner->title }}
                            </h3>
                            <div class="form-switch ps-0" style="line-height: 0;">
                                <input type="hidden" value="0" name="status">
                                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-switch">
                                    <input type="checkbox" name="status" value="1" class="sr-only peer" {{ $banner->status == 1 ? 'checked' : '' }}>
                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                </label>
                            </div>
                        </div>
                        <div class="px-2 py-4 h-full space-y-4 rounded-bl rounded-br">
                            <!-- BEGIN: Cards -->
                            <div class="card rounded-md bg-white dark:bg-slate-800 shadow-base custom-class card-body space-y-5 p-6">
                                <p class="card-text">
                                    {{ __('Lorem Ipsum is simply dummy text of the printing and typesetting industry.') }}
                                </p>
                                <div class="input-area">
                                    <label for="" class="form-label">{{ __('Banner Title') }}</label>
                                    <input type="text" name="title" class="form-control" value="{{ $banner->title }}">
                                </div>
                                <div class="input-area">
                                    <label for="" class="form-label">{{ __('Banner Subtitle') }}</label>
                                    <input type="text" name="subtitle" class="form-control" value="{{ $banner->subtitle }}">
                                </div>
                                <div class="input-area">
                                    <label for="" class="form-label">{{ __('Primary Link') }}</label>
                                    <input type="text" name="primary_link" class="form-control" value="{{ $banner->primary_link }}">
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
@endsection
