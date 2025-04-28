@extends('backend.layouts.app')
@section('title')
    {{ __('Deal Details') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ $deal->name }}
        </h4>
    </div>
    <div class="grid grid-cols-12 gap-5">
        <div class="lg:col-span-8 col-span-12">
            <div class="card">
                <div class="card-header noborder">
                    <h4 class="card-title">{{ __('Deal Info') }}</h4>
                    <div>
                        <div class="relative">
                            <div class="dropdown relative">
                                <button class="text-xl text-center block w-full " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="text-lg inline-flex h-6 w-6 flex-col items-center justify-center border border-slate-200 dark:border-slate-700 rounded dark:text-slate-400">
                                        <iconify-icon icon="heroicons-outline:dots-vertical"></iconify-icon>
                                    </span>
                                </button>
                                @can('deal-action')
                                <ul class="dropdown-menu min-w-[120px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                    <li>
                                        <a href="{{ route('admin.deal.edit', $deal->id) }}" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                            {{ __('Edit') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" type="button" data-id="{{ $deal->id }}" class="deleteDealBtn text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                            {{ __('Delete') }}
                                        </a>
                                    </li>
                                </ul>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-6 pt-3">
                    <div class="grid grid-cols-12 gap-3">
                        <div class="lg:col-span-8 col-span-12">
                            <div class="flex items-center gap-2 mb-5">
                                <span class="flex space-x-2 rtl:space-x-reverse items-center">
                                    <span class="inline-flex h-[12px] w-[12px] rounded-full" style="background-color: {{ $deal->pipeline->label_color }}"></span>
                                    <span class="font-medium">{{ $deal->pipeline->name }}</span>
                                </span>
                                <iconify-icon class="text-lg" icon="bi:arrow-right"></iconify-icon>
                                <span class="flex space-x-2 rtl:space-x-reverse items-center">
                                    <span class="inline-flex h-[12px] w-[12px] rounded-full" style="background-color: {{ $deal->pipelineStage->label_color }}"></span>
                                    <span class="font-medium">{{ $deal->pipelineStage->name }}</span>
                                </span>
                            </div>
                            <ul class="space-y-3">
                                <li class="text-sm text-slate-600 dark:text-slate-300 py-2">
                                    <div class="flex justify-between">
                                        <span>{{ __('Deal Name') }}</span>
                                        <span>{{ $deal->name }}</span>
                                    </div>
                                </li>
                                <li class="text-sm text-slate-600 dark:text-slate-300 py-2">
                                    <div class="flex justify-between">
                                        <span>{{ __('Lead Contact') }}</span>
                                        <span class="capitalize">{{ $deal->lead->salutation.' '.$deal->lead->first_name.' '.$deal->lead->last_name }}</span>
                                    </div>
                                </li>
                                <li class="text-sm text-slate-600 dark:text-slate-300 py-2">
                                    <div class="flex justify-between">
                                        <span>{{ __('Email') }}</span>
                                        <span>{{ $deal->lead->client_email }}</span>
                                    </div>
                                </li>
                                <li class="text-sm text-slate-600 dark:text-slate-300 py-2">
                                    <div class="flex justify-between">
                                        <span>{{ __('Company Name') }}</span>
                                        <span>{{ $deal->lead->company_name }}</span>
                                    </div>
                                </li>
                                <li class="text-sm text-slate-600 dark:text-slate-300 py-2">
                                    <div class="flex justify-between">
                                        <span>{{ __('Close Date') }}</span>
                                        <span>{{ $deal->close_date }}</span>
                                    </div>
                                </li>
                                <li class="text-sm text-slate-600 dark:text-slate-300 py-2">
                                    <div class="flex justify-between">
                                        <span>{{ __('Deal Value') }}</span>
                                        <span>{{ $deal->value.' '.setting('site_currency', 'global')  }}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-4 col-span-12">
            <div class="card">
                <div class="card-header noborder">
                    <h4 class="card-title">{{ __('Lead Contact Detail') }}</h4>
                </div>
                <div class="card-body p-6 pt-3">
                    <ul class="space-y-3">
                        <li class="text-sm text-slate-600 dark:text-slate-300 py-2">
                            <div class="flex justify-between">
                                <span>{{ __('Lead Contact') }}</span>
                                <span class="w-2/3">{{ $deal->lead->first_name.' '.$deal->lead->last_name }}</span>
                            </div>
                        </li>
                        <li class="text-sm text-slate-600 dark:text-slate-300 py-2">
                            <div class="flex justify-between">
                                <span>{{ __('Email') }}</span>
                                <span class="w-2/3">{{ $deal->lead->client_email }}</span>
                            </div>
                        </li>
                        <li class="text-sm text-slate-600 dark:text-slate-300 py-2">
                            <div class="flex justify-between">
                                <span>{{ __('Mobile') }}</span>
                                <span class="w-2/3">{{ $deal->lead->phone }}</span>
                            </div>
                        </li>
                        <li class="text-sm text-slate-600 dark:text-slate-300 py-2">
                            <div class="flex justify-between">
                                <span>{{ __('Company Name') }}</span>
                                <span class="w-2/3">{{ $deal->lead->company_name }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="lg:col-span-8 col-span-12">
            <div class="card">
                <div class="card-header noborder">
                    <h4 class="card-title">{{ __('Deal Notes') }}</h4>
                </div>
                <div class="card-body px-6 pb-3">
                    <div class="overflow-x-auto -mx-6">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden ">
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                    <thead class="border-t border-slate-100 dark:border-slate-800">
                                        <tr>
                                            <th scope="col" class="table-th">{{ __('Details') }}</th>
                                            <th scope="col" class="table-th">{{ __('Created at') }}</th>
                                            <th scope="col" class="table-th">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                        @if($deal->notes->isEmpty())
                                            <tr>
                                                <td class="table-td text-center" colspan="4">
                                                    {{ __('No notes available for this deal.') }}
                                                </td>
                                            </tr>
                                        @else
                                            @foreach($deal->notes as $note)
                                                <tr>
                                                    <td class="table-td">{{ $note->details }}</td>
                                                    <td class="table-td">{{ $note->created_at }}</td>
                                                    <td class="table-td">
                                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                                            <button class="editNote action-btn" data-id="{{ $note->id }}">
                                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                                            </button>
                                                            <button type="button" data-id="{{ $note->id }}" class="action-btn deleteNoteBtn">
                                                                <iconify-icon icon="lucide:trash"></iconify-icon>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Modal for deal delete--}}
    @include('backend.deals.modal.__delete')

    {{-- Modal for note update--}}
    @include('backend.deals.modal.__edit_note')

    {{-- Modal for note delete--}}
    @include('backend.deals.modal.__delete_note')

@endsection
@section('script')
    <script !src="">

        $('body').on('click', '.deleteDealBtn', function (event) {
            "use strict";
            event.preventDefault();
            var id = $(this).data('id');

            var url = '{{ route("admin.deal.destroy", ":id") }}';
            url = url.replace(':id', id);

            $('#dealDeleteForm').attr('action', url)
            $('#deleteDeal').modal('show');
        });

        /* open edit note modal */
        $('body').on('click', '.editNote', function() {
            var noteId = $(this).data('id');

            var url = "{{ route('admin.deal.note.edit', ':id ') }}";
            url = url.replace(':id', noteId);

            $('#note-modal-body').empty();

            $.get(url, function (data) {

                $('#editNoteModal').modal('show');
                $('#note-modal-body').append(data);

            })
        });

        $('body').on('click', '.deleteNoteBtn', function (event) {
            "use strict";
            event.preventDefault();
            var id = $(this).data('id');

            var url = '{{ route("admin.deal.note.destroy", ":id") }}';
            url = url.replace(':id', id);

            $('#noteDeleteForm').attr('action', url)
            $('#deleteNote').modal('show');
        });

    </script>
@endsection
