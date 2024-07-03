@extends('backend.layouts.app')
@section('title')
    {{ __('Edit SMS Template') }}
@endsection
@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> {{ __('Edit') }} {{  $template->name }} {{ __('Template') }}</h4>
                <div class="card-header-links">
                    <a href="{{ route('admin.template.sms.index') }}" class="btn btn-dark btn-sm inline-flex items-center justify-center">
                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                        {{ __('Back') }}
                    </a>
                </div>
            </div>
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