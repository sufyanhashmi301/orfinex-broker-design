@extends('backend.setting.lead.index')
@section('title')
    {{ __('Lead Pipeline') }}
@endsection
@section('title-btns')
    <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
        @can('lead-pipeline-create')
        <a href="javascript:;" class="btn btn-sm btn-primary inline-flex items-center justify-center"
           type="button"
           data-bs-toggle="modal"
           data-bs-target="#newPipelineModal">
            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
            {{ __('Add New Pipeline') }}
        </a>
        @endcan
        @can('lead-stage-create')
        <a href="javascript:;" class="btn btn-sm btn-primary inline-flex items-center justify-center" type="button" id="addStage">
            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
            {{ __('Add New Deal Stage') }}
        </a>
        @endcan
    </div>
@endsection
@section('lead-setting-content')
    <div class="card">
        <div class="card-body basicTable_wrapper p-6">
            @if(! $pipelines->isEmpty())
            <div class="space-y-4">
                @foreach($pipelines as $pipeline)
                    <div class="border dark:border-slate-700 rounded py-3 px-4">
                        <div class="grid grid-cols-12 items-center gap-5">
                            <div class="lg:col-span-8 col-span-12 space-y-5">
                                <div class="flex">
                                    <span class="inline-flex h-3 w-3 rounded-full mt-1" style="background: {{ $pipeline->label_color }}"></span>
                                    <div class="flex-1 ml-2">
                                        <div class="flex text-slate-800 dark:text-slate-300 text-sm font-medium mb-1">
                                            <span class="mr-1">{{ $pipeline->name }}</span>
                                            @can('lead-pipeline-edit')
                                            <a href="javascript:;" data-pipeline-id="{{ $pipeline->id }}" class="edit-pipeline inline-flex text-primary">
                                                <iconify-icon class="text-base" icon="lucide:edit"></iconify-icon>
                                            </a>
                                            @endcan
                                        </div>
                                        <div class="text-slate-400 dark:text-slate-400 text-xs">
                                            {{ $pipeline->stages->count() }}{{ __(' Deal Stages') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="lg:col-span-4 col-span-12 space-y-5">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center space-x-2 mr-7">
                                        <label class="set_default_pipeline relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer" data-pipeline-id="{{ $pipeline->id }}">
                                            <input type="checkbox" class="sr-only peer" @if($pipeline->default) checked @endif>
                                            <div class="w-14 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:z-10 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></div>
                                        </label>
                                        <span class="text-sm text-slate-600 font-Inter font-normal">{{ __('Default') }}</span>
                                    </div>
                                    <div class="flex space-x-3 rtl:space-x-reverse">
                                        @can('lead-pipeline-delete')
                                        @if(!$pipeline->default)
                                            <button type="button" data-pipeline-id="{{ $pipeline->id }}" data-pipeline-name="{{ $pipeline->name }}" class="deletePipeline toolTip onTop action-btn" data-tippy-content="delete pipeline">
                                                <iconify-icon icon="lucide:trash"></iconify-icon>
                                            </button>
                                        @endif
                                        @endcan
                                        @can('lead-stage-list')
                                        <button type="button" data-pipeline-id="{{ $pipeline->id }}" class="view-pipeline toolTip onTop action-btn" data-tippy-content="show stages">
                                            <iconify-icon icon="lucide:sliders-vertical"></iconify-icon>
                                        </button>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border dark:border-slate-700 rounded px-6 pb-3 hidden" id="pipeline-stages-table__{{ $pipeline->id }}">
                        <div class="overflow-x-auto -mx-6">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden">
                                    <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="table-th">{{ __('#') }}</th>
                                                <th scope="col" class="table-th">{{ __('Deal Stage') }}</th>
                                                <th scope="col" class="table-th">{{ __('Default Deal Stage') }}</th>
                                                <th scope="col" class="table-th">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pipeline->stages as $stage)
                                                <tr>
                                                    <td class="table-td">{{ $stage->id }}</td>
                                                    <td class="table-td">
                                                        <span class="flex space-x-2 rtl:space-x-reverse items-center">
                                                            <span class="inline-flex h-[10px] w-[10px] rounded-full" style="background-color: {{ $stage->label_color }}"></span>
                                                            <span>{{ $stage->name }}</span>
                                                        </span>
                                                    </td>
                                                    <td class="table-td">
                                                        <div class="success-radio">
                                                            <label class="set_default_stage flex items-center cursor-pointer" data-status-id="{{ $stage->id }}">
                                                                <input type="radio" class="hidden" value="{{ $stage->slug }}" @if($stage->default) checked @endif>
                                                                <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[12px] w-[12px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                                                <span class="text-success-500 text-sm leading-6 capitalize">{{ __('Default') }}</span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="table-td">
                                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                                            @can('lead-stage-edit')
                                                            <button class="editStage action-btn" data-id="{{ $stage->id }}">
                                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                                            </button>
                                                            @endcan
                                                            @can('lead-stage-delete')
                                                            @if (!$stage->default && $stage->slug != 'generated' &&  $stage->slug != 'win' && $stage->slug != 'lost' )
                                                                <button type="button" data-id="{{ $stage->id }}" data-name="{{ $stage->name }}" class="action-btn deleteStageBtn">
                                                                    <iconify-icon icon="lucide:trash"></iconify-icon>
                                                                </button>
                                                            @endif
                                                            @endcan
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @else
                <p class="text-xs text-center">{{ __('No data available in table') }}</p>
            @endif
        </div>
    </div>

    {{-- Modal for new pipeline--}}
    @include('backend.setting.lead.pipeline.modal.__create_modal')

    {{-- Modal for edit pipeline--}}
    @include('backend.setting.lead.pipeline.modal.__edit_modal')

    {{-- Modal for delete pipeline--}}
    @include('backend.setting.lead.pipeline.modal.__delete_modal')

    {{-- Modal for new deal stage--}}
    @include('backend.setting.lead.stage.modal.__create_modal')

    {{-- Modal for edit deal stage--}}
    @include('backend.setting.lead.stage.modal.__edit_modal')

    {{-- Modal for delete stage--}}
    @include('backend.setting.lead.stage.modal.__delete_modal')

@endsection
@section('user-management-script')
    <script !src="">
        // Function to synchronize text and color inputs in the same group
        function syncGroupInputs(group) {
            var $textInput = $(group).find('.text-input');
            var $colorInput = $(group).find('.color-input');

            $textInput.on('input', function() {
                var colorValue = $(this).val();
                if (isValidColor(colorValue)) {
                    $colorInput.val(colorValue).css('background-color', colorValue);
                }
            });

            $colorInput.on('input', function() {
                var colorValue = $(this).val();
                $textInput.val(colorValue);
            });
        }

        // Function to validate color input
        function isValidColor(value) {
            var s = new Option().style;
            s.color = value;
            return s.color !== '';
        }

        // Initialize synchronization for each input group
        $('.color-input-group').each(function() {
            syncGroupInputs(this);
        });

        $('body').on('click', '.view-pipeline', function() {

            var pipelineId = $(this).data('pipeline-id');
            $("#pipeline-stages-table__"+pipelineId).toggleClass('hidden');

        });

        /* open edit pipeline modal */
        $('body').on('click', '.edit-pipeline', function() {
            var pipelineId = $(this).data('pipeline-id');
            var url = "{{ route('admin.lead.pipeline.edit', ':id ') }}";
            url = url.replace(':id', pipelineId);

            $('#pipeline-modal-body').empty();

            $.get(url, function (data) {

                $('#editPipelineModal').modal('show');
                $('#pipeline-modal-body').append(data);
                $('.color-input-group').each(function() {
                    syncGroupInputs(this);
                });
                tippy(".shift-Away", {
                    placement: "top",
                    animation: "shift-away"
                });

            })
        });

        $('body').on('click', '.deletePipeline', function (event) {
            "use strict";
            event.preventDefault();
            var id = $(this).data('pipeline-id');
            var name = $(this).data('pipeline-name');

            var url = '{{ route("admin.lead.pipeline.destroy", ":id") }}';
            url = url.replace(':id', id);

            $('#pipelineDeleteForm').attr('action', url)

            $('.name').html(name);
            $('#deletePipeline').modal('show');
        });


        $('body').on('click', '.set_default_stage', function() {
            var id = $(this).data('status-id');

            var url = "{{ route('admin.lead.stage.statusUpdate', ':id') }}";
            url = url.replace(':id', id);

            $.get(url, function(response) {
                if (response.status == "success") {
                    window.location.reload();
                }
            })

        });

        $('body').on('click', '.set_default_pipeline', function() {
            var id = $(this).data('pipeline-id');

            var url = "{{ route('admin.lead.pipeline.statusUpdate', ':id') }}";
            url = url.replace(':id', id);

            // Make the GET request to update the pipeline's status
            $.get(url, function(response) {
                if (response.status == "success") {
                    window.location.reload();
                }
            })
        });

        /* open add stage modal */
        $('body').on('click', '#addStage', function() {
            var url = "{{ route('admin.lead.stage.create') }}";

            $('#create-stage-body').empty();

            $.get(url, function (data) {
                $('#newDealStageModal').modal('show');
                $('#create-stage-body').append(data);
                $('.select2').select2({
                    dropdownParent: $('#newDealStageModal'),
                });
                tippy(".shift-Away", {
                    placement: "top",
                    animation: "shift-away"
                });
            });
        });

        $('body').on('click', '.editStage', function (event) {
            "use strict";
            event.preventDefault();
            $('#edit-stage-body').empty();
            var id = $(this).data('id');

            $.get('stage/' + id + '/edit', function (data) {

                $('#editStageModal').modal('show');
                $('#edit-stage-body').append(data);
                $('.select2').select2({
                    dropdownParent: $('#editStageModal'),
                });
                tippy(".shift-Away", {
                    placement: "top",
                    animation: "shift-away"
                });

            })
        });

        $('body').on('click', '.deleteStageBtn', function (event) {
            "use strict";
            event.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            var url = '{{ route("admin.lead.stage.destroy", ":id") }}';
            url = url.replace(':id', id);
            $('#stageDeleteForm').attr('action', url)

            $('.name').html(name);
            $('#deleteStage').modal('show');
        });

    </script>
@endsection
