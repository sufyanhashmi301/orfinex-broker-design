@extends('backend.setting.index')
@section('title')
    {{ __('Risk Profile Tag') }}
@endsection
@section('setting-content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Risk Profile Tag Forms') }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="javascript:;" class="inline-flex items-center justify-center text-success-500" type="button" data-bs-toggle="modal" data-bs-target="#riskProfileTagModal">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                {{ __('Add New') }}
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
                                    <th scope="col" class="table-th">{{ __('Tag Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                            @foreach($riskProfileTags as $riskProfileTag)
                                <tr>
                                    <td class="table-td">
                                        <strong>{{ $riskProfileTag->name }}</strong>
                                    </td>
                                    <td class="table-td">
                                        @if( $riskProfileTag->status)
                                            <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">{{ __('Active') }}</div>
                                        @else
                                            <div class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">{{ __('Disabled') }}</div>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <button type="button" class="action-btn" data-id="{{$riskProfileTag->id}}" id="edit">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </button>
                                            <button type="button" data-id="{{ $riskProfileTag->id }}" data-name="{{ $riskProfileTag->name }}" class="action-btn deleteRiskProfileTag">
                                                <iconify-icon icon="lucide:trash-2"></iconify-icon>
                                            </button>
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

    <!-- Modal for Create deleteRiskProfileTagType -->
    @include('backend.risk_profile_tag.modal.__create_tag')

    <!-- Modal for Edit deleteRiskProfileTagType -->
    @include('backend.risk_profile_tag.modal.__edit_tag')

    <!-- Modal for Delete deleteRiskProfileTagType -->
    @include('backend.risk_profile_tag.modal.__delete_tag')

@endsection
@section('script')
    <script>
        $('.deleteRiskProfileTag').on('click',function (e) {
            "use strict";
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            var url = '{{ route("admin.risk-profile-tag.destroy", ":id") }}';
            url = url.replace(':id', id);
            $('#riskProfileTagEditForm').attr('action', url)

            $('.name').html(name);
            $('#deleteRiskProfileTag').modal('show');
        });

        $('body').on('click', '#edit', function (event) {
            "use strict";
            event.preventDefault();
            $('#edit-tag-body').empty();
            var id = $(this).data('id');

            $.get('risk-profile-tag/' + id + '/edit', function (data) {

                $('#editModal').modal('show');
                $('#edit-tag-body').append(data);

            })
        });
    </script>
@endsection
