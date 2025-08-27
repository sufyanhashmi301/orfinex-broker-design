@extends('frontend::layouts.user')
@section('title')
    {{ __('Change Password') }}
@endsection
@section('content')
    <div class="mx-auto max-w-2xl">
        <x-card
            title="{{ __('Change Password') }}"
            subtitle="{{ __('Enter your current password and confirm your new password.') }}"
        >
            <form action="{{ route('user.new.password') }}" method="post" class="space-y-4">
                @csrf

                @foreach ($errors->all() as $error)
                    @php
                        notify()->warning($error);
                    @endphp
                @endforeach
                <x-forms.field
                    fieldId="current_password"
                    fieldLabel="{{ __('Current Password') }}"
                    fieldName="current_password"
                    fieldPlaceholder="{{ __('Enter Current Password') }}"
                    type="password"
                    :fieldRequired="true"
                />
                <x-forms.field
                    fieldId="new_password"
                    fieldLabel="{{ __('New Password') }}"
                    fieldName="new_password"
                    fieldPlaceholder="{{ __('Enter New Password') }}"
                    type="password"
                    :fieldRequired="true"
                />
                <x-forms.field
                    fieldId="new_confirm_password"
                    fieldLabel="{{ __('Confirm Password') }}"
                    fieldName="new_confirm_password"
                    fieldPlaceholder="{{ __('Confirm New Password') }}"
                    type="password"
                    :fieldRequired="true"
                />
                <x-forms.button type="submit" class="w-full" size="lg" variant="primary">
                    {{ __('Change Password') }}
                </x-forms.button>
            </form>
        </x-card>
    </div>
@endsection
