   {{-- Bulk Detach User Modal --}}
   @can('staff-attach-users-delete')
   <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
       id="bulkDetachModal"
       tabindex="-1"
       aria-labelledby="bulkDetachModal"
       aria-hidden="true"
   >
       <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
           <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
               <div class="modal-body p-6 py-10 text-center">
                    <div class="space-y-3">
                        <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-danger text-danger bg-opacity-30">
                            <iconify-icon class="text-4xl" icon="lucide:alert-triangle"></iconify-icon>
                        </div>
                        <div class="title">
                            <h4 class="text-2xl font-medium dark:text-white capitalize">
                                {{ __('Are you sure?') }}
                            </h4>
                        </div>
                        <p class="dark:text-slate-300">
                            {{ __('You want to detach selected users') }}
                        </p>
                    </div>
                   <form method="post" id="bulkDetachForm" action="{{ route('admin.staff.detachUser', $staff->id) }}">
                       @csrf
                       <input type="hidden" name="user_ids" id="bulkUserIdsInput">
                       <div class="action-btns mt-10">
                           <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                               <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                               {{ __('Confirm') }}
                           </button>
                           <button type="button" class="btn btn-danger inline-flex items-center justify-center" data-bs-dismiss="modal" aria-label="Close">
                               <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                               {{ __('Cancel') }}
                           </button>
                       </div>
                   </form>
               </div>
           </div>
       </div>
   </div>
   @endcan