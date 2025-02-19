@extends('backend.links.index')
@section('page-title')
    <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
        {{ __('Social Links') }}
    </h4>
@endsection
@section('links-content')
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8 hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="socialLink-dataTable">
                            <thead>
                            <tr>
                                <th scope="col" class="table-th">{{ __('Title') }}</th>
                                <th scope="col" class="table-th">{{ __('URL') }}</th>
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

    {{--Modal for update social link--}}
    @can('social-link-edit')
        @include('backend.links.modal.__edit_social_link')
    @endcan

@endsection
@section('script')
    <script !src="">
        (function ($) {
            "use strict";
            var table = $('#socialLink-dataTable').DataTable();
            table.destroy();
            var table = $('#socialLink-dataTable').DataTable({
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
                ajax: "{{ route('admin.links.social.index') }}",
                columns: [
                    {data: 'title', name: 'title'},
                    {data: 'link', name: 'link'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'},
                ]
            });
        })(jQuery);

        $('body').on('click', '.editBtn', function (event){
            "use strict";
            event.preventDefault();
            $('#edit-social-link-body').empty();
            var recordId = $(this).data('id');
            var url = "{{ route('admin.links.social.edit', ':id') }}".replace(':id', recordId);

            $.get(url, function (response) {
                $('#editSocialLinkModal').modal('show');
                $('#edit-social-link-body').append(response);
            });
        });
    </script>
@endsection
