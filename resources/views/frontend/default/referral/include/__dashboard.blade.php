<div class="grid xl:grid-cols-3 grid-cols-1 gap-5 mb-5">
    <div class="card">
        <div class="card-body pt-4 pb-3 px-4">
            <div class="flex space-x-3 rtl:space-x-reverse">
                <div class="flex-none">
                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-[#E5F9FF] dark:bg-slate-900 text-info-500">
                        <iconify-icon icon="bi:currency-dollar"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{ __('Total Earnings') }}
                    </div>
                    <div class="text-slate-900 dark:text-white text-lg font-medium">
                        $ {{$balance}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body pt-4 pb-3 px-4">
            <div class="flex space-x-3 rtl:space-x-reverse">
                <div class="flex-none">
                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-[#FFEDE6] dark:bg-slate-900 text-warning-500">
                        <iconify-icon icon="iconoir:hand-cash"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{ __('Total Payouts') }}
                    </div>
                    <div class="text-slate-900 dark:text-white text-lg font-medium">
                        564
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body pt-4 pb-3 px-4">
            <div class="flex space-x-3 rtl:space-x-reverse">
                <div class="flex-none">
                    <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-[#EAE6FF] dark:bg-slate-900 text-[#5743BE]">
                        <iconify-icon icon="fluent:wallet-credit-card-32-filled"></iconify-icon>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                        {{ __('Current Balance') }}
                    </div>
                    <div class="text-slate-900 dark:text-white text-lg font-medium">
                        $ {{$balance}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                    <div class="text-center mb-3">
                        <h5 class="text-center text-success-600">$0.00</h5>
                        <p class="text-center">Balance</p>
                    </div>
                    <form action="" class="space-y-2">
                        <div class="input-area relative">
                            <label for="largeInput" class="form-label !flex items-center">
                                <span>Amount</span>
                                <span class="toolTip onTop leading-[0]" data-tippy-content="minimum Payout will be 50$" data-tippy-theme="dark">
                                    <iconify-icon class="text-base ml-1" icon="material-symbols:info"></iconify-icon>
                                </span>
                            </label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="input-area relative">
                            <label for="largeInput" class="form-label">Payout Methods</label>
                            <select name="" class="select2 form-control w-full mt-2 py-2">
                                <option value="bank">Bank</option>
                            </select>
                        </div>
                        <div class="input-area relative">
                            <label for="largeInput" class="form-label">Note</label>
                            <textarea name="" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn inline-flex justify-center btn-dark">
                                <span>Request Payout</span>
                            </button>
                        </div>
                    </form>
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