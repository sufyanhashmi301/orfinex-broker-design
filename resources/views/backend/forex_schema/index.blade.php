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
                            <h2 class="title">{{ __('All Forex Schemas') }}</h2>
                            @can('schema-create')
                                <a href="{{route('admin.schema.create')}}" class="title-btn"><i
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
                                        <th scope="col">{{ __('Icon') }}</th>
                                        <th scope="col">{{ __('Title') }}</th>
                                        <th scope="col">{{ __('Leverage') }}</th>
                                        <th scope="col">{{ __('Country') }}</th>
                                        <th scope="col">{{ __('Badge') }}</th>
                                        <th scope="col">{{ __('Status') }}</th>
                                        <th scope="col">{{ __('Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($schemas as $schema)
                                        <tr>
                                            <td>
                                                <img
                                                    class="avatar"
                                                    src="{{ asset($schema->icon) }}"
                                                    alt=""
                                                />
                                            </td>
                                            <td><strong>{{$schema->title}}</strong></td>
                                            <td>
                                                <strong>{{$schema->leverage}}</strong>
                                            </td>
                                            <td>
                                               <strong>@if( null != $schema->country) {{ implode(', ', json_decode($schema->country,true)) }} @endif </strong>
                                            </td>
                                            <td>
                                                <div @class([
                                                'site-badge', // common classes
                                                'success' => $schema->badge,
                                                'pending' => !$schema->badge
                                                ])>{{ $schema->badge ? $schema->badge : 'No Feature Badge' }}</div>
                                            </td>
                                            <td>
                                                <div @class([
                                                'site-badge', // common classes
                                                'success' => $schema->status,
                                                'danger' => !$schema->status
                                                ])>{{ $schema->status ? 'Active' : 'Deactivated' }}</div>
                                            </td>
                                            <td>
                                                @can('schema-edit')
                                                    <a href="{{route('admin.schema.edit',$schema->id)}}"
                                                       class="round-icon-btn primary-btn">
                                                        <i icon-name="edit-3"></i>
                                                    </a>
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
@endsection
