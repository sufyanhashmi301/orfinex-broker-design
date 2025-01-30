@extends('backend.layouts.app')
@section('title')
    {{ __('Contracts') }}
@endsection
@section('content')

    <div class="pageTitle flex justify-between flex-wrap items-center">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ $title }}
        </h4>

        @can('contract-edit')
             <a href="" class="btn btn-primary inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#config-modal">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:bolt"></iconify-icon>
                Configure Parameters
            </a>
        @endcan
        

    </div>

    <div class="innerMenu card p-6 mb-5 mt-3">
        <ul class="nav nav-pills flex items-center flex-wrap list-none pl-0 space-x-4 menu-open w-full">
            <li class="nav-item">
                <a href="{{ route('admin.contracts.index', ['status' => 'all']) }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->get('status') == 'all' ? 'active' : '' }} ">
                    All
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.contracts.index', ['status' => \App\Enums\ContractStatusEnums::PENDING ]) }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->get('status') == \App\Enums\ContractStatusEnums::PENDING ? 'active' : '' }} ">
                    Pending
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.contracts.index', ['status' => \App\Enums\ContractStatusEnums::SIGNED ]) }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->get('status') == \App\Enums\ContractStatusEnums::SIGNED ? 'active' : '' }}">
                    Signed
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.contracts.index', ['status' => \App\Enums\ContractStatusEnums::EXPIRED ]) }}" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 {{ request()->get('status') == \App\Enums\ContractStatusEnums::EXPIRED ? 'active' : '' }}">
                    Expired
                </a>
            </li>
            {{-- <li class="nav-item !ml-auto">
                <a href="javascript:void(0);" class="nav-link block font-medium font-Inter text-sm leading-tight capitalize rounded-md px-4 py-2 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300 filter-toggle-btn">
                    <span class="flex items-center">
                        <span>{{ __('More') }}</span>
                        <iconify-icon icon="lucide:chevron-down" class="text-xl ltr:ml-2 rtl:mr-2 font-light"></iconify-icon>
                    </span>
                </a>
            </li> --}}
        </ul>

        <div class="hidden mt-5" id="filters_div">
            <form id="filter-form" method="GET" action="{{ route('admin.contracts.index') }}">
                <div class="flex justify-between flex-wrap items-center">
                    <div class="flex-1 inline-flex sm:space-x-3 space-x-2 ltr:pr-4 rtl:pl-4 mb-2 sm:mb-0" style="max-width: 400px">
                        <div class="flex-1 input-area relative">
                            <input type="text" name="search" id="search" class="form-control h-full" placeholder="Search by Login, Name, or Email">
                        </div>
                        <div class="input-area relative">
                            <button type="submit" id="filter" class="btn btn-sm inline-flex items-center justify-center min-w-max bg-slate-100 text-slate-700 dark:bg-slate-700 !font-normal dark:text-white">
                                <iconify-icon class="text-base ltr:mr-2 rtl:ml-2 font-light" icon="lucide:filter"></iconify-icon>
                                {{ __('Filter') }}
                            </button>
                        </div>
                    </div>
                    <div class="flex sm:space-x-3 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                        
                        
                    </div>
                </div>
            </form>
        </div>
    </div>
    
   
    @include('backend.contracts.includes.__contracts')
                

    {{-- Modals --}}
    @can('contract-edit')
        @include('backend.contracts.includes.__config_modal')
        @include('backend.contracts.includes.__sign_contract_modal')
        @include('backend.contracts.includes.__expire_contract_modal')
        @include('backend.contracts.includes.__pending_contract_modal')
    @endcan

@endsection

@section('script')
    <script>

        function calculateDaysDifference(expiry) {
            
            var expiryDateStr = expiry;
            var expiryDate = new Date(expiryDateStr);
            var currentDate = new Date();
            var timeDiff = expiryDate.getTime() - currentDate.getTime();
            var daysDiff = timeDiff / (1000 * 3600 * 24);
            daysDiff = Math.floor(daysDiff);
            return daysDiff
            
        }

        // grid or list view
        $('.list-view-btn').click(function () {
            const targetId = $(this).data('target');
            $('#' + targetId + ' .grid').removeClass('grid-view').addClass('list-view');
            $(this).addClass('active');
            $('.grid-view-btn').removeClass('active');
        });

        $('.grid-view-btn').click(function () {
            const targetId = $(this).data('target');
            $('#' + targetId + ' .grid').removeClass('list-view').addClass('grid-view');
            $(this).addClass('active');
            $('.list-view-btn').removeClass('active');
        });

        // Event listener for delete buttons
        $('.mark-as-signed').on('click', function (e) {
            e.preventDefault();
            $('#signContractModal').find('.account-login').text($(this).data('login'))
            $('#signContractModal').find('.contract_id').val($(this).data('id'))
            $('#signContractModal').modal('show');
        });
        $('.mark-as-expired').on('click', function(e) {
            e.preventDefault();
            $('#expireContractModal').find('.contract_id').val($(this).data('id'))
            $('#expireContractModal').find('.account-login').text($(this).data('login'))
            $('#expireContractModal').find('.rem-days').text(calculateDaysDifference($(this).data('expiry')))
            $('#expireContractModal').modal('show');
        })
        $('.mark-as-pending').on('click', function(e) {
            e.preventDefault();
            $('#pendingContractModal').find('.contract_id').val($(this).data('id'))
            $('#pendingContractModal').find('.account-login').text($(this).data('login'))
            $('#pendingContractModal').modal('show');
        })

    </script>
@endsection
