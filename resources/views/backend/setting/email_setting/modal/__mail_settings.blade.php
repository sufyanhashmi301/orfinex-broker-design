<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="mailSettings" tabindex="-1" aria-labelledby="mailSettings" aria-hidden="true">
    <div class="modal-dialog modal-lg top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="flex items-center justify-between p-5">
                <h3 class="text-xl font-medium dark:text-white capitalize" id="mailSettingsLabel">
                    {{ __('SMTP Mail Settings') }}
                </h3>
                <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="modal-body p-6">
                <form action="{{ route('admin.settings.update') }}" method="post">
                    @csrf
                    <input type="hidden" name="section" value="mail">
                    <input type="hidden" name="mailing_driver" value="{{ setting('mailing_driver','mail') }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="input-area">
                            <label for="" class="form-label form-label">{{ __('Email From Name') }}</label>
                            <input
                                type="text"
                                class="form-control"
                                name="email_from_name"
                                value="{{ setting('email_from_name','mail') }}"
                                required
                            />
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label form-label">{{ __('Email From Address') }}</label>
                            <input
                                type="email"
                                class="form-control"
                                name="email_from_address"
                                value="{{ setting('email_from_address','mail') }}"
                                required
                            />
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">{{ __('Mail Username') }}</label>
                            <input
                                type="text"
                                class="form-control"
                                name="mail_username"
                                value="{{ setting('mail_username','mail') }}"
                                required=""
                            />
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">{{ __('Mail Password') }}</label>
                            <input
                                type="password"
                                class="form-control"
                                name="mail_password"
                                value="{{   !config('app.demo') ? setting('mail_password','mail') : 'demo-mode' }}"
                                required=""
                            />
                        </div>
                        <div class="md:col-span-2 input-area">
                            <label for="" class="form-label">{{ __('SMTP Host') }}</label>
                            <input
                                type="text"
                                class="form-control"
                                name="mail_host"
                                value="{{ setting('mail_host','mail') }}"
                                required=""
                            />
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">{{ __('SMTP Port') }}</label>
                            <input
                                type="text"
                                class="form-control"
                                name="mail_port"
                                value="{{ setting('mail_port','mail') }}"
                                required=""
                            />
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">{{ __('SMTP Secure') }}</label>
                            <input
                                type="text"
                                class="form-control"
                                name="mail_secure"
                                value="{{ setting('mail_secure','mail') }}"
                                required=""
                            />
                        </div>

                        <div class="col-span-2 text-right">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                {{ __(' Save Changes') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
