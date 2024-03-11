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
                        <a href="{{ route('admin.challenges.create') }}" class="title-btn" type="button">
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
                                    @foreach ($challeges as $challenge)
                                    <tr>
                                        <td>
                                            <strong>{{$challenge->challenge_name}}</strong>
                                        </td>
                                        <td>
                                            {{$challenge->challenge_code}}
                                        </td>
                                        <td>{{$challenge->schema_badge}}</td>
                                        <td>
                                            <a href="{{ url('admin/step_rules/create') }}" class="btn-link">
                                                Phase {{$challenge->type_of_phases}}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input me-2" type="checkbox" id="status" checked>
                                                <label class="form-check-label" for="status"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.challenges.edit', $challenge->id) }}" class="round-icon-btn primary-btn" data-bs-toggle="tooltip" title="" data-bs-original-title="Edit Record" aria-label="Edit Record">
                                                <i icon-name="edit-3"></i>
                                            </a>
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
@endsection
