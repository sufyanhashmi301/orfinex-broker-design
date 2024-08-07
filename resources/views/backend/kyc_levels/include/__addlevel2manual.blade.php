<!-- Modal Structure -->
<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="addKycLevel2Formmodal" tabindex="-1" aria-labelledby="editLevel2" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="popup-body-text p-6 pt-5 edit-plugin-section">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editLevel2Label">{{ __('Add KYC Form') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <form action="{{ route('admin.kyc.storeLevel2') }}" method="post" class="space-y-4">
                    <input type="hidden" name="kyc_sub_level_id" value="{{the_hash(3)}}">
                        @csrf
                    <div class="input-area">
                        <label class="form-label" for="">{{ __('Name:') }}</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                               placeholder="KYC Type Name" required/>
                    </div>
                    <div>
                        <a href="javascript:void(0)"  class="generateCreate btn btn-outline-dark btn-sm inline-flex items-center justify-center mb-3">
                            {{ __('Add Field option') }}
                        </a>
                    </div>
                    <div class="addOptions">
                    </div>
                    <div class="max-w-xs">
                        <div class="input-area">
                            <label class="form-label" for="">{{ __('Status:') }}</label>
                            <div class="switch-field flex mb-3 overflow-hidden">
                                <input
                                    type="radio"
                                    id="active-status-2"
                                    name="status"
                                    checked=""
                                    value="1"
                                />
                                <label for="active-status-2">{{ __('Active') }}</label>
                                <input
                                    type="radio"
                                    id="deactivate-status-2"
                                    name="status"
                                    value="0"
                                />
                                <label for="deactivate-status-2">{{ __('Deactivate') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="input-area text-right">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
