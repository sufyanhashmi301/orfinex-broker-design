@extends('backend.setting.site_setting.index')
@section('title')
    {{ __('Comments') }}
@endsection
@section('site-setting-content')
    <div class="card">
        <div class="card-body p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h4 class="text-lg font-medium text-slate-700 dark:text-slate-200">{{ __('Comments') }}</h4>
                    <p class="text-slate-500 text-sm mt-1 max-w-[500px]">{{ __('Create reusable comment templates to quickly prefill descriptions for deposits, withdraws, and KYC actions. Enable only the ones you want to use.') }}</p>
                </div>
                @can('comments-create-settings')
                <button type="button" class="btn btn-dark inline-flex items-center" data-bs-toggle="modal" data-bs-target="#commentCreateModal">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                    {{ __('Add New') }}
                </button>
                @endcan
            </div>

            <form id="filter-form" class="mb-5">
                <div class="flex flex-col sm:flex-row justify-between flex-wrap sm:items-center gap-3">
                    <div class="flex-1 w-full flex flex-col sm:flex-row sm:gap-3 gap-2">
                        <div class="flex-1 input-area relative">
                            <input type="text" id="filter-title" class="form-control h-full" placeholder="{{ __('Search by Title') }}">
                        </div>
                        <div class="flex-1 input-area relative">
                            <select id="filter-type" class="form-control h-full">
                                <option value="">{{ __('All Types') }}</option>
                                @foreach(\App\Enums\CommentType::cases() as $type)
                                    <option value="{{ $type->value }}">{{ __($type->label()) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex sm:space-x-3 space-x-2 sm:justify-end items-center">
                        <div class="input-area relative">
                            <button type="button" id="filter-apply" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                                <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                                {{ __('Filter') }}
                            </button>
                        </div>
                        <div class="input-area relative">
                            <button type="button" id="filter-reset" class="btn btn-sm btn-outline inline-flex items-center justify-center min-w-max">
                                <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:rotate-ccw"></iconify-icon>
                                {{ __('Reset') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="relative px-0 pt-0">
                <div class="overflow-x-auto -mx-0 dashcode-data-table">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                <thead>
                                <tr>
                                    <th scope="col" class="table-th w-[28%]">{{ __('Title') }}</th>
                                    <th scope="col" class="table-th w-[12%]">{{ __('Type') }}</th>
                                    <th scope="col" class="table-th w-[35%]">{{ __('Description') }}</th>
                                    <th scope="col" class="table-th w-[15%]">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th ltr:text-right rtl:text-left w-[120px]">{{ __('Action') }}</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @forelse($comments as $comment)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700" data-title="{{ Str::lower($comment->title) }}" data-type="{{ $comment->type }}">
                                        <td class="table-td font-medium text-slate-700 dark:text-slate-200 w-[28%]">{{ $comment->title }}</td>
                                        <td class="table-td w-[12%]">
                                            @php
                                                $typeEnum = \App\Enums\CommentType::tryFrom($comment->type);
                                                $typeLabel = $typeEnum ? $typeEnum->label() : \Illuminate\Support\Str::headline(str_replace('_',' ',$comment->type));
                                            @endphp
                                            <span class="badge bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-200">{{ $typeLabel }}</span>
                                        </td>
                                        <td class="table-td w-[35%]"><div class="truncate max-w-[520px]" title="{{ $comment->description }}">{{ \Illuminate\Support\Str::words(strip_tags($comment->description), 9, '...') }}</div></td>
                                        <td class="table-td w-[15%]">
                                            @if($comment->status)
                                                <span class="badge bg-success-500 text-white">{{ __('Active') }}</span>
                                            @else
                                                <span class="badge bg-danger-500 text-white">{{ __('Disabled') }}</span>
                                            @endif
                                        </td>
                                        <td class="table-td ltr:text-right rtl:text-left whitespace-nowrap w-[120px] pr-6">
                                            <div class="inline-flex items-center gap-2 justify-end">
                                            @can('comments-edit-settings')
                                            <button type="button"
                                                    class="action-btn toolTip onTop"
                                                    data-tippy-theme="dark"
                                                    data-tippy-content="{{ __('Edit') }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#commentEditModal"
                                                    data-id="{{ $comment->id }}"
                                                    data-title='{{ e($comment->title) }}'
                                                    data-type="{{ $comment->type }}"
                                                    data-description='{{ e($comment->description) }}'
                                                    data-status="{{ (int) $comment->status }}"
                                                    data-update-url="{{ route('admin.page.comments.update', $comment) }}">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </button>
                                            @endcan
                                            @can('comments-delete-settings')
                                            <button type="button"
                                                    class="action-btn text-danger-500 toolTip onTop"
                                                    data-tippy-theme="dark"
                                                    data-tippy-content="{{ __('Delete') }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#commentDeleteModal"
                                                    data-id="{{ $comment->id }}"
                                                    data-title="{{ $comment->title }}"
                                                    data-delete-url="{{ route('admin.page.comments.destroy', $comment) }}">
                                                <iconify-icon icon="lucide:trash-2"></iconify-icon>
                                            </button>
                                            @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="table-td text-center py-12">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center mb-4">
                                                    <iconify-icon icon="lucide:message-square" class="text-3xl text-slate-400"></iconify-icon>
                                                </div>
                                                <h4 class="text-slate-500 dark:text-slate-400 font-medium mb-2">{{ __('No Comments Found') }}</h4>
                                                <p class="text-slate-400 text-sm mb-4">{{ __('Add your first comment to get started.') }}</p>
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#commentCreateModal">
                                                    <iconify-icon class="text-base ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                                                    {{ __('Add Comment') }}
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-4">
                {{ $comments->links() }}
            </div>
        </div>
    </div>
@can('comments-create-settings')
    @include('backend.page.comments.modal.__create_modal')
    @endcan
    @can('comments-edit-settings')
    @include('backend.page.comments.modal.__edit_modal')
    @endcan
    @can('comments-delete-settings')
    @include('backend.page.comments.modal.__delete_modal')
    @endcan
@endsection

@section('website-script')
<script>
    (function($){
        'use strict';
        $(document).ready(function(){
            if (typeof tippy !== 'undefined') {
                tippy('.toolTip', { theme: 'dark', placement: 'top' });
                tippy('.shift-Away', { placement: 'top', animation: 'shift-away' });
            }

            function applyFilters(){
                var titleText = ($('#filter-title').val() || '').toLowerCase();
                var typeVal = $('#filter-type').val();
                $('tbody tr').each(function(){
                    var row = $(this);
                    var rTitle = (row.data('title') || '').toString();
                    var rType = (row.data('type') || '').toString();
                    var show = true;
                    if (titleText && rTitle.indexOf(titleText) === -1) show = false;
                    if (typeVal && rType !== typeVal) show = false;
                    row.toggle(show);
                });
            }

            $('#filter-apply').on('click', applyFilters);
            $('#filter-title,#filter-type').on('keyup change', function(e){
                if (e.type === 'keyup' && e.key !== 'Enter') return;
                applyFilters();
            });
            $('#filter-reset').on('click', function(){
                $('#filter-title').val('');
                $('#filter-type').val('');
                applyFilters();
            });
        });
        $('#commentEditModal').on('show.bs.modal', function (event) {
            var trigger = $(event.relatedTarget);
            if (!trigger.is('button')) { trigger = trigger.closest('button'); }
            var title = trigger.attr('data-title') || '';
            var type = trigger.attr('data-type') || '';
            var description = trigger.attr('data-description') || '';
            var updateUrl = trigger.attr('data-update-url');

            var modal = $(this);
            modal.find('form').attr('action', updateUrl);
            modal.find('input[name="title"]').val(title);
            modal.find('select[name="type"]').val(type);
            modal.find('textarea[name="description"]').val(description);
            // Set status toggle
            var isActive = trigger.data('status');
            if (typeof isActive !== 'undefined') {
                modal.find('input[name="status"]').prop('checked', !!isActive);
            }
        });

        $('#commentDeleteModal').on('show.bs.modal', function (event) {
            var trigger = $(event.relatedTarget);
            if (!trigger.is('button')) { trigger = trigger.closest('button'); }
            var title = trigger.attr('data-title') || '';
            var deleteUrl = trigger.attr('data-delete-url');

            var modal = $(this);
            modal.find('.name').text(title);
            modal.find('form').attr('action', deleteUrl);
        });
    })(jQuery);
</script>
@endsection


