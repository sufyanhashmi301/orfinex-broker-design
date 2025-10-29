@extends('backend.layouts.app')
@section('title')
    {{ __('Edit Success Page') }}
@endsection
@section('content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            {{ __('Edit') }} {{ $successPage->name }}
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ route('admin.settings.dynamic-content.success-page') }}" class="btn btn-sm btn-primary inline-flex items-center justify-center">
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
                            {{ __('Success Page Settings') }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Customize the success page content and appearance') }}</p>
                    </div>
                </div>
                <div class="card-body p-6">
                    <form action="{{ route('admin.settings.dynamic-content.success-page.update', $successPage->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="mb-6">
                            <div class="input-area grid grid-cols-12 gap-5 mb-6">
                                <label for="" class="md:col-span-3 col-span-12 form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Name to identify this success page">
                                        {{ __('Name') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="md:col-span-9 col-span-12">
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $successPage->name) }}" required/>
                                </div>
                            </div>

                            <div class="input-area grid grid-cols-12 gap-5 mb-6">
                                <label for="" class="md:col-span-3 col-span-12 form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Type of success page">
                                        {{ __('Type') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="md:col-span-9 col-span-12">
                                    <input type="text" class="form-control" value="{{ ucfirst($successPage->type) }}" disabled/>
                                    <input type="hidden" name="type" value="{{ $successPage->type }}"/>
                                </div>
                            </div>
                        </div>

                        <!-- Content Section -->
                        <div class="mb-6">
                            <div class="input-area grid grid-cols-12 gap-5 mb-6">
                                <label for="" class="md:col-span-3 col-span-12 form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Main heading of the success page">
                                        {{ __('Title') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="md:col-span-9 col-span-12">
                                    <input type="text" name="title" class="form-control" value="{{ old('title', $successPage->title) }}" placeholder="{{ __('e.g., Payment Successful!') }}"/>
                                </div>
                            </div>

                            <div class="input-area grid grid-cols-12 gap-5 mb-6">
                                <label for="" class="md:col-span-3 col-span-12 form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Subtitle or tagline">
                                        {{ __('Subtitle') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="md:col-span-9 col-span-12">
                                    <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $successPage->subtitle) }}" placeholder="{{ __('e.g., Your transaction was processed successfully') }}"/>
                                </div>
                            </div>

                            <div class="input-area grid grid-cols-12 gap-5 mb-6">
                                <label for="" class="md:col-span-3 col-span-12 form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Main message body">
                                        {{ __('Message') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="md:col-span-9 col-span-12">
                                    <textarea name="message" class="form-control" rows="4" placeholder="{{ __('Write your success message here...') }}">{{ old('message', $successPage->message) }}</textarea>
                                </div>
                            </div>

                            <div class="input-area grid grid-cols-12 gap-5 mb-6">
                                <label for="" class="md:col-span-3 col-span-12 form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Inspirational quote">
                                        {{ __('Quote') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="md:col-span-9 col-span-12">
                                    <textarea name="quote" class="form-control" rows="2" placeholder="{{ __('Enter an inspirational quote...') }}">{{ old('quote', $successPage->quote) }}</textarea>
                                </div>
                            </div>

                            <div class="input-area grid grid-cols-12 gap-5 mb-6">
                                <label for="" class="md:col-span-3 col-span-12 form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Author of the quote">
                                        {{ __('Quote Author') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="md:col-span-9 col-span-12">
                                    <input type="text" name="quote_author" class="form-control" value="{{ old('quote_author', $successPage->quote_author) }}" placeholder="{{ __('e.g., - Winston Churchill') }}"/>
                                </div>
                            </div>
                        </div>

                        <!-- Visual Elements -->
                        <div class="mb-6">
                            <div class="input-area grid grid-cols-12 gap-5 mb-6">
                                <label for="" class="md:col-span-3 col-span-12 form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Upload a custom success image">
                                        {{ __('Success Image') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="md:col-span-9 col-span-12">
                                    @if($successPage->image_path)
                                        <div class="mb-3">
                                            <img src="{{ asset($successPage->image_path) }}" alt="Current Image" class="w-32 h-32 object-contain border border-gray-200 dark:border-gray-700 rounded p-2">
                                        </div>
                                    @endif
                                    <input type="file" name="image" class="form-control" accept="image/*"/>
                                    <small class="text-slate-500">{{ __('Recommended: SVG or PNG (transparent background), max 2MB') }}</small>
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
                                    <input type="text" name="button_text" class="form-control" value="{{ old('button_text', $successPage->button_text) }}" placeholder="{{ __('e.g., Go to Dashboard') }}"/>
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
                                    <input type="text" name="button_link" class="form-control" value="{{ old('button_link', $successPage->button_link) }}" placeholder="e.g., @{{route.dashboard}} or /user/dashboard"/>
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
                                        <option value="primary" {{ old('button_type', $successPage->button_type) == 'primary' ? 'selected' : '' }}>{{ __('Primary') }}</option>
                                        <option value="secondary" {{ old('button_type', $successPage->button_type) == 'secondary' ? 'selected' : '' }}>{{ __('Secondary') }}</option>
                                        <option value="outline-dark" {{ old('button_type', $successPage->button_type) == 'outline-primary' ? 'selected' : '' }}>{{ __('Outline') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Trustpilot Settings -->
                        <div class="mb-6">
                            <div class="input-area grid grid-cols-12 gap-5 mb-6">
                                <label for="" class="md:col-span-3 col-span-12 form-label">
                                    <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Show Trustpilot review button">
                                        {{ __('Show Trustpilot Button') }}
                                        <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    </span>
                                </label>
                                <div class="md:col-span-9 col-span-12">
                                    <div class="form-switch ps-0">
                                        <input type="hidden" value="0" name="trustpilot_button_show">
                                        <label
                                            class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                            <input type="checkbox" name="trustpilot_button_show" value="1" class="sr-only peer"
                                                @checked($successPage->trustpilot_button_show)>
                                            <span
                                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                        </label>
                                    </div>
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

