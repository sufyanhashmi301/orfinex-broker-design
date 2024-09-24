@extends('backend.security.index')
@section('title')
    {{ __('All Sections') }}
@endsection
@section('security-content')
    <div class="card">
        <div class="card-body p-6">
            <form action="" method="post">
                <div class="grid grid-cols-12 gap-5 mb-5">
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="largeInput" class="form-label !flex items-center">
                                <span>Max Retries</span>
                                <span class="toolTip onTop leading-[0]" data-tippy-content="minimum Payout will be 50$" data-tippy-theme="dark">
                                    <i icon-name="info"></i>
                                </span>
                            </label>
                            <input type="text" name="max_retries" class="form-control">
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="inputEmail4" class="form-label">Lockout Time (in minutes)</label>
                            <input type="text" name="lockout_time" class="form-control">
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="inputEmail4" class="form-label">Max Lockouts</label>
                            <input type="text" name="max_lockouts" class="form-control">
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="inputEmail4" class="form-label !flex items-center">
                                <span>Extend Lockout (in hours)</span>
                                <span class="toolTip onTop leading-[0]" data-tippy-content="minimum Payout will be 50$" data-tippy-theme="dark">
                                    <i icon-name="info"></i>
                                </span>
                            </label>
                            <input type="text" name="extend_lockouts" class="form-control">
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="inputEmail4" class="form-label">Reset Retries (in hours)</label>
                            <input type="text" name="reset_retries" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-5 mb-5">
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="inputEmail4" class="form-label !flex items-center">
                                <span>Email Notifications</span>
                                <span class="toolTip onTop leading-[0]" data-tippy-content="minimum Payout will be 50$" data-tippy-theme="dark">
                                    <i icon-name="info"></i>
                                </span>
                            </label>
                            <input type="text" name="email_notification" class="form-control">
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="inputEmail4" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="" class="form-label d-block mb-4">
                                Send Email Notifications if login from different IP Address ?
                                <span class="text-danger-500 ml-1">*</span>
                            </label>
                            <div class="flex items-center space-x-7 flex-wrap">
                                <div class="basicRadio">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" class="hidden" name="different_ip_notification" value="yes" checked="checked">
                                        <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                        <span class="text-secondary-500 text-sm leading-6 capitalize">
                                            Yes
                                        </span>
                                    </label>
                                </div>
                                <div class="basicRadio">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" class="hidden" name="different_ip_notification" value="no">
                                        <span class="flex-none bg-white dark:bg-slate-500 rounded-full border inline-flex ltr:mr-2 rtl:ml-2 relative transition-all duration-150 h-[16px] w-[16px] border-slate-400 dark:border-slate-600 dark:ring-slate-700"></span>
                                        <span class="text-secondary-500 text-sm leading-6 capitalize">
                                            No
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="inputEmail4" class="form-label">IP</label>
                            <input type="text" name="ip" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection