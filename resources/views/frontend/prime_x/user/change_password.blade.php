@extends('frontend::layouts.user')
@section('title')
    {{ __('Change Password') }}
@endsection
@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="flex justify-end flex-wrap items-center mb-5">
            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                <a href="{{ route('user.setting.security') }}" class="btn btn-primary inline-flex items-center">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="akar-icons:arrow-back"></iconify-icon>
                    {{ __('Back') }}
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-6">
                <div class="progress-steps-form">
                    <form action="{{ route('user.new.password') }}" method="post" class="space-y-4">
                        @csrf

                        @foreach ($errors->all() as $error)
                            @php
                                notify()->warning($error);
                            @endphp
                        @endforeach
                        <div class="input-area relative">
                            <label for="" class="form-label">{{ __('Current Password') }}</label>
                            <input type="password" name="current_password" class="form-control !text-lg">
                        </div>
                        <div class="input-area relative">
                            <label for="" class="form-label">{{ __('New Password') }}</label>
                            <input type="password" name="new_password" class="form-control !text-lg">
                        </div>
                        <div class="input-area relative">
                            <label for="" class="form-label">{{ __('Confirm Password') }}</label>
                            <input type="password" name="new_confirm_password" class="form-control !text-lg">
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-dark">{{ __('Change Password') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
