@php
    $successPage = getSuccessPage('withdrawal');
    $title = $successPage->title ?? __('Your transaction was processed successfully!');
    $subtitle = $successPage->subtitle ?? __('Withdraw Successful!');
    $message = $successPage->message ?? __('Your withdrawal request has been processed successfully. You will receive an email with the details of your withdrawal.');
    $quote = $successPage->quote ?? __('Success is not final; failure is not fatal: It is the courage to continue that counts.');
    $quoteAuthor = $successPage->quote_author ?? __('- Winston Churchill');
    $buttonText = $successPage->button_text ?? __('Go to Withdraw');
    $buttonLink = $successPage->button_link ?? route('user.withdraw.view');
    $buttonType = $successPage->button_type ?? 'primary';
    $imagePath = $successPage->image_path ?? 'common/images/success-page__img.svg';
    $showTrustpilot = $successPage->trustpilot_button_show ?? false;
    $quoteShow = $successPage->quote_show ?? true;
@endphp

@extends('frontend::layouts.user')
@section('title')
    {{ __('Withdraw Successful') }}
@endsection
@section('content')
    <div class="card hidden md:block mb-6">
        <div class="card-body p-3">
            <div class="progress-steps md:flex justify-between items-center gap-5">
                <div class="single-step current">
                    <div class="progress_bar mb-5"></div>
                    <div class="">
                        <div class="text-sm text-slate-600 dark:text-slate-100 mb-2">{{ __('Step - 1') }}</div>
                        <h4 class="leading-none text-xl text-dark dark:text-white">{{ __('Withdraw Amount') }}</h4>
                    </div>
                </div>
                <div class="single-step current">
                    <div class="progress_bar mb-5"></div>
                    <div class="">
                        <div class="text-sm text-slate-600 dark:text-slate-100 mb-2">{{ __('Step - 2') }}</div>
                        <h4 class="leading-none text-xl text-dark dark:text-white">{{ __('Success') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card progress-steps-form">
        <div class="transaction-status flex flex-col items-center px-7 py-12">
            <div class="text-center max-w-xl w-full mx-auto mb-6">
                <img src="{{ asset($imagePath) }}" alt="{{ __('Success Image') }}" class="inline-block max-h-80">
            </div>
            <h5 class="text-lg text-gray-500 dark:text-gray-300 mb-2">
                {{ $subtitle }}
            </h5>
            <h2 class="text-3xl dark:text-white mb-5">
                {{ $title }}
            </h2>
            <p class="max-w-xl text-center text-slate-500 text-sm mb-3 dark:text-gray-300">
                {{ $message }}
            </p>
            @if($quote && $quoteShow)
                <blockquote class="border-0 text-slate-700 dark:text-slate-100 text-base my-5">
                    {{ $quote }}
                    @if($quoteAuthor)
                        <span class="text-sm text-slate-400 text-right block mt-3">
                            {{ $quoteAuthor }}
                        </span>
                    @endif
                </blockquote>
            @endif
            <div class="flex items-center gap-3">
                <a href="{{ $buttonLink }}" class="btn btn-{{ $buttonType }} inline-flex justify-center items-center">
                    <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:arrow-right"></iconify-icon>
                    {{ $buttonText }}
                </a>
                @if($showTrustpilot)
                    @php
                        $trustpilot = plugin_active('Trustpilot');
                    @endphp
                    @if($trustpilot && $trustpilot->status)
                        @php
                            $trustpilotData = json_decode($trustpilot->data, true);
                        @endphp
                    <a href="{{ $trustpilotData['link'] }}" target="_blank" class="btn btn-dark inline-flex items-center justify-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="simple-icons:trustpilot"></iconify-icon>
                        <span>{{ __('Review us on Trustpilot') }}</span>
                    </a>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
