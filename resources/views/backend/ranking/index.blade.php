@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Rankings') }}
@endsection
@section('content')
    <div class="card">
        <div class="card-header noborder">
            <h4 class="card-title">{{ __('User Rankings') }}</h4>
            @can('ranking-create')
                <a href="" class="btn btn-dark btn-sm inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#addNewRanking">
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
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                            @foreach($rankings as $ranking)
                                <tr>
                                    <td class="table-td">
                                        {{$ranking->ranking}}
                                    </td>
                                    <td class="table-td">
                                        <img class="avatar" src="{{ asset($ranking->icon) }}" alt="">
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
                                        {{ $ranking->description }}
                                    </td>
                                    <td class="table-td">
                                        @if($ranking->status)
                                            <div class="badge bg-warning-500 text-warning-500 bg-opacity-30 capitalize">
                                                {{ __('Active') }}
                                            </div>
                                        @else
                                            <div class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">
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
@section('script')
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
