@extends('frontend::user.setting.index')
@section('title')
    {{ __('Edit Withdraw Account') }}
@endsection
@section('settings-content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <h2 class="text-title-sm font-bold text-gray-800 dark:text-white/90 mb-1">
                {{ __('Edit Withdraw Account') }}
            </h2>
            <p class="text-gray-800 text-theme-sm dark:text-white/90">
                {{ __('Update your withdrawal payment method details and settings.') }}
            </p>
        </div>
        <x-frontend::link-button href="{{ route('user.withdraw.account.index') }}" variant="secondary" icon="arrow-left" icon-position="left">
            {{ __('Back to Accounts') }}
        </x-frontend::link-button>
    </div>
    <form action="{{ route('user.withdraw.account.update',$withdrawAccount->id) }}" method="post" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <input type="hidden" name="withdraw_method_id" value="{{$withdrawAccount->withdraw_method_id}}">
        
        <!-- Form Fields -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 selectMethodRow">
            <x-frontend::forms.field
                :fieldId="'method_name'"
                :fieldName="'method_name'"
                :fieldLabel="__('Method Name')"
                :fieldValue="$withdrawAccount->method_name"
                :fieldPlaceholder="__('eg. Withdraw Method - USD')"
                :fieldRequired="true"
            />

            @foreach(json_decode($withdrawAccount->credentials, true) as $key => $field)
                @if($field['type'] == 'file')
                    <input type="hidden" name="credentials[{{ $key }}][type]" value="{{ $field['type'] }}">
                    <input type="hidden" name="credentials[{{ $key }}][validation]" value="{{ $field['validation'] }}">

                    <x-frontend::forms.file-field
                        fieldId="credentials_{{ $key }}"
                        fieldName="credentials[{{ $key }}][value]"
                        fieldLabel="{{ __($key) }}"
                        fieldRequired="{{ $field['validation'] == 'required' ? 'true' : 'false' }}"
                        fieldValue="{{ $field['value'] ?? '' }}"
                        accept=".gif,.jpg,.png"
                    />
                @elseif($field['type'] == 'textarea')
                    <input type="hidden" name="credentials[{{ $key }}][type]" value="{{ $field['type'] }}">
                    <input type="hidden" name="credentials[{{ $key }}][validation]" value="{{ $field['validation'] }}">

                    <x-frontend::forms.textarea-field
                        fieldId="credentials_{{ $key }}"
                        fieldName="credentials[{{ $key }}][value]"
                        fieldLabel="{{ __($key) }}"
                        fieldRequired="{{ $field['validation'] == 'required' ? 'true' : 'false' }}"
                        fieldValue="{{ $field['value'] ?? '' }}"
                    />
                @else
                    <input type="hidden" name="credentials[{{ $key }}][type]" value="{{ $field['type'] }}">
                    <input type="hidden" name="credentials[{{ $key }}][validation]" value="{{ $field['validation'] }}">

                    <x-frontend::forms.field
                        fieldId="credentials_{{ $key }}"
                        fieldName="credentials[{{ $key }}][value]"
                        fieldLabel="{{ __($key) }}"
                        fieldRequired="{{ $field['validation'] == 'required' ? 'true' : 'false' }}"
                        fieldValue="{{ $field['value'] ?? '' }}"
                    />
                @endif
            @endforeach

        </div>
        <div class="buttons mt-6">
            <x-frontend::forms.button type="submit" size="md" variant="primary" icon="check" iconPosition="left">
                {{ __('Update Account') }}
            </x-frontend::forms.button>
        </div>
    </form>
@endsection
@section('script')
    <script>
        $("#selectMethod").on('change',function (e) {
            "use strict"
            e.preventDefault();

            //$('.manual-row').empty();
            $('.selectMethodRow').children().not(':first').remove();

            var id = $(this).val()

            var url = '{{ route("user.withdraw.method",":id") }}';
            url = url.replace(':id', id);
            $.get(url, function (data) {
                $(data).insertAfter(".selectMethodCol");
                imagePreview()
            })
        })
    </script>
@endsection
