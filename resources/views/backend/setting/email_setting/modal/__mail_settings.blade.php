<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="mailSettings" tabindex="-1" aria-labelledby="mailSettings" aria-hidden="true">
    <div class="modal-dialog modal-lg top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="flex items-center justify-between p-5">
                <h3 class="text-xl font-medium dark:text-white capitalize" id="mailSettingsLabel">
                    {{ __('SMTP Mail Settings') }}
                </h3>
                <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                    <svg aria-hidden="true" class="w-5 h-5 dark:fill-white" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
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
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="This name will appear as the sender in outgoing emails">
                                    {{ __('Email From Name') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                name="email_from_name"
                                value="{{ setting('email_from_name','mail') }}"
                                required
                            />
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The email address from which messages will be sent. Must be verified">
                                    {{ __('Email From Address') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input
                                type="email"
                                class="form-control"
                                name="email_from_address"
                                value="{{ setting('email_from_address','mail') }}"
                                required
                            />
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Your SMTP provider login name or API key used for authentication">
                                    {{ __('Mail Username') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                name="mail_username"
                                value="{{ setting('mail_username','mail') }}"
                                required=""
                            />
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The password or secret key for your SMTP account">
                                    {{ __('Mail Password') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input
                                type="password"
                                class="form-control"
                                name="mail_password"
                                value="{{   !config('app.demo') ? setting('mail_password','mail') : 'demo-mode' }}"
                                required=""
                            />
                        </div>
                        <div class="md:col-span-2 input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="The hostname or IP address of your SMTP server (e.g., smtp.mail.com)">
                                    {{ __('SMTP Host') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                name="mail_host"
                                value="{{ setting('mail_host','mail') }}"
                                required=""
                            />
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Port used for email delivery (usually 587 for TLS, 465 for SSL)">
                                    {{ __('SMTP Port') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                name="mail_port"
                                value="{{ setting('mail_port','mail') }}"
                                required=""
                            />
                        </div>
                        <div class="input-area">
                            <label for="" class="form-label">
                                <span class="shift-Away inline-flex items-center gap-1" data-tippy-content="Type of encryption used: select ‘tls’ or ‘ssl’ based on your SMTP provider">
                                    {{ __('SMTP Secure') }}
                                    <iconify-icon icon="mdi:information-slab-circle-outline" class="text-[16px]"></iconify-icon>
                                </span>
                            </label>
                            <input
                                type="text"
                                class="form-control"
                                name="mail_secure"
                                value="{{ setting('mail_secure','mail') }}"
                                required=""
                            />
                        </div>
                    </div>
                    <div class="action-btns text-right mt-10">
                        <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                            {{ __(' Save Changes') }}
                        </button>
                        <a href="#"
                           class="btn btn-danger inline-flex items-center justify-center"
                           data-bs-dismiss="modal"
                           aria-label="Close">
                            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                            {{ __('Close') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
