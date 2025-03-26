@extends('backend.layouts.app')
@section('title')
    {{ __('Edit Account Type') }}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('backend/css/choices.min.css') }}" >
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('Edit IB Account Type') }}</h4>
            <a href="{{ url()->previous() }}" class="btn btn-dark btn-sm inline-flex items-center justify-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                {{ __('Back') }}
            </a>
        </div>
        <div class="card-body p-6">
            <form action="{{route('admin.ibAccountType.update',$schema->id)}}" method="post" enctype="multipart/form-data" class="row">
                @method('PUT')
                @csrf
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-3">
                            <div class="site-input-groups">
                                <label class="box-input-label" for="">{{ __('Upload Icon') }}</label>
                                <div class="wrap-custom-file">
                                    <input
                                        type="file"
                                        name="icon"
                                        id="schema-icon"
                                        accept=".gif, .jpg, .png"
                                    />
                                    <label for="schema-icon" class="file-ok"
                                            style="background-image: url({{ asset($schema->icon) }})">
                                        <img
                                            class="upload-icon"
                                            src="{{ asset('global/materials/upload.svg')}}"
                                            alt=""
                                        />
                                        <span>{{ __('Update Avatar') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 schema-name">
                    <div class="site-input-groups">
                        <label class="box-input-label" for="">{{ __('Title') }}</label>
                        <input
                            type="text"
                            name="title"
                            value="{{$schema->title}}"
                            class="box-input"
                            placeholder="Account Title"
                            required
                        />
                    </div>
                </div>
                <div class="col-xl-6 schema-badge">
                    <div class="site-input-groups">
                        <label class="box-input-label" for="">{{ __('Schema Badge') }}</label>
                        <input
                            type="text"
                            class="box-input"
                            placeholder="Schema Badge"
                            name="badge"
                            value="{{$schema->badge}}"
                        />
                    </div>
                </div>
                <div class="col-xl-6 ">
                    <div class="site-input-groups">
                        <label class="box-input-label" for="">{{ __('Select IB Type') }}</label>
                        <select name="type" id="" class="site-nice-select w-100" required>
                            <option value="ib" @if($schema->type == 'ib') selected @endif>{{__("IB")}}</option>
                        </select>
                    </div>

                </div>
                <div class="col-xl-6 ">
                    <div class="site-input-groups">
                        <label class="box-input-label" for="">{{ __('Group') }}</label>
                        <input
                            type="text"
                            name="group"
                            value="{{$schema->group}}"
                            class="box-input"
                            placeholder="MT5 Group"
                            required
                        />
                    </div>
                </div>

                <div class="col-xl-12">
                    <div class="site-input-groups fw-normal">
                        <label for="" class="box-input-label">{{ __('Detail') }}</label>
                        <div class="site-editor">
                        <textarea class="summernote"
                                    name="desc">{{$schema->desc}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="site-input-groups">
                        <label class="box-input-label" for="">{{ __('Status') }}</label>
                        <div class="switch-field same-type">
                            <input
                                type="radio"
                                id="status-active"
                                name="status"
                                checked=""
                                value="1"
                                @checked($schema->status)
                            />
                            <label for="status-active">{{ __('Active') }}</label>
                            <input
                                type="radio"
                                id="status-deactivate"
                                name="status"
                                value="0"
                                @checked(!$schema->status)
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
