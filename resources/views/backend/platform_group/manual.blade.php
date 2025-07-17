@extends('backend.platform_group.index')
@section('title')
    {{ __('Manual Platform Groups') }}
@endsection
@section('platform-group-content')
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Group') }}</th>
                                    <th scope="col" class="table-th">{{ __('Currency') }}</th>
                                    <th scope="col" class="table-th">{{ __('Digits') }}</th>
                                    <th scope="col" class="table-th">{{ __('Platform') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach($groups as $group)
                                    <tr>
                                        <td class="table-td">{{$group->group}}</td>
                                        <td class="table-td">{{$group->currency}}</td>
                                        <td class="table-td">{{$group->currencyDigits}}</td>
                                        <td class="table-td">{{$group->trader_type}}</td>
                                        <td class="table-td">
                                            @if($group->status)
                                                <span class="badge badge-success capitalize rounded-full bg-opacity-30">
                                                    {{ __('Enabled') }}
                                                </span>
                                            @else
                                                <span class="badge badge-danger capitalize rounded-full bg-opacity-30">
                                                    {{ __('Disabled') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="table-td">
                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                @can('manual-group-edit')
                                                <button class="action-btn editGroupBtn"
                                                        data-id="{{$group->id}}" type="button"
                                                        data-bs-toggle="tooltip" title=""
                                                        data-bs-placement="top"
                                                        data-bs-original-title="Edit Group">
                                                    <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                                </button>
                                                @endcan
                                                @can('manual-group-delete')
                                                <button type="button" class="action-btn deleteGroupBtn" data-id="{{ $group->id }}">
                                                    <iconify-icon icon="lucide:trash"></iconify-icon>
                                                </button>
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
    </div>

@endsection
@section('platform-script')
    <script !src="">
        $('body').on('click', '.editGroupBtn', function (event) {
            "use strict";
            event.preventDefault();
            $('#edit-group-body').empty();
            var id = $(this).data('id');

            $.get(id + '/edit', function (data) {

                $('#editManualGroupModal').modal('show');
                $('#edit-group-body').append(data);
                tippy(".shift-Away", {
                    placement: "top",
                    animation: "shift-away"
                });

            })
        })

        $('.deleteGroupBtn').on('click',function (e) {
            "use strict";
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            var url = '{{ route("admin.group.delete", ":id") }}';
            url = url.replace(':id', id);
            $('#manualGroupForm').attr('action', url)

            $('.name').html(name);
            $('#deleteManualGroup').modal('show');
        })
    </script>
@endsection
