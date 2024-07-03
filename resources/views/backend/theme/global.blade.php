@extends('backend.theme.index')
@section('theme-title')
    {{ __('Global Settings') }}
@endsection
@section('theme-content')
    <div class="lg:col-span-8 col-span-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('Global Settings') }}</h4>
            </div>
            <div class="card-body p-6">
                <form action="" class="space-y-5">
                    <div class="input-area grid grid-cols-12 gap-5">
                        <label for="" class="lg:col-span-4 md:col-span-3 col-span-12 form-label flex items-center">
                            {{ __('App Title') }}
                            <iconify-icon class="toolTip onTop text-base ml-1" icon="lucide:info" data-tippy-content="Site Title will show on Breadcrumb" data-tippy-theme="dark"></iconify-icon>
                        </label>
                        <div class="lg:col-span-8 md:col-span-9 col-span-12">
                            <input type="text" name="title" class="form-control" placeholder="App title">
                        </div>
                    </div>
                    <div class="input-area grid grid-cols-12 gap-5">
                        <div class="lg:col-span-4 md:col-span-3 col-span-12 form-label">
                            {{ __('Site Logo (Dark)') }}
                        </div>
                        <div class="lg:col-span-8 md:col-span-9 col-span-12">
                            <div class="wrap-custom-file">
                                <input type="file" name="site_logo" id="site_logo" value="" accept=".jpeg, .jpg, .png" />
                                <label for="site_logo" class="file-ok" style="background-image: url()">
                                    <img class="upload-icon" src="{{ asset('global/materials/upload.svg') }}" alt="" />
                                    <span>{{ __('upload') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="input-area grid grid-cols-12 gap-5">
                        <div class="lg:col-span-4 md:col-span-3 col-span-12 form-label">
                            {{ __('Site Logo (Light)') }}
                        </div>
                        <div class="lg:col-span-8 md:col-span-9 col-span-12">
                            <div class="wrap-custom-file">
                                <input type="file" name="site_logo_light" id="site_logo_light" value="" accept=".jpeg, .jpg, .png" />
                                <label for="site_logo_light" class="file-ok" style="background-image: url()">
                                    <img class="upload-icon" src="{{ asset('global/materials/upload.svg') }}" alt="" />
                                    <span>{{ __('upload') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="input-area grid grid-cols-12 gap-5">
                        <div class="lg:col-span-4 md:col-span-3 col-span-12 form-label">
                            {{ __('Site Favicon') }}
                        </div>
                        <div class="lg:col-span-8 md:col-span-9 col-span-12">
                            <div class="wrap-custom-file">
                                <input type="file" name="site_favicon" id="site_favicon" value="" accept=".jpeg, .jpg, .png" />
                                <label for="site_favicon" class="file-ok" style="background-image: url()">
                                    <img class="upload-icon" src="{{ asset('global/materials/upload.svg') }}" alt="" />
                                    <span>{{ __('upload') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="input-area grid grid-cols-12 gap-5">
                        <div class="lg:col-span-4 md:col-span-3 col-span-12 form-label">
                            {{ __('Admin Login Cover') }}
                        </div>
                        <div class="lg:col-span-8 md:col-span-9 col-span-12">
                            <div class="wrap-custom-file">
                                <input type="file" name="admin_login_cover" id="admin_login_cover" value="" accept=".jpeg, .jpg, .png" />
                                <label for="admin_login_cover" class="file-ok" style="background-image: url()">
                                    <img class="upload-icon" src="{{ asset('global/materials/upload.svg') }}" alt="" />
                                    <span>{{ __('upload') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="input-area grid grid-cols-12 gap-5">
                        <div class="lg:col-span-4 md:col-span-3 col-span-12 form-label">
                            {{ __('Site Link Thumbnail') }}
                        </div>
                        <div class="lg:col-span-8 md:col-span-9 col-span-12">
                            <div class="wrap-custom-file">
                                <input type="file" name="site_link_thumbnail" id="site_link_thumbnail" value="" accept=".jpeg, .jpg, .png" />
                                <label for="site_link_thumbnail" class="file-ok" style="background-image: url()">
                                    <img class="upload-icon" src="{{ asset('global/materials/upload.svg') }}" alt="" />
                                    <span>{{ __('upload') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection