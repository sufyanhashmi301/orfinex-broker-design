@extends('backend.layouts.app')
@section('title')
    {{ __('Edit Error Page') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Edit') }} {{ $errorPage->name }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ route('admin.settings.dynamic-content.error-page') }}" class="btn btn-sm btn-primary inline-flex items-center justify-center">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:corner-down-left"></iconify-icon>
                {{ __('Back') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-5">
        <!-- Shortcode Sidebar -->
        <div class="lg:col-span-4 col-span-12">
            <div class="card">
                <div class="card-header noborder">
                    <div>
                        <h4 class="card-title mb-2">{{ __('Route Shortcodes') }}</h4>
                        <p class="card-text">
                            <iconify-icon icon="lucide:alert-triangle"></iconify-icon>
                            {{ __('Use these shortcodes for button links') }}
                        </p>
                    </div>
                </div>
                <div class="card-body space-y-5 p-6">
                    @php
                        $routeShortcodes = [
                            '{{route.dashboard}}' => 'Dashboard',
                            '{{route.transactions}}' => 'Transactions',
                            '{{route.deposit}}' => 'Deposit',
                            '{{route.withdraw}}' => 'Withdraw',
                            '{{route.profile}}' => 'Profile',
                            '{{route.transfer}}' => 'Transfer',
                            '{{route.wallet}}' => 'Wallet',
                        ];
                    @endphp
                    @foreach($routeShortcodes as $shortcode => $label)
                        <div class="input-areaa relative pl-32">
                            <label for="" class="form-label inline-inputLabel">
                                {{ $label }}:
                            </label>
                            <div class="relative">  
                                <input type="text" class="form-control !pr-12" id="shortcode-{{ $loop->index }}" value="{{ $shortcode }}" readonly>
                                <button
                                    class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full flex items-center justify-center copy-button"
                                    type="button" data-target="#shortcode-{{ $loop->index }}">
                                    <iconify-icon icon="lucide:copy"></iconify-icon>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-8 col-span-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                            <iconify-icon icon="lucide:edit" class="text-primary"></iconify-icon>
                            {{ __('Error Page Settings') }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Customize the error page content and appearance') }}</p>
                    </div>
                </div>
                <div class="card-body p-6">
                    <form action="{{ route('admin.settings.dynamic-content.error-page.update', $errorPage->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="mb-6">
                            <div class="input-area grid grid-cols-12 gap-5 mb-6">
                                <label for="" class="md:col-span-3 col-span-12 form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Name to identify this error page">
                                        {{ __('Name') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="md:col-span-9 col-span-12">
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $errorPage->name) }}" required/>
                                </div>
                            </div>

                            <div class="input-area grid grid-cols-12 gap-5 mb-6">
                                <label for="" class="md:col-span-3 col-span-12 form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Type of error page">
                                        {{ __('Type') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="md:col-span-9 col-span-12">
                                    <input type="text" class="form-control" value="{{ ucfirst($errorPage->type) }}" disabled/>
                                    <input type="hidden" name="type" value="{{ $errorPage->type }}"/>
                                </div>
                            </div>
                        </div>

                        <!-- Content Section -->
                        <div class="mb-6">
                            <div class="input-area grid grid-cols-12 gap-5 mb-6">
                                <label for="" class="md:col-span-3 col-span-12 form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Main heading of the error page">
                                        {{ __('Title') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="md:col-span-9 col-span-12">
                                    <input type="text" name="title" class="form-control" value="{{ old('title', $errorPage->title) }}" placeholder="{{ __('e.g., Payment Successful!') }}"/>
                                </div>
                            </div>

                            <div class="input-area grid grid-cols-12 gap-5 mb-6">
                                <label for="" class="md:col-span-3 col-span-12 form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Message at the bottom of the page">
                                        {{ __('Message') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="md:col-span-9 col-span-12">
                                    <input type="text" name="message" class="form-control" value="{{ old('message', $errorPage->message) }}" placeholder="{{ __('e.g., Your transaction was processed successfully') }}"/>
                                </div>
                            </div>

                            <div class="input-area grid grid-cols-12 gap-5 mb-6">
                                <label for="" class="md:col-span-3 col-span-12 form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Description or reason of the error">
                                        {{ __('Description') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="md:col-span-9 col-span-12">
                                    <textarea name="description" class="form-control" rows="4" placeholder="{{ __('Write your error description here...') }}">{{ old('description', $errorPage->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        
                        <!-- Button Settings -->
                        <div class="mb-6">
                            <div class="input-area grid grid-cols-12 gap-5 mb-6">
                                <label for="" class="md:col-span-3 col-span-12 form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Text displayed on the button">
                                        {{ __('Button Text') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="md:col-span-9 col-span-12">
                                    <input type="text" name="button_text" class="form-control" value="{{ old('button_text', $errorPage->button_text) }}" placeholder="{{ __('e.g., Go to Dashboard') }}"/>
                                </div>
                            </div>

                            <div class="input-area grid grid-cols-12 gap-5 mb-6">
                                <label for="" class="md:col-span-3 col-span-12 form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="URL or route shortcode for the button">
                                        {{ __('Button Link') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="md:col-span-9 col-span-12">
                                    <input type="text" name="button_link" class="form-control" value="{{ old('button_link', $errorPage->button_link) }}" placeholder="e.g., @{{route.dashboard}} or /user/dashboard"/>
                                    <small class="text-slate-500">{{ __('Use route shortcodes from the sidebar or enter a full URL') }}</small>
                                </div>
                            </div>

                            <div class="input-area grid grid-cols-12 gap-5 mb-6">
                                <label for="" class="md:col-span-3 col-span-12 form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Button style">
                                        {{ __('Button Type') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="md:col-span-9 col-span-12">
                                    <select name="button_type" class="form-control">
                                        <option value="primary" {{ old('button_type', $errorPage->button_type) == 'primary' ? 'selected' : '' }}>{{ __('Primary') }}</option>
                                        <option value="secondary" {{ old('button_type', $errorPage->button_type) == 'secondary' ? 'selected' : '' }}>{{ __('Secondary') }}</option>
                                        <option value="outline-dark" {{ old('button_type', $errorPage->button_type) == 'outline-primary' ? 'selected' : '' }}>{{ __('Outline') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end gap-3">
                            <button type="submit" class="btn btn-primary inline-flex items-center justify-center">
                                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    // Copy shortcode functionality
    document.querySelectorAll('.copy-button').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.querySelector(targetId);
            
            input.select();
            document.execCommand('copy');
            
            // Visual feedback
            const icon = this.querySelector('iconify-icon');
            const originalIcon = icon.getAttribute('icon');
            icon.setAttribute('icon', 'lucide:check');
            
            setTimeout(() => {
                icon.setAttribute('icon', originalIcon);
            }, 1000);
        });
    });
</script>
@endsection

