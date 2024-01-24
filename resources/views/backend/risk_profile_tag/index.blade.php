@extends('backend.layouts.app')
@section('title')
    {{ __('Risk Profile Tag') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('Risk Profile Tag Forms') }}</h2>
                            <a href="{{ route('admin.risk-profile-tag.create') }}" class="title-btn"><i
                                    icon-name="plus-circle"></i>{{ __('Add New') }}</a>
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
                                        <th scope="col">{{ __('Tag Name') }}</th>
                                        <th scope="col">{{ __('Status') }}</th>
                                        <th scope="col">{{ __('Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($riskProfileTags as $riskProfileTag)
                                        <tr>
                                            <td>
                                                <strong>{{ $riskProfileTag->name }}</strong>
                                            </td>
                                            <td>
                                                @if( $riskProfileTag->status)
                                                    <div class="site-badge success">{{ __('Active') }}</div>
                                                @else
                                                    <div class="site-badge pending">{{ __('Disabled') }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.risk-profile-tag.edit',$riskProfileTag->id) }}"
                                                   class="round-icon-btn primary-btn">
                                                    <i icon-name="edit-3"></i>
                                                </a>
                                                <button type="button" data-id="{{ $riskProfileTag->id }}"
                                                        data-name="{{ $riskProfileTag->name }}"
                                                        class="round-icon-btn red-btn deleteRiskProfileTag">
                                                    <i icon-name="trash-2"></i>
                                                </button>
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

        <!-- Modal for Delete deleteRiskProfileTagType -->
        <div
            class="modal fade"
            id="deleteRiskProfileTag"
            tabindex="-1"
            aria-labelledby="deleteRiskProfileTagTypeModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content site-table-modal">
                    <div class="modal-body popup-body">
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                        <div class="popup-body-text centered">
                            <form method="post" id="riskProfileTagEditForm">
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
                                        class="name"></strong> {{ __('Risk Profile Tag?') }}
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
        <!-- Modal for Delete deleteRiskProfileTagType-->
    </div>
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
        })
    </script>
@endsection
