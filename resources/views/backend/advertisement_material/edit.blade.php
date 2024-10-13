@extends('backend.layouts.app')
@section('title')
    {{ __('Edit Advertisement Material') }}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('backend/css/choices.min.css') }}" >
@endsection
@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between flex-wrap items-center mb-6">
            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                {{ __('Edit Advertisement Material') }}
            </h4>
            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary inline-flex items-center justify-center">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                    {{ __('Back') }}
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-6">
                <form action="{{route('admin.advertisement_material.update',$advertisement->id)}}" method="post" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col-span-2">
                            <div class="max-w-xs">
                                <div class="site-input-groups">
                                    <label class="form-label" for="">{{ __('Upload Image:') }}</label>
                                    <div class="wrap-custom-file">
                                        <input
                                            type="file"
                                            name="image"
                                            id="schema-icon"
                                            accept=".gif, .jpg, .png"
                                        />
                                        <label for="schema-icon" class="file-ok" style="background-image: url({{ asset($advertisement->img) }})">
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

                        <div class="col-span-2 md:col-span-1">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Select language') }}</label>
                                <select class="select2 form-control w-full" name="language" placeholder="Language" multiple>
                                    @foreach($languages as $language)
                                        <option value="{{$language->name}}" @if( $language->name == $advertisement->language ) selected @endif>{{$language->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Select Type') }}</label>
                                <select class="select2 form-control w-full" name="type" placeholder="Language" multiple>
                                    <option  value="social_media"  @if( 'social_media' == $advertisement->type ) selected @endif>
                                        {{ __('Social Media') }}
                                    </option>
                                    <option  value="website_banner" @if( 'website_banner' == $advertisement->type ) selected @endif>
                                        {{ __('Website Banner') }}
                                    </option>


                                </select>
                            </div>

                        </div>

                        <div class="col-span-1">
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <label class="form-label !w-auto">
                                        {{ __('Status:') }}
                                    </label>
                                    <div class="form-switch ps-0">
                                        <input type="hidden" value="0" name="status">
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="status" value="1" class="sr-only peer" @checked($advertisement->status)>
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-2 text-right">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                {{ __('Update Account Type') }}
                            </button>
                        </div>
                    </div>
                </form>
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
