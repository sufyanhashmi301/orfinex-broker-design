@extends('backend.layouts.app')
@section('title')
    {{ __('Send Email to All') }}
@endsection
@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="flex justify-between flex-wrap items-center mb-6">
            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                {{ __('Send Email to All Subscriber') }}
            </h4>
        </div>
        <div class="card">
            <div class="card-body p-6">
                <form action="{{ route('admin.mail.send.subscriber.now') }}" method="post" class="space-y-5">
                    @csrf
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Subject:') }}</label>
                        <input type="text" name="subject" class="form-control mb-0" />
                        @error('subject')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Email Details') }}</label>
                        <textarea class="form-control summernote" rows="6"></textarea>
                        <input type="hidden" name="message">
                        @error('message')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="action-btns text-right">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:send"></iconify-icon>
                            {{ __('Send Email') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
