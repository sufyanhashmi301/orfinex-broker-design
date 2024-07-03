@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Advertisement') }}
@endsection
@section('content')
    <div class="card">
        <div class="card-header noborder">
            <h2 class="card-title">{{ __('All Advertisements') }}</h2>
            @can('advertisement_material-create')
                <a href="{{route('admin.advertisement_material.create')}}" class="btn btn-dark btn-sm inline-flex items-center justify-center">
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
                                    <th scope="col" class="table-th">{{ __('Icon') }}</th>
                                    <th scope="col" class="table-th">{{ __('Language') }}</th>
                                    <th scope="col" class="table-th">{{ __('Type') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach($advertisements as $advertisement)
                                <tr>
                                    <td class="table-td">
                                        <div class="w-8 h-8 rounded-[100%]">
                                            <img src="{{ asset($advertisement->img) }}" alt="" class="w-full h-full rounded-[100%] object-cover">
                                        </div>
                                    </td>
                                    <td class="table-td">
                                        <strong>{{$advertisement->language}}</strong>
                                    </td>
                                    <td class="table-td">
                                        <strong>{{ucwords(str_replace('_', ' ', $advertisement->type))}}</strong>
                                    </td>

                                    <td class="table-td">
                                        <div @class([
                                        'badge bg-opacity-30 capitalize', // common classes
                                        'bg-success-500 text-success-500' => $advertisement->status,
                                        'bg-danger-500 text-danger-500' => !$advertisement->status
                                        ])>{{ $advertisement->status ? 'Active' : 'Deactivated' }}</div>
                                    </td>
                                    <td class="table-td">
                                        @can('advertisement-material-edit')
                                            <a href="{{route('admin.advertisement_material.edit',$advertisement->id)}}"
                                               class="action-btn">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
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
@endsection
