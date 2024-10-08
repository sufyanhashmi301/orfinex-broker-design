@extends('backend.setting.misc.index')
@section('title')
    {{ __('Language Settings') }}
@endsection
@section('misc-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Language Settings') }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ route('admin.language-sync-missing') }}" class="btn btn-white inline-flex items-center justify-center mr-2">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:refresh-ccw"></iconify-icon>
                {{ __('Sync Missing Translation Keys') }}
            </a>
            <a href="{{ route('admin.language.create') }}" class="btn btn-primary inline-flex items-center justify-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                {{ __('Add New') }}
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Language Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Delete Language -->
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="deleteLanguage" tabindex="-1" aria-labelledby="deleteLanguage" aria-hidden="true">
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="modal-body popup-body">
                    <div class="popup-body-text p-6 py-8 text-center space-y-5">
                        <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-danger-500 text-danger-500 bg-opacity-30">
                            <iconify-icon class="text-4xl" icon="lucide:alert-triangle"></iconify-icon>
                        </div>
                        <div class="title">
                            <h4 class="text-xl font-medium dark:text-white capitalize">{{ __('Are you sure?') }}</h4>
                        </div>
                        <p>
                            {{ __('You want to delete') }}
                            <strong id="language-name"></strong> {{ __('Language?') }}
                        </p>
                        <div class="action-btns text-center">
                            <form id="deleteLanguageForm" method="post">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                    Confirm
                                </button>
                                <a href="" class="btn btn-danger inline-flex items-center justify-center" type="button"
                                    data-bs-dismiss="modal" aria-label="Close">
                                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                    {{ __('Cancel') }}
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Delete Language End-->
@endsection
@section('misc-script')
    <script>
        (function ($) {
            "use strict";

            var table = $('#dataTable').DataTable({
                dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
                searching: false,
                lengthChange: false,
                info: true,
                language: {
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        previous: "<iconify-icon icon=\"ic:round-keyboard-arrow-left\"></iconify-icon>",
                        next: "<iconify-icon icon=\"ic:round-keyboard-arrow-right\"></iconify-icon>"
                    },
                    search: "Search:"
                },
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('admin.language.index') }}",
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'},
                ]
            });


            $('body').on('click', '#deleteLanguageModal', function () {
                var id = $(this).data('id');
                var name = $(this).data('name');

                $('#language-name').html(name);
                var url = '{{ route("admin.language.destroy", ":id") }}';
                url = url.replace(':id', id);
                $('#deleteLanguageForm').attr('action', url);
                $('#deleteLanguage').modal('toggle')

            })

        })(jQuery);
    </script>
@endsection
