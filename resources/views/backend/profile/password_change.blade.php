@extends('backend.layouts.app')
@section('title')
    {{ __('Change Password') }}
@endsection
@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between flex-wrap items-center mb-6">
            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                {{ __('Change Password') }}
            </h4>
        </div>
        <div class="card">
            <div class="card-body p-6">
                <form method="post" action="{{ route('admin.password-update') }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="col-span-2">
                            <div class="input-area relative">
                                <label for="" class="form-label">{{ __('Old Password') }}</label>
                                <input type="password" name="current_password" class="form-control"
                                    required="">
                            </div>
                        </div>
                        <div class="input-area relative">
                            <label for="" class="form-label">{{ __('New Password') }}</label>
                            <input type="password" name="new_password" class="form-control" required="">
                        </div>
                        <div class="input-area relative">
                            <label for="" class="form-label">{{ __('Confirm Password') }}</label>
                            <input type="password" name="new_confirm_password" class="form-control"
                                required="">
                        </div>
                        <div class="col-span-2 text-right">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                {{ __('Change Password') }}
                            </button>
                        </div>
                    </div>
                </form>
    
            </div>
        </div>
    </div>
@endsection
