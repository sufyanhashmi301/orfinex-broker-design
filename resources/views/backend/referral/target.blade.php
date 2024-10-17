@extends('backend.layouts.app')
@section('title')
    {{ __('Set Target') }}
@endsection
@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="space-y-6">
            <div class="flex flex-wrap justify-between items-center mb-4">
                <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                    {{ __('Set Referral Target') }}
                </h4>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('List of Targets') }}</h4>
                    <div class="card-header-links">
                        <a href="" class="btn btn-dark btn-sm inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#addNewTarget">
                            <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="ph:plus-bold"></iconify-icon>
                            {{ __('Add New') }}
                        </a>
                    </div>
                </div>
                <div class="card-body p-6">
                    <p class="paragraph text-slate-600 dark:text-slate-400 text-sm mb-4">
                        <iconify-icon class="text-base text-warning mr-1" icon="lucide:alert-triangle"></iconify-icon>
                        {{ __('You can') }}
                        <strong>{{ __('Add or Edit') }}</strong> {{ __('any of the') }}
                        <strong>{{ __('Targets') }}</strong>
                    </p>
                    @foreach($targets as $target)
                        <div class="single-gateway flex items-center justify-between border dark:border-slate-700 rounded py-3 px-4 mb-4">
                            <div class="gateway-name flex items-center">
                                <div class="gateway-title">
                                    <h4 class="text-sm">{{ $target->name }}</h4>
                                </div>
                            </div>
                            <div class="gateway-right flex items-center">
                                <div class="gateway-edit">
                                    <a href="#" type="button" class="action-btn edit-target dark:text-slate-300" data-id="{{ $target->id }}" data-name="{{ $target->name }}">
                                        <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Add New Target -->
    @include('backend.referral.include.__new_target')
    <!-- Modal for Add New Target-->

    <!-- Modal for Edit Target -->
    @include('backend.referral.include.__edit_target')
    <!-- Modal for Edit Target-->
@endsection

@section('script')
    <script>
        $('.edit-target').on('click',function (e) {
            "use strict";
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            $('#target-id').val(id);
            $('.target-name').val(name);
            $('#editTarget').modal('show');

        })


    </script>
@endsection
