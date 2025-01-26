@extends('backend.layouts.app')

@php
    use App\Enums\AccountType;

    // Retrieve the type parameter and check if it’s one of the valid enum values
    $type = request('type') && in_array(request('type'), [AccountType::CHALLENGE, AccountType::FUNDED, AccountType::AUTO_EXPIRE]) 
            ? request('type') 
            : AccountType::CHALLENGE;
@endphp

@section('title')
    {{ str_replace('_', ' ', $type) . __(' Accounts') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>

       

        <div class="flex sm:space-x-3 space-x-2 sm:justify-end items-center rtl:space-x-reverse">

            
            <a href="javascript:void(0)" class="btn btn-primary inline-flex items-center" type="button" data-bs-toggle="modal" data-bs-target="#config-modal">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:bolt"></iconify-icon>
                Configure Parameters
            </a>
            
            
            <a href="{{route('admin.account-type.create', ['type' => $type  ])}}" class="btn btn-primary inline-flex items-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                {{ __('Add New') }}
            </a>
        </div>
    </div>
    {{-- <div class="innerMenu card p-6 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open w-full">
            <li class="nav-item">
                <a href="javascript:;" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 active">
                    {{ __('MT5') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300">
                    {{ __('Coming Soon') }}
                </a>
            </li>
        </ul>
    </div> --}}

    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper" style="min-height: calc(100vh - 385px);">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Title') }}</th>
                                    <th scope="col" class="table-th">{{ __('Badge') }}</th>
                                    <th scope="col" class="table-th">{{ __('# of Phases') }}</th>
                                    <th scope="col" class="table-th">{{ __('Trading Platform') }}</th>
                                    {{-- <th scope="col" class="table-th">{{ __('Priority') }}</th> --}}
                                    {{-- <th scope="col" class="table-th">{{ __('Leverage') }}</th> --}}
                                    {{-- <th scope="col" class="table-th">{{ __('Country/Tags') }}</th> --}}

                                    <th scope="col" class="table-th">{{ __('Auto Expire') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @forelse($account_types as $account_type)
                                <tr>
                                    <td class="table-td">
                                        {{$account_type->title}}
                                    </td>
                 
                                    {{-- <td class="table-td">
                                        @if( null != $account_type->countries) {{ implode(', ', json_decode($account_type->countries,true)) }} @endif
                                    </td> --}}
                                    <td class="table-td">
                                        <div @class([
                                        'badge bg-opacity-30 capitalize', // common classes
                                        'bg-success-500 text-success-500' => $account_type->badge,
                                        'bg-warning-500 text-warning-500' => !$account_type->badge
                                        ])>{{ $account_type->badge ? $account_type->badge : 'No Feature Badge' }}</div>
                                    </td>

                                    <td class="table-td">
                                        <span class="badge badge-primary">{{ count($account_type->accountTypePhases) }} Phases</span>
                                    </td>

                                    <td class="table-td">
                                        {{$account_type->trader_type}}
                                    </td>

                                    <td class="table-td">
                                        @if ($account_type->is_trial == 1)
                                            <span class="text-success-500" data-bs-toggle="tooltip" data-bs-title="Active">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1.75rem" height="1.75rem" viewBox="0 0 24 24">
                                                    <path fill="currentColor" fill-rule="evenodd" d="M12 1.25C6.063 1.25 1.25 6.063 1.25 12S6.063 22.75 12 22.75S22.75 17.937 22.75 12S17.937 1.25 12 1.25M7.53 11.97a.75.75 0 0 0-1.06 1.06l3 3a.75.75 0 0 0 1.06 0l7-7a.75.75 0 0 0-1.06-1.06L10 14.44z" clip-rule="evenodd"></path>
                                                </svg>
                                            </span>
                                        @else
                                            <span class="text-danger-500" data-bs-toggle="tooltip" data-bs-title="Unverified">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1.75rem" height="1.75rem" viewBox="0 0 24 24">
                                                    <path fill="currentColor" fill-rule="evenodd" d="M12 1.25C6.063 1.25 1.25 6.063 1.25 12S6.063 22.75 12 22.75S22.75 17.937 22.75 12S17.937 1.25 12 1.25M9.702 8.641a.75.75 0 0 0-1.061 1.06L10.939 12l-2.298 2.298a.75.75 0 0 0 1.06 1.06L12 13.062l2.298 2.298a.75.75 0 0 0 1.06-1.06L13.06 12l2.298-2.298a.75.75 0 1 0-1.06-1.06L12 10.938z" clip-rule="evenodd"></path>
                                                </svg>
                                            </span>
                                        @endif
                                    </td>

                                    <td class="table-td">

                                        @if ($account_type->status)
                                            <span class="text-success-500" data-bs-toggle="tooltip" data-bs-title="Active">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1.75rem" height="1.75rem" viewBox="0 0 24 24">
                                                    <path fill="currentColor" fill-rule="evenodd" d="M12 1.25C6.063 1.25 1.25 6.063 1.25 12S6.063 22.75 12 22.75S22.75 17.937 22.75 12S17.937 1.25 12 1.25M7.53 11.97a.75.75 0 0 0-1.06 1.06l3 3a.75.75 0 0 0 1.06 0l7-7a.75.75 0 0 0-1.06-1.06L10 14.44z" clip-rule="evenodd"></path>
                                                </svg>
                                            </span>
                                        @else
                                            <span class="text-danger-500" data-bs-toggle="tooltip" data-bs-title="Unverified">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1.75rem" height="1.75rem" viewBox="0 0 24 24">
                                                    <path fill="currentColor" fill-rule="evenodd" d="M12 1.25C6.063 1.25 1.25 6.063 1.25 12S6.063 22.75 12 22.75S22.75 17.937 22.75 12S17.937 1.25 12 1.25M9.702 8.641a.75.75 0 0 0-1.061 1.06L10.939 12l-2.298 2.298a.75.75 0 0 0 1.06 1.06L12 13.062l2.298 2.298a.75.75 0 0 0 1.06-1.06L13.06 12l2.298-2.298a.75.75 0 1 0-1.06-1.06L12 10.938z" clip-rule="evenodd"></path>
                                                </svg>
                                            </span>
                                        @endif
                                        
                                    </td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                        {{-- @can('schema-edit')
                                                <a href="{{route('admin.multi-level.view',$account_type->id)}}" class="action-btn">
                                                    <iconify-icon icon="lucide:eye"></iconify-icon>
                                                </a>
                                            @endcan --}}
                                            @can('schema-edit')
                                                <a href="{{route('admin.account-type.edit',$account_type->id)}}" class="action-btn">
                                                    <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                                </a>
                                            @endcan
                                            @can('schema-delete')
                                                <a href="#" class="action-btn delete-schema-btn" data-id="{{ $account_type->id }}">
                                                    <iconify-icon icon="lucide:trash"></iconify-icon>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td class="pt-4" colspan="9">
                                            <small><center>No {{ str_replace('_', ' ', $type) }} account types available</center></small>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto">
                            <div>
                                @php
                                    $from = $account_types->firstItem(); // The starting item number on the current page
                                    $to = $account_types->lastItem(); // The ending item number on the current page
                                    $total = $account_types->total(); // The total number of items
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
                            {{ $account_types->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modals --}}
    @include('backend.account_types.include.__config_modal')
    @include('backend.account_types.include.__delete')

@endsection

@section('script')

<script>
    $(document).ready(function () {
        let deleteSchemaId = null;

        // Event listener for delete buttons
        $('.delete-schema-btn').on('click', function (e) {
            e.preventDefault();
            deleteSchemaId = $(this).data('id');
            $('#deleteConfirmationModal').modal('show');
        });

        // Event listener for the confirm delete button in the modal
        $('#confirmDeleteButton').on('click', function () {
            const input = $('#deleteConfirmationInput').val();
            if (input.toLowerCase() === 'delete') {
                // Create a form and submit it
                const form = $('<form>', {
                    'method': 'POST',
                    'action': '{{ route('admin.account-type.destroy', ':id') }}'.replace(':id', deleteSchemaId)
                });

                // Add the CSRF token and method fields
                const csrfToken = $('meta[name="csrf-token"]').attr('content');
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': csrfToken
                }));

                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_method',
                    'value': 'DELETE'
                }));

                $('body').append(form);
                form.submit();
            } else {
                alert('You must type "delete" to confirm.');
            }
        });

    });
</script>
@endsection
