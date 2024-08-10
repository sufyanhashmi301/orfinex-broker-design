@extends('backend.layouts.app')
@section('style')
    <link href="{{ asset('backend/css/codemirror.css') }}" rel='stylesheet'>
    <link href="{{ asset('backend/css/ayu-dark.css') }}" rel='stylesheet'>
@endsection
@section('title')
    {{ __('Add Custom CSS') }}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('Add Custom CSS') }}</h4>
        </div>
        <div class="card-body p-6">
            <div class="paragraph text-xs mb-4"><iconify-icon icon="lucide:alert-triangle"></iconify-icon>You can add <strong>Custom CSS</strong> to write the css below and it will effect on the <strong>User Front End Pages</strong></div>
            <form action="{{ route('admin.custom-css.update') }}" method="post">
                @csrf
                <div class="input-area mb-5">
                    <textarea name="custom_css" class="form-conttrol editorContainer">{{ $customCss }}</textarea>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('backend/js/codemirror.js') }}"></script>
    <script src="{{ asset('backend/js/code-css.js') }}"></script>
    <script>
        ( () => {
            'use strict';
            var editorContainer = document.querySelector('.editorContainer')

            var editor = CodeMirror.fromTextArea(editorContainer, {
                lineNumbers: true,
                mode: 'css',
                theme: 'ayu-dark',
            });
        } )();
    </script>
@endsection
