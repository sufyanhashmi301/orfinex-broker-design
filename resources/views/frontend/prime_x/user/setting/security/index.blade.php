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
                {{-- 2 Factor Authentication --}}
                @include('frontend::user.setting.include.__two_fa')
        
                <div class="card border border-slate-100 dark:border-slate-700">
                    <div class="card-body p-6">
                        <p class="font-normal text-sm text-slate-500 mb-1">{{ __('Security') }}</p>
                        <h4 class="card-title mb-3">{{ __('Change Password') }}</h4>
                        <p class="dark:text-white mb-3">
                            {{ __('Always change your password after 2 months to keep your account secure.') }}
                        </p>
                        <a href="{{ route('user.change.password') }}" class="btn btn-dark block-btn inline-flex items-center">
                            {{ __('Change Password') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection