@extends('backend.setting.communication.index')
@section('title')
    {{ __('Edit SMS Template') }}
@endsection
@section('communication-content')
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between flex-wrap items-center mb-6">
            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                {{ __('Edit') }} {{  $template->name }} {{ __('Template') }}
            </h4>
            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                <a href="{{ route('admin.template.sms.index') }}" class="btn btn-sm btn-primary inline-flex items-center justify-center">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                    {{ __('Back') }}
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('admin.template.sms.template-update') }}" method="post"
                      enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <input type="hidden" name="id" value="{{ $template->id }}">
                    <div>
                        {!! $template->message_body !!}
                    </div>
                    <div class="input-area grid grid-cols-12 gap-5">
                        <label for="" class="md:col-span-3 col-span-12 form-label">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Write the main Messages here">
                                {{ __('Message Body') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <div class="md:col-span-9 col-span-12">
                            <textarea class="form-control summernote" cols="30" rows="8">
                                {{ $template->message_body }}
                            </textarea>
                            <input type="hidden" name="message_body" value="{{ str_replace(['<', '>'], ['{', '}'], $template->message_body) }}">
                            <p class="paragraph text-sm mb-0 mt-2">
                                <i icon-name="alert-triangle"></i>
                                {{ __('The Shortcuts you can use') }}
                                <strong>{{ implode(", ",json_decode($template->short_codes)) }}</strong>
                            </p>
                        </div>
                    </div>

                    <div class="input-area grid grid-cols-12 gap-5">
                        <label for="" class="md:col-span-3 col-span-12 form-label pt-0">
                            <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Toggle to activate or deactivate this template">
                                {{ __('Template Status') }}
                                <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                            </span>
                        </label>
                        <div class="md:col-span-9 col-span-12">
                            <div class="input-area">
                                <div class="form-switch ps-0">
                                    <input type="hidden" value="0" name="status">
                                    <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                        <input type="checkbox" name="status" value="1" class="sr-only peer" @checked($template->status)>
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right mt-10">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            <span class="flex items-center">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Save Changes') }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
