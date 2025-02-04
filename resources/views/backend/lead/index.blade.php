@extends('backend.layouts.app')
@section('title')
    {{ __('Leads') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ route('admin.lead.create') }}" class="btn btn-sm btn-primary inline-flex items-center justify-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                {{ __('Add New Lead') }}
            </a>
        </div>
    </div>

    <div class="flex items-stretch space-x-6 overflow-hidden overflow-x-auto pb-4 rtl:space-x-reverse">
        @foreach($stages as $stage)
            <div class="w-[320px] flex-none rounded transition-all duration-100 shadow-none bg-slate-200 dark:bg-slate-700">
                <div class="relative flex justify-between items-center bg-white dark:bg-slate-800 rounded shadow-base px-6 py-5">
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-[2px]" style="background-color: {{ $stage->label_color }}"></span>
                    <h3 class="text-lg text-slate-900 dark:text-white font-medium capitalize">
                        {{ $stage->name }}
                    </h3>
                </div>

                <div id="{{ __('stage-container__').$stage->id }}" class="min-h-full" data-stage-id="{{ $stage->id }}">
                    @foreach ($stage->leads as $lead)
                        <div class="p-2 h-full space-y-4 rounded-bl rounded-br cursor-grab" data-lead-id="{{ $lead->id }}">
                            <div class="card rounded-md bg-white dark:bg-slate-800 shadow-base custom-class card-body p-3">
                                <div class="flex items-center justify-between items-end">
                                    <div class="flex space-x-4 items-center rtl:space-x-reverse">
                                        <div class="flex-none">
                                            <div class="h-10 w-10 rounded-md text-lg bg-slate-100 text-slate-900 dark:bg-slate-600 dark:text-slate-200 flex flex-col items-center justify-center font-normal capitalize">
                                                {{ $lead->first_name[0].$lead->last_name[0] }}
                                            </div>
                                        </div>
                                        <div class="font-medium text-base leading-6">
                                            <div class="dark:text-slate-200 text-slate-900 max-w-[160px] truncate">
                                                {{ $lead->first_name.' '.$lead->last_name }}
                                            </div>
                                            <div class="text-xs font-normal text-slate-600 dark:text-slate-400">
                                                {{ $lead->client_email }}
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="dropdown">
                                            <button class="action-btn" type="button" id="cardDropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                <iconify-icon class="text-xl" icon="heroicons-outline:dots-vertical"></iconify-icon>
                                            </button>
                                            <ul class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                                <li>
                                                    <a href="{{ route('admin.lead.show', $lead->id) }}" class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse">
                                                        <iconify-icon icon="lucide:eye"></iconify-icon>
                                                        <span>{{ __('View') }}</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('admin.lead.edit', $lead->id) }}" class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse">
                                                        <iconify-icon icon="clarity:note-edit-line"></iconify-icon>
                                                        <span>{{ __('Edit') }}</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" type="button" data-id="{{ $lead->id }}" class="deleteLeadBtn hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse">
                                                        <iconify-icon icon="fluent:delete-28-regular"></iconify-icon>
                                                        <span>{{ __('Delete') }}</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" type="button" data-lead-id="{{ $lead->id }}" class="loseLeadBtn hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse">
                                                        <iconify-icon icon="material-symbols:close-rounded"></iconify-icon>
                                                        <span>{{ __('Close As Lose') }}</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

    </div>

    {{-- Modal for lead delete--}}
    @include('backend.lead.modal.__delete')

@endsection
@section('script')
    <script>
        dragula([
            @foreach($stages as $stage)
                document.getElementById("{{ __('stage-container__').$stage->id }}"),
            @endforeach
        ])
        .on('drop', function (el, target) {
            var leadId = el.getAttribute('data-lead-id');
            var stageId = target.getAttribute('data-stage-id');

            updateUserTag(leadId, stageId);
        });

        function updateUserTag(leadId, stageId) {
            fetch('{{ route('admin.lead.stageUpdate', ':lead') }}'.replace(':lead', leadId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    stage_id: stageId,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    tNotify('success', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        $('body').on('click', '.loseLeadBtn', function (event) {
            var leadId = $(this).data('lead-id');
            var stageId = 7;

            fetch('{{ route('admin.lead.stageUpdate', ':lead') }}'.replace(':lead', leadId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    stage_id: stageId,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    tNotify('success', data.message);
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        $('body').on('click', '.deleteLeadBtn', function (event) {
            "use strict";
            event.preventDefault();
            var id = $(this).data('id');

            var url = '{{ route("admin.lead.destroy", ":id") }}';
            url = url.replace(':id', id);
            $('#leadDeleteForm').attr('action', url)

            $('#deleteLead').modal('show');
        });

    </script>
@endsection
