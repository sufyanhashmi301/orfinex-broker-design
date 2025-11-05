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
            background-image: url('{{ config('app.r2_asset_url') . '/fallback/header.svg' }}');
            background-repeat: repeat;
            border-radius: 0;
        }
        
        /* Enhanced card styles */
        .enhanced-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        
        .enhanced-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-color: #cbd5e1;
        }
        
        .dark .enhanced-card {
            background: #1e293b;
            border-color: #334155;
        }
        
        .dark .enhanced-card:hover {
            border-color: #475569;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        
        /* Chart container improvements */
        .chart-container {
            position: relative;
            padding: 24px;
            background: #fafafa;
            border-radius: 8px;
            min-height: 300px;
        }
        
        .dark .chart-container {
            background: #0f172a;
        }
        
        /* Card header enhancements */
        .enhanced-card-header {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 1.25rem 1.5rem;
            border-radius: 0.5rem 0.5rem 0 0;
        }
        
        .dark .enhanced-card-header {
            background: #0f172a;
            border-bottom-color: #334155;
        }
        
        .enhanced-card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .dark .enhanced-card-title {
            color: #f1f5f9;
        }
        
        .enhanced-card-title iconify-icon {
            font-size: 1.25rem;
            opacity: 0.7;
        }
        
        /* Grid improvements */
        .dashboard-grid {
            gap: 1.5rem;
        }
        
        .dashboard-grid > div {
            min-height: 400px;
        }
        
        /* Statistics cards specific styling */
        .stats-card {
            position: relative;
            overflow: hidden;
        }
        
        /* Canvas container */
        .canvas-wrapper {
            position: relative;
            height: 280px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .canvas-wrapper canvas {
            max-height: 100%;
            border-radius: 8px;
        }
        
        /* Filter section improvements */
        .filter-section {
            background: #f1f5f9;
            padding: 0.75rem;
            border-radius: 0.375rem;
            border: 1px solid #e2e8f0;
        }
        
        .dark .filter-section {
            background: #0f172a;
            border-color: #334155;
        }
        
        /* Animation for loading states */
        @keyframes pulse-subtle {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        
        .chart-loading {
            animation: pulse-subtle 2s infinite;
        }
    </style>
@endsection
@section('content')

    <div class="dashboardTitle card shadow-sm dark:shadow-slate-700 p-6 pb-0 mb-6 -mx-6">
        <div class="flex justify-between flex-wrap items-center gap-3 mb-5">
            <div>
                <h4 class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 flex space-x-3 rtl:space-x-reverse">
                    {{ __('Hello, ') }}{{ Auth::user()->name }}
                </h4>
                <p class="flex items-center text-slate-600 dark:text-slate-300 text-sm">
                    <iconify-icon class="mr-2" icon="lucide:mail"></iconify-icon>
                    {{ Auth::user()->email }}
                </p>
            </div>
            @canany(['deposit-list','withdraw-list','kyc-list',])
                @if($data['withdraw_count'] || $data['kyc_count'] || $data['deposit_count'] || $data['smtp_failure_count'])
                    <div class="md:text-right">
                        <p class="text-base dark:text-white font-medium mb-2">
                            {{ __("Explore what's important to review first") }}
                        </p>
                        <div class="flex md:justify-end flex-wrap gap-3">
                            @can('withdraw-list')
                                @if($data['withdraw_count'])
                                    <a href="{{ route('admin.withdraw.pending') }}" class="btn btn-sm btn-danger inline-flex items-center justify-center">
                                        <iconify-icon class="spining-icon text-lg mr-2"  icon="lucide:loader"></iconify-icon>
                                        {{ __('Withdraw Requests') }}
                                        ({{ $data['withdraw_count'] }})
                                    </a>
                                @endif
                            @endcan

                            @can('kyc-list')
                                @if($data['kyc_count'])
                                    <a href="{{ route('admin.kyc.pending') }}" class="btn btn-sm btn-success inline-flex items-center justify-center">
                                        <iconify-icon class="spining-icon text-lg mr-2"  icon="lucide:loader"></iconify-icon>
                                        {{ __('KYC Requests') }}
                                        ({{ $data['kyc_count'] }})
                                    </a>
                                @endif
                            @endcan

                            @can('deposit-list')
                                @if($data['deposit_count'])
                                    <a href="{{ route('admin.deposit.manual.pending') }}" class="btn btn-sm btn-dark inline-flex items-center justify-center">
                                        <iconify-icon class="spining-icon text-lg mr-2"  icon="lucide:loader"></iconify-icon>
                                        {{ __('Deposit Requests') }}
                                        ({{ $data['deposit_count'] }})
                                    </a>
                                @endif
                            @endcan

                            {{-- SMTP Failure Alert --}}
                            @if(!empty($data['smtp_failure_count']) && $data['smtp_failure_count'] > 0)
                                <a href="{{ route('admin.smtp.monitoring.logs') }}" class="btn btn-sm btn-warning inline-flex items-center justify-center">
                                    <iconify-icon class="text-lg mr-2" icon="lucide:mail-warning"></iconify-icon>
                                    {{ __('SMTP Failures') }}
                                    ({{ $data['smtp_failure_count'] }})
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            @endcanany
        </div>
        <ul class="nav nav-tabs flex flex-wrap list-none" id="tabs-tab" role="tablist">
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
            <div class="space-y-5 mb-6">
                @include('backend.include.__data_card')
            </div>

            <div class="grid grid-cols-12 dashboard-grid">

                @include('backend.include.__latest_tickets')

                <!-- Payment Statistics - Enhanced -->
                <div class="lg:col-span-8 col-span-12">
                    <div class="card enhanced-card stats-card h-full">
                        <div class="enhanced-card-header">
                            <div class="flex justify-between items-center">
                                <h3 class="enhanced-card-title">
                                    <iconify-icon icon="lucide:trending-up" class="text-blue-500"></iconify-icon>
                                    {{ __('Payment Statistics') }}
                                </h3>
                                <div class="filter-section">
                                    <form id="transactions_statistics_filter" action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center gap-3">
                                        <div class="input-area relative">
                                            <iconify-icon icon="lucide:calendar" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400 z-10"></iconify-icon>
                                            <input class="form-control flatpickr h-[36px] !py-2 !pl-10" data-mode="range" type="text" name="daterange" value="{{ $data['start_date'] .' - '. $data['end_date'] }}" />
                                        </div>
                                        <button type="submit" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-600 hover:bg-slate-700 text-white transition-all duration-200">
                                            <iconify-icon class="text-base ltr:mr-2 rtl:ml-2" icon="lucide:filter"></iconify-icon>
                                            {{ __('Filter') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="chart-container">
                                <div class="canvas-wrapper">
                                    <canvas id="depositChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deposit Statistics - Enhanced -->
                <div class="lg:col-span-4 col-span-12">
                    <div class="card enhanced-card stats-card h-full">
                        <div class="enhanced-card-header">
                            <h3 class="enhanced-card-title">
                                <iconify-icon icon="lucide:pie-chart" class="text-slate-600 dark:text-slate-400"></iconify-icon>
                                {{ __('Deposit Statistics') }}
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="chart-container">
                                <div class="canvas-wrapper">
                                    <canvas id="schemeChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Country Statistics - Enhanced -->
                <div class="lg:col-span-4 col-span-12">
                    <div class="card enhanced-card stats-card h-full">
                        <div class="enhanced-card-header">
                            <h3 class="enhanced-card-title">
                                <iconify-icon icon="lucide:globe" class="text-slate-600 dark:text-slate-400"></iconify-icon>
                                {{ __('Top Country Statistics') }}
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="chart-container">
                                <div class="canvas-wrapper">
                                    <canvas id="countryChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Best Browser Statistics - Enhanced -->
                <div class="lg:col-span-4 col-span-12">
                    <div class="card enhanced-card stats-card h-full">
                        <div class="enhanced-card-header">
                            <h3 class="enhanced-card-title">
                                <iconify-icon icon="lucide:monitor" class="text-slate-600 dark:text-slate-400"></iconify-icon>
                                {{ __('Best Browser Statistics') }}
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="chart-container">
                                <div class="canvas-wrapper">
                                    <canvas id="browserChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Best OS Statistics - Enhanced -->
                <div class="lg:col-span-4 col-span-12">
                    <div class="card enhanced-card stats-card h-full">
                        <div class="enhanced-card-header">
                            <h3 class="enhanced-card-title">
                                <iconify-icon icon="lucide:smartphone" class="text-slate-600 dark:text-slate-400"></iconify-icon>
                                {{ __('Best OS Statistics') }}
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="chart-container">
                                <div class="canvas-wrapper">
                                    <canvas id="osChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
                    // console.log(data);
                    $('#changelog-container').html(data);
                }
            })
        })
    </script>
@endsection