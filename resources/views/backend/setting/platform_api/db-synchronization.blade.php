@extends('backend.setting.platform_api.index')
@section('title')
    {{ __('MySQL Database Credentials') }}
@endsection
@section('platform-content')
    <div class="card">
        <div class="card-body p-6">
            <form action="" method="" enctype="multipart/form-data">
                <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-5">
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('Database Host') }}
                        </label>
                        <input type="text" name="name" class=" form-control " placeholder="CTrader">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('Database Port') }}
                        </label>
                        <input type="text" name="name" class=" form-control " placeholder="CTrader">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('Database Name') }}
                        </label>
                        <input type="text" name="name" class=" form-control " placeholder="CTrader">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('Database Username') }}
                        </label>
                        <input type="text" name="name" class=" form-control " placeholder="CTrader">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('Database Password') }}
                        </label>
                        <input type="password" name="name" class=" form-control " placeholder="********">
                    </div>
                </div>
                <div class="flex justify-between mt-10">
                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                        {{ __('Save') }}
                    </button>
                    <button type="button" class="btn btn-outline-dark inline-flex items-center justify-center">
                        {{ __('Test Connection') }}
                    </button>
                </div>
            </form>
        </div>
    </div>


@endsection
