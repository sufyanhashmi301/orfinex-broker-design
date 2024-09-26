@extends('frontend::layouts.auth')
@section('title')
    {{ __('Forgot password') }}
@endsection
@section('description')
    {{ __('Enter your Email and instructions will be sent to you!') }}
@endsection
@section('content')
    <div class="">
        @if ($errors->any())
            <div class="alert alert-warning alert-dismissible fade show mt-2 text-sm" role="alert">
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <p class="flex-1 font-Inter">
                        @foreach($errors->all() as $error)
                            {{$error}}
                        @endforeach
                    </p>
                    <div class="flex-0 text-lg cursor-pointer">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <iconify-icon icon="line-md:close"></iconify-icon>
                        </button>
                    </div>
                </div>
            </div>
        @endif
        @if(session('status'))
            <div class="alert alert-warning alert-dismissible fade show mt-2 text-sm" role="alert">
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <p class="flex-1 font-Inter">
                        {{ session('status') }}
                    </p>
                    <div class="flex-0 text-lg cursor-pointer">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <iconify-icon icon="line-md:close"></iconify-icon>
                        </button>
                    </div>
                </div>
            </div>
        @endif
        <!-- BEGIN: Login Form -->
        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <div class="fromGroup">
                <label class="block capitalize form-label" for="email">{{ __('Email') }}</label>
                <div class="relative">
                    <input
                        class="form-control py-2 h-[48px]"
                        type="text"
                        name="email"
                        placeholder="Enter your email address"
                        required
                        value="{{ old('email') }}"
                    />
                </div>
            </div>
            <button type="submit" class="btn btn-primary block w-full text-center">
                {{ __('Email Password Reset Link') }}
            </button>
        </form>
        <!-- END: Login Form -->
        <div class="relative border-b-[#9AA2AF] border-opacity-[16%] border-b pt-6">
            <div class="absolute inline-block bg-white dark:bg-slate-800 left-1/2 top-1/2 transform -translate-x-1/2 px-4 min-w-max text-sm text-slate-500 dark:text-slate-400font-normal">
                {{ __('Or') }}
            </div>
        </div>
        <div class="md:max-w-[345px] mt-6 mx-auto font-normal text-slate-500 text-center dark:text-slate-400mt-12 uppercase text-sm">
            {{ __('Already have access?') }}
            <a href="{{ route('login') }}" class="text-slate-900 dark:text-white font-medium hover:underline">
                {{ __('Sign In') }}
            </a>
        </div>
    </div>
@endsection


