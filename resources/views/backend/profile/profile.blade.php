@extends('backend.layouts.app')
@section('title')
    {{ __('Details of Admin') }}
@endsection
@section('content')
    <div class="space-y-5">
        <form action="{{ route('admin.profile-update') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-12 gap-5">
                <div class="lg:col-span-3 col-span-12">
                    <div class="profile-card card p-6">
                        <div class="top mb-0">
                            <div class="wrap-custom-file mb-2">
                                <input
                                    type="file"
                                    name="avatar"
                                    id="admin_profile_image"
                                    accept=".gif, .jpg, .png"
                                />
                                <label for="admin_profile_image" class="file-ok"
                                       style="background-image: url({{ asset(auth()->user()->avatar) }})">
                                    <img
                                        class="upload-icon"
                                        src="{{ asset('global/materials/upload.svg') }}"
                                        alt=""
                                    />
                                    <span>{{ __('Update Profile Image') }}</span>
                                </label>
                            </div>
                            <div class="title-des text-center mb-0">
                                <h4 class="card-title">{{ auth()->user()->name }}</h4>
                                <p class="mb-0"> {{  str_replace('-', ' ', auth()->user()->getRoleNames()->first() )  }} </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-9 col-span-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ __("Information's") }}</h4>
                        </div>
                        <div class="card-body p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="input-area relative">
                                    <label for="" class="form-label">{{ __('Name:') }}</label>
                                    <input type="text" class="form-control" name="name"
                                           value="{{ Auth::user()->name }}" required="">
                                </div>

                                <div class="input-area relative">
                                    <label for="" class="form-label">{{ __('Email:') }}</label>
                                    <input type="email" class="form-control" name="email"
                                           value="{{ Auth::user()->email }}" required="">
                                </div>
                                <div class="input-area relative">
                                    <label for="" class="form-label">{{ __('Phone:') }}</label>
                                    <input type="text" class="form-control" name="phone"
                                           value="{{ Auth::user()->phone }}" required="">
                                </div>

                                <div class="input-area relative">
                                    <label for="" class="form-label">{{ __('Joining Date:') }}</label>
                                    <input type="text" class="form-control" value="{{ Auth::user()->created_at }}"
                                           required="" disabled>
                                </div>
                                <div class="col-span-2 text-right">
                                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                        {{ __('Save Changes') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

