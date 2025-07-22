@extends('backend.setting.website.index')
@section('title')
    {{ __('Auth Covers Settings') }}
@endsection
@section('website-content')
    <?php
        $section = 'theme';
        $defaultCover = 'fallback/branding/admin-login-cover.png';
        // Get the current selected cover from settings
        $currentCover = setting('login_bg', 'theme');
        $currentCover = (!empty($currentCover) && file_exists(public_path($currentCover))) ? $currentCover : $defaultCover;
     
    ?>

    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>

    @include('backend.setting.branding.include.__tabs_nav')

    <div class="card">
        <div class="card-body p-6">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="section" value="theme">

                <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
                    <!-- Default Cover -->
                    <div class="card h-full">
                        <div class="card-body rounded-md bg-white dark:bg-slate-800 shadow-base p-0">
                            <div class="bg-slate-50 dark:bg-slate-900 p-4">
                                <img src="{{ asset($defaultCover) }}" alt="Default Cover" class="block w-full h-[200px] object-cover rounded-t-md">
                            </div>
                            <div class="p-4 border-t dark:border-slate-700">
                                <div class="flex items-center">
                                    <input type="radio" id="cover_default" name="login_bg" value="{{ $defaultCover }}"
                                        {{ $currentCover == $defaultCover ? 'checked' : '' }}
                                        class="h-4 w-4 text-success-500 focus:ring-success-500">
                                    <label for="cover_default" class="ltr:ml-2 rtl:mr-2 block text-sm text-slate-700 dark:text-slate-400">
                                        {{ __('Default Cover') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Uploaded Covers -->
                    @foreach($uploadedCovers as $index => $cover)
                        <div class="card h-full">
                            <div class="card-body rounded-md bg-white dark:bg-slate-800 shadow-base p-0">
                                <div class="bg-slate-50 dark:bg-slate-900 p-4">
                                    <img src="{{ asset($cover) }}" alt="Uploaded Cover" class="block w-full h-[200px] object-cover rounded-t-md">
                                </div>
                                <div class="p-4 border-t dark:border-slate-700">
                                    <div class="flex items-center">
                                        <input type="radio" id="cover_{{ $index }}" name="login_bg" value="{{ $cover }}"
                                            {{ $currentCover == $cover ? 'checked' : '' }}
                                            class="h-4 w-4 text-success-500 focus:ring-success-500">
                                        <label for="cover_{{ $index }}" class="ltr:ml-2 rtl:mr-2 block text-sm text-slate-700 dark:text-slate-400">
                                            {{ __('Cover ') }}{{ $index + 1 }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                        <iconify-icon icon="heroicons-outline:save" class="ltr:mr-2 rtl:ml-2"></iconify-icon>
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection