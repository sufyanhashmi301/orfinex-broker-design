@extends('backend.layouts.app')
@section('title')
    {{ __('Language Keywords') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Language Keywords') }}
        </h4>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <div class="mb-6 mt-3">
                <form action="{{ route('admin.language-keyword', ['language' => $language]) }}" method="get">
                    <div class="table-filter flex justify-between flex-wrap items-center">
                        <div class="filter inline-flex ltr:pr-4 rtl:pl-4 mb-2 sm:mb-0">
                            <div class="search input-area flex items-center">
                                <label for="" class="text-sm mr-2">{{ __('Search:') }}</label>
                                <input type="text" name="filter" class="form-control" value="{{ Request::get('filter') }}">
                            </div>
                        </div>
                        <div class="filter flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                            @include('backend.language.include.select', ['name' => 'language', 'items' => $languages, 'submit' => true, 'selected' => $language])
                            @include('backend.language.include.select', ['name' => 'group', 'items' => $groups, 'submit' => true, 'selected' => Request::get('group'), 'optional' => true])
                        </div>
                    </div>
                </form>
            </div>
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        @if(count($translations))
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="border-t border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th scope="col" class="table-th">{{ __('GROUP / SINGLE') }}</th>
                                    <th scope="col" class="table-th">{{ __('KEY') }}</th>
                                    <th scope="col" class="table-th">en</th>
                                    <th scope="col" class="table-th">{{$language}}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($translations as $type => $items)
                                @foreach($items as $group => $translations)
                                    @foreach($translations as $key => $value)
                                        @if(!is_array($value['en']))
                                            <tr>
                                                <td class="table-td">{{ $group }}</td>
                                                <td class="table-td">{{ $key }}</td>
                                                <td class="table-td">{{ $value['en'] }}</td>
                                                <td class="table-td">
                                                    {{ $value[$language] }}
                                                </td>
                                                <td class="table-td">
                                                    <button class="action-btn edit-language-keyword"
                                                        data-language="{{ $language }}"
                                                        data-group="{{ $group }}" data-key="{{ $key }}"
                                                        data-value="{{ $value[$language] }}"
                                                        data-bs-toggle="tooltip" title=""
                                                        data-bs-original-title="Edit Language">
                                                        <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Edit Language Key-->
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="editKeyword" tabindex="-1" aria-labelledby="editKeyword" aria-hidden="true">
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative max-w-xl w-full pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
                <div class="flex items-center justify-between p-5">
                    <h3 class="text-xl font-medium dark:text-white capitalize">
                        {{ __('Edit Keyword') }}
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
                <div class="modal-body popup-body p-6 pt-0">
                    <form action="{{ route('admin.language-keyword-update') }}" method="post">
                        @csrf
                        <div class="popup-body-text space-y-5">
                            <div class="input-area">
                                <label class="form-label key-label"></label>
                                <input type="hidden" class="form-control key-key" name="key">
                                <input type="text" class="form-control key-value" name="value">
                                <input type="hidden" class="form-control key-group" name="group">
                                <input type="hidden" class="form-control key-language" name="language">
                            </div>
                        </div>
                        <div class="action-btns text-right mt-10">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Save Changes') }}
                            </button>
                            <a href="" class="btn btn-danger inline-flex items-center justify-center" type="button" data-bs-dismiss="modal" aria-label="Close">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                {{ __('Close') }}
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Edit Language Key End-->
@endsection
@section('script')

    <script>
        (function ($) {
            "use strict";

            $('.edit-language-keyword').on('click',function (e) {

                var key = $(this).data('key');
                var value = $(this).data('value');
                var group = $(this).data('group');
                var language = $(this).data('language');


                $('.key-label').html(key);
                $('.key-key').val(key);
                $('.key-value').val(value);
                $('.key-group').val(group);
                $('.key-language').val(language);

                $('#editKeyword').modal('toggle')
            })


        })(jQuery);
    </script>
@endsection
