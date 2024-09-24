@extends('backend.layouts.app')
@section('title')
    {{ __('Announcements') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="javascript:;" class="btn btn-dark inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#newAnnouncementModal">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                {{ __('Add Announcement') }}
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                            <tr>
                                <th scope="col" class="table-th">{{ __('Name') }}</th>
                                <th scope="col" class="table-th">{{ __('Date') }}</th>
                                <th scope="col" class="table-th">{{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                <tr>
                                    <td class="table-td">
                                        {{ __('Involved in this way! Stop this moment, I tell you!') }}
                                    </td>
                                    <td class="table-td">{{ __('2020-05-01 06:05:46') }}</td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <a href="javascript:;" class="action-btn">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </a>
                                            <a href="javascript:;" class="action-btn">
                                                <iconify-icon icon="lucide:trash"></iconify-icon>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-td">
                                        {{ __('Involved in this way! Stop this moment, I tell you!') }}
                                    </td>
                                    <td class="table-td">{{ __('2020-05-01 06:05:46') }}</td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <a href="javascript:;" class="action-btn">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </a>
                                            <a href="javascript:;" class="action-btn">
                                                <iconify-icon icon="lucide:trash"></iconify-icon>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('backend.announcements.modals.__create')
@endsection
