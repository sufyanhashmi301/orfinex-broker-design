@extends('backend.setting.customization.index')
@section('title')
    {{ __('Dynamic Content') }}
@endsection
@section('customization-content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div class="card">
        <div class="card-body p-6">
            <form action="" method="post">
                <div class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 gap-7 gap-x-3">
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="My Trading Accounts" readonly>
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="" placeholder="">
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="profile" readonly>
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="" placeholder="">
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="tag" readonly>
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="" placeholder="">
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="reading-list" readonly>
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="" placeholder="">
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="setting" readonly>
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="" placeholder="">
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="social accounts" readonly>
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="" placeholder="">
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="preferences" readonly>
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="" placeholder="">
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="change password" readonly>
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="" placeholder="">
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="forget password" readonly>
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="" placeholder="">
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="reset password" readonly>
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="" placeholder="">
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="delete account" readonly>
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="" placeholder="">
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="register" readonly>
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="" placeholder="">
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="login" readonly>
                    </div>
                    <div class="input-area">
                        <input type="text" name="" class="form-control" value="" placeholder="">
                    </div>
                </div>
                <div class="text-right mt-10">
                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                        {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
