@extends('backend.layouts.app')
@section('title')
    {{ __('Edit Advertisement Material') }}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('backend/css/choices.min.css') }}" >
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-xl-8">
                        <div class="title-content">
                            <h2 class="title">{{ __('Edit Advertisement Material') }}</h2>
                            <a href="{{ url()->previous() }}" class="title-btn"><i
                                    icon-name="corner-down-left"></i>{{ __('Back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-12 col-md-12">
                    <div class="site-card">
                        <div class="site-card-body">
                            <form action="{{route('admin.advertisement_material.update',$advertisement->id)}}" method="post"
                                  enctype="multipart/form-data" class="row">
                                @method('PUT')
                                @csrf
                                <div class="col-xl-12">
                                    <div class="row">
                                        <div class="col-xl-3">
                                            <div class="site-input-groups">
                                                <label class="box-input-label" for="">{{ __('Upload Image:') }}</label>
                                                <div class="wrap-custom-file">
                                                    <input
                                                        type="file"
                                                        name="image"
                                                        id="schema-icon"
                                                        accept=".gif, .jpg, .png"
                                                    />
                                                    <label for="schema-icon" class="file-ok"
                                                           style="background-image: url({{ asset($advertisement->img) }})">
                                                        <img
                                                            class="upload-icon"
                                                            src="{{ asset('global/materials/upload.svg')}}"
                                                            alt=""
                                                        />
                                                        <span>{{ __('Update Image') }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Select language') }}</label>
                                        <select class="site-nice-select w-100" name="language" placeholder="Language" multiple>
                                            @foreach($languages as $language)
                                                <option value="{{$language->name}}" @if( $language->name == $advertisement->language ) selected @endif>{{$language->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Select Type') }}</label>
                                        <select class="site-nice-select w-100" name="type" placeholder="Language" multiple>
                                            <option  value="social_media"  @if( 'social_media' == $advertisement->type ) selected @endif>
                                                {{ __('Social Media') }}
                                            </option>
                                            <option  value="website_banner" @if( 'website_banner' == $advertisement->type ) selected @endif>
                                                {{ __('Website Banner') }}
                                            </option>


                                        </select>
                                    </div>

                                </div>

                                <div class="col-xl-6">
                                    <div class="site-input-groups">
                                        <label class="box-input-label" for="">{{ __('Status:') }}</label>
                                        <div class="switch-field same-type">
                                            <input
                                                type="radio"
                                                id="status-active"
                                                name="status"
                                                checked=""
                                                value="1"
                                                @checked($advertisement->status)
                                            />
                                            <label for="status-active">{{ __('Active') }}</label>
                                            <input
                                                type="radio"
                                                id="status-deactivate"
                                                name="status"
                                                value="0"
                                                @checked(!$advertisement->status)
                                            />
                                            <label for="status-deactivate">{{ __('Deactivate') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <button type="submit" class="site-btn-sm primary-btn w-100">
                                        {{ __('Update Schema') }}
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
@section('script')

     <script src="{{ asset('backend/js/choices.min.js') }}"></script>
     <script>


        var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
            removeItemButton: true,
            // maxItemCount:7,
            // searchResultLimit:7,
            // renderChoiceLimit:7
        });

    </script>
@endsection
