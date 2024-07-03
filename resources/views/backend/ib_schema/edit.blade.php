@extends('backend.layouts.app')
@section('title')
    {{ __('Edit Account Type') }}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('backend/css/choices.min.css') }}" >
@endsection
@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('Edit IB Account Type') }}</h4>
                <a href="{{ url()->previous() }}" class="btn btn-dark btn-sm inline-flex items-center justify-center">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                    {{ __('Back') }}
                </a>
            </div>
            <div class="card-body p-6">
                <form action="{{route('admin.ibAccountType.update',$schema->id)}}" method="post" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="grid grid-cols-2 gap-5">
                        <div class="lg:col-span-1 col-span-2 schema-name">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Title:') }}</label>
                                <input
                                    type="text"
                                    name="title"
                                    value="{{$schema->title}}"
                                    class="form-control"
                                    placeholder="Forex Account Title"
                                    required
                                />
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2 schema-badge">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Schema Badge:') }}</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Schema Badge"
                                    name="badge"
                                    value="{{$schema->badge}}"
                                />
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2 ">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Select IB Type:') }}</label>
                                <select name="type" id="" class="form-control w-100" required>
                                    <option value="ib" @if($schema->type == 'ib') selected @endif>{{__("IB")}}</option>
                                    <option value="multi_ib" @if($schema->type == 'multi_ib') selected @endif>{{__("Multi IB")}}</option>
                                </select>
                            </div>

                        </div>
                        <div class="lg:col-span-1 col-span-2 ">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Group:') }}</label>
                                <input
                                    type="text"
                                    name="group"
                                    value="{{$schema->group}}"
                                    class="form-control"
                                    placeholder="MT5 Group"
                                    required
                                />
                            </div>
                        </div>

                        <div class="col-span-2">
                            <div class="input-area fw-normal">
                                <label for="" class="form-label">{{ __('Detail:') }}</label>
                                <div class="site-editor">
                                <textarea class="summernote" name="desc">{{$schema->desc}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Status:') }}</label>
                                <div class="switch-field flex overflow-hidden same-type">
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

                        <div class="col-span-2 text-right">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                {{ __('Update Schema') }}
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