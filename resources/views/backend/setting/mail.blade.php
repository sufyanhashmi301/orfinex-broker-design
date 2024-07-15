@extends('backend.setting.index')
@section('title')
    {{ __('Mail Settings') }}
@endsection
@section('setting-content')
    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a data-bs-toggle="modal" data-bs-target="#mailConnection" href="javascript:void(0);" class="inline-flex items-center justify-center text-success-500"> 
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:mail-check"></iconify-icon> 
                {{ __('Connection Check') }}
            </a>
        </div>
    </div>
    @include('backend.setting.plugin.include.__menu')

    <div class="card">
        <div class="card-body p-6">
            <form action="{{ route('admin.settings.update') }}" method="post">
                @csrf
                <input type="hidden" name="section" value="mail">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="input-area">
                        <label for=""
                               class="form-label form-label">{{ __('Email From Name') }}</label>
                        <input
                            type="text"
                            class="form-control"
                            name="email_from_name"
                            value="{{ setting('email_from_name','mail') }}"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label for=""
                               class="form-label form-label">{{ __('Email From Address') }}</label>
                        <input
                            type="email"
                            class="form-control"
                            name="email_from_address"
                            value="{{ setting('email_from_address','mail') }}"
                            required
                        />
                    </div>
                    <div class="input-area">
                        <label for="" class="form-label">{{ __('Mail Driver') }}</label>
                        <div class="form-check flex items-center">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="mailing_driver"
                                id="smtp"
                                value="smtp"
                                checked=""
                            />
                            <label class="form-check-label form-label !mb-0 ml-2" for="smtp">
                                {{ __('SMTP') }}
                            </label>
                        </div>
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
                    <div class="input-area">
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


{{--mail connection test--}}

    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="mailConnection" tabindex="-1" aria-labelledby="mailConnection" aria-hidden="true">
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
            <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="flex items-center justify-between p-5">
                    <h3 class="text-xl font-medium dark:text-white capitalize" id="mailConnectionLabel">
                        {{ __('SMTP Connection') }}
                    </h3>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                    11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="modal-body p-6">
                    <form action="{{ route('admin.settings.mail.connection.test') }}" method="post" class="space-y-4">
                        @csrf
                        <div class="input-area !mt-0">
                            <label for="" class="form-label">{{ __('Your Email:') }}</label>
                            <input
                                type="email"
                                name="email"
                                class="form-control mb-0"
                                required=""
                            />
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                                {{ __('Check Now') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection
