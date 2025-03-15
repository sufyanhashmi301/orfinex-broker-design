@extends('frontend::layouts.user')
@section('title')
    {{ __('Add New Support Ticket') }}
@endsection
@section('content')
    <div class="mb-5">
        <ul class="m-0 p-0 list-none">
            <li class="inline-block relative top-[3px] text-base text-primary font-Inter ">
                <a href="{{route('user.dashboard')}}">
                    <iconify-icon icon="heroicons-outline:home"></iconify-icon>
                    <iconify-icon icon="heroicons-outline:chevron-right" class="relative text-slate-500 text-sm rtl:rotate-180"></iconify-icon>
                </a>
            </li>
            <li class="inline-block relative text-sm text-primary font-Inter ">
                {{ __('Support Tickets') }}
                <iconify-icon icon="heroicons-outline:chevron-right" class="relative top-[3px] text-slate-500 rtl:rotate-180"></iconify-icon>
            </li>
            <li class="inline-block relative text-sm text-slate-500 font-Inter dark:text-white">
                {{ __('New Ticket') }}
            </li>
        </ul>
    </div>
    <div class="grid grid-cols-12 gap-5">
        <div class="lg:col-span-4 col-span-12">
            <div class="card">
                <div class="card-body p-6">
                    <di class="space-y-5">
                        <div>
                            <p class="text-lg text-slate-900 font-medium mb-2 dark:text-white">Tell Us!</p>
                            <p class="text-sm dark:text-slate-100">Please provide us with as much information as possible when creating your support ticket. The more details you share, the better we can assist you.</p>
                        </div>
                        <div>
                            <p class="text-lg text-slate-900 font-medium mb-2 dark:text-white">Show Us!</p>
                            <p class="text-sm dark:text-slate-100">
                                If you're encountering any issues, consider attaching screenshots or images to your ticket. Visual aids can help us better understand and address your concerns.
                            </p>
                        </div>
                        <div>
                            <p class="text-lg text-slate-900 font-medium mb-2 dark:text-white">Caution</p>
                            <p class="text-sm dark:text-slate-100">
                                Please be aware that our ticket response time may extend up to 2 business days.
                            </p>
                        </div>
                        <div>
                            <p class="text-lg text-slate-900 font-medium mb-2 dark:text-white">Response Time</p>
                            <p class="text-sm dark:text-slate-100">
                                Our dedicated support team is available from Monday to Friday, operating from 8:00 AM to 8:00 PM (Australian Timezone) and 9:00 AM to 6:00 PM (Dubai Time Zone). We make every effort to handle tickets promptly. However, during weekends or government holidays, our response time may experience a delay of one or two business days.
                            </p>
                        </div>
                        <div>
                            <p class="text-lg text-danger-600 font-medium mb-2">Important Notice</p>
                            <p class="text-sm dark:text-slate-100">
                                Tickets that remain unresponsive for more than one or two business days or are unrelated to our support items may be locked. Additionally, please avoid creating duplicate tickets, as this may also result in ticket locking. We appreciate your cooperation in helping us provide efficient support.
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
                        <a href="{{ route('user.ticket.index') }}" class="btn btn-dark">
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
                                            <span>{{ __('Attach Image') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="buttons mt-5">
                                <button type="submit" class="btn btn-dark">
                                    {{ __('Add New Ticket') }}<i class="anticon anticon-double-right"></i>
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
