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
                <a href="{{ route('admin.template.sms.index') }}" class="btn btn-primary inline-flex items-center justify-center">
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
                    <div class="input-area grid grid-cols-12 gap-5">
                        <label for="" class="md:col-span-3 col-span-12 form-label">{{ __('Message Body') }}
                            <iconify-icon
                                class="toolTip onTop"
                                icon="lucide:info"
                                data-tippy-theme="dark"
                                title=""
                                data-tippy-content="Write the main Messages here">
                            </iconify-icon>
                        </label>
                        <div class="md:col-span-9 col-span-12">
                            <textarea name="message_body" class="form-control" cols="30" rows="8">
                                {{ br2nl($template->message_body) }}
                            </textarea>
                            <p class="paragraph text-sm mb-0 mt-2">
                                <i icon-name="alert-triangle"></i>
                                {{ __('The Shortcuts you can use') }}
                                <strong>{{ implode(", ",json_decode($template->short_codes)) }}</strong>
                            </p>
                        </div>
                    </div>

                    <div class="input-area grid grid-cols-12 gap-5">
                        <label for="" class="md:col-span-3 col-span-12 form-label pt-0">
                            {{ __('Template Status') }}
                            <iconify-icon
                                class="toolTip onTop"
                                icon="lucide:info"
                                data-tippy-theme="dark" title=""
                                data-tippy-content="Template Status">
                            </iconify-icon>
                        </label>
                        <div class="md:col-span-9 col-span-12">
                            <div class="max-w-xs">
                                <div class="switch-field flex overflow-hidden mb-0">
                                    <input
                                        type="radio"
                                        id="template_status_enable"
                                        name="status"
                                        value="1"
                                        @checked($template->status)
                                    />
                                    <label for="template_status_enable">{{ __('Enable') }}</label>
                                    <input
                                        type="radio"
                                        id="template_status_disable"
                                        name="status"
                                        value="0"
                                        @checked(!$template->status)
                                    />
                                    <label for="template_status_disable">{{ __('Disable') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
