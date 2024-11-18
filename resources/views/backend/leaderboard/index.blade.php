@extends('backend.layouts.app')
@section('title')
    {{ __('Leaderboard') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>

    <form id="badges-form" action="{{ route('admin.leaderboard-badge.store') }}" method="POST">
        @csrf
    </form>
    
    <div class="grid md:grid-cols-3 grid-cols-1 gap-5 mb-5">
        {{-- Named Badges --}}
        <div class="card badge-container" data-slug="highest_payout">
            @php
                $badge_1 = $badges->where('title_slug', 'highest_payout')->first();
            @endphp
            <div class="card-body p-4">
                <div class="flex justify-between">
                    <div class="flex space-x-3 rtl:space-x-reverse">
                        <div class="flex-none">
                            <img src="{{ asset('frontend/images/highest-payout__badge.png') }}" alt="">
                        </div>
                        <div class="flex-1">
                            <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium"> 
                                {{ $badge_1->title }}
                            </div>
                            <div class="text-slate-900 dark:text-white text-lg font-medium field" data-field="user_name" contenteditable="true">
                                {{ $badge_1->user_name }}
                            </div>
                        </div>
                    </div>
                    {{-- <button class="action-btn editBtn" type="button" data-title="{{ $badge_1->title }}">
                        <iconify-icon icon="heroicons-outline:dots-vertical"></iconify-icon>
                    </button> --}}
                </div>
                <ul class="space-y-3 mt-4">
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="text-base field" contenteditable="true" data-field="amount">{{ $badge_1->amount }}</span>
                        <span>
                            <span class="text-lg font-medium field" contenteditable="true" data-field="details_achieved_amount">{{ $badge_1->details['achieved_amount'] }}</span>
                            <span class="text-success-500 ml-1 field" contenteditable="true" data-field="details_gain">{{ $badge_1->details['gain'] }}</span>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card badge-container" data-slug="best_ratio">
            @php
                $badge_2 = $badges->where('title_slug', 'best_ratio')->first();
            @endphp
            <div class="card-body p-4">
                <div class="flex justify-between">
                    <div class="flex space-x-3 rtl:space-x-reverse">
                        <div class="flex-none">
                            <img src="{{ asset('frontend/images/best-ratio__badge.png') }}" alt="">
                        </div>
                        <div class="flex-1">
                            <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                                {{ $badge_2->title }}
                            </div>
                            <div class="text-slate-900 dark:text-white text-lg font-medium field" contenteditable="true" data-field="user_name">
                                {{ $badge_2->user_name }}
                            </div>
                        </div>
                    </div>
                    {{-- <button class="action-btn editBtn" type="button" data-title="{{ $badge_2->title }}">
                        <iconify-icon icon="heroicons-outline:dots-vertical"></iconify-icon>
                    </button> --}}
                </div>
                <ul class="space-y-3 mt-4">
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="text-base field" contenteditable="true" data-field="amount" >{{ $badge_2->amount }}</span>
                        <span class="text-lg font-medium field" contenteditable="true" data-field="details_percentage">{{ $badge_2->details['percentage'] }}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card badge-container" data-slug="fastest_evalution">
            @php
                $badge_3 = $badges->where('title_slug', 'fastest_evalution')->first();
            @endphp
            <div class="card-body p-4">
                <div class="flex justify-between">
                    <div class="flex space-x-3 rtl:space-x-reverse">
                        <div class="flex-none">
                            <img src="{{ asset('frontend/images/fastest-evalution__badge.png') }}" alt="">
                        </div>
                        <div class="flex-1">
                            <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                                {{ $badge_3->title }}
                            </div>
                            <div class="text-slate-900 dark:text-white text-lg font-medium field" contenteditable="true" data-field="user_name">
                                {{ $badge_3->user_name }}
                            </div>
                        </div>
                    </div>
                    {{-- <button class="action-btn editBtn" type="button" data-title="{{ $badge_3->title }}">
                        <iconify-icon icon="heroicons-outline:dots-vertical"></iconify-icon>
                    </button> --}}
                </div>
                <ul class="space-y-3 mt-4">
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="text-base field" contenteditable="true" data-field="amount">{{ $badge_3->amount }}</span>
                        <span class="text-lg font-medium field" contenteditable="true" data-field="details_time">{{ $badge_3->details['time'] }}</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Medals Badges  --}}
        <div class="card badge-container" data-slug="medal_1">
            @php
                $badge_4 = $badges->where('title_slug', 'medal_1')->first();
            @endphp
            <div class="card-body relative p-4">
                <div class="flex items-center">
                    <span class="text-slate-900 dark:text-white text-lg font-medium mr-1 field" contenteditable="true" data-field="user_name">{{ $badge_4->user_name }}</span>
                </div>
                <div class="absolute top-0 right-4">
                    <img src="{{ asset('frontend/images/medal-1__badge.png') }}" alt="">
                </div>
                <ul class="space-y-3 mt-5">
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="badge capitalize bg-{{ $badge_4->details['buy_or_sell'] == 'buy' ? 'success' : 'danger' }}-500 text-white capitalize field" contenteditable="true" data-field="details_buy_or_sell">{{ $badge_4->details['buy_or_sell'] }}</span>
                        <span class="text-base field" contenteditable="true" data-field="amount">{{ $badge_4->amount }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="text-base field" contenteditable="true" data-field="details_currency">{{ $badge_4->details['currency'] }}</span>
                        <span class="text-base text-success-500 field" contenteditable="true" data-field="details_gain">{{ $badge_4->details['gain'] }}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card badge-container" data-slug="medal_2">
            @php
                $badge_5 = $badges->where('title_slug', 'medal_2')->first();
            @endphp
            <div class="card-body relative p-4">
                <div class="flex items-center">
                    <span class="text-slate-900 dark:text-white text-lg font-medium mr-1 field" contenteditable="true" data-field="user_name">{{ $badge_5->user_name }}</span>
                </div>
                <div class="absolute top-0 right-4">
                    <img src="{{ asset('frontend/images/medal-2__badge.png') }}" alt="">
                </div>
                <ul class="space-y-3 mt-5">
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="badge capitalize bg-{{ strtolower($badge_5->details['buy_or_sell']) == 'buy' ? 'success' : 'danger' }}-500 text-white capitalize field" contenteditable="true" data-field="details_buy_or_sell">{{ $badge_5->details['buy_or_sell'] }}</span>
                        <span class="text-base field" contenteditable="true" data-field="amount">{{ $badge_5->amount }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="text-base field" contenteditable="true" data-field="details_currency">{{ $badge_5->details['currency'] }}</span>
                        <span class="text-base text-success-500 field" contenteditable="true" data-field="details_gain">{{ $badge_5->details['gain'] }}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card badge-container" data-slug="medal_3">
            @php
                $badge_6 = $badges->where('title_slug', 'medal_3')->first();
            @endphp
            <div class="card-body relative p-4">
                <div class="flex items-center">
                    <span class="text-slate-900 dark:text-white text-lg font-medium mr-1 field" contenteditable="true" data-field="user_name">{{ $badge_6->user_name }}</span>
                </div>
                <div class="absolute top-0 right-4">
                    <img src="{{ asset('frontend/images/medal-3__badge.png') }}" alt="">
                </div>
                <ul class="space-y-3 mt-5">
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="badge capitalize bg-{{ strtolower($badge_6->details['buy_or_sell']) == 'buy' ? 'success' : 'danger' }}-500 text-white capitalize field" contenteditable="true" data-field="details_buy_or_sell">{{ $badge_6->details['buy_or_sell'] }}</span>
                        <span class="text-base field" contenteditable="true" data-field="amount">{{ $badge_6->amount }}</span>
                    </li>
                    <li class="flex items-center justify-between text-sm text-slate-600 dark:text-slate-300">
                        <span class="text-base field" contenteditable="true" data-field="details_currency">{{ $badge_6->details['currency'] }}</span>
                        <span class="text-base text-success-500 field" contenteditable="true" data-field="details_gain">{{ $badge_6->details['gain'] }}</span>
                    </li>
                </ul>
            </div>
        </div>

        
    </div>
    {{-- Button to save changes --}}
    <button class="btn mb-3 save-badges-changes btn-primary float-right" disabled>Save Badges Changes</button>

    <style>
        button:disabled {
            opacity: 0.6
        }
    </style>

    <br style="clear: both">
    
    <div class="flex flex-wrap items-center justify-between mb-3">
        <h4 class="text-lg text-slate-600 dark:text-white font-semibold">
            {{ __('Best account in profit') }}
        </h4>
        <ul class="nav nav-tabs custom-tabs inline-flex items-center overflow-hidden rounded list-none border-0 pl-0">
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary active">
                    All
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary">
                    10k
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary">
                    25k
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary">
                    50k
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary">
                    100k
                </a>
            </li>
        </ul>
    </div>

    <div class="card mb-6">
        <div class="card-body p-6 pt-3">
            <!-- BEGIN: Company Table -->
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('#') }}</th>
                                    <th scope="col" class="table-th">{{ __('Name') }}</th>
                                    <th scope="col" class="table-th">{{ __('Profit') }}</th>
                                    <th scope="col" class="table-th">{{ __('Equity') }}</th>
                                    <th scope="col" class="table-th">{{ __('Account size') }}</th>
                                    <th scope="col" class="table-th">{{ __('Gain %') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="table-td">{{ 1 }}</td>
                                    <td class="table-td">{{ __('Debra') }}</td>
                                    <td class="table-td">{{ __('$7188') }}</td>
                                    <td class="table-td">{{ __('$9194') }}</td>
                                    <td class="table-td">{{ __('$7492') }}</td>
                                    <td class="table-td">{{ __('7%') }}</td>
                                    <td class="table-td">
                                        <button class="action-btn" type="button">
                                            <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-td">{{ 2 }}</td>
                                    <td class="table-td">{{ __('Debra') }}</td>
                                    <td class="table-td">{{ __('$7188') }}</td>
                                    <td class="table-td">{{ __('$9194') }}</td>
                                    <td class="table-td">{{ __('$7492') }}</td>
                                    <td class="table-td">{{ __('7%') }}</td>
                                    <td class="table-td">
                                        <button class="action-btn" type="button">
                                            <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-td">{{ 3 }}</td>
                                    <td class="table-td">{{ __('Debra') }}</td>
                                    <td class="table-td">{{ __('$7188') }}</td>
                                    <td class="table-td">{{ __('$9194') }}</td>
                                    <td class="table-td">{{ __('$7492') }}</td>
                                    <td class="table-td">{{ __('7%') }}</td>
                                    <td class="table-td">
                                        <button class="action-btn" type="button">
                                            <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-td">{{ 4 }}</td>
                                    <td class="table-td">{{ __('Debra') }}</td>
                                    <td class="table-td">{{ __('$7188') }}</td>
                                    <td class="table-td">{{ __('$9194') }}</td>
                                    <td class="table-td">{{ __('$7492') }}</td>
                                    <td class="table-td">{{ __('7%') }}</td>
                                    <td class="table-td">
                                        <button class="action-btn" type="button">
                                            <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-td">{{ 5 }}</td>
                                    <td class="table-td">{{ __('Debra') }}</td>
                                    <td class="table-td">{{ __('$7188') }}</td>
                                    <td class="table-td">{{ __('$9194') }}</td>
                                    <td class="table-td">{{ __('$7492') }}</td>
                                    <td class="table-td">{{ __('7%') }}</td>
                                    <td class="table-td">
                                        <button class="action-btn" type="button">
                                            <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--Modal for update leaderboard--}}
    @include('backend.leaderboard.modal.__edit')
@endsection
@section('script')

    <script>
        $('.field').on('keyup', function(){
            $('.save-badges-changes').removeAttr('disabled')

        })
        
        $('.save-badges-changes').on('click', function() {
            let badges_data = [];
            
            // Collect the badges data
            for(let i = 0; i < $('.badge-container').length; i++) {
                let badge = $('.badge-container').eq(i);

                let badge_data = {};
                badge_data['slug'] = badge.attr('data-slug');
                for(let j = 0; j < badge.find('.field').length; j++) {
                    let field = badge.find('.field').eq(j);
                    badge_data[field.attr('data-field')] = field.text().trim();
                }

                badges_data.push(badge_data);
            }

            // Convert badges_data array to JSON string
            let badges_data_json = JSON.stringify(badges_data);

            // Check if hidden input already exists, if not create it
            if ($('#badges-form input[name="badges_data"]').length === 0) {
                // Create a hidden input field for badges_data
                $('<input>').attr({
                    type: 'hidden',
                    name: 'badges_data',
                    value: badges_data_json
                }).appendTo('#badges-form');
            } else {
                // If it exists, update the value
                $('#badges-form input[name="badges_data"]').val(badges_data_json);
            }

            // Submit the form
            $('#badges-form').submit();
        });

    </script>

    <script>



        // $(document).ready(function() {
        //     // When the "Open Modal" button is clicked
        //     $('.editBtn').click(function () {

        //         var title = $(this).data('title');

        //         $('#modalTitle').text(title);
        //         $('#titleInput').val(title);

        //         $('#editLeaderBoardModal').modal('show');

        //     });
        // })
    </script>
@endsection
