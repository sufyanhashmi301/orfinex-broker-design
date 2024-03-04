
<div
    class="modal fade"
    id="addReferralModal"
    tabindex="-1"
    aria-labelledby="addReferralModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content site-table-modal">
            <div class="modal-body popup-body">
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
                <div class="popup-body-text">
                    <h3 class="title mb-4"> {{ __('Add Referral Under ') }} <span id="referral-add-name">{{ $user->full_name ?? ''}}</span></h3>
                    <form action="{{ route('admin.referral.direct.add') }}" method="post" >
                        @csrf

                        <input type="hidden" name="ref_id" value="{{$user->id}}">
                        <div class="formGroup">
                            <label class="block capitalize form-label">{{ __('Select User*') }}</label>
                            <div class="relative ">
                                <select name="user_id" id="countrySelect" class="select2 form-control py-2 h-[48px] w-full mt-2">
                                    @foreach( $users as $user)
                                        <option  value="{{ $user->id }}"
                                                 class="py-1 inline-block font-Inter font-normal text-sm text-slate-600">
                                            {{ $user->email  }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
<br>
<br>
                        <div class="action-btns">
                            <button type="submit" class="site-btn-sm primary-btn me-2">
                                <i icon-name="send"></i>
                                {{ __('Add Referral') }}
                            </button>
                            <a
                                href="#"
                                class="site-btn-sm red-btn"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            >
                                <i icon-name="x"></i>
                                {{ __('Close') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
