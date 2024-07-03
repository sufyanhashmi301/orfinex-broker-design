@extends('backend.theme.index')
@section('theme-title')
    {{ __('Site Themes') }}
@endsection
@section('theme-content')

    @foreach($themes as $theme)
        <div class="lg:col-span-4 col-span-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ ucwords( str_replace('_', ' ',$theme->name) ) }} Theme</h4>
                </div>
                <div class="card-body p-6">
                    <div class="theme-img relative overflow-hidden">
                        @if($theme->status)
                            <div class="text-sm font-medium bg-slate-900 dark:bg-slate-900 text-white py-2 text-center absolute ltr:-right-[43px] rtl:-left-[43px] top-6 px-10 transform ltr:rotate-[45deg] rtl:-rotate-45">
                                {{ __('Activated') }}
                            </div>
                        @endif
                        <img class="w-100" src="{{ asset('backend/materials/theme/'.$theme->name . '.jpg') }}" alt="">
                    </div>
                    @if($theme->status)
                        <a href="javascript:;" class="btn btn-dark w-full inline-flex items-center justify-center mt-4 disabled">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:circle-slash-2"></iconify-icon>
                            {{ __('Activated Theme') }}
                        </a>
                    @else
                        <a href="{{ route('admin.theme.status-update',['id' => $theme->id]) }}" class="btn btn-dark w-full inline-flex items-center justify-center mt-4">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                            {{ __('Active Now') }}
                        </a>
                    @endif

                </div>
            </div>
        </div>
    @endforeach
@endsection
