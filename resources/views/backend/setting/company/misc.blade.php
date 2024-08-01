@extends('backend.setting.company.index')
@section('title')
    {{ __('Misc Settings') }}
@endsection
@section('company-content')
    <div class="card">
        <div class="card-body p-6">
            <form action="" method="post" class="space-y-5">
                <div class="input-area relative">
                    <label for="" class="form-label">
                        {{ __('Disclaimer') }}
                    </label>
                    <textarea name="" class="form-control" rows="3"></textarea>
                </div>
                <div class="input-area relative">
                    <label for="" class="form-label">
                        {{ __('Disclaimer (Email)') }}
                    </label>
                    <textarea name="" class="form-control" rows="3"></textarea>
                </div>
                <div class="input-area relative">
                    <label for="" class="form-label">
                        {{ __('Footer') }}
                    </label>
                    <input type="text" name="" class="form-control" placeholder="">
                </div>
                <div class="input-area relative">
                    <label for="" class="form-label">
                        {{ __('Footer (Email)') }}
                    </label>
                    <input type="text" name="" class="form-control" placeholder="">
                </div>
                <div class="input-area relative">
                    <label for="" class="form-label">
                        {{ __('Risk Warning') }}
                    </label>
                    <input type="text" name="" class="form-control" placeholder="">
                </div>
                <div class="input-area relative">
                    <label for="" class="form-label">
                        {{ __('Risk Warning (Email)') }}
                    </label>
                    <input type="text" name="" class="form-control" placeholder="">
                </div>
                <div class="input-area">
                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                        {{ __('Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
