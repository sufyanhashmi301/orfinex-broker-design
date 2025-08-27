@extends('frontend::layouts.user')
@section('title')
    {{ __('Partner Dashboard') }}
@endsection
@section('content')
        @if(auth()->user()->ib_status == \App\Enums\IBStatus::PENDING )
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6">
                <div class="max-w-2xl mx-auto progress-steps-form">
                    <div class="transaction-status text-center">
                        <div class="relative flex items-center justify-center z-1 mb-7">
                            <svg class="fill-warning-50 dark:fill-warning-500/15" width="90" height="90" viewBox="0 0 90 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M34.364 6.85053C38.6205 -2.28351 51.3795 -2.28351 55.636 6.85053C58.0129 11.951 63.5594 14.6722 68.9556 13.3853C78.6192 11.0807 86.5743 21.2433 82.2185 30.3287C79.7862 35.402 81.1561 41.5165 85.5082 45.0122C93.3019 51.2725 90.4628 63.9451 80.7747 66.1403C75.3648 67.3661 71.5265 72.2695 71.5572 77.9156C71.6123 88.0265 60.1169 93.6664 52.3918 87.3184C48.0781 83.7737 41.9219 83.7737 37.6082 87.3184C29.8831 93.6664 18.3877 88.0266 18.4428 77.9156C18.4735 72.2695 14.6352 67.3661 9.22531 66.1403C-0.462787 63.9451 -3.30193 51.2725 4.49185 45.0122C8.84391 41.5165 10.2138 35.402 7.78151 30.3287C3.42572 21.2433 11.3808 11.0807 21.0444 13.3853C26.4406 14.6722 31.9871 11.951 34.364 6.85053Z" fill="" fill-opacity=""></path>
                            </svg>

                            <span class="absolute -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2">
                                <i data-lucide="hourglass" class="text-warning-600 dark:text-orange-400 w-8 h-8"></i>
                            </span>
                        </div>
                        <h2 class="text-2xl dark:text-white my-5">{{ __('Partner Request Pending') }}</h2>
                        <p class="mb-3 text-gray-500 dark:text-gray-400">
                            {{ __("Your partnership request is under review and we'll confirm with you shortly. Stay tuned!") }}
                        </p>
                        <div class="flex flex-wrap items-center justify-center gap-3 my-6">
                            <a href="{{setting('IB_partner_agreement_link','document_links',false)}}" target="_blank" class="flex items-center justify-center rounded-lg border border-gray-300 bg-white p-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                                {{ __('Read Partner Agreement') }}
                            </a>
                            @php
                                $trustpilot = plugin_active('Trustpilot');
                            @endphp
                            @if($trustpilot && $trustpilot->status)
                                @php
                                    $trustpilotData = json_decode($trustpilot->data, true);
                                @endphp
                                <a href="{{ $trustpilotData['link'] }}" target="_blank" class="flex items-center justify-center p-3 font-medium text-white rounded-lg bg-brand-500 text-theme-sm shadow-theme-xs hover:bg-brand-600">
                                    {{ __('Read Our Reviews on Trustpilot') }}
                                </a>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('If you face any issue, please visit our') }}
                                <a href="{{setting('customer_support_link','platform_links','javascript:void(0);')}}" class="text-brand-500 hover:text-brand-600 dark:text-brand-400 text-sm">
                                    {{ __('Customer Support') }}
                                </a>
                                {{ __('or Email us at') }}
                                <a href="mailto:{{ setting('support_email','global')}}" class="text-brand-500 hover:text-brand-600 dark:text-brand-400 text-sm">
                                    {{ setting('support_email','global')}}
                                </a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(auth()->user()->ib_status == \App\Enums\IBStatus::REJECTED )
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6">
                <div class="max-w-2xl mx-auto progress-steps-form">
                    <div class="transaction-status text-center">
                        <div class="relative flex items-center justify-center z-1 mb-7">
                            <svg class="fill-error-50 dark:fill-error-500/15" width="90" height="90" viewBox="0 0 90 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M34.364 6.85053C38.6205 -2.28351 51.3795 -2.28351 55.636 6.85053C58.0129 11.951 63.5594 14.6722 68.9556 13.3853C78.6192 11.0807 86.5743 21.2433 82.2185 30.3287C79.7862 35.402 81.1561 41.5165 85.5082 45.0122C93.3019 51.2725 90.4628 63.9451 80.7747 66.1403C75.3648 67.3661 71.5265 72.2695 71.5572 77.9156C71.6123 88.0265 60.1169 93.6664 52.3918 87.3184C48.0781 83.7737 41.9219 83.7737 37.6082 87.3184C29.8831 93.6664 18.3877 88.0266 18.4428 77.9156C18.4735 72.2695 14.6352 67.3661 9.22531 66.1403C-0.462787 63.9451 -3.30193 51.2725 4.49185 45.0122C8.84391 41.5165 10.2138 35.402 7.78151 30.3287C3.42572 21.2433 11.3808 11.0807 21.0444 13.3853C26.4406 14.6722 31.9871 11.951 34.364 6.85053Z" fill="" fill-opacity=""></path>
                            </svg>

                            <span class="absolute -translate-x-1/2 -translate-y-1/2 left-1/2 top-1/2">
                                <i data-lucide="x" class="text-error-600 dark:text-red-400 w-8 h-8"></i>
                            </span>
                        </div>
                        <h2 class="text-2xl dark:text-white my-5">{{ __('Partner Request Rejected') }}</h2>
                        <p class="mb-3 text-gray-500 dark:text-gray-400">
                            {{ __("Unfortunately, your partnership request has been rejected. If you have any questions or need clarification, feel free to contact us.") }}
                        </p>
                        <div class="flex flex-wrap items-center justify-center gap-3">
                            <a href="{{setting('IB_partner_agreement_link','document_links',false)}}" target="_blank" class="flex items-center justify-center rounded-lg border border-gray-300 bg-white p-3 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                                {{ __('Read Partner Agreement') }}
                            </a>
                            @php
                                $trustpilot = plugin_active('Trustpilot');
                            @endphp
                            @if($trustpilot && $trustpilot->status)
                                @php
                                    $trustpilotData = json_decode($trustpilot->data, true);
                                @endphp
                                <a href="{{ $trustpilotData['link'] }}" target="_blank" class="flex items-center justify-center p-3 font-medium text-white rounded-lg bg-brand-500 text-theme-sm shadow-theme-xs hover:bg-brand-600">
                                    {{ __('Read Our Reviews on Trustpilot') }}
                                </a>
                            @endif
                        </div>
                        <div class="mt-5">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('If you face any issue, please visit our') }}
                                <a href="{{setting('customer_support_link','platform_links','javascript:void(0);')}}" class="text-brand-500 hover:text-brand-600 dark:text-brand-400 text-sm">
                                    {{ __('Customer Support') }}
                                </a>
                                {{ __('or Email us at') }}
                                <a href="mailto:{{ setting('support_email','global')}}" class="text-brand-500 hover:text-brand-600 dark:text-brand-400 text-sm">
                                    {{ setting('support_email','global')}}
                                </a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @elseif((auth()->user()->ib_status == \App\Enums\IBStatus::UNPROCESSED) && !isset(auth()->user()->ref_id))
        <div class="max-w-4xl mx-auto">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6">
                <div class="flex flex-col gap-5 mb-8 sm:items-center">
                    <div class="max-w-2xl text-center">
                        <div class="inline-flex h-16 w-16 items-center justify-center rounded-xl bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-white/90">
                            <i data-lucide="handshake" class="w-7 h-7"></i>
                        </div>
                        <h4 class="text-2xl font-semibold text-gray-800 dark:text-white/90">
                            {{ __('Become a Introducing Broker') }}
                        </h4>
                        <p class="mt-1 text-gray-500 text-theme-sm dark:text-gray-400">
                            {{ __('Make sure your details are correct-after applying, we will reach you to discuss your experience. We will also answer all the questions you might have.') }}
                        </p>
                    </div>
                </div>
                <div>
                    <form 
                        x-data="ibFormHandler"
                        @submit.prevent="submitForm"
                        action="{{ route('user.ib-program.store') }}" 
                        method="POST" 
                        id="ib-from-create" 
                        class="space-y-5">
                        @csrf
                        @foreach($ibQuestions as $qIndex=>$ibQuestion)
                            @foreach(json_decode($ibQuestion->fields) as $field)
                                <div class="input-area">
                                    <div class="grid grid-cols-12">
                                        <div class="col-span-12">
                                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                                {{ $field->name }}
                                            </label>
                                        </div>
                                        @if($field->type === 'text')
                                            <div class="col-span-12">
                                                <input 
                                                    name="fields[{{ $field->name }}]" 
                                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" 
                                                    type="text" 
                                                    value="" 
                                                    @if($field->validation === 'required') required @endif>
                                            </div>
                                        @elseif($field->type === 'checkbox')
                                            <div class="col-span-12">
                                                <div x-data="{ selected: [] }" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                                    @foreach($field->options as $index => $option)
                                                        <label 
                                                            for="flexCheckDefault{{$qIndex}}{{$index}}" 
                                                            class="flex px-3 py-3.5 w-full bg-white border border-gray-200 rounded-lg text-sm focus:border-brand-500 focus:ring-brand-500 dark:bg-gray-900 dark:border-gray-800 dark:text-gray-400 cursor-pointer">

                                                            <div class="relative">
                                                                <input 
                                                                    type="checkbox" 
                                                                    id="flexCheckDefault{{$qIndex}}{{$index}}" 
                                                                    class="sr-only"
                                                                    name="fields[{{ $field->name }}][]"
                                                                    value="{{ $option }}"
                                                                    x-model="selected"
                                                                    @if($field->validation === 'required') required @endif
                                                                >

                                                                <div 
                                                                    :class="selected.includes('{{ $option }}') 
                                                                        ? 'border-brand-500 bg-brand-500' 
                                                                        : 'bg-transparent border-gray-300 dark:border-gray-700'" 
                                                                    class="mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px]">
                                                                    <span :class="selected.includes('{{ $option }}') ? '' : 'opacity-0'">
                                                                        <svg
                                                                        width="14"
                                                                        height="14"
                                                                        viewBox="0 0 14 14"
                                                                        fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        >
                                                                        <path
                                                                            d="M11.6666 3.5L5.24992 9.91667L2.33325 7"
                                                                            stroke="white"
                                                                            stroke-width="1.94437"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                        />
                                                                        </svg>
                                                                    </span>
                                                                    
                                                                </div>
                                                            </div>

                                                            {{ $option }}
                                                        </label>
                                                    @endforeach
                                                </div>

                                            </div>
                                        @elseif($field->type === 'radio')
                                            <div class="col-span-12">
                                                <div x-data="{ isChecked: '' }">
                                                @foreach($field->options as $option)
                                                    <div class="basicRadio mb-2">
                                                        <label 
                                                            :class="isChecked === '{{ $option }}' ? 'text-gray-700 dark:text-gray-400' : 'text-gray-500 dark:text-gray-400'" 
                                                            class="relative flex cursor-pointer items-center gap-3 text-sm font-medium select-none text-gray-500 dark:text-gray-400">
                                                            <input 
                                                                class="sr-only" 
                                                                type="radio" 
                                                                name="fields[{{ $field->name }}]"
                                                                value="{{ $option }}"
                                                                @if($field->validation === 'required') required @endif>
                                                            <span :class="isChecked === '{{ $option }}' ? 'border-brand-500 bg-brand-500' : 'bg-transparent border-gray-300 dark:border-gray-700'" class="flex h-5 w-5 items-center justify-center rounded-full border-[1.25px] bg-transparent border-gray-300 dark:border-gray-700">
                                                                <span :class="isChecked === '{{ $option }}' ? 'block' : 'hidden'" class="h-2 w-2 rounded-full bg-white hidden"></span>
                                                            </span>
                                                                {{ $option }}
                                                            </label>
                                                        </div>
                                                        
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif($field->type === 'dropdown')
                                            <div class="col-span-12">
                                                <select 
                                                    name="fields[{{ $field->name }}]" 
                                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 z-20 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" 
                                                    @if($field->validation === 'required') required @endif>
                                                    @foreach($field->options as $option)
                                                        <option value="{{ $option }}" class="inline-block font-Inter font-normal text-sm text-slate-600">
                                                            {{ $option }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                        <div>
                            <label
                                for="agreement-check"
                                class="flex cursor-pointer items-center text-sm font-medium text-gray-700 select-none dark:text-gray-400"
                            >
                                <div class="relative">
                                    <input
                                        x-model="agreementChecked"
                                        type="checkbox"
                                        id="agreement-check"
                                        class="sr-only"
                                    />
                                    <div
                                        :class="agreementChecked ? 'border-brand-500 bg-brand-500' : 'bg-transparent border-gray-300 dark:border-gray-700'"
                                        class="f hover:border-brand-500 dark:hover:border-brand-500 mr-3 flex h-5 w-5 items-center justify-center rounded-md border-[1.25px]"
                                    >
                                        <span :class="agreementChecked ? '' : 'opacity-0'">
                                            <svg
                                            width="14"
                                            height="14"
                                            viewBox="0 0 14 14"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                            >
                                            <path
                                                d="M11.6666 3.5L5.24992 9.91667L2.33325 7"
                                                stroke="white"
                                                stroke-width="1.94437"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                                {{ __('I have read and agree with the ') }}
                                <a href="javascript:;" class="text-brand-500 hover:text-brand-600 dark:text-brand-400 text-sm ms-1">
                                    {{ __('IB Agreement') }}
                                </a> 
                            </label>
                        </div>
                        <div class="col-span-12 text-right">
                            <button type="submit" 
                                class="bg-brand-500 hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white save-btn"
                                :disabled="loading">

                                <!-- Loader icon when submitting -->
                                <span x-show="loading" class="animate-spin">
                                    <i data-lucide="loader-circle" class="w-4 h-4"></i>
                                </span>

                                <!-- Check icon when not submitting -->
                                <span x-show="!loading">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                </span>

                                {{ __('Submit Request') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        @if(request()->routeIs('user.referral.members'))
            @include('frontend::referral.include.__members')
        @endif
        @if(request()->routeIs('user.referral.advertisement.material'))
        <div id="advertisement-container">
            @include('frontend::referral.include.__advertisement_material')
        </div>
        @endif
        @if(request()->routeIs('user.referral.network'))
            @include('frontend::referral.include.__network')
        @endif
        @if(request()->routeIs('user.referral.reports'))
            @include('frontend::referral.include.__reports')
        @endif
        {{-- IB account modal --}}
        @include('frontend::referral.modal.__ib_form')

@endsection
@section('script')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('ibFormHandler', () => ({
                loading: false,
                agreementChecked: false,
                selectedLanguage: '',

                async submitForm() {
                    if (!this.agreementChecked) {
                        alert('Kindly check the agreement before proceeding!');
                        return;
                    }
                    this.loading = true;
                    try {
                        const form = this.$el;
                        const formData = new FormData(form);

                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value
                            }
                        });

                        if (!response.ok) throw new Error('Form submission failed');

                        alert('Form submitted successfully!');
                    } catch (error) {
                        console.error(error);
                        alert('Something went wrong.');
                    } finally {
                        this.loading = false;
                    }
                },

                async changeLanguage() {
                    if (!this.selectedLanguage) return;

                    try {
                        const response = await fetch(
                            `{{ route("user.referral.advertisement.material") }}?language=${encodeURIComponent(this.selectedLanguage)}`,
                            { headers: { 'X-Requested-With': 'XMLHttpRequest' } }
                        );

                        const html = await response.text();
                        document.getElementById('advertisement-container').innerHTML = html;

                        // Keep correct active tab state
                        const activeTabLink = document.querySelector('.nav-pills .nav-link.active');
                        if (activeTabLink) {
                            const activeTabContent = activeTabLink.getAttribute('href');
                            document.getElementById('tabs-socialMediaMaterial')?.classList.remove('show', 'active');
                            document.getElementById('tabs-websiteBanners')?.classList.remove('show', 'active');
                            document.querySelector(activeTabContent)?.classList.add('show', 'active');
                        }
                    } catch (error) {
                        console.error('Error fetching advertisements.', error);
                    }
                },

                copyRef() {
                    const copyApi = document.getElementById("refLink");
                    copyApi.select();
                    copyApi.setSelectionRange(0, 999999999);
                    document.execCommand('copy');
                    document.getElementById('copy').textContent = document.getElementById('copied').value;
                }
            }));
        });
    </script>

@endsection
