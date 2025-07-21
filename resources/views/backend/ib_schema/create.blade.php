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
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary inline-flex items-center justify-center">
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
                                <label class="form-label" for="">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the name of the IB account type">
                                        {{ __('Title') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <input
                                    type="text"
                                    name="title"
                                    class="form-control"
                                    placeholder="Account Title"
                                />
                                @error('title')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2 schema-badge">
                            <div class="input-area">
                                <label class="form-label" for="">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The IB account type badge">
                                        {{ __('Account Type Badge') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Account Type Badge"
                                    name="badge"
                                />
                                @error('badge')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="lg:col-span-1 col-span-2 ">
                            <div class="input-area">
                                <label class="form-label" for="">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Choose IB category">
                                        {{ __('Select IB Type') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <select name="type" id="" class="form-control w-100">
                                    <option value="ib">{{__("IB")}}</option>
                                    <option value="multi_ib">{{__("Multi IB")}}</option>
                                </select>
                                @error('type')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                        <div class="lg:col-span-1 col-span-2 ">
                            <div class="input-area">
                                <label class="form-label" for="">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The IB account type group">
                                        {{ __('Group') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <input
                                    type="text"
                                    name="group"
                                    class="form-control"
                                    placeholder="MT5 Group"
                                />
                                @error('group')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-span-2">
                            <div class="input-area fw-normal">
                                <label for="" class="form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Describe the IB account type">
                                        {{ __('Detail') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="site-editor">
                                    <textarea class="summernote"></textarea>
                                </div>
                                <input type="hidden" name="desc">
                            </div>
                        </div>

                        <div class="lg:col-span-1 col-span-2">
                            <div class="input-area">
                                <div class="flex items-center space-x-7 flex-wrap">
                                    <label class="form-label !w-auto">
                                        <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enable or disable this IB account type">
                                            {{ __('Status') }}
                                            <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                        </span>
                                    </label>
                                    <div class="form-switch ps-0">
                                        <input type="hidden" value="0" name="status">
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="status" value="1" class="sr-only peer">
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-2 text-right">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
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
