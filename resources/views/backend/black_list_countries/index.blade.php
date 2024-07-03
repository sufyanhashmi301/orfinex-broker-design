@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Schema') }}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('All Black List Countries') }}</h4>
            @can('schema-create')
                <a href="{{route('admin.blackListCountry.create')}}" class="btn btn-dark btn-sm inline-flex items-center justify-center">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus-circle"></iconify-icon>
                    {{ __('Add New') }}
                </a>
            @endcan
        </div>
        <div class="card-body px-6 pb-6">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="border-t border-slate-100 dark:border-slate-800">
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Country') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach($countries as $country)
                                    <tr>
                                        <td class="table-td">
                                            {{$country->name}}
                                        </td>
                                        <td class="table-td">
                                            @can('schema-edit')
                                                {{--<a href="{{route('admin.blackListCountry.edit',$country->id)}}"--}}
                                                {{--class="round-icon-btn primary-btn">--}}
                                                {{--<i icon-name="edit-3"></i>--}}
                                                {{--</a>--}}
                                                <button class="action-btn delete-btn" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" data-country_id="{{ $country->id }}">
                                                    <iconify-icon icon="lucide:trash"></iconify-icon>
                                                </button>
                                            @endcan

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

    {{-- confirmation Delete --}}
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
        id="deleteConfirmationModal"
        tabindex="-1"
        aria-labelledby="deleteConfirmationModal"
        aria-hidden="true"
    >
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="modal-body p-6 py-8 text-center space-y-5">
                    <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-danger-500 text-danger-500 bg-opacity-30">
                        <iconify-icon class="text-4xl" icon="lucide:alert-triangle"></iconify-icon>
                    </div>
                    <div class="title">
                        <h4 class="text-xl font-medium dark:text-white capitalize">
                            {{ __('Are you sure?') }}
                        </h4>
                    </div>
                    <p>{{ __('Are you sure you want to delete this item?') }}</p>
                    <div class="text-center">
                        <button type="button" id="confirmDelete" class="btn btn-dark inline-flex items-center justify-center mr-2">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                            Delete
                        </button>
                        <button type="button" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('body').on('click','.delete-btn', function () {
                var countryId = $(this).data('country_id');
                $('#confirmDelete').on('click', function () {
                    // Make an AJAX request to delete the country
                    $.ajax({
                        url: '{{ route('admin.blackListCountry.destroy', ['blackListCountry' => '__country_id__']) }}'.replace('__country_id__', countryId),
                        type: 'POST', // Change the request type to POST
                        data: {
                            '_method': 'DELETE', // Add this field to mimic the DELETE request
                            '_token': '{{ csrf_token() }}',
                        },
                        success: function (data) {
                            // Handle success, e.g., reload the page
                            location.reload();
                        },
                        error: function (error) {
                            console.error(error);
                        }
                    });
                });
            });
        });
    </script>

@endsection
