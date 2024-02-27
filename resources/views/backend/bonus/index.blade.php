@extends('backend.layouts.app')
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">
                                {{ __('Bonus')}}
                            </h2>
                            <a href="{{ url('admin/bonus/create') }}" class="title-btn" type="button">
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
                                            <th>{{ __('Bonus Title') }}</th>
                                            <th>{{ __('Type') }}</th>
                                            <th>{{ __('Process') }}</th>
                                            <th>{{ __('Applicable by') }}</th>
                                            <th>{{ __('Start Date') }}</th>
                                            <th>{{ __('End Date') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>100% Bonus</strong>
                                            </td>
                                            <td>
                                                <span class="site-badge primary-bg">In Percentage</span>
                                            </td>
                                            <td>On Deposit</td>
                                            <td>Auto Apply</td>
                                            <td>Jan 01 2024</td>
                                            <td>Mar 01 2024</td>
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
