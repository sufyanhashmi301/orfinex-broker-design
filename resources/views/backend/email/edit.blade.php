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
            <a href="{{ url()->previous() }}" class="btn btn-sm btn-primary inline-flex items-center justify-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                {{ __('Back') }}
            </a>
             <a href="javascript:;" class="btn btn-sm btn-outline-dark inline-flex items-center" type="button" data-bs-toggle="modal" data-bs-target="#templateTestModal">
        {{ __('Test Template') }}
    </a>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-5">
        <!-- Variables Sidebar -->
        <div class="lg:col-span-4 col-span-12">
            <div class="card">
                <div class="card-header noborder">
                    <div>
                        <h4 class="card-title mb-2">{{ __('Shortcut Glossary') }}</h4>
                        <p class="card-text">
                            <iconify-icon icon="lucide:alert-triangle"></iconify-icon>
                            {{ __('The Shortcuts you can use') }}
                            <strong>{{ implode(", ",json_decode($template->short_codes)) }}</strong>
                        </p>
                    </div>
                </div>
                <div class="card-body space-y-5 p-6">
                    @foreach(json_decode($template->short_codes) as $shortcode)
                        @php
                            $cleanShortcode = preg_replace('/[\[\]]/', '', $shortcode);
                            $label = ucwords(str_replace('_', ' ', $cleanShortcode));
                            $inputId = $cleanShortcode . '-input';
                        @endphp
                        <div class="input-areaa relative pl-32">
                            <label for="" class="form-label inline-inputLabel">
                                {{ $label }}:
                            </label>
                            <div class="relative">
                                <input type="text" class="form-control !pr-12" id="{{ $inputId }}" value="{{ $shortcode }}" readonly>
                                <button
                                    class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full flex items-center justify-center copy-button"
                                    type="button" data-target="#{{ $inputId }}">
                                    <iconify-icon icon="lucide:copy"></iconify-icon>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-8 col-span-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                            <iconify-icon icon="lucide:code" class="text-purple-500"></iconify-icon>
                            {{ __('Template Mode') }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Choose between dynamic template builder or custom HTML') }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Dynamic Template') }}</span>
                        <div class="form-switch leading-none ps-0">
                            <input type="hidden" value="0" name="use_custom_html">
                            <label
                                class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer template-mode-toggle">
                                <input type="checkbox" name="use_custom_html" value="1" class="sr-only peer" @checked($template->use_custom_html)>
                                <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                            </label>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Custom HTML') }}</span>
                    </div>
                </div>
                <div class="card-body p-6">
                    <form action="{{ route('admin.email-template-update') }}" method="post" enctype="multipart/form-data" id="form-submit">
                        @csrf
                        <input type="hidden" name="page" value="{{ request('page') }}">
                        <input type="hidden" name="id" value="{{ $template->id }}">

                        <div id="dynamic-template-section" style="{{ $template->use_custom_html ? 'display: none;' : '' }}">
                            <div class="input-area grid grid-cols-12 gap-5 mb-6">
                                <label for="" class="md:col-span-3 col-span-12 form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Leave it blank if you don't need the title">
                                        {{ __('Email Type') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="md:col-span-9 col-span-12">
                                    <input type="text" name="title" class="form-control" value="{{ $template->title }}" required/>
                                </div>
                            </div>
                            <div class="input-area grid grid-cols-12 gap-5 mb-6">
                                <label for="" class="md:col-span-3 col-span-12 form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Here the Email Subject will come">
                                        {{ __('Email Subject') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="md:col-span-9 col-span-12">
                                    <input type="text" name="subject" class="form-control" value="{{ $template->subject }}"
                                        required/>
                                </div>
                            </div>
                            <div class="input-area grid grid-cols-12 gap-5 mb-6">
                                <label for="" class="md:col-span-3 col-span-12 form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Write the main Messages here">
                                        {{ __('Message Body') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="md:col-span-9 col-span-12">
                                    <textarea name="message_body" class="summernote message-body" id="summernote" cols="30" rows="8">
                                        {!! $template->message_body!!}
                                    </textarea>
                                    <input type="hidden" name="html_message_body" class="html-message-body"/>
                                </div>
                            </div>
                            <div class="input-area grid grid-cols-12 gap-5 mb-6">
                                <label for="" class="md:col-span-3 col-span-12 form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Leave it blank if you don't need the button">
                                        {{ __('Button') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="md:col-span-4 col-span-12">
                                    <input type="text" name="button_level" class="form-control"
                                        value="{{ $template->button_level }}" required/>
                                </div>
                                <div class="md:col-span-5 col-span-12">
                                    <input type="text" name="button_link" class="form-control"
                                        value="{{ $template->button_link }}" required/>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-5 mb-6">
                                <label for="" class="md:col-span-3 col-span-12 form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Newslatter Bottom Status">
                                        {{ __('Secondary Message Body') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="md:col-span-9 col-span-12">
                                    <div class="input-area mb-5">
                                        <div class="form-switch ps-0">
                                            <input type="hidden" value="0" name="bottom_status">
                                            <label
                                                class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer secondary_message__toggle">
                                                <input type="checkbox" name="bottom_status" value="1" class="sr-only peer"
                                                    @checked( $template->bottom_status)>
                                                <span
                                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="input-area mb-5" id="secondary_message__body">
                                        <textarea name="bottom_body" class="summernote bottom-body"  cols="30" rows="8">
                                        {{ br2nl($template->bottom_body) }}
                                    </textarea>
                                        <input type="hidden" name="html_bottom_body" class="html-bottom-body"/>

                                    </div>

                                    <div class="grid lg:grid-cols-3 grid-cols-1 gap-5">
                                        <div class="flex items-center space-x-7 flex-wrap">
                                            <label class="form-label !w-auto pt-0">
                                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Toggle to activate or deactivate this template">
                                                    {{ __('Template Status') }}
                                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                                </span>
                                            </label>
                                            <div class="form-switch ps-0">
                                                <input type="hidden" value="0" name="status">
                                                <label
                                                    class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                    <input type="checkbox" name="status" value="1" class="sr-only peer"
                                                        @checked($template->status)>
                                                    <span
                                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-7 flex-wrap">
                                            <label class="form-label !w-auto pt-0">
                                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enable to append a disclaimer message to the email">
                                                    {{ __('Disclaimer') }}
                                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                                </span>
                                            </label>
                                            <div class="form-switch ps-0">
                                                <input type="hidden" value="0" name="is_disclaimer">
                                                <label
                                                    class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                    <input type="checkbox" name="is_disclaimer" value="1"
                                                        class="sr-only peer" @checked($template->is_disclaimer)>
                                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-7 flex-wrap">
                                            <label class="form-label !w-auto pt-0">
                                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enable to append a risk-related note to the email footer">
                                                    {{ __('Risk Warning') }}
                                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                                </span>
                                            </label>
                                            <div class="form-switch ps-0">
                                                <input type="hidden" value="0" name="is_risk_warning">
                                                <label
                                                    class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                    <input type="checkbox" name="is_risk_warning" value="1"
                                                        class="sr-only peer" @checked($template->is_risk_warning)>
                                                    <span
                                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Custom HTML Section -->
                        <div id="custom-html-section" style="{{ $template->use_custom_html ? '' : 'display: none;' }}">
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <iconify-icon icon="lucide:file-code" class="text-2xl text-green-400"></iconify-icon>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-800 dark:text-white">{{ __('Custom HTML Editor') }}</h4>
                                        <p class="text-gray-400 dark:text-gray-400 text-sm">{{ __('Write your complete HTML email template') }}</p>
                                    </div>
                                </div>
                                
                                <textarea name="custom_html_content" class="w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-lg text-green-400 font-mono text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" rows="20" placeholder="Enter your custom HTML code here...">{{ $template->custom_html_content }}</textarea>
                                
                                <div class="flex items-center gap-3">
                                    <iconify-icon icon="lucide:file-code" class="text-2xl text-green-400"></iconify-icon>
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-800 dark:text-white">{{ __('Example Template') }}</h4>
                                        <p class="text-gray-400 dark:text-gray-400 text-sm">
                                            {{ __('Example template for the HTML email template') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="px-4 py-3 bg-gray-800 border border-gray-600 rounded-lg text-green-400 text-xs overflow-x-auto">
                                    @include('backend.email.include.__example_html')
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end items-center mt-8 pt-6 border-t border-gray-200 dark:border-slate-600">
                            <button type="button" class="btn btn-dark inline-flex items-center justify-center email-template-form">
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
    </div>
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="templateTestModal" tabindex="-1" aria-labelledby="templateTestModalLabel" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="flex items-center justify-between p-5">
                <h3 class="text-xl font-medium dark:text-white capitalize" id="templateTestModalLabel">
                    {{ __('Test Email Template') }}
                </h3>
                <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                            dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                    <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="modal-body p-6">
                <form action="{{ route('admin.email-template.test') }}" method="post" class="space-y-4">
                    @csrf
                    <input type="hidden" name="template_id" value="{{ $template->id }}">
                    <div class="input-area !mt-0">
                        <label for="" class="form-label">{{ __('Recipient Email:') }}</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control mb-0"
                            required
                            placeholder="Enter email address to test"
                        />
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            {{ __('Send Test Email') }}
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
        $('.summernote').summernote({
            height: 150, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: true // set focus to editable area after initializing summernote
        });

        $('.email-template-form').on('click', function() {
            // console.log($('.message-body').html())
            var markupStr = $('.summernote').summernote('code');
            // console.log(markupStr);

            $('.html-message-body').val(markupStr.replace(/</g, '{').replace(/>/g, '}'))

            var bottom = $('.summernote').eq(1).summernote('code');

            // $('.html-message-body').val($('.note-editable').html().replace(/</g, '{').replace(/>/g, '}'))
            $('.html-bottom-body').val(bottom.replace(/</g, '{').replace(/>/g, '}'))
            // $('.html-bottom-body').val(bottom);
            $('#form-submit').submit()

        });

        $(document).ready(function() {
            $('.copy-button').click(function() {

                var targetSelector = $(this).data('target');
                var $input = $(targetSelector);

                $input.select();
                document.execCommand('copy');

                // Change the button text and style
                var $button = $(this);
                var $icon = $button.find('iconify-icon');
                $icon.addClass('text-success');
                $button.addClass('copy-button');

                // Revert the button text and style after 2 seconds
                setTimeout(function() {
                    $icon.removeClass('text-success');
                }, 2000);

            });
        });

         // Template mode toggle functionality
         $('.template-mode-toggle input[type="checkbox"]').on('change', function() {
             if ($(this).is(':checked')) {
                 // Switch to Custom HTML mode
                 $('#dynamic-template-section').slideUp();
                 $('#custom-html-section').slideDown();
             } else {
                 // Switch to Dynamic Template mode
                 $('#custom-html-section').slideUp();
                 $('#dynamic-template-section').slideDown();
             }
         });
    </script>
@endsection
