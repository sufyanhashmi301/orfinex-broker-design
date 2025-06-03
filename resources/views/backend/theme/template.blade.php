@extends('backend.theme.index')
@section('title')
    {{ __('Template Settings') }}
@endsection
@section('theme-content')
    <div class="space-y-5">
        <div class="card">
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
                                <div class="items-center dark:bg-body p-5">
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
                                            <span class="text-success text-sm leading-6 capitalize">
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
        <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h4 class="card-title">{{ __('Dashboard Quick Links') }}</h4>
                        <p class="card-text">{{ __('Customize and access your most important dashboard features with one click.') }}</p>
                    </div>
                </div>
                <div class="card-body p-6">
                    <img src="{{ asset('backend/images/dashboard-quick-links.png') }}" alt="image" class="w-full mb-5">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="section" value="user_dashboard">
                        <div class="space-y-5">
                            <div class="flex items-center" style="line-height: 0;">
                                <input type="hidden" value="0" name="is_desktop_dashboard_quick_link">
                                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-switch">
                                    <input type="checkbox" name="is_desktop_dashboard_quick_link" value="1" class="sr-only peer" @if(setting('is_desktop_dashboard_quick_link', 'user_dashboard')) checked @endif>
                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                </label>
                                <div class="flex flex-col ml-5">
                                    <span class="text-slate-500 dark:text-slate-400 text-sm leading-6 font-medium">
                                        {{ __('Desktop') }}
                                    </span>
                                    <span class="text-xs font-Inter font-normal text-slate-600">
                                        {{ __('Show or hide quick links section on the dashboard.') }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center" style="line-height: 0;">
                                <input type="hidden" value="0" name="is_mobile_dashboard_quick_link">
                                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-switch">
                                    <input type="checkbox" name="is_mobile_dashboard_quick_link" value="1" class="sr-only peer" @if(setting('is_mobile_dashboard_quick_link', 'user_dashboard')) checked @endif>
                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                </label>
                                <div class="flex flex-col ml-5">
                                    <span class="text-slate-500 dark:text-slate-400 text-sm leading-6 font-medium">
                                        {{ __('Mobile') }}
                                    </span>
                                    <span class="text-xs font-Inter font-normal text-slate-600">
                                        {{ __('Show or hide quick links section on the dashboard.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-10">
                            <button type="submit" class="btn btn-dark w-full inline-flex items-center justify-center">
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

