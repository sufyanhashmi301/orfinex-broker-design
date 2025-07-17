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
                            <label for="largeInput" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Maximum number of failed login attempts allowed before triggering a lockout">
                                    {{ __('Max Retries') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" name="max_retries" class="form-control" placeholder="e.g. 5">
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="inputEmail4" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Duration (in minutes) the user remains locked out after reaching max retries">
                                    {{ __('Lockout Time (in minutes)') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" name="lockout_time" class="form-control" placeholder="e.g. 15">
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="inputEmail4" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Total number of lockouts allowed before permanent or extended lock is enforced">
                                    {{ __('Max Lockouts') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" name="max_lockouts" class="form-control" placeholder="e.g. 3">
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="inputEmail4" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Additional time to lock a user after reaching max lockouts">
                                    {{ __('Extend Lockout (in hours)') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" name="extend_lockouts" class="form-control" placeholder="e.g. 24">
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="inputEmail4" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Time window (in hours) after which failed attempt counter resets">
                                    {{ __('Reset Retries (in hours)') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" name="reset_retries" class="form-control" placeholder="e.g. 1">
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-5 mb-5">
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="inputEmail4" class="form-label !flex items-center">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter one or more email addresses to receive security alerts">
                                    {{ __('Email Notifications') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" name="email_notification" class="form-control" placeholder="e.g. admin@yourdomain.com">
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="inputEmail4" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Address where critical security notifications will be sent">
                                    {{ __('Email') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="email" name="email" class="form-control" placeholder="e.g. alerts@yourdomain.com">
                        </div>
                    </div>
                    <div class="lg:col-span-6 col-span-12">
                        <div class="input-area">
                            <label for="" class="form-label d-block mb-4">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enable to receive alerts when a user logs in from an unknown IP address">
                                    {{ __('Send Email Notifications if login from different IP Address ?') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                    <span class="text-danger">*</span>
                                </span>
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
                            <label for="inputEmail4" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Add IP addresses that are trusted or to be excluded from notifications">
                                    {{ __('IP') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" name="ip" class="form-control" placeholder="e.g. 192.168.1.100">
                        </div>
                    </div>
                </div>
                <div class="text-right mt-10">
                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
