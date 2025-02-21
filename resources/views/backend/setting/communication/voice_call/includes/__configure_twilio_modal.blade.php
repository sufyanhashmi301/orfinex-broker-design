<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="configureTwilio" tabindex="-1" aria-labelledby="configureTwilio" aria-hidden="true">
    <div class="modal-dialog modal-lg top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body popup-body">
                <div class="flex items-start justify-between gap-3 p-5">
                    <h5 class="modal-title" id="configureTwilioLabel">Configure Twilio</h5>
                    <button type="button"
                        class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white"
                        data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-6 pt-0 edit-plugin-section">
                    <form action="{{ route('admin.settings.voice_call.update') }}" method="post" class="space-y-3">
                        @csrf 
                        <input type="hidden" name="method" value="twilio">
                        
                        <div class="input-area">
                            <label class="form-label" for="">SID</label>
                            <input type="text" name="sid" value="{{ $method->details['sid'] ?? '' }}" class="form-control" placeholder="####"  />
                        </div>

                        <div class="input-area">
                            <label class="form-label" for="">Auth Token</label>
                            <input type="text" name="auth_token" value="{{ $method->details['auth_token'] ?? '' }}" class="form-control" placeholder="####"  />
                        </div>

                        <div class="input-area">
                            <label class="form-label" for="">API Key SID</label>
                            <input type="text" name="api_key_sid" value="{{ $method->details['api_key_sid'] ?? '' }}" class="form-control" placeholder="####"  />
                        </div>

                        <div class="input-area">
                            <label class="form-label" for="">API Key Secret</label>
                            <input type="text" name="api_key_secret" value="{{ $method->details['api_key_secret'] ?? '' }}" class="form-control" placeholder="####"  />
                        </div>

                        <div class="input-area">
                            <label class="form-label" for="">TWIML App SID</label>
                            <input type="text" name="twiml_app_sid" value="{{ $method->details['twiml_app_sid'] ?? '' }}" class="form-control" placeholder="####"  />
                        </div>

                        <div class="input-area">
                            <label class="form-label" for="">Phone Number</label>
                            <input type="text" name="phone_number" value="{{ $method->details['phone_number'] ?? '' }}" class="form-control" placeholder="####"  />
                        </div>

                        <div class="input-area">
                            <label class="form-label" for="">Your Verified Phone Number</label>
                            <input type="text" name="verified_phone_number" value="{{ $method->details['verified_phone_number'] ?? '' }}" class="form-control" placeholder="####"  />
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
