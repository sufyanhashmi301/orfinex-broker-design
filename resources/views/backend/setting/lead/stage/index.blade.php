@extends('backend.setting.lead.index')
@section('title')
    {{ __('Lead Stage') }}
@endsection
@section('title-btns')
    <a href="javascript:;" class="btn btn-sm btn-primary inline-flex items-center justify-center"
       type="button"
       data-bs-toggle="modal"
       data-bs-target="#newStageModal">
        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
        {{ __('Add New Stage') }}
    </a>
@endsection
@section('lead-setting-content')
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                            <tr>
                                <th scope="col" class="table-th">{{ __('#') }}</th>
                                <th scope="col" class="table-th">{{ __('Name') }}</th>
                                <th scope="col" class="table-th">{{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($stages as $stage)
                                <tr>
                                    <td class="table-td">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="table-td">
                                        <span class="flex space-x-2 rtl:space-x-reverse items-center">
                                            <span class="inline-flex h-[10px] w-[10px] rounded-full" style="background-color: {{ $stage->label_color }}"></span>
                                            <span>{{ $stage->name }}</span>
                                        </span>
                                    </td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <button class="action-btn" id="editStage" data-id="{{ $stage->id }}">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </button>
                                            <button type="button" data-id="{{ $stage->id }}" data-name="{{ $stage->name }}" class="action-btn deleteStageBtn">
                                                <iconify-icon icon="lucide:trash"></iconify-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto">
                            <div>
                                @php
                                    $from = $stages->firstItem(); // The starting item number on the current page
                                    $to = $stages->lastItem(); // The ending item number on the current page
                                    $total = $stages->total(); // The total number of items
                                @endphp

                                <p class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-medium">{{ $from }}</span>
                                    to
                                    <span class="font-medium">{{ $to }}</span>
                                    of
                                    <span class="font-medium">{{ $total }}</span>
                                    results
                                </p>
                            </div>
                            {{ $stages->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--    Modal for new lead stage --}}
    @include('backend.setting.lead.stage.modal.__create_modal')

    {{--    Modal for edit stage --}}
    @include('backend.setting.lead.stage.modal.__edit_modal')

    {{--    Modal for delete stage --}}
    @include('backend.setting.lead.stage.modal.__delete_modal')

@endsection
@section('user-management-script')
    <script>
        $(document).ready(function() {

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

            $('body').on('click', '#editStage', function (event) {
                "use strict";
                event.preventDefault();
                $('#edit-stage-body').empty();
                var id = $(this).data('id');

                $.get('stage/' + id + '/edit', function (data) {

                    $('#editStageModal').modal('show');
                    $('#edit-stage-body').append(data);
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

        });
    </script>
@endsection
