<div class="tab-pane fade space-y-5" id="pills-security" role="tabpanel" aria-labelledby="pills-security-tab">
    @can('customer-change-password')
        <div class="card basicTable_wrapper">
            <div class="card-header">
                <h3 class="card-title">{{ __('Change Password') }}</h3>
            </div>
            <div class="card-body p-5">
                <form action="{{route('admin.user.password-update',$user->id)}}" method="post">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the new password">
                                    {{ __('New Password') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="password" name="new_password" class="form-control" required="">
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Enter the confirm password">
                                    {{ __('Confirm Password') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input type="password" name="new_confirm_password" class="form-control" required="">
                        </div>
                    </div>
                    <div class="input-area text-right mt-10">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            {{ __('Change Password') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    @endcan
</div>
