<!-- Confirmation Modal -->
<div
    class="modal fade"
    id="addMIBModal"
    tabindex="-1"
    aria-labelledby="addMIBModalLabel"
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
                    <h3 class="title mb-4"> {{ __('Add Multi IB To ') }} <span id="name">{{ $user->full_name ?? ''}}</span></h3>
                    <form id="addIBModalForm" action="{{ route('admin.ib.multi.approve') }}" method="POST">
                        @csrf

                        <input type="hidden" name="user_id" value="{{$user->id}}">

                        <p>
                            Are you sure you want to add Multi IB Account?
                        </p>

                        <div class="action-btns">
                            <button type="submit" class="site-btn-sm primary-btn me-2">
                                <i icon-name="send"></i>
                                {{ __('Add Multi IB') }}
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


