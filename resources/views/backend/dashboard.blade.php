@php use App\Enums\InvestStatus; @endphp
@extends('backend.layouts.app')
@section('title')
    {{ __('Dashboard') }}
@endsection
@section('content')

    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ setting('site_title', 'global') }} {{ __('Dashboard') }}
        </h4>
    </div>
    @canany(['deposit-action','withdraw-action','kyc-action',])
        @if($data['withdraw_count'] || $data['kyc_count'] || $data['deposit_count'])
            <div class="admin-latest-announcements flex flex-wrap justify-between items-center py-[18px] px-4 sm:px-6 font-normal font-Inter rounded-md bg-danger bg-opacity-[14%] text-danger mb-5">
                <div class="content flex items-center ltr:pr-4 rtl:pl-4 mb-2 sm:mb-0">
                    <iconify-icon class="text-lg mr-2" icon="lucide:zap"></iconify-icon>
                    {{ __("Explore what's important to review first") }}
                </div>
                <div class="content">
                    @can('withdraw-action')
                        @if($data['withdraw_count'])
                            <a href="{{ route('admin.withdraw.pending') }}" class="btn btn-sm btn-danger inline-flex items-center justify-center">
                                <iconify-icon class="spining-icon text-lg mr-2"  icon="lucide:loader"></iconify-icon>
                                {{ __('Withdraw Requests') }}
                                ({{ $data['withdraw_count'] }})
                            </a>
                        @endif
                    @endcan

                    @can('kyc-action')
                        @if($data['kyc_count'])
                            <a href="{{ route('admin.kyc.pending') }}" class="btn btn-sm btn-success inline-flex items-center justify-center">
                                <iconify-icon class="spining-icon text-lg mr-2"  icon="lucide:loader"></iconify-icon>
                                {{ __('KYC Requests') }}
                                ({{ $data['kyc_count'] }})
                            </a>
                        @endif
                    @endcan

                    @can('deposit-action')
                        @if($data['deposit_count'])
                            <a href="{{ route('admin.deposit.manual.pending') }}" class="btn btn-sm btn-dark inline-flex items-center justify-center">
                                <iconify-icon class="spining-icon text-lg mr-2"  icon="lucide:loader"></iconify-icon>
                                {{ __('Deposit Requests') }}
                                ({{ $data['deposit_count'] }})
                            </a>
                        @endif
                    @endcan
                </div>
            </div>
        @endif
    @endcanany

    <div class="space-y-5 mb-5">
        @include('backend.include.__data_card')
    </div>

    <div class="grid grid-cols-12 gap-5">
        <div class="lg:col-span-8 col-span-12">
            <div class="card h-full">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Site Statistics') }}</h3>
                    <div class="card-header-links">
                        <input class="form-control !py-1" data-mode="range" type="text" name="daterange" value="{{ $data['start_date'] .' - '. $data['end_date'] }}" />
                    </div>
                </div>
                <div class="card-body p-6">
                    <canvas id="depositChart"></canvas>
                </div>
            </div>
        </div>
        <div class="lg:col-span-4 col-span-12">
            <div class="card h-full">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Scheme Statistics') }}</h3>
                </div>
                <div class="card-body p-6">
                    <canvas id="schemeChart"></canvas>
                </div>
            </div>
        </div>
        <div class="lg:col-span-4 col-span-12">
            <div class="card h-full">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Top Country Statistics') }}</h3>
                </div>
                <div class="card-body p-6">
                    <canvas id="countryChart"></canvas>
                </div>
            </div>
        </div>
        <div class="lg:col-span-4 col-span-12">
            <div class="card h-full">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Best Browser Statistics') }}</h3>
                </div>
                <div class="card-body p-6">
                    <canvas id="browserChart"></canvas>
                </div>
            </div>
        </div>
        <div class="lg:col-span-4 col-span-12">
            <div class="card h-full">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Best OS Statistics') }}</h3>
                </div>
                <div class="card-body p-6">
                    <canvas id="osChart"></canvas>
                </div>
            </div>
        </div>

        @include('backend.include.__latest_user_invest')

    </div>

    <!-- Modal for Send Email -->
    @include('backend.user.include.__mail_send')
    <!-- Modal for Send Email-->

@endsection
@section('script')
    @include('backend.include.__chartjs')
    <script>
        (function ($) {
            'use strict'
            //send mail modal form open
            $('.send-mail').on('click', function () {
                var id = $(this).data('id')
                var name = $(this).data('name')
                $('#name').html(name)
                $('#userId').val(id)
                $('#sendEmail').modal('toggle')
            })
        })(jQuery)
    </script>
@endsection
