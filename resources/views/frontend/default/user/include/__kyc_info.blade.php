@if($user->kyc != \App\Enums\KYCStatus::Level1->value)
<div class="alert alert-dismissible py-[18px] px-6 font-normal text-sm rounded-md bg-warning bg-opacity-[14%] text-white mb-5" role="alert">
    <div class="flex items-center space-x-3 rtl:space-x-reverse">
        <iconify-icon class="text-2xl flex-0 text-warning" icon="typcn:warning"></iconify-icon>
        <p class="flex-1 text-warning font-Inter">
            @if($user->kyc == \App\Enums\KYCStatus::Pending->value)
                <strong>{{ __('KYC Pending') }}</strong>
            @else
                {{ __('You need to submit your') }}
                <strong>{{ __('KYC and Other Documents') }}</strong> {{ __('before proceed to the system.') }}
            @endif
        </p>
        <div class="flex-0 text-xl cursor-pointer text-warning">
            @if($user->kyc != \App\Enums\KYCStatus::Pending->value)
                <a href="{{ route('user.kyc') }}" class="btn inline-flex justify-center btn-dark btn-sm">
                    <span class="flex items-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="heroicons-outline:newspaper"></iconify-icon>
                        <span>{{ __('Submit Now') }}</span>
                    </span>
                </a>
                <a href="" class="btn inline-flex justify-center btn-dark btn-sm" type="button" data-bs-dismiss="alert" aria-label="Close">
                    <span class="flex items-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="line-md:close"></iconify-icon>
                        <span>{{ __('Later') }}</span>
                    </span>
                </a>
            @endif
        </div>
    </div>
</div>
@endif
