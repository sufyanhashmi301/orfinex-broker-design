@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Referral') }}
@endsection
@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap justify-between items-center mb-4">
            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                {{ __('Multi Level Referrals') }}
            </h4>
            <div class="flex space-x-4 justify-end items-center rtl:space-x-reverse">
                @can('referral-create')
                    <button class="btn inline-flex justify-center btn-dark new-referral" type="button">
                        <span class="flex items-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                            <span>{{ __('Add New') }}</span>
                    </button>
                @endcan
            </div>
        </div>
        <div class="grid grid-cols-3 gap-5">
            <div class="lg:col-span-1 col-span-3">
                <div class="h-full rounded transition-all duration-100 shadow-none bg-slate-200 dark:bg-slate-700">
                    <div class="relative flex justify-between items-center bg-white dark:bg-slate-800 rounded shadow-base px-6 py-5">
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-[2px] bg-primary-500"></span>
                        <h3 class="text-lg text-slate-900 dark:text-white font-medium capitalize">
                            {{ __('Deposit Bounty') }}
                        </h3>
                        <div class="flex items-center space-x-2">
                            <form action="{{ route('admin.referral.level-status') }}" method="post" id="deposit-status">
                                @csrf
                                <input type="hidden" name="type" value="deposit_level">
                                <div class="form-switch ps-0" style="line-height: 0;">
                                    <input 
                                        class="form-check-input" 
                                        type="hidden" 
                                        value="0" 
                                        name="status"
                                    />
                                    <label class="deposit-status relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-switch">
                                        <input 
                                            type="checkbox" 
                                            name="status" 
                                            value="1" 
                                            class="sr-only peer"
                                            @checked(setting('deposit_level'))
                                        />
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="px-2 py-4 h-full space-y-4 rounded-bl rounded-br">
                        <!-- BEGIN: Cards -->
                        <div class="card rounded-md bg-white dark:bg-slate-800 shadow-base custom-class card-body p-6">
                            <p class="paragraph text-slate-600 dark:text-slate-400 text-sm py-4">
                                {{ __('You can') }}
                                <strong>{{ __('Add').','. __('Edit').' '. __('or').' '. __('Delete') }}</strong> {{ __('any of the') }}
                                <strong>{{ __('Level Referred User Deposit Bounty') }}</strong>
                            </p>
                            @foreach($deposits as $raw)
                            <div class="single-gateway flex items-center justify-between border rounded py-3 px-4 mb-4">
                                <div class="gateway-name flex items-center">
                                    <div class="gateway-title">
                                        <h4 class="text-sm">{{  __('Level '). $raw->the_order }}</h4>
                                    </div>
                                </div>
                                <div class="gateway-right flex items-center">
                                    <div class="gateway-status mr-7">
                                        <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
                                            {{ $raw->bounty }}%
                                        </div>
                                    </div>
                                    <div class="gateway-edit flex space-x-3 rtl:space-x-reverse">
                                        @can('referral-edit')
                                            <a href="" type="button" class="action-btn edit-referral"
                                               data-id="{{$raw->id}}"
                                               data-editfor="{{ 'Update ' . $raw->type . ' level '. $raw->the_order }}"
                                               data-bounty="{{ $raw->bounty }}"
                                            >
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </a>
                                        @endcan
                                        @can('referral-delete')
                                            <a href="" class="action-btn delete-referral" type="button"
                                               data-id="{{$raw->id}}"
                                               data-type="{{$raw->type}}"
                                               data-target="{{  $raw->type . ' level '. $raw->the_order }}"
                                            >
                                                <iconify-icon icon="lucide:trash-2"></iconify-icon>
                                            </a>
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
            <div class="lg:col-span-1 col-span-3">
                <div class="h-full rounded transition-all duration-100 shadow-none bg-slate-200 dark:bg-slate-700">
                    <div class="relative flex justify-between items-center bg-white dark:bg-slate-800 rounded shadow-base px-6 py-5">
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-[2px] bg-primary-500"></span>
                        <h3 class="text-lg text-slate-900 dark:text-white font-medium capitalize">
                            {{ __('Multi IB Bounty') }}
                        </h3>
                        <div class="flex items-center space-x-2">
                            <form action="{{ route('admin.referral.level-status') }}" method="post" id="MultiIB-status">
                                @csrf
                                <input type="hidden" name="type" value="multi_ib_level">
                                <div class="form-switch ps-0" style="line-height: 0;">
                                    <input 
                                        class="form-check-input" 
                                        type="hidden" 
                                        value="0" 
                                        name="status"
                                    />
                                    <label class="MultiIB-status relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-switch">
                                        <input 
                                            type="checkbox" 
                                            name="status" 
                                            value="1" 
                                            class="sr-only peer"
                                            @checked(setting('multi_ib_level','global',1))
                                        />
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="px-2 py-4 h-full space-y-4 rounded-bl rounded-br">
                        <!-- BEGIN: Cards -->
                        <div class="card rounded-md bg-white dark:bg-slate-800 shadow-base custom-class card-body p-6">
                            <p class="paragraph text-slate-600 dark:text-slate-400 text-sm py-4">
                                {{ __('You can') }}
                                <strong>{{ __('Add').','. __('Edit').' '. __('or').' '. __('Delete') }}</strong> {{ __('any of the') }}
                                <strong>{{ __('Level Referred User Multi IB Bounty') }}</strong>
                            </p>
                            @foreach($multiIBs as $raw)
                            <div class="single-gateway flex items-center justify-between border rounded py-3 px-4 mb-4">
                                <div class="gateway-name flex items-center">
                                    <div class="gateway-title">
                                        <h4 class="text-sm">{{  __('Level '). $raw->the_order }}</h4>
                                    </div>
                                </div>
                                <div class="gateway-right flex items-center">
                                    <div class="gateway-status mr-7">
                                        <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
                                            {{ $raw->bounty }}%
                                        </div>
                                    </div>
                                    <div class="gateway-edit flex space-x-3 rtl:space-x-reverse">
                                        @can('referral-edit')
                                            <a href="" type="button" class="action-btn edit-referral"
                                                data-id="{{$raw->id}}"
                                                data-editfor="{{ 'Update ' . $raw->type . ' level '. $raw->the_order }}"
                                                data-bounty="{{ $raw->bounty }}"
                                            ><iconify-icon icon="lucide:edit-3"></iconify-icon></a>
                                        @endcan
                                        @can('referral-delete')
                                            <a href="" class="action-btn delete-referral" type="button"
                                                data-id="{{$raw->id}}"
                                                data-type="{{$raw->type}}"
                                                data-target="{{  $raw->type . ' level '. $raw->the_order }}"
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
            <div class="lg:col-span-1 col-span-3">
                <div class="h-full rounded transition-all duration-100 shadow-none bg-slate-200 dark:bg-slate-700">
                    <div class="relative flex justify-between items-center bg-white dark:bg-slate-800 rounded shadow-base px-6 py-5">
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 h-8 w-[2px] bg-primary-500"></span>
                        <h3 class="text-lg text-slate-900 dark:text-white font-medium capitalize">
                            {{ __('Profit Bounty') }}
                        </h3>
                        <div class="flex items-center space-x-2">
                            <form action="{{ route('admin.referral.level-status') }}" method="post" id="profit-status">
                                @csrf
                                <input type="hidden" name="type" value="profit_level">
                                <div class="form-switch ps-0" style="line-height: 0;">
                                    <input 
                                        class="form-check-input" 
                                        type="hidden" 
                                        value="0" 
                                        name="status"
                                    />
                                    <label class="profit-status relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer toggle-switch">
                                        <input 
                                            type="checkbox" 
                                            name="status" 
                                            value="1" 
                                            class="sr-only peer"
                                            @checked(setting('profit_level'))
                                        />
                                        <span class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                    </label>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="px-2 py-4 h-full space-y-4 rounded-bl rounded-br">
                        <!-- BEGIN: Cards -->
                        <div class="card rounded-md bg-white dark:bg-slate-800 shadow-base custom-class card-body p-6">
                            <p class="paragraph text-slate-600 dark:text-slate-400 text-sm py-4">
                                {{ __('You can') }}
                                <strong>{{ __('Add').','. __('Edit').' '. __('or').' '. __('Delete') }}</strong> {{ __('any of the') }}
                                <strong>{{ __('Level Referred User Profit Bounty') }}</strong>
                            </p>
                            @foreach($profits as $raw)
                            <div class="single-gateway flex items-center justify-between border rounded py-3 px-4 mb-4">
                                <div class="gateway-name flex items-center">
                                    <div class="gateway-title">
                                        <h4 class="text-sm">{{  __('Level '). $raw->the_order }}</h4>
                                    </div>
                                </div>
                                <div class="gateway-right flex items-center">
                                    <div class="gateway-status mr-7">
                                        <div class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">
                                            {{ $raw->bounty }}%
                                        </div>
                                    </div>
                                    <div class="gateway-edit flex space-x-3 rtl:space-x-reverse">
                                        @can('referral-edit')
                                            <a href="" type="button" class="action-btn edit-referral"
                                                data-id="{{$raw->id}}"
                                                data-editfor="{{ 'Update ' . $raw->type . ' level '. $raw->the_order }}"
                                                data-bounty="{{ $raw->bounty }}"
                                            ><iconify-icon icon="lucide:edit-3"></iconify-icon></a>
                                        @endcan
                                        @can('referral-delete')
                                            <a href="" class="action-btn delete-referral" type="button"
                                                data-id="{{$raw->id}}"
                                                data-type="{{$raw->type}}"
                                                data-target="{{  $raw->type . ' level '. $raw->the_order }}"
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
        @include('backend.referral.include.__new_level_referral')
    @endcan
    <!-- Modal for Add New Level-->

    <!-- Modal for Edit Level -->
    @can('referral-edit')
        @include('backend.referral.include.__edit_level_referral')
    @endcan
    <!-- Modal for Edit Level-->

    {{--<!-- Modal for Delete Level -->--}}
    @can('referral-delete')
        @include('backend.referral.include.__delete_level_referral')
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
            var editFor = $(this).data('editfor');
            var bounty = $(this).data('bounty');

            var url = '{{ route("admin.referral.level.update",":id") }}';
            url = url.replace(':id', id);

            var form = document.getElementById("level-form");
            form.setAttribute("action", url);



            $('.referral-id').val(id);
            $('.edit-for').html(editFor);
            $('.bounty').val(bounty);

            $('#editReferral').modal('show');

        })
        $('.delete-referral').on('click',function (e) {
            "use strict";
            e.preventDefault();
            var id = $(this).data('id');
            var target = $(this).data('target');
            var type = $(this).data('type');

            var url = '{{ route("admin.referral.level.destroy",":id") }}';
            url = url.replace(':id', id);

            var form = document.getElementById("level-delete");
            form.setAttribute("action", url);

            $('.target').html(target);
            $('.level-type').val(type);
            $('#deleteReferral').modal('show');

        })



        $(".toggle-switch").click(function (message) {
            let className = $(this).attr('class');
            var idNames = className.split(' ')[0]; // Split the class names into an array
            // alert(idNames);
            $("#"+idNames).submit();
        });

    </script>
@endsection
