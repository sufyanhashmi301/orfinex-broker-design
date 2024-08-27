@extends('backend.layouts.app')
@section('title')
    {{ __('IB Resources') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('IB Resources') }}</h2>
                            <a href="" class="title-btn">
                                <i icon-name="plus-circle"></i>
                                {{ __('Add New') }}
                            </a>
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
                                        <th scope="col">{{ __('Resource Title') }}</th>
                                        <th scope="col">{{ __('Type') }}</th>
                                        <th scope="col">{{ __('Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>Test Resource</strong>
                                            </td>
                                            <td>
                                                Social Media
                                            </td>
                                            <td>
                                                <a href="" class="round-icon-btn primary-btn">
                                                    <i icon-name="edit-3"></i>
                                                </a>
                                                <button type="button" data-id="deleteIbSource" data-name="deleteIbSource" class="round-icon-btn red-btn deleteKyc">
                                                    <i icon-name="trash-2"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>2nd Resource</strong>
                                            </td>
                                            <td>
                                                Website Banner
                                            </td>
                                            <td>
                                                <a href="" class="round-icon-btn primary-btn">
                                                    <i icon-name="edit-3"></i>
                                                </a>
                                                <button type="button" data-id="deleteIbSource" data-name="deleteIbSource" class="round-icon-btn red-btn deleteKyc">
                                                    <i icon-name="trash-2"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Delete deleteKycType -->
        <div class="modal fade" id="deleteIbSource" tabindex="-1" aria-labelledby="deleteIbSourceTypeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content site-table-modal">
                    <div class="modal-body popup-body">
                        <button  type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="popup-body-text centered">
                            <form method="post" id="ibSourceEditForm">
                                @method('DELETE')
                                @csrf
                                <div class="info-icon">
                                    <i icon-name="alert-triangle"></i>
                                </div>
                                <div class="title">
                                    <h4>{{ __('Are you sure?') }}</h4>
                                </div>
                                <p>
                                    {{ __('You want to Delete') }} <strong
                                        class="name"></strong> {{ __('IB Source?') }}
                                </p>
                                <div class="action-btns">
                                    <button type="submit" class="site-btn-sm primary-btn me-2">
                                        <i icon-name="check"></i>
                                        {{ __(' Confirm') }}
                                    </button>
                                    <a href="" class="site-btn-sm red-btn" type="button"
                                       class="btn-close"
                                       data-bs-dismiss="modal"
                                       aria-label="Close">
                                        <i icon-name="x"></i>
                                        {{ __('Cancel') }}
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal for Delete deleteKycType-->
    </div>
@endsection
@section('script')
    <script>
        $('.deleteKyc').on('click',function (e) {
            "use strict";
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            var url = '{{ route("admin.ib-form.destroy", ":id") }}';
            url = url.replace(':id', id);
            $('#kycEditForm').attr('action', url)

            $('.name').html(name);
            $('#deleteKyc').modal('show');
        })
    </script>
@endsection
