@extends('backend.layouts.app')
@section('title')
    {{ __('Edit Email Template') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Edit') }} {{  $template->name }} {{ __('Template') }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ route('admin.email-template') }}" class="btn btn-primary inline-flex items-center justify-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                {{ __('Back') }}
            </a>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-5">
        <div class="lg:col-span-4 col-span-12">
            <div class="card">
                <div class="card-body p-6">

                </div>
            </div>
        </div>
        <div class="lg:col-span-8 col-span-12">
            <div class="card">
                <div class="card-body p-6">
                    <form action="{{ route('admin.email-template-update') }}" method="post" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        <input type="hidden" name="id" value="{{ $template->id }}">
                        <div class="input-area grid grid-cols-12 gap-5">
                            <label for="" class="md:col-span-3 col-span-12 form-label">
                                {{ __('Email Type') }}
                                <iconify-icon class="toolTip onTop text-base" icon="lucide:info" data-tippy-theme="dark" title="Leave it blank if you don't need the title" data-tippy-content="Leave it blank if you don't need the title"></iconify-icon>
                            </label>
                            <div class="md:col-span-9 col-span-12">
                                <input type="text" name="title" class="form-control" value="{{ $template->title }}" required/>
                            </div>
                        </div>
                        <div class="input-area grid grid-cols-12 gap-5">
                            <label for="" class="md:col-span-3 col-span-12 form-label">
                                {{ __('Email Subject') }}
                                <iconify-icon class="toolTip onTop text-base" icon="lucide:info" data-tippy-theme="dark" title="" data-tippy-content="Here the Email Subject will come"></iconify-icon>
                            </label>
                            <div class="md:col-span-9 col-span-12">
                                <input type="text" name="subject" class="form-control" value="{{ $template->subject }}" required/>
                            </div>
                        </div>

                        <div class="input-area grid grid-cols-12 gap-5">
                            <label for="" class="md:col-span-3 col-span-12 form-label">
                                {{ __('Salutation') }}
                                <iconify-icon class="toolTip onTop text-base" icon="lucide:info" data-tippy-theme="dark" title="" data-tippy-content="Show the Greetings here"></iconify-icon>
                            </label>
                            <div class="md:col-span-9 col-span-12">
                                <input type="text" name="salutation" class="form-control" value="{{ $template->salutation }}" required/>
                            </div>
                        </div>
                        <div class="input-area grid grid-cols-12 gap-5">
                            <label for="" class="md:col-span-3 col-span-12 form-label">
                                {{ __('Message Body') }}
                                <iconify-icon class="toolTip onTop text-base" icon="lucide:info" data-tippy-theme="dark" title="" data-tippy-content="Write the main Messages here"></iconify-icon>
                            </label>
                            <div class="md:col-span-9 col-span-12">
                        <textarea name="message_body" class="form-control" cols="30" rows="8">
                            {{ br2nl($template->message_body) }}
                        </textarea>
                            </div>
                        </div>
                        <div class="input-area grid grid-cols-12 gap-5">
                            <label for="" class="md:col-span-3 col-span-12 form-label">
                                {{ __('Button') }}
                                <iconify-icon class="toolTip onTop text-base" icon="lucide:info" data-tippy-theme="dark" title="" data-tippy-content="Leave it blank if you don't need the button"></iconify-icon>
                            </label>
                            <div class="md:col-span-4 col-span-12">
                                <input type="text" name="button_level" class="form-control" value="{{ $template->button_level }}" required/>
                            </div>
                            <div class="md:col-span-5 col-span-12">
                                <input type="text" name="button_link" class="form-control" value="{{ $template->button_link }}" required/>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-5">
                            <label for="" class="md:col-span-3 col-span-12 form-label pt-0">
                                {{ __('Secondary Message Body') }}
                                <iconify-icon class="toolTip onTop text-base" icon="lucide:info" data-tippy-theme="dark" title="" data-tippy-content="Newslatter Bottom Status"></iconify-icon>
                            </label>
                            <div class="md:col-span-9 col-span-12">
                                <div class="input-area max-w-xs">
                                    <div class="switch-field flex overflow-hidden mb-0">
                                        <input
                                            type="radio"
                                            id="footer_bottom"
                                            name="bottom_status"
                                            value="1"
                                            @checked( $template->bottom_status)
                                        />
                                        <label for="footer_bottom">{{ __('Enable') }}</label>
                                        <input
                                            type="radio"
                                            id="footer_bottom_disable"
                                            name="bottom_status"
                                            value="0"
                                            @checked(!$template->bottom_status)
                                        />
                                        <label for="footer_bottom_disable">{{ __('Disable') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="input-area grid grid-cols-12 gap-5">
                            <div class="md:col-span-3 col-span-12"></div>
                            <div class="md:col-span-9 col-span-12">
                                <textarea name="bottom_body" class="form-control" cols="30" rows="8">
                                    {{ br2nl($template->bottom_body) }}
                                </textarea>
                                <p class="paragraph text-sm mb-0 mt-2">
                                    <iconify-icon icon="lucide:alert-triangle"></iconify-icon>
                                    {{ __('The Shortcuts you can use') }}
                                    <strong>{{ implode(", ",json_decode($template->short_codes)) }}</strong>
                                </p>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-5">
                            <label for="" class="md:col-span-3 col-span-12 form-label pt-0">
                                {{ __('Template Status') }}
                                <iconify-icon class="toolTip onTop text-base" icon="lucide:info" data-tippy-theme="dark" title="" data-tippy-content="Template Status"></iconify-icon>
                            </label>
                            <div class="md:col-span-9 col-span-12">
                                <div class="input-area max-w-xs">
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
    </div>
@endsection

