@extends('backend.layouts.app')
@section('title')
    {{ __('Edit Account Type') }}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('backend/css/choices.min.css') }}" >
@endsection
@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between flex-wrap items-center mb-6">
            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                {{ __('Edit IB Account Type') }}
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
                                    placeholder="Account Title"
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
                                <textarea class="basicTinymce" name="desc">{{$schema->desc}}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2">
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <label class="form-label !w-auto">
                                        {{ __('Status:') }}
                                    </label>
                                    <div class="form-switch ps-0">
                                        <input type="hidden" value="0" name="status">
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="status" value="1" class="sr-only peer" @checked($schema->status)>
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
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
    <script>
        var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
            removeItemButton: true,
            // maxItemCount:7,
            // searchResultLimit:7,
            // renderChoiceLimit:7
        });

    </script>
@endsection
