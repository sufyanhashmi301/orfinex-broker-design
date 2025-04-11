@extends('backend.layouts.app')
@section('title')
   {{ $title }}
@endsection

@section('content')

   
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ $title }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ route('admin.user.create') }}" class="btn btn-primary inline-flex items-center justify-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                {{ __('Add Customer') }}
            </a>
        </div>
    </div>
    <div class="innerMenu card p-6 mb-5">
        <ul class="nav nav-pills flex items-center overflow-x-auto list-none pl-0 pb-1 md:pb-0 gap-4 menu-open w-full">
            <li class="nav-item">
                <a href="{{route('admin.user.index', ['status' => 'all'])}}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize text-nowrap rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->get('status') == 'all' ? 'active' : '' }} ">
                    {{ __('All Customers') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('admin.user.index', ['status' => 'active'])}}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize text-nowrap rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->get('status') == 'active' ? 'active' : '' }}">
                    {{ __('Active Customers') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('admin.user.index', ['status' => 'disabled'])}}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize text-nowrap rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->get('status') == 'disabled' ? 'active' : '' }}">
                    {{ __('Disabled Customers') }}
                </a>
            </li>
            <div class="!ml-auto">
                <li class="nav-item mr-2" style="display: inline-block">
                    <a href="{{ route('admin.export.all_users') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize text-nowrap rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300">
                        <span class="flex items-center">
                            <span>{{ __('Export') }}</span>
                            <iconify-icon icon="lucide:share" class="text-lg ltr:ml-2 rtl:mr-2 font-light"></iconify-icon>
                        </span>
                    </a>
                </li>
                <li class="nav-item" style="display: inline-block">
                    <a href="javascript:;" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize text-nowrap rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 filter-toggle-btn">
                        <span class="flex items-center">
                            <span>{{ __('More') }}</span>
                            <iconify-icon icon="lucide:chevron-down" class="text-xl ltr:ml-2 rtl:mr-2 font-light"></iconify-icon>
                        </span>
                    </a>
                </li>
            </div>
        </ul>

        <div class="hidden mt-5" id="filters_div">
            <form id="filter-form" method="GET" action="{{route('admin.user.index')}}">
                <div class="flex flex-col sm:flex-row justify-between flex-wrap sm:items-center gap-3">
                    <div class="flex-1 w-full flex flex-col sm:flex-row sm:gap-3 gap-2">
                        <div class="flex-1 input-area relative">
                            <input type="text" name="search" id="global_search" class="form-control h-full" placeholder="Search by Name, Username, Email or Phone">
                        </div>
                        <div class="flex-1 input-area relative">
                            <select name="country" id="country" class="select2 form-control h-full w-full" data-placeholder="{{ __('Select a country') }}">
                                <option value="" selected>
                                    {{ __('country') }}
                                </option>
                                @foreach( getCountries() as $country)
                                    <option value="{{ $country['name'] }}">
                                        {{ $country['name']  }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="flex-1 input-area relative">
                            <input type="date" name="created_at" id="created_at" class="form-control h-full" placeholder="Created At">
                        </div> --}}
                    </div>
                    <div class="flex sm:space-x-3 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                        <div class="input-area relative">
                            <button type="submit" id="filter" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                                <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                                {{ __('Filter') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @yield('customers-content')
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.filter-toggle-btn').click(function() {
                const $content = $('#filters_div');

                if ($content.hasClass('hidden')) {
                    $content.removeClass('hidden').slideDown();
                } else {
                    $content.slideUp(function() {
                        $content.addClass('hidden');
                    });
                }
            });
        });
    </script>
    @yield('customers-script')
@endsection
