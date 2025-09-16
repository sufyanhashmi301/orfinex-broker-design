@extends('backend.layouts.app')
@section('title')
    {{ __('Pending KYC') }}
@endsection
@section('filters')
    <form id="filter-form" method="POST" action="{{ route('admin.kyc.export',['type' => 'level2']) }}">
        @csrf
        <div class="flex flex-col sm:flex-row justify-between flex-wrap sm:items-center gap-3">
            <div class="flex-1 w-full flex flex-col sm:flex-row sm:gap-3 gap-2">
                <div class="flex-1 input-area relative">
                    <input type="text" name="global_search" id="global_search" class="form-control h-full" placeholder="Search by Name, Username, Email">
                </div>
                <div class="flex-1 input-area relative">
                    <select name="status" class="form-control h-full" id="status">
                        <option value="">Status</option>
                        <option value="1">{{__('Level1'), }}</option>
                        <option value="2">{{ __('Pending'),}}</option>
                        <option value="3">{{__('Rejected'), }}</option>
                        <option value="4">{{__('Level2'), }}</option>
                        <option value="5">{{ __('PendingLevel3'),}}</option>
                        <option value="6">{{__('RejectLevel3'), }}</option>
                        <option value="7">{{__('Level3'), }}</option>
                    </select>
                </div>

                <div class="flex-1 input-area relative">
                    <input type="date" name="created_at" id="created_at" class="form-control h-full" placeholder="Created At">
                </div>

            </div>
            <div class="flex sm:space-x-3 space-x-2 sm:justify-end items-center">
                <div class="input-area relative">
                    <button type="button" id="filter" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                        {{ __('Apply Filter') }}
                    </button>
                </div>
                @can('kyc-export')
                <div class="input-area relative">
                    <button type="submit" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                        <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lets-icons:export-fill"></iconify-icon>
                        {{ __('Export') }}
                    </button>
                </div>
                @endcan
                <div class="input-area relative">
                    <button type="button" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white" data-bs-toggle="modal" data-bs-target="#configureModal">
                        <iconify-icon class="text-base font-light" icon="lucide:wrench"></iconify-icon>
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Pending KYC') }}
        </h4>
    </div>
    @include('backend.kyc.include.__menu')
    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="pending-kyc-dataTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Date') }}</th>
                                    <th scope="col" class="table-th">{{ __('User') }}</th>
                                    <th scope="col" class="table-th">{{ __('Type') }}</th>
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
            <div id="processingIndicator" class="text-center">
                {{-- <img src="{{ asset('global/images/loading.gif') }}" class="inline-block h-20" alt="Loader"> --}}
                <iconify-icon class="spining-icon text-5xl dark:text-slate-100" icon="lucide:loader"></iconify-icon>
            </div>
        </div>
    </div>
    <!-- Modal for Pending KYC Details -->
    @can('kyc-action')
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
        id="kyc-action-modal"
        tabindex="-1"
        aria-labelledby="kyc-action-modal"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-lg top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
                <div class="modal-body space-y-5" id="kyc-action-data">

                </div>
            </div>
        </div>
    </div>
    @endcan
    <!-- Modal for Pending KYC Details -->
@endsection
@section('script')
    <script>
        (function ($) {
            "use strict";

            var table = $('#pending-kyc-dataTable')
            .on('processing.dt', function (e, settings, processing) {
                $('#processingIndicator').css('display', processing ? 'block' : 'none');
            }).DataTable({
                dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
                processing: true,
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
                    search: "Search:",
                    processing: '<iconify-icon icon="lucide:loader"></iconify-icon>'
                },
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.kyc.pending') }}",
                    data: function (d) {
                        d.global_search = $('#global_search').val();
                        d.status = $('#status').val();
                        d.created_at = $('#created_at').val();
                    }
                },
                columns: [
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'user', name: 'user',orderable : false},
                    {data: 'type', name: 'type',orderable : false},
                    {data: 'status', name: 'status',orderable : false},
                    {data: 'action', name: 'action',orderable : false},
                ]
            });
            $('#filter').click(function () {
                table.draw();
            });
            $('#filter-form').on('keypress', function(e) {
                if (e.which === 13) { // 13 is the Enter key code
                    e.preventDefault(); // Prevent form submission
                    table.draw(); // Trigger filtering only
                    return false;
                }
            });
            $('#global_search').keyup(function() {
                table.draw();
            });
        })(jQuery);

        $('body').on('click', '#action-kyc', function (e) {
            "use strict";
            e.preventDefault()
            $('#kyc-action-data').empty();

            var id = $(this).data('id');
            var url = '{{ route("admin.kyc.action",":id") }}';
            url = url.replace(':id', id);
            $.get(url, function (data) {

                $('#kyc-action-data').append(data)
                imagePreview()
                if (typeof tippy === 'function') {
                    tippy(".shift-Away", { placement: "top", animation: "shift-away" });
                }
                if ($.fn.select2) {
                    $('#kyc-comment-select, #kyc-comment-select-level3').select2();
                }
                var fillKycMessage = function(desc){
                    var val = desc || '';
                    if (typeof val === 'string') { try { val = JSON.parse(val); } catch(e) {} }
                    var $ta = $('textarea[name="message"]');
                    if ($ta.length) { $ta.val(val); } else { $('input[name="message"]').val(val); }
                };
                $('#kyc-comment-select, #kyc-comment-select-level3').off('change.kyc').on('change.kyc', function(){
                    var desc = $(this).find('option:selected').data('description');
                    fillKycMessage(desc);
                });
            })

            $('#kyc-action-modal').modal('toggle')
        });
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
@endsection
