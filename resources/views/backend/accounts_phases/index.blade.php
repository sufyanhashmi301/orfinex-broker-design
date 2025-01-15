@extends('backend.layouts.app')
@section('title')
    {{ __('Accounts Activities') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{route('user.schema')}}" class="btn btn-primary inline-flex items-center justify-center">
                <span class="flex items-center">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="bi:plus"></iconify-icon>
                    <span>{{ __('Start Challenge') }}</span>
                </span>
            </a>
        </div>
    </div>
    <div class="innerMenu card p-6 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open w-full">
            <li class="nav-item" role="presentation">
                <a href="{{ route('admin.accounts-phases.log') }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ url()->current() === route('admin.accounts-phases.log') && empty(request()->query()) ? 'active' : '' }}" aria-controls="tabs-realAccounts" aria-selected="true">{{ __('All Logs') }}</a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="?pending-approvals" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->has('pending-approvals') ? 'active' : '' }}" aria-controls="tabs-demoAccounts" aria-selected="false">{{ __('Pending Approvals') }}</a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="?violated-acounts" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->has('violated-acounts') ? 'active' : '' }}" aria-controls="tabs-archivedAccounts" aria-selected="false">{{ __('Violated Accounts') }}</a>
            </li>
            @if (request()->has('unique_id'))
              <li class="nav-item" role="presentation">
                <a href="?violated-acounts" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->has('unique_id') ? 'active' : '' }}" aria-controls="tabs-archivedAccounts" aria-selected="false">{{ __( request('unique_id') . ' Logs') }}</a>
              </li>
            @endif
            <li class="nav-item !ml-auto">
                <a href="javascript:;" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 filter-toggle-btn">
                    <span class="flex items-center">
                        <span>{{ __('More') }}</span>
                        <iconify-icon icon="lucide:chevron-down" class="text-xl ltr:ml-2 rtl:mr-2 font-light"></iconify-icon>
                    </span>
                </a>
            </li>
        </ul>
        <div class="hidden mt-5" id="filters_div">
            <form id="filter-form" method="POST" action="">
                @csrf
                <div class="flex justify-between flex-wrap items-center">
                    <div class="flex-1 inline-flex sm:space-x-3 space-x-2 ltr:pr-4 rtl:pl-4 mb-2 sm:mb-0">
                        <div class="flex-1 input-area relative">
                            <input type="text" name="global_search" id="global_search" class="form-control h-full" placeholder="Search by Login or User">
                        </div>
                        <div class="flex-1 input-area relative">
                            <select name="country" id="country" class="select2 form-control h-full w-full" data-placeholder="{{ __('Select Phase Type') }}">
                                <option value="evaluation phase" selected>
                                    {{ __('evaluation phase') }}
                                </option>
                            </select>
                        </div>
                        <div class="flex-1 input-area relative">
                            <input type="date" name="created_at" id="created_at" class="form-control h-full" placeholder="Created At">
                        </div>
                    </div>
                    <div class="flex sm:space-x-3 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                        <div class="input-area relative">
                            <button type="submit" id="filter" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                                <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                                {{ __('Filter') }}
                            </button>
                        </div>
                        <div class="input-area relative">
                            <button type="button" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                                <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lets-icons:export-fill"></iconify-icon>
                                {{ __('Export') }}
                            </button>
                        </div>
                        <div class="input-area relative">
                            <button type="button" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white" data-bs-toggle="modal" data-bs-target="#configureModal">
                                <iconify-icon class="text-base font-light" icon="lucide:wrench"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="tab-content" id="">
                <div class="tab-pane fade show active" id="" role="tabpanel" aria-labelledby="">
                    @include('backend.accounts_phases.includes.logs')
                </div>
            </div>
        </div>
    </div>


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

        // grid or list view
        $('.list-view-btn').click(function () {
            const targetId = $(this).data('target');
            $('#' + targetId + ' .grid').removeClass('grid-view').addClass('list-view');
            $(this).addClass('active');
            $('.grid-view-btn').removeClass('active');
        });

        $('.grid-view-btn').click(function () {
            const targetId = $(this).data('target');
            $('#' + targetId + ' .grid').removeClass('list-view').addClass('grid-view');
            $(this).addClass('active');
            $('.list-view-btn').removeClass('active');
        });
    </script>
@endsection
