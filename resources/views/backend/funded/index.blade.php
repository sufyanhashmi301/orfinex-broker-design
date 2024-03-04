@extends('backend.layouts.app')
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">
                                {{ __('Challenges')}}
                            </h2>
                            <a href="{{ url('admin/challenge/create') }}" class="title-btn" type="button">
                                <i icon-name="plus"></i>
                                ADD NEW
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
                        <div class="site-card-body table-responsive">
                            <div class="site-datatable">
                                <table id="dataTable" class="display data-table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Challenge Title') }}</th>
                                            <th>{{ __('Challenge Code ') }}</th>
                                            <th>{{ __('Schema Badge') }}</th>
                                            <th>{{ __('Step Rules') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>100% Bonus</strong>
                                            </td>
                                            <td>
                                                END1234
                                            </td>
                                            <td>Deposit</td>
                                            <td>
                                                <a href="{{ url('admin/step_rules/create') }}" class="btn-link">
                                                    Phase 2
                                                </a>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input me-2" type="checkbox" id="status" checked>
                                                    <label class="form-check-label" for="status"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="" class="round-icon-btn primary-btn" data-bs-toggle="tooltip" title="" data-bs-original-title="Edit Record" aria-label="Edit Record">
                                                    <i icon-name="edit-3"></i>
                                                </a>
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
    </div>
@endsection
