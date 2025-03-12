@extends('frontend::layouts.user')

@section('title')
    {{ __('Basic KYC') }}
@endsection

@section('content')
    @if($user->kyc ==  \App\Enums\KYCStatus::Pending->value)
        <div class="card py-10 px-10">
            <div class="flex items-center justify-center flex-col gap-3">
                <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M26 19.875V30.9167" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M25.988 37.5417H26.0075" stroke="rgba({{ implode(' ', getColorFromSettings('danger_color')) }})" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p class="text-lg text-slate-600 dark:text-slate-100 mb-3">
                    {{ __('Your KYC is Pending') }}
                </p>
            </div>
        </div>
    @elseif($user->kyc ==  \App\Enums\KYCStatus::Level2->value)
        <div class="card py-10 px-10">
            <div class="flex items-center justify-center flex-col gap-3">
                <iconify-icon class="text-success" icon="solar:user-check-bold" style="font-size: 52px;"></iconify-icon>
                <p class="text-lg text-slate-600 dark:text-slate-100 mb-3">
                    {{ __('Your KYC is Verified') }}
                </p>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <form action="{{ route('user.kyc.submit') }}" method="post" enctype="multipart/form-data">
                        @csrf
                            <div class="progress-steps-form">
                                <label for="kycTypeSelect" class="form-label">{{ __('Verification Type') }}</label>
                                <div class="input-group">
                                    <select name="kyc_id" id="kycTypeSelect" class="select2 form-control" required>
                                        <option selected disabled>{{ __('----') }}</option>
                                        @foreach($kycs as $kyc)
                                            <option value="{{ $kyc->id }}">{{ $kyc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="kycData mt-4"></div>
                            <button type="submit" class="btn btn-dark mt-3">{{ __('Submit Now') }}</button>
                        </form>
                    </div>
                    <div>
                        <p class="text-sm dark:text-white mt-7 mb-3">
                            {{ __('The document you are providing must be valid for at least 30 days and contain all of the following details:') }}
                        </p>
                        <figure class="figure d-block">
                            <svg alt="{{ __('verification example') }}" viewBox="0 0 320 178" class="img-fluid">
                                <use xlink:href="{{ asset('frontend/images/cards.svg#pid-passport') }}"></use>
                            </svg>
                        </figure>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <ul class="space-y-3">
                    <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                        <iconify-icon class="relative text-xl mr-2 text-success" icon="material-symbols:check-box"></iconify-icon>
                        {{ __('Upload a colorful full-size (4 sides visible) photo of the document.') }}
                    </li>
                    <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                        <iconify-icon class="relative text-xl mr-2 text-danger" icon="entypo:squared-cross"></iconify-icon>
                        {{ __('Do not upload selfies, screenshots, and do not modify the images in graphic editors.') }}
                    </li>
                </ul>
            </div>
        </div>
    @endif
@endsection

@section('script')
    <script>
        $("#kycTypeSelect").on('change', function(e) {
            "use strict";
            e.preventDefault();

            $('.kycData').empty();
            var id = $(this).val();
            var url = '{{ route("user.kyc.data", ":id") }}';
            url = url.replace(':id', id);

            $.get(url, function(data) {
                console.log(data);
                $('.kycData').append(data);
                imagePreview();
            });
        });
    </script>
@endsection
