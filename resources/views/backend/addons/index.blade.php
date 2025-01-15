@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Addons') }}
@endsection
@section('content')

    <div class="pageTitle flex justify-between flex-wrap items-center mb-4">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Manage Addons') }}
        </h4>

        {{-- <a href="" class="btn btn-primary inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#config-modal">
        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:bolt"></iconify-icon>
          Configure Parameters
        </a> --}}

    </div>

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="tab-content" id="trading-accounts">
                <div class="tab-pane fade show active" id="tabs-realAccounts" role="tabpanel" aria-labelledby="tabs-realAccounts-tab">
                    @include('backend.addons.includes.__addons')
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
