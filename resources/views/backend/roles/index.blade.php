@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Roles') }}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('Manage Roles') }}</h4>
            @can('role-create')
                <a href="{{route('admin.roles.create')}}" class="btn btn-dark btn-sm inline-flex items-center justify-center">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus-circle"></iconify-icon>
                    {{ __('Add New Role') }}
                </a>
            @endcan
        </div>
        <div class="card-body p-6 pt-0">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead class="bg-slate-200 dark:bg-slate-700">
                                <tr>
                                    <th scope="col" class="table-th">{{ __('#') }}</th>
                                    <th scope="col" class="table-th">{{ __('Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                            @foreach($roles as  $role)
                                <tr>
                                    <td class="table-td">{{ ++$loop->index }}</td>
                                    <td class="table-td"><strong>{{ str_replace('-',' ',$role->name) }}</strong></td>
                                    <td class="table-td">
                                        @if($role->name == 'Super-Admin')
                                            <button class="btn btn-danger btn-sm inline-flex items-center justify-center">
                                                <iconify-icon class="text-base ltr:mr-2 rtl:ml-2" icon="lucide:alert-triangle"></iconify-icon>
                                                {{ __('Not Editable') }}
                                            </button>
                                        @else
                                            @can('role-edit')
                                                <a href="{{route('admin.roles.edit',$role->id)}}"
                                                class="btn btn-dark btn-sm inline-flex items-center justify-center">
                                                    <iconify-icon class="text-base ltr:mr-2 rtl:ml-2" icon="lucide:edit-3"></iconify-icon>
                                                    {{ __('Edit Permission') }}
                                                </a>
                                            @endcan
                                        @endif
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
@endsection
