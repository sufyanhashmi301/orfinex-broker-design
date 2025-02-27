@extends('backend.setting.user_management.index')
@section('title')
    {{ __('Manage Rankings') }}
@endsection
@section('user-management-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('User Rankings') }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            @can('ranking-create')
                <a href="" class="btn btn-sm btn-primary inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#addNewRanking">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                    {{ __('Add New') }}
                </a>
            @endcan
        </div>
    </div>
    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Ranking') }}</th>
                                    <th scope="col" class="table-th">{{ __('Ranking Icon') }}</th>
                                    <th scope="col" class="table-th">{{ __('Ranking Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Minimum Earning') }}</th>
                                    <th scope="col" class="table-th">{{ __('Bonus') }}</th>
                                    <th scope="col" class="table-th">{{ __('Description') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @foreach($rankings as $ranking)
                                <tr>
                                    <td class="table-td">
                                        {{$ranking->ranking}}
                                    </td>
                                    <td class="table-td">
                                        <img class="avatar h-12" src="{{ asset($ranking->icon) }}" alt="">
                                    </td>
                                    <td class="table-td">
                                        {{ $ranking->ranking_name }}
                                    </td>
                                    <td class="table-td">
                                        {{ $ranking->minimum_earnings.' '.$currency }}
                                    </td>
                                    <td class="table-td">
                                        {{ $ranking->bonus.' '.$currency }}
                                    </td>
                                    <td class="table-td">
                                        {!! $ranking->description !!}
                                    </td>
                                    <td class="table-td">
                                        @if($ranking->status)
                                            <div class="badge badge-warning capitalize">
                                                {{ __('Active') }}
                                            </div>
                                        @else
                                            <div class="badge badge-danger capitalize">
                                                {{ __('Disabled') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="table-td">
                                        @can('ranking-edit')
                                            <button class="action-btn editRanking" type="button" data-ranking="{{ json_encode($ranking) }}">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </button>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto">
                            <div>
                                @php
                                    $from = $rankings->firstItem(); // The starting item number on the current page
                                    $to = $rankings->lastItem(); // The ending item number on the current page
                                    $total = $rankings->total(); // The total number of items
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
                            {{ $rankings->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Modal for Add New Ranking -->
    @can('ranking-create')
        @include('backend.ranking.include.__add_new')
    @endcan
    <!-- Modal for Add New Ranking-->

    <!-- Modal for Edit Ranking -->
    @can('ranking-edit')
        @include('backend.ranking.include.__edit')
    @endcan
    <!-- Modal for Edit Ranking-->


@endsection
@section('user-management-script')
    <script>
        $('.editRanking').on('click',function (e) {
            "use strict";
            e.preventDefault();
            var ranking = $(this).data('ranking');

            var url = '{{ route("admin.ranking.update", ":id") }}';
            url = url.replace(':id', ranking.id);
            $('#rankingEditForm').attr('action', url)
            $('.ranking').val(ranking.ranking);
            $('.ranking-name').val(ranking.ranking_name);
            $('.minimum-deposit').val(ranking.minimum_deposit);
            $('.minimum-invest').val(ranking.minimum_invest);
            $('.minimum-referral').val(ranking.minimum_referral);
            $('.minimum-referral-deposit').val(ranking.minimum_referral_deposit);
            $('.minimum-referral-invest').val(ranking.minimum_referral_invest);
            $('.minimum-earnings').val(ranking.minimum_earnings);
            $('.bonus').val(ranking.bonus);
            $('.description').val(ranking.description);
            imagePreviewAdd(ranking.icon);

            if (ranking.status) {
                $('#disableStatus').attr('checked', false);
                $('#activeStatus').attr('checked', true);
            } else {
                $('#activeStatus').attr('checked', false);
                $('#disableStatus').attr('checked', true);
            }

            $('#editRanking').modal('show');
        });
    </script>
@endsection
