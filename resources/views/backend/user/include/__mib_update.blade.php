
<div
    class="modal fade"
    id="updateMIBModal"
    tabindex="-1"
    aria-labelledby="updateMIBModalLabel"
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
                    <h3 class="title mb-4"> {{ __('Update Multi IB To ') }} <span id="name">{{ $user->full_name ?? ''}}</span></h3>
                    <form action="{{ route('admin.ib.multi.update') }}" method="post" >
                        @csrf

                        <input type="hidden" name="user_id" value="{{$user->id}}">

                        <div class="site-input-groups">
                            <label for="" class="box-input-label">{{ __('Multi IB Login:') }}</label>
                            <input
                                type="text"
                                name="multi_ib_login"
                                value="{{ $user->multi_ib_login}}"
                                class="box-input mb-0"
                                required=""
                            />
                        </div>

                        <div class="action-btns">
                            <button type="submit" class="site-btn-sm primary-btn me-2">
                                <i icon-name="send"></i>
                                {{ __('Update Multi IB') }}
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
