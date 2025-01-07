@extends('backend.layouts.app')
@section('title')
    {{ __('All Investments') }}
@endsection
@section('content')
    <div class="innerMenu flex justify-between flex-wrap items-center gap-5 mb-5">
        <ul class="nav nav-tabs custom-tabs inline-flex items-center overflow-hidden rounded list-none border-0 pl-0" id="tabs-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <a href="{{ route('admin.accounts-phases.log') }}" class="btn btn-sm inline-flex justify-center btn-outline-primary {{ url()->current() === route('admin.accounts-phases.log') && empty(request()->query()) ? 'active' : '' }}" aria-controls="tabs-realAccounts" aria-selected="true">{{ __('All Logs') }}</a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="?pending-approvals" class="btn btn-sm inline-flex justify-center btn-outline-primary {{ request()->has('pending-approvals') ? 'active' : '' }}" aria-controls="tabs-demoAccounts" aria-selected="false">{{ __('Pending Approvals') }}</a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="?violated-acounts" class="btn btn-sm inline-flex justify-center btn-outline-primary {{ request()->has('violated-acounts') ? 'active' : '' }}" aria-controls="tabs-archivedAccounts" aria-selected="false">{{ __('Violated Accounts') }}</a>
            </li>
            @if (request()->has('unique_id'))
              <li class="nav-item" role="presentation">
                <a href="?violated-acounts" class="btn btn-sm inline-flex justify-center btn-outline-primary {{ request()->has('unique_id') ? 'active' : '' }}" aria-controls="tabs-archivedAccounts" aria-selected="false">{{ __( request('unique_id') . ' Logs') }}</a>
              </li>
            @endif
           
        </ul>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <div class="flex justify-between sm:space-x-4 space-x-2">

                <div class="flex items-center space-x-2 sm:rtl:space-x-reverse md:flex hidden">
                    <button type="button" class="btn btn-outline-secondary btn-sm inline-flex items-center justify-center grid-view-btn active" data-target="trading-accounts">
                        <iconify-icon class="text-lg" icon="heroicons:view-columns"></iconify-icon>
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm inline-flex items-center justify-center list-view-btn" data-target="trading-accounts">
                        <iconify-icon class="text-lg" icon="heroicons:list-bullet"></iconify-icon>
                    </button>
                </div>
            </div>
            {{-- <a href="{{route('user.schema')}}" class="btn inline-flex justify-center btn-primary btn-sm">
                <span class="flex items-center">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="bi:plus"></iconify-icon>
                    <span>{{ __('Start Challenge') }}</span>
                </span>
            </a> --}}
        </div>
    </div>
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="tab-content" id="trading-accounts">
                <div class="tab-pane fade show active" id="tabs-realAccounts" role="tabpanel" aria-labelledby="tabs-realAccounts-tab">
                    @include('backend.certificates.includes.__certificates')
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')
    <script>
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
    </script>
@endsection
