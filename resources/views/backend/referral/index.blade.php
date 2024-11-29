@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Referral') }}
@endsection
@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap justify-between items-center mb-4">
            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                {{ __('Target Referrals') }}
            </h4>
            <div class="flex space-x-4 justify-end items-center rtl:space-x-reverse">
                @can('target-manage')
                    <a href="{{ route('admin.referral.target') }}" class="btn btn-sm btn-primary inline-flex items-center justify-center">
                        <span class="flex items-center">
                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                            <span>{{ __('Set Targets') }}</span>
                        </span>
                    </a>
                @endcan
            </div>
        </div>
        <div class="grid grid-cols-2 gap-5">
            <div class="lg:col-span-1 col-span-2">
                <div class="h-full rounded transition-all duration-100 shadow-none bg-slate-200 dark:bg-dark">
                    <div class="relative flex justify-between items-center bg-white dark:bg-slate-800 rounded shadow-base px-6 py-5">
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-[2px] bg-primary"></span>
                        <h3 class="text-lg text-slate-900 dark:text-white font-medium capitalize">
                            {{ __('Investment Bounty') }}
                        </h3>
                        <div class="flex items-center space-x-2">
                            @can('referral-create')
                                <button class="btn btn-dark btn-sm inline-flex justify-center new-referral" type="button" data-type="investment">
                                    <span class="flex items-center">
                                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                                        <span>{{ __('Add New') }}</span>
                                    </span>
                                </button>
                            @endcan
                        </div>
                    </div>
                    <div class="px-2 py-4 h-full space-y-4 rounded-bl rounded-br">
                        <!-- BEGIN: Cards -->
                        <div class="card rounded-md bg-white dark:bg-slate-800 shadow-base custom-class card-body p-6">
                            <p class="paragraph text-slate-600 dark:text-slate-400 text-sm py-4">
                                {{ __('You can') }}
                                <strong>{{ __('Add').','. __('Edit').' '. __('or').' '. __('Delete') }}</strong> {{ __('any of the') }}
                                <strong>{{ __('Referred User Investment Bounty') }}</strong>
                            </p>
                            @foreach($investments as $investment)
                            <div class="single-gateway flex items-center justify-between border dark:border-slate-700 rounded py-3 px-4 mb-4">
                                <div class="gateway-name flex items-center">
                                    <div class="gateway-title">
                                        <h4 class="text-sm dark:text-white">{{ $investment->target->name }}</h4>
                                        <p class="text-xs dark:text-slate-300">{{ $investment->description }}</p>
                                    </div>
                                </div>
                                <div class="gateway-right flex items-center">
                                    <div class="gateway-status mr-7">
                                        <div class="badge badge-success capitalize">
                                            {{ $investment->bounty }}%
                                        </div>
                                    </div>
                                    <div class="gateway-edit flex space-x-3 rtl:space-x-reverse">
                                        @can('referral-edit')
                                            <a href="" type="button" class="action-btn edit-referral dark:text-slate-300"
                                                data-id="{{$investment->id}}"
                                                data-type="{{$investment->type}}"
                                                data-target="{{ $investment->referral_target_id }}"
                                                data-target-amount="{{ $investment->target_amount }}"
                                                data-bounty="{{ $investment->bounty }}"
                                                data-description="{{ $investment->description }}"
                                            ><iconify-icon icon="lucide:edit-3"></iconify-icon></a>
                                        @endcan
                                        @can('referral-delete')
                                            <a href="" class="action-btn delete-referral dark:text-slate-300" type="button"
                                                data-id="{{$investment->id}}"
                                                data-target="{{ $investment->target->name }}"
                                            ><iconify-icon icon="lucide:trash-2"></iconify-icon></a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <!-- END: Cards -->
                    </div>
                </div>
            </div>
            <div class="lg:col-span-1 col-span-2">
                <div class="h-full rounded transition-all duration-100 shadow-none bg-slate-200 dark:bg-dark">
                    <div class="relative flex justify-between items-center bg-white dark:bg-slate-800 rounded shadow-base px-6 py-5">
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-[2px] bg-primary"></span>
                        <h3 class="text-lg text-slate-900 dark:text-white font-medium capitalize">
                            {{ __('Deposit Bounty') }}
                        </h3>
                        <div class="flex items-center space-x-2">
                            @can('referral-create')
                                <button class="btn btn-dark btn-sm inline-flex justify-center new-referral" type="button" data-type="deposit">
                                    <span class="flex items-center">
                                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                                        <span>{{ __('Add New') }}</span>
                                    </span>
                                </button>
                            @endcan
                        </div>
                    </div>
                    <div class="px-2 py-4 h-full space-y-4 rounded-bl rounded-br">
                        <!-- BEGIN: Cards -->
                        <div class="card rounded-md bg-white dark:bg-slate-800 shadow-base custom-class card-body p-6">
                            <p class="paragraph text-slate-600 dark:text-slate-400 text-sm py-4">
                                {{ __('You can') }}
                                <strong>{{ __('Add').','. __('Edit').' '. __('or').' '. __('Delete') }}</strong> {{ __('any of the') }}
                                <strong>{{ __('Referred User Deposit Bounty') }}</strong>
                            </p>
                            @foreach($deposits as $deposit)
                            <div class="single-gateway flex items-center justify-between border dark:border-slate-700 rounded py-3 px-4 mb-4">
                                <div class="gateway-name flex items-center">
                                    <div class="gateway-title">
                                        <h4 class="text-sm dark:text-white">{{ $deposit->target->name }}</h4>
                                        <p class="text-xs dark:text-slate-300">{{ $deposit->description }}</p>
                                    </div>
                                </div>
                                <div class="gateway-right flex items-center">
                                    <div class="gateway-status mr-7">
                                        <div class="badge badge-success capitalize">
                                            {{ $deposit->bounty .''.__('%')}}
                                        </div>
                                    </div>
                                    <div class="gateway-edit flex space-x-3 rtl:space-x-reverse">
                                        @can('referral-edit')
                                            <a href="" type="button" class="action-btn edit-referral dark:text-slate-300"
                                                data-id="{{$deposit->id}}"
                                                data-type="{{$deposit->type}}"
                                                data-target="{{ $deposit->referral_target_id }}"
                                                data-target-amount="{{ $deposit->target_amount }}"
                                                data-bounty="{{ $deposit->bounty }}"
                                                data-description="{{ $deposit->description }}"
                                            ><iconify-icon icon="lucide:edit-3"></iconify-icon></a>
                                        @endcan
                                        @can('referral-delete')
                                            <a href="" class="action-btn delete-referral dark:text-slate-300" type="button"
                                                data-id="{{ $deposit->id }}"
                                                data-target="{{ $deposit->target->name }}"
                                            ><iconify-icon icon="lucide:trash-2"></iconify-icon></a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <!-- END: Cards -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Add New Level -->
    @can('referral-create')
        @include('backend.referral.include.__new_referral')
    @endcan
    <!-- Modal for Add New Level-->

    <!-- Modal for Edit Level -->
    @can('referral-edit')
        @include('backend.referral.include.__edit_referral')
    @endcan
    <!-- Modal for Edit Level-->

    <!-- Modal for Delete Level -->
    @can('referral-delete')
        @include('backend.referral.include.__delete_referral')
    @endcan
    <!-- Modal for Delete Level End-->

@endsection
@section('script')
    <script>

        $('.new-referral').on('click',function (e) {
            "use strict";
            e.preventDefault();
            var type = $(this).data('type');
            $('.referral-type').val(type);
            $('#addNewReferral').modal('show');

        })

        $('.edit-referral').on('click',function (e) {
            "use strict";
            e.preventDefault();
            var id = $(this).data('id');
            var type = $(this).data('type');
            var target = $(this).data('target');
            var targetAmount = $(this).data('target-amount');
            var bounty = $(this).data('bounty');
            var description = $(this).data('description');


            $('.referral-id').val(id);
            $('.referral-type').val(type);
            $('.target_id').val(target);
            $('.target-amount').val(targetAmount);
            $('.bounty').val(bounty);
            $('.description').val(description);

            $('#editReferral').modal('show');

        })
        $('.delete-referral').on('click',function (e) {
            "use strict";
            e.preventDefault();
            var id = $(this).data('id');
            var target = $(this).data('target');
            $('.referral-id').val(id);
            $('.target').html(target);
            $('#deleteReferral').modal('show');

        })

    </script>
@endsection
