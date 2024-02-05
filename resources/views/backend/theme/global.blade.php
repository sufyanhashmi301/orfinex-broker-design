@extends('backend.theme.index')
@section('theme-title')
    {{ __('Global Settings') }}
@endsection
@section('theme-content')
    <div class="col-xl-8 col-lg-8 col-md-12 col-12">
        <div class="site-card">
            <div class="site-card-header">
                <h3 class="title">{{ __('Global Settings') }}</h3>
            </div>
            <div class="site-card-body">
                <form action="">
                    <div class="site-input-groups row">
                        <label for="" class="col-xl-4 col-lg-4 col-md-3 col-12 col-label">
                            {{ __('App Title') }}
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="info" icon-name="info" data-bs-toggle="tooltip" title="" data-bs-original-title="Site Title will show on Breadcrumb" class="lucide lucide-info">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M12 16v-4"></path>
                                <path d="M12 8h.01"></path>
                            </svg>
                        </label>
                        <div class="col-xl-8 col-lg-8 col-md-9 col-12">
                            <input type="text" name="title" class="box-input" placeholder="App title">
                        </div>
                    </div>
                    <div class="site-input-groups row">
                        <div class="col-xl-4 col-lg-4 col-md-3 col-12 col-label">
                            {{ __('Site Logo (Dark)') }}
                        </div>
                        <div class="col-xl-8 col-lg-8 col-md-9 col-12">
                            <div class="wrap-custom-file">
                                <input type="file" name="site_logo" id="site_logo" value="" accept=".jpeg, .jpg, .png" />
                                <label for="site_logo" class="file-ok" style="background-image: url()">
                                    <img class="upload-icon" src="{{ asset('global/materials/upload.svg') }}" alt="" />
                                    <span>{{ __('upload') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="site-input-groups row">
                        <div class="col-xl-4 col-lg-4 col-md-3 col-12 col-label">
                            {{ __('Site Logo (Light)') }}
                        </div>
                        <div class="col-xl-8 col-lg-8 col-md-9 col-12">
                            <div class="wrap-custom-file">
                                <input type="file" name="site_logo_light" id="site_logo_light" value="" accept=".jpeg, .jpg, .png" />
                                <label for="site_logo_light" class="file-ok" style="background-image: url()">
                                    <img class="upload-icon" src="{{ asset('global/materials/upload.svg') }}" alt="" />
                                    <span>{{ __('upload') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="site-input-groups row">
                        <div class="col-xl-4 col-lg-4 col-md-3 col-12 col-label">
                            {{ __('Site Favicon') }}
                        </div>
                        <div class="col-xl-8 col-lg-8 col-md-9 col-12">
                            <div class="wrap-custom-file">
                                <input type="file" name="site_favicon" id="site_favicon" value="" accept=".jpeg, .jpg, .png" />
                                <label for="site_favicon" class="file-ok" style="background-image: url()">
                                    <img class="upload-icon" src="{{ asset('global/materials/upload.svg') }}" alt="" />
                                    <span>{{ __('upload') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="site-input-groups row">
                        <div class="col-xl-4 col-lg-4 col-md-3 col-12 col-label">
                            {{ __('Admin Login Cover') }}
                        </div>
                        <div class="col-xl-8 col-lg-8 col-md-9 col-12">
                            <div class="wrap-custom-file">
                                <input type="file" name="admin_login_cover" id="admin_login_cover" value="" accept=".jpeg, .jpg, .png" />
                                <label for="admin_login_cover" class="file-ok" style="background-image: url()">
                                    <img class="upload-icon" src="{{ asset('global/materials/upload.svg') }}" alt="" />
                                    <span>{{ __('upload') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="site-input-groups row">
                        <div class="col-xl-4 col-lg-4 col-md-3 col-12 col-label">
                            {{ __('Site Link Thumbnail') }}
                        </div>
                        <div class="col-xl-8 col-lg-8 col-md-9 col-12">
                            <div class="wrap-custom-file">
                                <input type="file" name="site_link_thumbnail" id="site_link_thumbnail" value="" accept=".jpeg, .jpg, .png" />
                                <label for="site_link_thumbnail" class="file-ok" style="background-image: url()">
                                    <img class="upload-icon" src="{{ asset('global/materials/upload.svg') }}" alt="" />
                                    <span>{{ __('upload') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="offset-sm-4 col-sm-8">
                            <button type="submit" class="site-btn-sm primary-btn w-100">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection