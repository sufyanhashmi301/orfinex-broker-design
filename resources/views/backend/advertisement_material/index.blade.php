@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Advertisement') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('All Advertisements') }}</h2>
                            @can('advertisement_material-create')
                                <a href="{{route('admin.advertisement_material.create')}}" class="title-btn"><i
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
                                        <th scope="col">{{ __('Language') }}</th>
                                        <th scope="col">{{ __('Type') }}</th>
                                        <th scope="col">{{ __('Status') }}</th>
                                        <th scope="col">{{ __('Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($advertisements as $advertisement)
                                        <tr>
                                            <td>
                                                <img
                                                    class="avatar"
                                                    src="{{ asset($advertisement->img) }}"
                                                    alt=""
                                                />
                                            </td>
                                            <td><strong>{{$advertisement->language}}</strong></td>
                                            <td><strong>{{ucwords(str_replace('_', ' ', $advertisement->type))}}</strong></td>

                                            <td>
                                                <div @class([
                                                'site-badge', // common classes
                                                'success' => $advertisement->status,
                                                'danger' => !$advertisement->status
                                                ])>{{ $advertisement->status ? 'Active' : 'Deactivated' }}</div>
                                            </td>
                                            <td>
                                                @can('advertisement-material-edit')
                                                    <a href="{{route('admin.advertisement_material.edit',$advertisement->id)}}"
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
