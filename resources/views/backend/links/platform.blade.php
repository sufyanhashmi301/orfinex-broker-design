@extends('backend.links.index')
@section('links-title')
    {{ __('Platform Links') }}
@endsection
@section('title')
    {{ __('Platform Links') }}
@endsection
@section('links-content')
    <div class="col-xl-12 col-lg-12 col-md-12 col-12">
        <div class="site-card">
            <div class="site-card-body">
                <form action="" method="post" class="row align-items-center">
                    @csrf
                    <div class="site-input-groups row align-items-center">
                        <label class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-label pt-0">
                            Desktop Terminal - Windows
                        </label>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <input type="url" name="cash_out_redeem" class="box-input" placeholder="">
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                            <div class="form-switch ps-0">
                                <input class="form-check-input" type="hidden" value="0" name="desktop_terminal_window">
                                <div class="switch-field same-type m-0">
                                    <input type="radio" id="active-1" name="desktop_terminal_window" value="1">
                                    <label for="active-1">Enable</label>
                                    <input type="radio" id="disable-1" name="desktop_terminal_window" value="0" checked="">
                                    <label for="disable-1">Disabled</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="site-input-groups row align-items-center">
                        <label class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-label pt-0">
                            Desktop Terminal - Mac
                        </label>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <input type="url" name="cash_out_redeem" class="box-input" placeholder="">
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                            <div class="form-switch ps-0">
                                <input class="form-check-input" type="hidden" value="0" name="desktop_terminal_mac">
                                <div class="switch-field same-type m-0">
                                    <input type="radio" id="active-2" name="desktop_terminal_mac" value="1">
                                    <label for="active-2">Enable</label>
                                    <input type="radio" id="disable-2" name="desktop_terminal_mac" value="0" checked="">
                                    <label for="disable-2">Disabled</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="site-input-groups row align-items-center">
                        <label class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-label pt-0">
                            Mobile Application - Android
                        </label>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <input type="url" name="cash_out_redeem" class="box-input" placeholder="">
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                            <div class="form-switch ps-0">
                                <input class="form-check-input" type="hidden" value="0" name="mobile_android">
                                <div class="switch-field same-type m-0">
                                    <input type="radio" id="active-3" name="mobile_android" value="1">
                                    <label for="active-3">Enable</label>
                                    <input type="radio" id="disable-3" name="mobile_android" value="0" checked="">
                                    <label for="disable-3">Disabled</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="site-input-groups row align-items-center">
                        <label class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-label pt-0">
                            Mobile Application - iOS
                        </label>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <input type="url" name="cash_out_redeem" class="box-input" placeholder="">
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                            <div class="form-switch ps-0">
                                <input class="form-check-input" type="hidden" value="0" name="mobile_ios">
                                <div class="switch-field same-type m-0">
                                    <input type="radio" id="active-4" name="mobile_ios" value="1">
                                    <label for="active-4">Enable</label>
                                    <input type="radio" id="disable-4" name="mobile_ios" value="0" checked="">
                                    <label for="disable-4">Disabled</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="site-input-groups row align-items-center">
                        <label class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-label pt-0">
                            Mobile Application - Android (APK)
                        </label>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <input type="url" name="cash_out_redeem" class="box-input" placeholder="">
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                            <div class="form-switch ps-0">
                                <input class="form-check-input" type="hidden" value="0" name="android_apk">
                                <div class="switch-field same-type m-0">
                                    <input type="radio" id="active-5" name="android_apk" value="1">
                                    <label for="active-5">Enable</label>
                                    <input type="radio" id="disable-5" name="android_apk" value="0" checked="">
                                    <label for="disable-5">Disabled</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="site-input-groups row align-items-center">
                        <label class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-label pt-0">
                            Web Terminal (No Download)
                        </label>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <input type="url" name="cash_out_redeem" class="box-input" placeholder="">
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                            <div class="form-switch ps-0">
                                <input class="form-check-input" type="hidden" value="0" name="web_terminal">
                                <div class="switch-field same-type m-0">
                                    <input type="radio" id="active-6" name="web_terminal" value="1">
                                    <label for="active-6">Enable</label>
                                    <input type="radio" id="disable-6" name="web_terminal" value="0" checked="">
                                    <label for="disable-6">Disabled</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection