<!-- Confirmation Modal -->
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
     id="addMIBModal"
     tabindex="-1"
     aria-labelledby="addMIBModal"
     aria-hidden="true"
 >
     <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
         <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body p-6 py-8 text-center space-y-5">
                <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-danger text-danger bg-opacity-30">
                    <iconify-icon class="text-4xl" icon="lucide:shield-question"></iconify-icon>
                </div>
                <div class="title">
                    <h4 class="text-xl font-medium dark:text-white capitalize">
                        {{ __('Convert to IB member ') }} <span id="name">{{ $user->full_name ?? ''}}</span>
                    </h4>
                </div>
                <p class="dark:text-slate-300">
                    {{ __('Are you sure you want to convert IB member? ') }}
                </p>

                <form id="addIBModalForm" action="{{ route('admin.ib.multi.approve') }}" method="POST">
                    @csrf

                    <input type="hidden" name="user_id" value="{{$user->id}}">
                    <div class="action-btns">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:send"></iconify-icon>
                            {{ __('Confirm IB member') }}
                        </button>
                        <a
                            href="#"
                            class="btn btn-danger inline-flex items-center justify-center"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        >
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                            {{ __('Close') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


