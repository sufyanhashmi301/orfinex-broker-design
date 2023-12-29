<div class="grid grid-cols-12 gap-5">
    <div class="lg:col-span-8 col-span-12">
        <div class="space-y-5">
            <div class="alert alert-dismissible py-[18px] px-6 font-normal text-sm rounded-md bg-primary-500 bg-opacity-[14%] text-white" role="alert">
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <p class="flex-1 text-primary-500 font-Inter">
                        Your commission for the day is paid out on the next day before 23:59 (GMT-2).
                    </p>
                    <div class="flex-0 text-xl cursor-pointer text-primary-500">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <iconify-icon icon="line-md:close"></iconify-icon>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-6">
                    <div class="flex items-center justify-between flex-wrap">
                        <div class="flex space-x-3 rtl:space-x-reverse mb-4 sm:mb-0">
                            <div class="flex-none">
                                <div class="h-12 w-12 rounded-lg flex flex-col items-center justify-center text-2xl bg-slate-200 dark:bg-slate-900 dark:text-white">
                                    <iconify-icon icon="bi:currency-dollar"></iconify-icon>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                                    {{ __('Balance') }}
                                </div>
                                <div class="text-slate-900 dark:text-white text-lg font-medium">
                                    $ {{$balance}}
                                </div>
                            </div>
                        </div>
                        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                            <a href="{{route('user.deposit.amount')}}" class="btn btn-outline-dark btn-sm">
                                Go to Finance
                            </a>
                            <a href="{{route('user.withdraw.view')}}" class="btn btn-dark btn-sm mt-0">
                                Withdraw
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header noborder">
                    <h4 class="card-title">
                        {{ __('Referral URL') }} 
                        @if(setting('site_referral','global') == 'level')
                            {{ __('and Tree') }}
                        @endif
                    </h4>
                </div>
                <div class="card-body px-6 pb-6">
                    <div class="referral-link">
                        <div class="referral-link-form">
                            <input type="text" class="form-control !text-lg text-sm" value="{{ $getReferral->link }}" id="refLink"/>
                        </div>
                        <div class="flex justify-between flex-wrap mt-3">
                            <p class="referral-joined text-sm dark:text-white mb-4 sm:mb-0">
                                {{ $getReferral->relationships()->count() }} {{ __('peoples are joined by using this URL') }}
                            </p>
                            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                                <button type="button" class="btn inline-flex items-center justify-center btn-light btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#qrCodePopup">
                                    <span class="flex items-center">
                                        <iconify-icon class="text-xl" icon="bx:qr"></iconify-icon>
                                    </span>
                                </button>
                                <button type="submit" onclick="copyRef()" class="btn inline-flex items-center justify-center btn-dark btn-sm">
                                    <span class="flex items-center">
                                        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="bi:copy"></iconify-icon>
                                        <span id="copy">{{ __('Copy Url') }}</span>
                                        <input id="copied" hidden value="{{ __('Copied') }}">
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="lg:col-span-4 col-span-12">
        <div class="space-y-5">
            <div class="card">
                <div class="card-body p-6">
                    <p class="dark:text-white mb-1">{{ __('Grade 2') }}</p>
                    <h3 class="card-title font-bold mb-4">{{ __('33% of spread') }}</h3>
                    <p class="text-sm dark:text-white mb-1">
                        {{ __('To get the next grade earn at least $652.50 in commission up until 31.12') }}
                    </p>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-6">
                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-slate-200 dark:bg-slate-900 dark:text-white">
                        <iconify-icon icon="bi:gift"></iconify-icon>
                    </div>
                    <h5 class="card-title my-2">Attach to level a partner</h5>
                    <p class="text-sm dark:text-white mb-4">Join casecade and get guidance and support you need to grow!</p>
                    <a href="" class="btn btn-dark block-btn">Attach</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-6">
                    <div class="text-center">
                        <img src="" class="rounded-full" alt="...">
                        <h5 class="card-title my-2">Rebate</h5>
                        <p class="text-sm dark:text-white mb-4">Boost your income by increasing client retention and attracting new clients.</p>
                        <a href="" class="btn btn-dark block-btn">More details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>