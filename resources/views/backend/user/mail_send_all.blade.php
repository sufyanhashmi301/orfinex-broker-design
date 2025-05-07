@extends('backend.layouts.app')
@section('title')
    {{ __('Send Email to All') }}
@endsection
@section('content')
    <style>
        .note-group-select-from-files {
            display: none !important
        }
        .note-modal-content {
            height: 230px;
        }
    </style>
    <div class="max-w-5xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('Send Email to All') }}</h4>
            </div>
            <div class="card-body p-6">
                <form action="{{ route('admin.user.mail-send') }}" method="post" class="space-y-5">
                    @csrf
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Subject') }}</label>
                        <input type="text" name="subject" class="form-control mb-0" required=""/>
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Email Details') }}</label>
                        <textarea name="message" class="summernote form-control mb-0" rows="7"></textarea>
                        <input type="hidden" class="html-message-body" name="html_message_body">
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
