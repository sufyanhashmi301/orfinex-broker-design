@extends('frontend::layouts.user')
@section('title')
    {{ __('Change Password') }}
@endsection
@section('content')
    <div class="mb-5">
        <ul class="m-0 p-0 list-none">
            <li class="inline-block relative top-[3px] text-base text-primary font-Inter ">
                <a href="{{route('user.dashboard')}}">
                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                </a>
            </li>
            <li class="inline-block relative text-sm text-primary font-Inter ">
                {{ __('Settings') }}
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ __('Change Password') }}
            </li>
        </ul>
    </div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('Change Password') }}</h4>
            <div>
                <a href="{{ route('user.setting.show') }}" class="btn btn-dark">{{ __('Back') }}</a>
            </div>
        </div>
        <div class="card-body p-6">
            <div class="progress-steps-form">
                <form action="{{ route('user.new.password') }}" method="post">
                    @csrf

                    @foreach ($errors->all() as $error)
                        @php
                            notify()->warning($error);
                        @endphp
                    @endforeach

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5">
                        <div class="input-area relative">
                            <label for="exampleFormControlInput1" class="form-label">{{ __('Current Password') }}</label>
                            <input type="password" name="current_password" class="form-control !text-lg">
                        </div>
                        <div class="input-area relative">
                            <label for="exampleFormControlInput1" class="form-label">{{ __('New Password') }}</label>
                            <input type="password" name="new_password" class="form-control !text-lg">
                        </div>
                        <div class="input-area relative">
                            <label for="exampleFormControlInput1" class="form-label">{{ __('Confirm Password') }}</label>
                            <input type="password" name="new_confirm_password" class="form-control !text-lg">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-dark">{{ __('Change Password') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
