@extends('backend.layouts.app')
@section('title')
    {{ __('Create Template') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Create Template') }}
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
                <div class="card-header noborder">
                    <div>
                        <h4 class="card-title mb-2">{{ __('Shortcut Glossary') }}</h4>
                        <p class="card-text">
                            <iconify-icon icon="lucide:alert-triangle"></iconify-icon>
                            {{ __('The Shortcuts you can use') }}
                            <strong>{{ __('Shortcuts') }}</strong>
                        </p>
                    </div>
                </div>
                <div class="card-body space-y-5 p-6">
                    <div class="input-areaa relative pl-28">
                        <label for="" class="form-label inline-inputLabel">{{ __('Full Name') }}</label>
                        <div class="relative">
                            <input type="text" class="form-control !pr-12" id="fullname-input" value="[[full_name]]" readonly>
                            <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full flex items-center justify-center copy-button" type="button" data-target="#fullname-input">
                                <iconify-icon icon="lucide:copy"></iconify-icon>
                            </button>
                        </div>
                    </div>
                    <div class="input-areaa relative pl-28">
                        <label for="" class="form-label inline-inputLabel">{{ __('Site title') }}</label>
                        <div class="relative">
                            <input type="text" class="form-control !pr-12" id="sitetitle-input" value="[[site_title]]" readonly>
                            <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full flex items-center justify-center copy-button" type="button" data-target="#sitetitle-input">
                                <iconify-icon icon="lucide:copy"></iconify-icon>
                            </button>
                        </div>
                    </div>
                    <div class="input-areaa relative pl-28">
                        <label for="" class="form-label inline-inputLabel">{{ __('Site URL') }}</label>
                        <div class="relative">
                            <input type="text" class="form-control !pr-12" id="siteurl-input" value="[[site_url]]" readonly>
                            <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full flex items-center justify-center copy-button" type="button" data-target="#siteurl-input">
                                <iconify-icon icon="lucide:copy"></iconify-icon>
                            </button>
                        </div>
                    </div>
                    <div class="input-areaa relative pl-28">
                        <label for="" class="form-label inline-inputLabel">{{ __('Token') }}</label>
                        <div class="relative">
                            <input type="text" class="form-control !pr-12" id="token-input" value="[[token]]" readonly>
                            <button class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full flex items-center justify-center copy-button" type="button" data-target="#token-input">
                                <iconify-icon icon="lucide:copy"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-8 col-span-12">
            <div class="card">
                <div class="card-body p-6">
                    <form action="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="input-area grid grid-cols-12 gap-5 mb-6">
                            <label for="" class="md:col-span-3 col-span-12 form-label flex items-center">
                                {{ __('Email Type') }}
                                <iconify-icon class="toolTip onTop text-sm ml-1" icon="lucide:info" data-tippy-theme="dark" title="Leave it blank if you don't need the title" data-tippy-content="Leave it blank if you don't need the title"></iconify-icon>
                            </label>
                            <div class="md:col-span-9 col-span-12">
                                <input type="text" name="title" class="form-control" required/>
                            </div>
                        </div>
                        <div class="input-area grid grid-cols-12 gap-5 mb-6">
                            <label for="" class="md:col-span-3 col-span-12 form-label flex items-center">
                                {{ __('Email Subject') }}
                                <iconify-icon class="toolTip onTop text-sm ml-1" icon="lucide:info" data-tippy-theme="dark" title="" data-tippy-content="Here the Email Subject will come"></iconify-icon>
                            </label>
                            <div class="md:col-span-9 col-span-12">
                                <input type="text" name="subject" class="form-control" required/>
                            </div>
                        </div>
                        <div class="input-area grid grid-cols-12 gap-5 mb-6">
                            <label for="" class="md:col-span-3 col-span-12 form-label flex items-center">
                                {{ __('Message Body') }}
                                <iconify-icon class="toolTip onTop text-sm ml-1" icon="lucide:info" data-tippy-theme="dark" title="" data-tippy-content="Write the main Messages here"></iconify-icon>
                            </label>
                            <div class="md:col-span-9 col-span-12">
                                <textarea name="message_body" class="form-control" cols="30" rows="8"></textarea>
                            </div>
                        </div>
                        <div class="input-area grid grid-cols-12 gap-5 mb-6">
                            <label for="" class="md:col-span-3 col-span-12 form-label flex items-center">
                                {{ __('Button') }}
                                <iconify-icon class="toolTip onTop text-sm ml-1" icon="lucide:info" data-tippy-theme="dark" title="" data-tippy-content="Leave it blank if you don't need the button"></iconify-icon>
                            </label>
                            <div class="md:col-span-4 col-span-12">
                                <input type="text" name="button_level" class="form-control" required/>
                            </div>
                            <div class="md:col-span-5 col-span-12">
                                <input type="text" name="button_link" class="form-control" required/>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-5 mb-6">
                            <label for="" class="md:col-span-3 col-span-12 form-label flex items-center">
                                {{ __('Secondary Message Body') }}
                                <iconify-icon class="toolTip onTop text-sm ml-1" icon="lucide:info" data-tippy-theme="dark" title="" data-tippy-content="Newslatter Bottom Status"></iconify-icon>
                            </label>
                            <div class="md:col-span-9 col-span-12">
                                <div class="input-area mb-5">
                                    <div class="form-switch ps-0">
                                        <input type="hidden" value="0" name="bottom_status">
                                        <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer secondary_message__toggle">
                                            <input type="checkbox" name="bottom_status" value="1" class="sr-only peer">
                                            <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="input-area mb-5" id="secondary_message__body">
                                    <textarea name="bottom_body" class="form-control" cols="30" rows="8"></textarea>
                                </div>

                                <div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
                                    <div class="flex items-center space-x-7 flex-wrap">
                                        <label class="form-label !w-auto pt-0">
                                            {{ __('Template Status') }}
                                        </label>
                                        <div class="form-switch ps-0">
                                            <input type="hidden" value="0" name="status">
                                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                <input type="checkbox" name="status" value="1" class="sr-only peer">
                                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-7 flex-wrap">
                                        <label class="form-label !w-auto pt-0">
                                            {{ __('Disclaimer') }}
                                        </label>
                                        <div class="form-switch ps-0">
                                            <input type="hidden" value="0" name="disclaimer">
                                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                <input type="checkbox" name="disclaimer" value="1" class="sr-only peer">
                                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-7 flex-wrap">
                                        <label class="form-label !w-auto pt-0">
                                            {{ __('Risk Warning') }}
                                        </label>
                                        <div class="form-switch ps-0">
                                            <input type="hidden" value="0" name="risk_warning">
                                            <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                <input type="checkbox" name="risk_warning" value="1" class="sr-only peer">
                                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right mt-10">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                {{ __('Add Template') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.copy-button').click(function() {

                var targetSelector = $(this).data('target');
                var $input = $(targetSelector);

                $input.select();
                document.execCommand('copy');

                // Change the button text and style
                var $button = $(this);
                var $icon = $button.find('iconify-icon');
                $icon.addClass('text-success-500');
                $button.addClass('copy-button');

                // Revert the button text and style after 2 seconds
                setTimeout(function() {
                    $icon.removeClass('text-success-500');
                }, 2000);

            });
        });
    </script>
@endsection
