@extends('frontend::layouts.user')
@section('title')
    {{ __('Contracts') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-3">
        <ul class="nav nav-tabs custom-tabs inline-flex items-center overflow-hidden rounded list-none border-0 pl-0">
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary active">
                    All
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary">
                    Challenges
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary">
                    Payout
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary">
                    Max Allocation
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary">
                    Lifetime Payout
                </a>
            </li>
        </ul>
    </div>

    <div class="card p-6">
        <div class="max-w-xl text-center py-10 mx-auto space-y-5">
            <div class="w-20 h-20 bg-danger-500 text-white rounded-full inline-flex items-center justify-center">
                <iconify-icon icon="fa6-solid:box-open" class="text-5xl"></iconify-icon>
            </div>
            <h4 class="text-xl text-slate-900 dark:text-white">
                {{ __("No active or pending contracts") }}
            </h4>
            <a href="" class="btn btn-primary inline-flex items-center justify-center">
                {{ __('Start a new challenge') }}
            </a>
        </div>
    </div>

@endsection
