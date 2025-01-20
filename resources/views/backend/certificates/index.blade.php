@extends('backend.layouts.app')
@section('title')
    {{ $title }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0" style="text-transform: capitalize">
            @yield('title')
        </h4>
    </div>
    <div class="innerMenu card p-6 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open w-full">

            <form id="filter-form" method="GET" action="{{ route('admin.certificates.index') }}">
                <div class="flex justify-between flex-wrap items-center">
                    <div class="flex-1 inline-flex sm:space-x-3 space-x-2 ltr:pr-4 rtl:pl-4 mb-2 sm:mb-0">
                        
                        <div class="input-area relative">
                            <select name="hook" id="Certificate Hook Type" class="select2 form-control h-full w-full"
                                data-placeholder="{{ __('Select Certificate Hook ') }}">
                                  <option value="" hidden>Select Certificate Hook</option>
                                  @foreach ($certificates as $certificate)
                                    <option value="{{ $certificate->hook }}">{{ str_replace('_', ' ', $certificate->hook) }}</option>
                                  @endforeach
                    
                            </select>
                        </div>
                        
                    </div>
                    <style>
                      .select2-selection {
                        width: 300px;
                        text-transform: capitalize
                      }
                      .select2-results__option {
                        text-transform: capitalize
                      }
                    </style>
                    <div class="flex sm:space-x-3 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                        <div class="input-area relative">
                            <button type="submit" id="filter"
                                class="btn  inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white" style="padding-top: 8px; padding-bottom: 8px">
                                <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light"
                                    icon="lucide:filter"></iconify-icon>
                                {{ __('Filter') }}
                            </button>
                        </div>
                       
                    </div>
                </div>
            </form>

        </ul>

    </div>

    @include('backend.certificates.includes.__certificates_awarded')
@endsection
@section('script')
    <script></script>
@endsection
