@extends('frontend::layouts.user')
@section('title')
    {{ __('Legal Agreements') }}
@endsection
@section('content')
    <div class="mb-5">
        <ul class="m-0 p-0 list-none">
            <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter ">
                <a href="{{route('user.dashboard')}}">
                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                    <iconify-icon icon="heroicons-outline:chevron-right"
                                  class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                </a>
            </li>
            <li class="inline-block relative text-sm text-primary-500 font-Inter ">
                {{ __('Settings') }}
                <iconify-icon icon="heroicons-outline:chevron-right"
                              class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ __('Portal Guide') }}
            </li>
        </ul>
    </div>
    <div class="card">
        <header class=" card-header">
            <h4 class="card-title">
                {{ __('Portal Guide') }}
            </h4>
        </header>
        <div class="card-body p-6">

            <ul class="list-item space-y-3 h-full overflow-x-auto">
                {{--                {{dd(setting('client_agreement_show','',false))}}--}}
                @if(setting('client_portal_show','document_links',false))
                    <li class="flex items-center space-x-3 rtl:space-x-reverse border-b border-slate-100 dark:border-slate-700 last:border-b-0 pb-3 last:pb-0">
                        <div>
                            <div
                                class="w-10 h-10 lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mr-2">
                                <iconify-icon icon="iconoir:card-security"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex-1 text-start overflow-hidden text-ellipsis whitespace-nowrap">
                            <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                Client Portal Policy
                            </h4>
                            <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                PDF
                            </div>
                        </div>
                        <div class="ltr:text-right rtl:text-left">
                            <a href="{{setting('client_portal_link','document_links','javascript:void(0);')}}"
                               class="btn inline-flex justify-center btn-dark btn-sm w-auto md:w-[250px]"
                               target="_blank">
                            <span class="flex items-center">
                                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="quill:link-out"></iconify-icon>
                                <span>{{ __('Read Client Portal Agreement') }}</span>
                            </span>
                            </a>
                        </div>
                    </li>
                @endif
                @if(setting('partnership_show','document_links',false))
                    <li class="flex items-center space-x-3 rtl:space-x-reverse border-b border-slate-100 dark:border-slate-700 last:border-b-0 pb-3 last:pb-0">
                        <div>
                            <div
                                class="w-10 h-10 lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mr-2">
                                <iconify-icon icon="ph:handshake-thin"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex-1 text-start overflow-hidden text-ellipsis whitespace-nowrap">
                            <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                Partnership Policy
                            </h4>
                            <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                PDF
                            </div>
                        </div>
                        <div class="ltr:text-right rtl:text-left">
                            <a href="{{setting('partnership_link','document_links','javascript:void(0);')}}"
                               class="btn inline-flex justify-center btn-dark btn-sm w-auto md:w-[250px]"
                               target="_blank">
                            <span class="flex items-center">
                                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="quill:link-out"></iconify-icon>
                                <span>{{ __('Read Partnership Policy') }}</span>
                            </span>
                            </a>
                        </div>
                    </li>
                @endif
                @if(setting('copy_trading_show','document_links',false))
                    <li class="flex items-center space-x-3 rtl:space-x-reverse border-b border-slate-100 dark:border-slate-700 last:border-b-0 pb-3 last:pb-0">
                        <div>
                            <div
                                class="w-10 h-10 lg:bg-slate-100 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center mr-2">
                                <iconify-icon icon="mdi:document-sign"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex-1 text-start overflow-hidden text-ellipsis whitespace-nowrap">
                            <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                Copy Trading Policy
                            </h4>
                            <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                PDF
                            </div>
                        </div>
                        <div class="ltr:text-right rtl:text-left">
                            <a href="{{setting('copy_trading_link','document_links','javascript:void(0);')}}"
                               class="btn inline-flex justify-center btn-dark btn-sm w-auto md:w-[250px]"
                               target="_blank">
                            <span class="flex items-center">
                                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="quill:link-out"></iconify-icon>
                                <span>{{ __('Read Copy Trading Policy') }}</span>
                            </span>
                            </a>
                        </div>
                    </li>
                @endif

            </ul>

        </div>
    </div>
@endsection
