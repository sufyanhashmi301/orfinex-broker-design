@extends('frontend::layouts.user')
@section('title')
    {{ __('Add New Support Ticket') }}
@endsection
@section('content')
    <div class="grid grid-cols-12 gap-5">
        <div class="lg:col-span-4 col-span-12">
            <div class="card">
                <div class="card-body p-6">
                    <di class="space-y-5">
                        <div>
                            <p class="text-lg text-slate-900 font-medium mb-2 dark:text-white">{{ __('Tell Us!') }}</p>
                            <p class="text-sm dark:text-slate-100">
                                {{ __('Please provide us with as much information as possible when creating your support ticket. The more details you share, the better we can assist you.') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-lg text-slate-900 font-medium mb-2 dark:text-white">{{ __('Show Us!') }}</p>
                            <p class="text-sm dark:text-slate-100">
                                {{ __("If you're encountering any issues, consider attaching screenshots or images to your ticket. Visual aids can help us better understand and address your concerns.") }}
                            </p>
                        </div>
                        <div>
                            <p class="text-lg text-slate-900 font-medium mb-2 dark:text-white">{{ __('Caution') }}</p>
                            <p class="text-sm dark:text-slate-100">
                                {{ __('Please be aware that our ticket response time may extend up to 2 business days.') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-lg text-slate-900 font-medium mb-2 dark:text-white">{{ __('Response Time') }}</p>
                            <p class="text-sm dark:text-slate-100">
                                {{ __('Our dedicated support team is available from Monday to Friday, operating from 8:00 AM to 8:00 PM (Australian Timezone) and 9:00 AM to 6:00 PM (Dubai Time Zone). We make every effort to handle tickets promptly. However, during weekends or government holidays, our response time may experience a delay of one or two business days.') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-lg text-danger-600 font-medium mb-2">{{ __('Important Notice') }}</p>
                            <p class="text-sm dark:text-slate-100">
                                {{ __('Tickets that remain unresponsive for more than one or two business days or are unrelated to our support items may be locked. Additionally, please avoid creating duplicate tickets, as this may also result in ticket locking. We appreciate your cooperation in helping us provide efficient support.') }}
                            </p>
                        </div>
                    </di>
                </div>
            </div>
        </div>
        <div class="lg:col-span-8 col-span-12">
            <div class="card">
                <div class="card-header noborder">
                    <h4 class="card-title">{{ __('Add Ticket') }}</h4>
                    <div>
                        <a href="{{ route('user.ticket.index') }}" class="btn btn-primary inline-flex items-center justify-center">
                            {{ __('All Tickets') }}
                        </a>
                    </div>
                </div>
                <div class="card-body px-6 pb-6">
                    <div class="progress-steps-form">
                        <form action="{{ route('user.ticket.new-store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-1 gap-5">
                                <div class="col-xl-12 col-input-area relative">
                                    <label for="exampleFormControlInput1" class="form-label">
                                        {{ __('Ticket Title') }}
                                    </label>
                                    <input type="text" class="form-control" name="title">
                                </div>
                                <div class="col-xl-12 col-input-area relative">
                                    <label for="exampleFormControlInput1" class="form-label">
                                        {{ __('Ticket Descriptions') }}
                                    </label>
                                    <textarea class="form-control textarea" rows="5" name="message"></textarea>
                                </div>
                                <div class="input-area relative">
                                    <div class="wrap-custom-file">
                                        <input
                                            type="file"
                                            name="attach"
                                            id="ticket-attach"
                                            accept=".gif, .jpg, .png"
                                        />
                                        <label for="ticket-attach">
                                            <img
                                                class="upload-icon"
                                                src="{{ asset('global/materials/upload.svg') }}"
                                                alt=""
                                            />
                                            <span class="dark:text-slate-300">{{ __('Attach Image') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="buttons mt-5">
                                <button type="submit" class="btn btn-primary inline-flex items-center justify-center">
                                    {{ __('Add New Ticket') }}
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
