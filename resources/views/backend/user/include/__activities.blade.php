<div class="tab-pane space-y-5 fade" id="pills-activities" role="tabpanel" aria-labelledby="pills-activities-tab">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('Activities') }}</h4>
        </div>
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="user-activity-dataTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Activity') }}</th>
                                    <th scope="col" class="table-th">{{ __('Description') }}</th>
                                    <th scope="col" class="table-th">{{ __('Time') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="processingIndicator text-center">
                {{-- <img src="{{ asset('global/images/loading.gif') }}" class="inline-block h-20" alt="Loader"> --}}
                <iconify-icon class="spining-icon text-5xl dark:text-slate-100" icon="lucide:loader"></iconify-icon>
            </div>
        </div>
    </div>
</div>
@push('single-script')
    <script>
        (function ($) {
            "use strict";
            var table = $('#user-activity-dataTable')
                .on('processing.dt', function(e, settings, processing) {
                    $('.processingIndicator').css('display', processing ? 'block' : 'none');
                }).DataTable({
                    dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
                    searching: false,
                    lengthChange: false,
                    info: true,
                    order: [[0, 'desc']],
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
                    ajax: "{{ route('admin.activity-logs.user.activities',$user->id) }}",
                    columns: [
                        {data: 'action', name: 'action'},
                        {data: 'description', name: 'description'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'action_btn', name: 'action_btn', orderable: false, searchable: false},
                    ]
            });
        })(jQuery);

        $(document).on("click", ".view-activity", function () {
            let id = $(this).data("id");

            $.ajax({
                url: "{{ route('admin.activity-logs.show', ['id' => ':id']) }}".replace(':id', id),
                type: "GET",
                data: { id: id },
                success: function (res) {
                    $("#act_action").text(res.action);
                    $("#act_description").text(res.description ?? '-');
                    $("#act_ip").text(res.ip);
                    $("#act_agent").text(res.agent);
                    $("#act_location").text(res.location ?? '-');
                    $("#act_datetime").text(res.created_at);

                    let metaHtml = '';

                    if (res.meta && typeof res.meta === 'object' && Object.keys(res.meta).length > 0) {
                        metaHtml += '<div class="flex flex-col gap-2">';
                        $.each(res.meta, function (key, value) {
                            metaHtml += `
                                <div class="flex justify-between border-b border-slate-200 dark:border-slate-700 px-2 py-1 rounded">
                                    <span class="font-medium text-slate-900 dark:text-white text-sm">${key}</span>
                                    <span class="text-slate-600 dark:text-slate-200 text-xs">${value ?? '-'}</span>
                                </div>
                            `;
                        });
                        metaHtml += '</div>';
                    } else {
                        metaHtml = '<span class="text-muted">-</span>';
                    }

                    $("#act_meta").html(metaHtml);

                    $("#activityDetailsModal").modal("show");
                }
            });
        });
    </script>
@endpush