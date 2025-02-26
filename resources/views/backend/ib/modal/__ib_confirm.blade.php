<!-- Add IB Modal -->
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
     id="addIBModal"
     tabindex="-1"
     aria-labelledby="addIBModalLabel"
     aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body p-6 py-8 text-center space-y-5">
                <div class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-primary text-primary bg-opacity-30">
                    <iconify-icon class="text-4xl" icon="lucide:user-plus"></iconify-icon>
                </div>
                <div class="title">
                    <h4 class="text-xl font-medium dark:text-white capitalize">
                        {{ __('Convert to IB Member') }} <span id="modalUserName"></span>
                    </h4>
                </div>

                <form id="addIBModalForm" action="{{ route('admin.ib.approve') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-span-12">
                            <div class="site-input-groups relative">
                                <label class="form-label" for="ibGroupIDSelect">{{ __('IB Group:') }}</label>
                                <select name="ib_group_id" id="ibGroupIDSelect" class="form-control h-full w-full">
                                    <option value="">{{__('Select IB Group')}}</option>
                                    @foreach($ibGroups as $ibGroup)
                                        <option value="{{$ibGroup->id}}">{{$ibGroup->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="action-btns text-center">
                        <input type="hidden" name="user_id" id="modalUserId" value="">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                            {{ __('Approve IB Member') }}
                        </button>
                        <button type="button" class="btn btn-danger inline-flex items-center justify-center"
                                data-bs-dismiss="modal" aria-label="Close">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
