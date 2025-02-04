@extends('backend.layouts.app')
@section('title')
    {{ __('Lead Details') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        <div class="dropdown relative ">
            <button class="btn btn-sm inline-flex justify-center btn-dark items-center cursor-default relative !pr-14" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                {{ __('Actions') }}
                <span class="cursor-pointer absolute ltr:border-l rtl:border-r border-slate-100 h-full ltr:right-0 rtl:left-0 px-2 flex items-center justify-center leading-none">
                    <iconify-icon class="leading-none text-xl" icon="ic:round-keyboard-arrow-down"></iconify-icon>
                </span>
            </button>
            <ul class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                <li>
                    <a href="{{ route('admin.lead.edit', $lead->id) }}" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                        {{ __('Edit') }}
                    </a>
                </li>
                <li>
                    <a href="#" type="button" data-id="{{ $lead->id }}" class="deleteLeadBtn text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                        {{ __('Delete') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.lead.createClient', $lead->id) }}" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                        {{ __('Change To Client') }}
                    </a>
                </li>
                <li>
                    <a href="#" type="button" data-lead-id="{{ $lead->id }}" class="loseLeadBtn text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                        <iconify-icon icon="material-symbols:close-rounded"></iconify-icon>
                        <span>{{ __('Close As Lose') }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <div class="grid grid-cols-12 gap-3">
                <div class="lg:col-span-8 col-span-12">
                    <ul class="space-y-3">
                        <li class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-300 py-2 first:uppercase">
                            <div class="flex justify-between">
                                <span>{{ __('Name') }}</span>
                                <span>{{ $lead->first_name.' '.$lead->last_name }}</span>
                            </div>
                        </li>
                        <li class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-300 py-2 first:uppercase">
                            <div class="flex justify-between">
                                <span>{{ __('Email') }}</span>
                                <span>{{ $lead->client_email }}</span>
                            </div>
                        </li>
                        <li class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-300 py-2 first:uppercase">
                            <div class="flex justify-between">
                                <span>{{ __('Lead Owner') }}</span>
                                <span>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-[100%]">
                                            <img src="{{ asset($leadOwner->avatar ?? 'global/materials/user.png') }}" class="w-full h-full rounded-[100%] object-cover" alt="">
                                        </div>
                                        <span>
                                            {{ $leadOwner->first_name .' '. $leadOwner->last_name }}
                                        </span>
                                        <span class="badge badge-primary">
                                            {{ $leadOwner->getRoleNames()->first() }}
                                        </span>
                                    </div>
                                </span>
                            </div>
                        </li>
                        <li class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-300 py-2 first:uppercase">
                            <div class="flex justify-between">
                                <span>{{ __('Lead Stage') }}</span>
                                <span>
                                    <span class="flex space-x-2 rtl:space-x-reverse items-center">
                                        <span class="inline-flex h-[10px] w-[10px] rounded-full" style="background-color: {{ $stage->label_color }}"></span>
                                        <span>{{ $stage->name }}</span>
                                    </span>
                                </span>
                            </div>
                        </li>
                        <li class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-300 py-2 first:uppercase">
                            <div class="flex justify-between">
                                <span>{{ __('Lead Source') }}</span>
                                <span>{{ $source->name }}</span>
                            </div>
                        </li>
                        <li class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-300 py-2 first:uppercase">
                            <div class="flex justify-between">
                                <span>{{ __('Company Name') }}</span>
                                <span>{{ $lead->company_name ?? '-'  }}</span>
                            </div>
                        </li>
                        <li class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-300 py-2 first:uppercase">
                            <div class="flex justify-between">
                                <span>{{ __('Website') }}</span>
                                <span>{{ $lead->website ?? '-'  }}</span>
                            </div>
                        </li>
                        <li class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-300 py-2 first:uppercase">
                            <div class="flex justify-between">
                                <span>{{ __('Mobile') }}</span>
                                <span>{{ $lead->phone }}</span>
                            </div>
                        </li>
                        <li class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-300 py-2 first:uppercase">
                            <div class="flex justify-between">
                                <span>{{ __('Office Phone Number') }}</span>
                                <span>{{ $lead->office_phone_number ?? '-'  }}</span>
                            </div>
                        </li>
                        <li class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-300 py-2 first:uppercase">
                            <div class="flex justify-between">
                                <span>{{ __('Country') }}</span>
                                <span>{{ $lead->country ?? '-'  }}</span>
                            </div>
                        </li>
                        <li class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-300 py-2 first:uppercase">
                            <div class="flex justify-between">
                                <span>{{ __('State') }}</span>
                                <span>{{ $lead->state ?? '-'  }}</span>
                            </div>
                        </li>
                        <li class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-300 py-2 first:uppercase">
                            <div class="flex justify-between">
                                <span>{{ __('City') }}</span>
                                <span>{{ $lead->city ?? '-'  }}</span>
                            </div>
                        </li>
                        <li class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-300 py-2 first:uppercase">
                            <div class="flex justify-between">
                                <span>{{ __('Postal Code') }}</span>
                                <span>{{ $lead->postal_code ?? '-'  }}</span>
                            </div>
                        </li>
                        <li class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-300 py-2 first:uppercase">
                            <div class="flex justify-between">
                                <span>{{ __('Address') }}</span>
                                <span>{{ $lead->address ?? '-' }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal for lead delete--}}
    @include('backend.lead.modal.__delete')

@endsection
@section('script')
    <script !src="">

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
