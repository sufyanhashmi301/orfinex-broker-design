@php use App\Enums\InvestStatus; @endphp
@extends('backend.layouts.app')
@section('title')
    {{ __('Dashboard') }}
@endsection
@section('style')
    <style>
        .page-content {
            padding-top: 0;
        }
        .dashboardTitle {
            background-image: url('https://cdn.brokeret.com/crm-assets/admin/home/header.svg');
            background-repeat: repeat;
            border-radius: 0;
        }
    </style>
@endsection
@section('content')

    <div class="dashboardTitle card shadow-sm dark:shadow-slate-700 p-6 pb-0 mb-6 -mx-6">
        <div class="flex justify-between flex-wrap items-center mb-5">
            <div>
                <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
                    {{ __('Hello, ') }}{{ Auth::user()->name }}
                </h4>
                <p class="flex items-center text-slate-600 dark:text-slate-300 text-sm">
                    <iconify-icon class="mr-2" icon="lucide:mail"></iconify-icon>
                    {{ Auth::user()->email }}
                </p>
            </div>
            @canany(['deposit-action','withdraw-action','kyc-action',])
                @if($data['withdraw_count'] || $data['kyc_count'] || $data['deposit_count'])
                    <div class="text-right">
                        <p class="text-base dark:text-white font-medium mb-2">
                            {{ __("Explore what's important to review first") }}
                        </p>
                        <div class="flex justify-end gap-3">
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
        </div>
        <ul class="nav nav-tabs flex flex-col md:flex-row flex-wrap list-none" id="tabs-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-2 pb-2 hover:border-transparent focus:border-transparent active dark:text-slate-300" id="tabs-dashboard-tab" data-bs-toggle="tab" href="#tabs-dashboard" role="tab" aria-controls="tabs-dashboard" aria-selected="false">
                    {{ __('Dashboard') }}
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link w-full block font-medium text-sm font-Inter leading-tight capitalize border-x-0 border-t-0 border-b border-transparent px-2 pb-2 hover:border-transparent focus:border-transparent dark:text-slate-300" id="tabs-changelog-tab" data-bs-toggle="tab" href="#tabs-changelog" role="tab" aria-controls="tabs-changelog" aria-selected="false">
                    {{ __('Changelog') }}
                </a>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="tabs-tabContent">
        <div class="tab-pane fade show active" id="tabs-dashboard" role="tabpanel" aria-labelledby="tabs-dashboard-tab">
            <div class="space-y-5 mb-5">
                @include('backend.include.__data_card')
            </div>

            <div class="grid grid-cols-12 gap-5">
{{--                <div class="lg:col-span-8 col-span-12">--}}
{{--                    <div class="card h-full">--}}
{{--                        <div class="card-header">--}}
{{--                            <h3 class="card-title">{{ __('Site Statistics') }}</h3>--}}
{{--                            <div class="card-header-links">--}}
{{--                                <input class="form-control !py-1" data-mode="range" type="text" name="daterange" value="{{ $data['start_date'] .' - '. $data['end_date'] }}" />--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="card-body p-6">--}}
{{--                            <canvas id="depositChart"></canvas>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="lg:col-span-4 col-span-12">--}}
{{--                    <div class="card h-full">--}}
{{--                        <div class="card-header">--}}
{{--                            <h3 class="card-title">{{ __('Scheme Statistics') }}</h3>--}}
{{--                        </div>--}}
{{--                        <div class="card-body p-6">--}}
{{--                            <canvas id="schemeChart"></canvas>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="lg:col-span-4 col-span-12">--}}
{{--                    <div class="card h-full">--}}
{{--                        <div class="card-header">--}}
{{--                            <h3 class="card-title">{{ __('Top Country Statistics') }}</h3>--}}
{{--                        </div>--}}
{{--                        <div class="card-body p-6">--}}
{{--                            <canvas id="countryChart"></canvas>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="lg:col-span-4 col-span-12">--}}
{{--                    <div class="card h-full">--}}
{{--                        <div class="card-header">--}}
{{--                            <h3 class="card-title">{{ __('Best Browser Statistics') }}</h3>--}}
{{--                        </div>--}}
{{--                        <div class="card-body p-6">--}}
{{--                            <canvas id="browserChart"></canvas>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="lg:col-span-4 col-span-12">--}}
{{--                    <div class="card h-full">--}}
{{--                        <div class="card-header">--}}
{{--                            <h3 class="card-title">{{ __('Best OS Statistics') }}</h3>--}}
{{--                        </div>--}}
{{--                        <div class="card-body p-6">--}}
{{--                            <canvas id="osChart"></canvas>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

                @include('backend.include.__latest_user_invest')

            </div>

            <!-- Modal for Send Email -->
            @include('backend.user.include.__mail_send')
            <!-- Modal for Send Email-->
        </div>
        <div class="tab-pane fade" id="tabs-changelog" role="tabpanel" aria-labelledby="tabs-changelog-tab">
            <div id="changelog-container"></div>
        </div>
    </div>

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

        $(document).ready(function() {
            $.ajax({
                url: '{{ route("admin.changelog") }}',
                method: 'GET',
                success: function (data) {
                    console.log(data);
                    $('#changelog-container').html(data);
                }
            })
        })
    </script>
@endsection
