@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Advertisement') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('All Advertisements') }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            @can('advertisement_material-create')
                <a href="{{route('admin.advertisement_material.create')}}" class="btn btn-sm btn-primary inline-flex items-center justify-center">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                    {{ __('Add New') }}
                </a>
            @endcan
        </div>
    </div>

    @include('backend.ib.include.__menu')

    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Icon') }}</th>
                                    <th scope="col" class="table-th">{{ __('Language') }}</th>
                                    <th scope="col" class="table-th">{{ __('Type') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
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
                                        'bg-success text-success' => $advertisement->status,
                                        'bg-danger text-danger' => !$advertisement->status
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
                        <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto">
                            <div>
                                @php
                                    $from = $advertisements->firstItem(); // The starting item number on the current page
                                    $to = $advertisements->lastItem(); // The ending item number on the current page
                                    $total = $advertisements->total(); // The total number of items
                                @endphp

                                <p class="text-sm text-gray-700">
                                    Showing
                                    <span class="font-medium">{{ $from }}</span>
                                    to
                                    <span class="font-medium">{{ $to }}</span>
                                    of
                                    <span class="font-medium">{{ $total }}</span>
                                    results
                                </p>
                            </div>
                            {{ $advertisements->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
