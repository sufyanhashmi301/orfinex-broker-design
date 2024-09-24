@extends('frontend::layouts.user')
@section('title')
    {{ __('Basic KYC') }}
@endsection
@section('content')
    <div class="mb-5">
        <ul class="m-0 p-0 list-none">
            <li class="inline-block relative top-[3px] text-base text-primary-500 font-Inter ">
                <a href="{{route('user.dashboard')}}">
                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                </a>
            </li>
            <li class="inline-block relative text-sm text-primary-500 font-Inter ">
                {{ __('KYC') }}
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ __('Basic') }}
            </li>
        </ul>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('Basic KYC') }}</h3>
        </div>
        <div class="card-body p-6">
            @if($user->kyc == \App\Enums\KYCStatus::Pending->value)
                <div class="site-badge warnning"> {{ __('Your Kyc Is Pending') }}</div>
            @elseif($user->kyc == \App\Enums\KYCStatus::Level1->value)
                <div class="site-badge success"> {{ __('Your Kyc Is Verified') }} </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="">
                    <form action="{{ route('user.kyc.submit') }}" method="post" enctype="multipart/form-data">
                    @csrf
                        <div class="progress-steps-form">
                            <label for="exampleFormControlInput1" class="form-label">{{ __('Verification Type') }}</label>
                            <div class="input-group">
                                <select name="kyc_id" id="kycTypeSelect" class="select2 form-control" required>
                                    <option selected disabled>----</option>
                                    @foreach($kycs as $kyc)
                                        <option value="{{ $kyc->id }}">{{$kyc->name}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="kycData mt-4">
                        </div>
                        <button type="submit" class="btn btn-dark mt-3">{{ __('Submit Now') }}</button>
                    </form>
                </div>
                <div>
                    <p class="text-sm dark:text-white mt-7 mb-3">
                        The document you are providing must be valid at least 30 days and contain all of the following details:
                    </p>
                    <figure class="figure d-block">
                        <svg alt="verification example" viewBox="0 0 320 178" class="img-fluid">
                            <use xlink:href="{{ asset('frontend/images/cards.svg#pid-passport') }}"></use>
                        </svg>
                    </figure>
                </div>
            </div>
            @endif
        </div>
        <div class="card-footer">
            <ul class="space-y-3">
                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                    <iconify-icon class="relative text-xl mr-2 text-success-500" icon="material-symbols:check-box"></iconify-icon>
                    Upload a colourful full-size (4 sides visible) photo of the document.
                </li>
                <li class="text-sm text-slate-900 dark:text-slate-300 flex space-x-2 items-center rtl:space-x-reverse">
                    <iconify-icon class="relative text-xl mr-2 text-danger-500" icon="entypo:squared-cross"></iconify-icon>
                    Do not upload selfies, screenshots and do not modify the images in graphic editors.
                </li>
            </ul>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $("#kycTypeSelect").on('change',function (e) {
            "use strict"
            e.preventDefault();

            $('.kycData').empty();

            var id = $(this).val();

            var url = '{{ route("user.kyc.data",":id") }}';
            url = url.replace(':id', id);
            $.get(url, function (data) {

                console.log(data);
                $('.kycData').append(data)
                imagePreview()

            });


        });
    </script>
@endsection
