@php
    // Fetch custom success page data
    $successPage = getSuccessPage('deposit');
    
    // Set variables with fallbacks to default values
    $title = $successPage->title ?? __('Your transaction was processed successfully!');
    $subtitle = $successPage->subtitle ?? __('Payment Successful!');
    $message = $successPage->message ?? __('Thank you for your trust in '. setting('site_title','global') .'. Feel free to explore more of our services or check your account for the updated balance.');
    $quote = $successPage->quote ?? __('Success is not final; failure is not fatal: It is the courage to continue that counts.');
    $quoteAuthor = $successPage->quote_author ?? __('- Winston Churchill');
    $buttonText = $successPage->button_text ?? __('Go to Dashboard');
    $buttonLink = $successPage->button_link ?? route('user.dashboard');
    $buttonType = $successPage->button_type ?? 'primary';
    $imagePath = $successPage->image_path ?? 'common/images/success-page__img.svg';
    $showTrustpilot = $successPage->trustpilot_button_show ?? false;
@endphp

@extends('frontend::deposit.index')
@section('deposit_content')
    <div class="card progress-steps-form">
        <div class="transaction-status flex flex-col items-center px-7 py-12">
            <div class="max-w-xl w-full mx-auto mb-6">
                <img src="{{ asset($imagePath) }}" alt="{{ __('Success Image') }}" class="w-full">
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
            @if($quote)
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
