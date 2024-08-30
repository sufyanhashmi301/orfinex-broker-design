@extends('backend.theme.index')
@section('title')
    {{ __('Template Settings') }}
@endsection
@section('theme-content')

    <div class="card mb-6">
        <div class="card-body p-6">
            <div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
                @foreach($themes as $theme)
                    <div class="card h-full">
                        <div class="card-body rounded-md bg-white dark:bg-slate-800 shadow-base overflow-hidden">
                            <div class="h-fit group">
                                <div class="relative overflow-hidden">
                                    <div class="bg-slate-50 dark:bg-slate-900 p-4">
                                        <img src="{{ asset('backend/materials/theme/'.$theme->name . '.jpg') }}" alt="image" class="block w-full h-[350px] object-cover rounded-t-md">
                                    </div>
                                    <div class="absolute h-full w-full bg-black/20 flex items-center justify-center -bottom-10 group-hover:bottom-0 opacity-0 group-hover:opacity-100 transition-all duration-300">
                                        @if($theme->status)
                                            <a href="javascript:;" class="btn btn-dark inline-flex items-center justify-center mt-4 disabled">
                                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:circle-slash-2"></iconify-icon>
                                                {{ __('Activated Theme') }}
                                            </a>
                                        @else
                                            <a href="{{ route('admin.theme.status-update',['id' => $theme->id]) }}" class="btn btn-dark inline-flex items-center justify-center mt-4">
                                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                                {{ __('Active Now') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="items-center p-5">
                                <div class="success-radio">
                                    <label class="flex items-center cursor-pointer">
                                        <input
                                            type="radio"
                                            class="hidden"
                                            name="theme"
                                            value="{{ ucwords( str_replace('_', ' ',$theme->name) ) }} Theme"
                                            @if($theme->status) checked @endif
                                            disabled="disabled"
                                        >
                                        <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                        <span class="text-success-500 text-sm leading-6 capitalize">
                                            {{ ucwords( str_replace('_', ' ',$theme->name) ) }} Theme
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
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
