@if(setting('sign_up_referral','permission'))
<div class="card">
    <div class="card-header">
        <h4 class="card-title">{{ __('Partner Link') }}</h4>
    </div>
    <div class="card-body p-6">
        <div class="referral-link">
            <div class="referral-link-form input-area">
                <input type="text" class="form-control text-slate-400" value="{{ $referral->link }}" id="refLink"/>
            </div>
            <p class="referral-joined text-sm dark:text-white mt-2">
                {{ $referral->relationships()->count() }} {{ __('peoples are joined by using this URL') }}
            </p>
            <div class="flex sm:space-x-2 space-x-2 sm:justify-end items-center rtl:space-x-reverse mt-3">
                <button type="button" class="btn inline-flex items-center justify-center btn-light btn-sm p-3" data-bs-toggle="modal" data-bs-target="#qrCodePopup">
                    <span class="flex items-center">
                        <iconify-icon class="text-lg" icon="bx:qr"></iconify-icon>
                    </span>
                </button>
                <button type="submit" onclick="copyRef()" class="btn inline-flex items-center justify-center btn-dark btn-sm p-3">
                    <span class="flex items-center">
                        <iconify-icon class="text-lg" icon="bi:copy"></iconify-icon>
                        <input id="copied" hidden="" value="Copied">
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
@include('frontend::.referral.modal.__qr_code')
@endif
<div class="row hidden">
    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12">
        <div class="user-ranking" @if($user->avatar) style="background: url({{ asset($user->avatar) }});" @endif>
            <h4>{{ $user->rank->ranking }}</h4>
            <p>{{ $user->rank->ranking_name }}</p>
            <div class="rank" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $user->rank->description }}">
                <img src="{{ asset( $user->rank->icon) }}" alt="">
            </div>
        </div>
    </div>
    @if(setting('sign_up_referral','permission'))
        <div class="col-xl-9 col-lg-9 col-md-8 col-sm-12 col-12">
            <div class="site-card">
                <div class="site-card-header">
                    <h3 class="title">{{ __('Referral URL') }}</h3>
                </div>
                <div class="site-card-body">
                    <div class="referral-link">
                        <div class="referral-link-form">
                            <input type="text" value="{{ $referral->link }}" id="refLink"/>
                            <button type="submit" onclick="copyRef()">
                                <i class="anticon anticon-copy"></i>
                                <span id="copy">{{ __('Copy') }}</span>
                            </button>
                        </div>
                        <p class="referral-joined">
                            {{ $referral->relationships()->count() }} {{ __('peoples are joined by using this URL') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
