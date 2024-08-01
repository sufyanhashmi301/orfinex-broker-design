@extends('backend.layouts.app')
@section('title')
    {{ __('Add New IB Account Type') }}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('backend/css/choices.min.css') }}" >
@endsection
@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between flex-wrap items-center mb-6">
            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                {{ __('Add New IB Account Type') }}
            </h4>
            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                <a href="{{ url()->previous() }}" class="btn btn-primary inline-flex items-center justify-center">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                    {{ __('Back') }}
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-6">
                <form action="{{route('admin.ibAccountType.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-2 gap-5">
                        <div class="lg:col-span-1 col-span-2 schema-name">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Title:') }}</label>
                                <input
                                    type="text"
                                    name="title"
                                    class="form-control"
                                    placeholder="Account Title"
                                    required
                                />
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2 schema-badge">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Account Type Badge:') }}</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Account Type Badge"
                                    name="badge"
                                    required
                                />
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2 ">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Select IB Type:') }}</label>
                                <select name="type" id="" class="form-control w-100" required>
                                    <option value="ib">{{__("IB")}}</option>
                                    <option value="multi_ib">{{__("Multi IB")}}</option>
                                </select>
                            </div>

                        </div>
                        <div class="lg:col-span-1 col-span-2 ">
                            <div class="input-area">
                                <label class="form-label" for="">{{ __('Group:') }}</label>
                                <input
                                    type="text"
                                    name="group"
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
                                <textarea class="summernote" name="desc"></textarea>
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
                                    />
                                    <label for="status-active">{{ __('Active') }}</label>
                                    <input
                                        type="radio"
                                        id="status-deactive"
                                        name="status"
                                        value="0"
                                    />
                                    <label for="status-deactive">{{ __('Deactivate') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-2 text-right">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                {{ __('Add New') }}
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

        (function ($) {
            'use strict';

        var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
            removeItemButton: true,
            // maxItemCount:7,
            // searchResultLimit:7,
            // renderChoiceLimit:20
        });

        })(jQuery)
    </script>
@endsection
