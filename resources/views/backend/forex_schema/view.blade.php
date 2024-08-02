@extends('backend.layouts.app')
@section('title')
    {{ __('Edit Account Type') }}
@endsection
@section('content')
    <div class="card mb-10">
        <div class="card-body p-6">
            <div class="grid lg:grid-cols-2 grid-cols-1 gap-7">
                <div class="h-full flex flex-col">
                    <h6 class="text-xl text-slate-900 dark:text-slate-300 mb-5">
                        {{ __('Account name') }}
                        <span class="text-sm text-slate-500 dark:text-slate-300 ml-2">
                            {{ __('Standard Account') }}
                        </span>
                    </h6>
                    <div class="input-area relative mt-auto">
                        <p class="text-base text-slate-900 dark:text-slate-300 mb-5">
                            {{ __('Default Group ') }}
                        </p>
                        <label for="" class="form-label">
                            {{ __('Platform Group') }}
                        </label>
                        <input
                            type="text"
                            name="real_swap_free"
                            value="{{$schema->real_swap_free}}"
                            class="form-control"
                            placeholder="Platform Group"
                            required
                        />
                    </div>
                </div>
                <div class="h-full space-y-5">
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('Trading Server (Live) ') }}
                        </label>
                        <select name="" class="select2 form-control w-full">
                            <option value="severname">{{ __('ServerName') }}</option>
                        </select>
                    </div>
                    <div class="input-area !mb-7">
                        <div class="flex items-center space-x-5 flex-wrap">
                            <div class="form-switch ps-0" style="line-height:0;">
                                <label class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-checkbox" data-target="#live-islamic-group">
                                    <input
                                        type="checkbox"
                                        name="is_real_islamic"
                                        value="1"
                                        class="sr-only peer"
                                        @if($schema->is_real_islamic) checked @endif
                                    >
                                    <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                </label>
                            </div>
                            <label class="form-label !w-auto pt-0 !mb-0">
                                {{ __('Enable Separate Swap-Free (Islamic)') }}
                            </label>
                        </div>
                    </div>
                    <div id="live-islamic-group" class="@if(!$schema->is_real_islamic) hidden @endif">
                        <div class="input-area">
                            <label class="form-label" for="">{{ __('Platform Group - Swap Free') }}</label>
                            <input
                                type="text"
                                name="real_islamic"
                                value="{{$schema->real_islamic}}"
                                class="form-control"
                                placeholder="Platform Group (Islamic)"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 grid-cols-1 gap-7">
        <div>
            <div class="flex justify-between flex-wrap items-center mb-5">
                <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                    {{ __('Swap Based Accounts') }}
                </h4>
                <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                    <a href="javascript:;" class="btn btn-primary inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#addLevelModal">
                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                        {{ __('Add Level') }}
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-6 pt-3">
                    <div class="overflow-x-auto -mx-6">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden ">
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="table-th">{{ __('ID') }}</th>
                                            <th scope="col" class="table-th">{{ __('Title') }}</th>
                                            <th scope="col" class="table-th">{{ __('Level Order') }}</th>
                                            <th scope="col" class="table-th">{{ __('Status') }}</th>
                                            <th scope="col" class="table-th">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="table-td">
                                                {{ __('1') }}
                                            </td>
                                            <td class="table-td">
                                                {{ __('Level 1') }}
                                            </td>
                                            <td class="table-td">
                                                {{ __('1') }}
                                            </td>
                                            <td class="table-td">
                                                <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
                                                    {{ __('Enable') }}
                                                </div>
                                            </td>
                                            <td class="table-td">
                                                <div class="flex space-x-3 rtl:space-x-reverse">
                                                    <a href="javascript:;" class="action-btn">
                                                        <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                                    </a>
                                                    <button class="action-btn" type="button">
                                                        <iconify-icon icon="lucide:trash"></iconify-icon>
                                                    </button>
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
        </div>
        <div>
            <div class="flex justify-between flex-wrap items-center mb-5">
                <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                    {{ __('Swap Free Accounts') }}
                </h4>
                <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                    <a href="javascript:;" class="btn btn-primary inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#addLevelModal">
                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                        {{ __('Add Level') }}
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-6 pt-3">
                    <div class="overflow-x-auto -mx-6">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden ">
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="table-th">{{ __('ID') }}</th>
                                        <th scope="col" class="table-th">{{ __('Title') }}</th>
                                        <th scope="col" class="table-th">{{ __('Level Order') }}</th>
                                        <th scope="col" class="table-th">{{ __('Status') }}</th>
                                        <th scope="col" class="table-th">{{ __('Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="table-td">
                                            {{ __('1') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('Level 1') }}
                                        </td>
                                        <td class="table-td">
                                            {{ __('1') }}
                                        </td>
                                        <td class="table-td">
                                            <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
                                                {{ __('Enable') }}
                                            </div>
                                        </td>
                                        <td class="table-td">
                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                <a href="javascript:;" class="action-btn">
                                                    <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                                </a>
                                                <button class="action-btn" type="button">
                                                    <iconify-icon icon="lucide:trash"></iconify-icon>
                                                </button>
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
        </div>
    </div>

    {{-- Modal for Add Level --}}
    @include('backend.forex_schema.modal.__create')

@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('.toggle-checkbox').change(function() {
                var target = $(this).data('target');
                $(target).toggleClass('hidden');
            });
        });
    </script>
@endsection
