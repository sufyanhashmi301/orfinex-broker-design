@extends('backend.layouts.app')
@section('title')
    {{ __('Add New Resource') }}
@endsection
@section('content')

    <style>
        .options-container {
            display: flex;
            align-items: flex-start;
        }

        .option-row {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .option-input {
            margin-right: 8px;
        }
    </style>
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-xl-8">
                        <div class="title-content">
                            <h2 class="title">{{ __('Add New Resource') }}</h2>
                            <a href="{{ route('admin.ib-form.index') }}" class="title-btn">
                                <i icon-name="corner-down-left"></i>
                                {{ __('Back') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="site-card">
                        <div class="site-card-body">
                            <form action="" method="post" class="row">
                                @csrf
                                <div class="col-xl-12">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Resource Image:') }}</label>
                                        <div class="wrap-custom-file">
                                            <input
                                                type="file"
                                                name="resource_image"
                                                accept=".gif, .jpg, .png"
                                            />
                                            <label for="logo">
                                                <img
                                                    class="upload-icon"
                                                    src="{{ asset('global/materials/upload.svg') }}"
                                                    alt=""
                                                />
                                                <span>{{ __('Upload Image') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Resource Title:') }}</label>
                                        <input type="text" name="name" value="" class="box-input" placeholder="Resource Title" required/>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Resource Category:') }}</label>
                                        <select name="category" id="" class="site-nice-select w-100" required>
                                            <option value="">-- Select Category --</option>
                                            <option value="Social Media">-- Social Media --</option>
                                            <option value="Website Banner">-- Website Banner --</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <button type="submit" class="site-btn primary-btn w-100">
                                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                        {{ __('Save Changes') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
