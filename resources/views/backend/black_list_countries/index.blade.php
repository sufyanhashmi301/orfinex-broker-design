@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Schema') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('All Black List Countries') }}</h2>
                            @can('schema-create')
                                <a href="{{route('admin.blackListCountry.create')}}" class="title-btn"><i
                                        icon-name="plus-circle"></i>{{ __('Add New') }}</a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="site-card">
                        <div class="site-card-body">
                            <div class="site-table table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">{{ __('Country') }}</th>
                                        <th scope="col">{{ __('Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($countries as $country)
                                        <tr>

                                            <td><strong>{{$country->name}}</strong></td>

                                        <td>
                                            @can('schema-edit')
{{--                                                <a href="{{route('admin.blackListCountry.edit',$country->id)}}"--}}
{{--                                                   class="round-icon-btn primary-btn">--}}
{{--                                                    <i icon-name="edit-3"></i>--}}
{{--                                                </a>--}}
                                                <button class="round-icon-btn danger-btn delete-btn" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" data-country_id="{{ $country->id }}">
                                                    <i icon-name="trash"></i>
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
        </div>

    </div>

//confirmation Delete
        <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">

        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this country?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
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
