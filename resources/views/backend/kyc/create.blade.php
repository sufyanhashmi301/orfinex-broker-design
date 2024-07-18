@extends('backend.layouts.app')
@section('title')
    {{ __('Add New KYC') }}
@endsection
@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between flex-wrap items-center mb-6">
            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                {{ __('Add New KYC Form') }}
            </h4>
            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                <a href="{{ route('admin.kyc-form.index') }}" class="inline-flex items-center justify-center text-success-500">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                    {{ __('Back') }}
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-6">
                <form action="{{ route('admin.kyc-form.store') }}" method="post" class="space-y-4">
                    @csrf
                   
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Name:') }}</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                               placeholder="KYC Type Name" required/>
                    </div>

                    <div>
                        <a href="javascript:void(0)" id="generate" class="btn btn-outline-dark btn-sm inline-flex items-center justify-center mb-3">
                            {{ __('Add Field option') }}
                        </a>
                    </div>
                    <div class="addOptions">

                    </div>
                    <div class="max-w-xs">
                        <div class="input-area">
                            <label class="form-label" for="">{{ __('Status:') }}</label>
                            <div class="switch-field flex mb-3 overflow-hidden">
                                <input
                                    type="radio"
                                    id="active-status"
                                    name="status"
                                    checked=""
                                    value="1"
                                />
                                <label for="active-status">{{ __('Active') }}</label>
                                <input
                                    type="radio"
                                    id="deactivate-status"
                                    name="status"
                                    value="0"
                                />
                                <label for="deactivate-status">{{ __('Deactivate') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="input-area text-right">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function (e) {
            var i = 0;
            "use strict";

            $("#generate").on('click', function () {
                ++i;
                var form = `<div class="option-remove-row grid grid-cols-12 gap-5">
                    <div class="xl:col-span-4 col-span-12">
                      <div class="input-area">
                        <input name="fields[` + i + `][name]" class="form-control" type="text" value="" required placeholder="Field Name">
                      </div>
                    </div>

                    <div class="xl:col-span-4 col-span-12">
                      <div class="input-area">
                        <select name="fields[` + i + `][type]" class="form-control w-full mb-3">
                            <option value="text">Input Text</option>
                            <option value="textarea">Textarea</option>
                            <option value="file">File upload</option>
                        </select>
                      </div>
                    </div>
                    <div class="xl:col-span-3 col-span-12">
                      <div class="input-area">
                        <select name="fields[` + i + `][validation]" class="form-control w-full mb-3">
                            <option value="required">Required</option>
                            <option value="nullable">Optional</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-span-1">
                      <button class="btn-dark h-[32px] w-[32px] flex items-center justify-center rounded-full text-xl delete-option-row delete_desc" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12z"/>
                        </svg>
                      </button>
                    </div>
                    </div>`;
                $('.addOptions').append(form)
            });

            $(document).on('click', '.delete_desc', function () {
                $(this).closest('.option-remove-row').remove();
            });
        });
    </script>
@endsection