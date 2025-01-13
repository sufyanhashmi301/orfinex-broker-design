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
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">

            

            @can('schema-create')
                <a href="{{route('admin.account-type.create', ['type' => $type  ])}}" class="btn btn-primary inline-flex items-center justify-center">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                    {{ __('Add New') }}
                </a>
            @endcan
        </div>
    </div>
    <div class="innerMenu card p-6 mb-5">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open w-full">
            <li class="nav-item">
                <a href="javascript:;" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 active">
                    {{ __('Metatrader') }}
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300">
                    {{ __('X9trader') }}
                </a>
            </li>
        </ul>
    </div>

    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper" style="min-height: calc(100vh - 385px);">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Icon') }}</th>
                                    <th scope="col" class="table-th">{{ __('Trader Type') }}</th>
                                    <th scope="col" class="table-th">{{ __('Priority') }}</th>
                                    <th scope="col" class="table-th">{{ __('Title') }}</th>
                                    <th scope="col" class="table-th">{{ __('Leverage') }}</th>
                                    <th scope="col" class="table-th">{{ __('Country/Tags') }}</th>
                                    <th scope="col" class="table-th">{{ __('Badge') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach($account_types as $account_type)
                                <tr>
                                    <td class="table-td">
                                        <img
                                            class="h-7"
                                            src="{{ asset($account_type->icon) }}"
                                            alt=""
                                        />
                                    </td>
                                    <td class="table-td">
                                        {{$account_type->trader_type}}
                                    </td>
                                    <td class="table-td">
                                        {{$account_type->priority}}
                                    </td>

                                    <td class="table-td">
                                        {{$account_type->title}}
                                    </td>
                                    <td class="table-td">
                                        {{$account_type->leverage}}
                                    </td>
                                    <td class="table-td">
                                        @if( null != $account_type->countries) {{ implode(', ', json_decode($account_type->countries,true)) }} @endif
                                    </td>
                                    <td class="table-td">
                                        <div @class([
                                        'badge bg-opacity-30 capitalize', // common classes
                                        'bg-success-500 text-success-500' => $account_type->badge,
                                        'bg-warning-500 text-warning-500' => !$account_type->badge
                                        ])>{{ $account_type->badge ? $account_type->badge : 'No Feature Badge' }}</div>
                                    </td>
                                    <td class="table-td">
                                        <div @class([
                                        'badge bg-opacity-30 capitalize', // common classes
                                        'bg-success-500 text-success-500' => $account_type->status,
                                        'bg-danger-500 text-danger-500' => !$account_type->status
                                        ])>{{ $account_type->status ? 'Active' : 'Deactivated' }}</div>
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
                                @endforeach
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

    <!-- Delete Confirmation Modal -->
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
