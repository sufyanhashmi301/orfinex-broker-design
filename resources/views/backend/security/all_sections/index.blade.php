@extends('backend.security.index')
@section('security-title')
    {{ __('All Sections') }}
@endsection
@section('title')
    {{ __('All Sections') }}
@endsection
@section('security-content')
    <div class="col-lg-12 col-12">
        <div class="site-card">
            <div class="site-card-body">
                <form action="" method="post">
                    <div class="form-row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="site-input-groups">
                                <label for="largeInput" class="box-input-label !flex items-center">
                                    <span>Max Retries</span>
                                    <span class="toolTip onTop leading-[0]" data-tippy-content="minimum Payout will be 50$" data-tippy-theme="dark">
                                        <i icon-name="info"></i>
                                    </span>
                                </label>
                                <input type="text" name="max_retries" class="box-input">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="site-input-groups">
                                <label for="inputEmail4" class="box-input-label">Lockout Time (in minutes)</label>
                                <input type="text" name="lockout_time" class="box-input">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="site-input-groups">
                                <label for="inputEmail4" class="box-input-label">Max Lockouts</label>
                                <input type="text" name="max_lockouts" class="box-input">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="site-input-groups">
                                <label for="inputEmail4" class="box-input-label !flex items-center">
                                    <span>Extend Lockout (in hours)</span>
                                    <span class="toolTip onTop leading-[0]" data-tippy-content="minimum Payout will be 50$" data-tippy-theme="dark">
                                        <i icon-name="info"></i>
                                    </span>
                                </label>
                                <input type="text" name="extend_lockouts" class="box-input">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="site-input-groups">
                                <label for="inputEmail4" class="box-input-label">Reset Retries (in hours)</label>
                                <input type="text" name="reset_retries" class="box-input">
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="site-input-groups">
                                <label for="inputEmail4" class="box-input-label !flex items-center">
                                    <span>Email Notifications</span>
                                    <span class="toolTip onTop leading-[0]" data-tippy-content="minimum Payout will be 50$" data-tippy-theme="dark">
                                        <i icon-name="info"></i>
                                    </span>
                                </label>
                                <input type="text" name="email_notification" class="box-input">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="site-input-groups">
                                <label for="inputEmail4" class="box-input-label">Email</label>
                                <input type="email" name="email" class="box-input">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="site-input-groups">
                                <label for="" class="box-input-label d-block mb-4">
                                    Send Email Notifications if login from different IP Address ?
                                    <span class="text-danger ml-1">*</span>
                                </label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="different_ip_notification" id="different_ip_notification1" value="yes" checked>
                                    <label class="form-check-label" for="different_ip_notification1">
                                    Yes
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="different_ip_notification" id="different_ip_notification2" value="no">
                                    <label class="form-check-label" for="different_ip_notification2">
                                    No
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="site-input-groups">
                                <label for="inputEmail4" class="box-input-label">IP</label>
                                <input type="text" name="ip" class="box-input">
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="site-btn-sm primary-btn">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection