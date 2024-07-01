@extends('frontend::user.setting.index')
@section('title')
    {{ __('Security Settings') }}
@endsection
@section('settings-content')

    <div class="card">
        <div class="card-body p-6">
            <div class="mb-4">
                <h4 class="card-title mb-2">{{ __('Security Settings') }}</h4>
                <p class="block font-normal text-sm text-slate-500">
                    {{ __("Strengthen Your Online Security: It's your primary defense.") }}
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="card border dark:border-slate-700 h-full">
                    <div class="card-body h-full flex flex-col items-start p-6 gap-3">
                        <div>
                            <p class="font-normal text-sm text-slate-500 mb-1">{{ __('Security') }}</p>
                            <h4 class="card-title">{{ __('Authorization') }}</h4>
                        </div>
                        <div>
                            <p class="dark:text-white">
                                {{ __('Information for logging in to Primex.') }}
                            </p>
                            <p class="dark:text-white">
                                {{ __('Change your password whenever you think it might have been compromised.') }}
                            </p>
                        </div>
                        <div class="input-area w-full">
                            <div class="relative">
                                <input type="text" class="form-control form-control-lg !pr-32" value="{{ $user->email }}" disabled>
                                <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                    <a href="javascript:;" type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#emailEditModal" class="text-sm text-success-500">
                                        Change
                                    </a>
                                </span>
                            </div>
                        </div>
                        <div class="input-area w-full">
                            <div class="relative">
                                <input type="password" class="form-control form-control-lg !pr-32" value="12345678" disabled>
                                <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                    <a href="javascript:;" type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#changePasswordModal" class="text-sm text-success-500">
                                        Change
                                    </a>
                                </span>
                            </div>
                        </div>
                        <div class="mt-auto w-full">
                            <a href="" class="btn btn-dark block-btn mt-5">Update</a>
                        </div>
                    </div>
                </div>
        
                <div class="card border dark:border-slate-700 h-full">
                    <div class="card-body h-full flex flex-col items-start p-6 gap-3">
                        <div>
                            <p class="font-normal text-sm text-slate-500">{{ __('Security') }}</p>
                            <h4 class="card-title">{{ __('2-Step verification') }}</h4>
                        </div>
                        <div>
                            <p class="dark:text-white">
                                {{ __('2-step verification ensures that all sensitive transactions are authorized by you.') }}
                            </p>
                            <p class="dark:text-white">
                                {{ __('We encourage you to enter verification codes to confirm these transactions.') }}
                            </p>
                        </div>
                        <div class="input-area w-full">
                            <label for="" class="text-sm block w-full mb-1 text-slate-500 dark:text-slate-300">
                                {{ __('Security type') }}
                            </label>
                            <div class="relative">
                                <input type="text" class="form-control form-control-lg !pr-32" value="{{ $user->email }}">
                                <span class="absolute right-0 top-1/2 px-3 -translate-y-1/2 h-full border-none flex items-center justify-center">
                                    <a href="" class="text-sm text-success-500">
                                        Change
                                    </a>
                                </span>
                            </div>
                        </div>
                        <div class="mt-auto w-full">
                            <a href="{{ route('user.change.password') }}" class="btn btn-dark block-btn inline-flex items-center">
                                {{ __('Update') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Edit Email -->
    @include('frontend::user.setting.security.modal.__edit_email')
    
    <!-- Modal for Change Password -->
    @include('frontend::user.setting.security.modal.__change_password')

@endsection