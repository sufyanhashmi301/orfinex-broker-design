@extends('frontend::layouts.user')
@section('title')
    {{ __('Add New Support Ticket') }}
@endsection
@section('content')
    <div class="grid grid-cols-12 gap-5">
        <div class="lg:col-span-4 col-span-12">
            <div class="card">
                <div class="card-body p-6">
                    <div class="space-y-5">
                        <div>
                            <p class="text-lg text-slate-900 font-medium mb-2 dark:text-white">{{ __('Tell Us!') }}</p>
                            <p class="text-sm dark:text-slate-100">
                                {{ __('Please provide as much detail as possible when submitting your support ticket. Clear and complete information helps us assist you more efficiently.') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-lg text-slate-900 font-medium mb-2 dark:text-white">{{ __('Show Us!') }}</p>
                            <p class="text-sm dark:text-slate-100">
                                {{ __('If you encounter any issues, attach relevant images or screenshots to your ticket. Visual references help us understand your concern better and resolve it faster.') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-lg text-slate-900 font-medium mb-2 dark:text-white">{{ __('Caution') }}</p>
                            <p class="text-sm dark:text-slate-100">
                                {{ __('Please note that the response time for tickets may extend up to 2 business days based on the nature of the inquiry.') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-lg text-slate-900 font-medium mb-2 dark:text-white">{{ __('Response Time') }}</p>
                            <p class="text-sm dark:text-slate-100">
                                {{ __('Our support team operates during standard business hours, Monday through Friday. We aim to resolve all inquiries promptly. However, response times may be affected during weekends or public holidays.') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-lg text-danger600 font-medium mb-2">{{ __('Important Notice') }}</p>
                            <p class="text-sm dark:text-slate-100">
                                {{ __('Tickets that remain inactive for a specified period or are unrelated to support inquiries may be closed. To ensure prompt resolution, please avoid submitting duplicate tickets. We appreciate your understanding and cooperation in providing seamless support.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-8 col-span-12">
            <div class="card">
                <div class="card-header noborder">
                    <h4 class="card-title">{{ __('Add Ticket') }}</h4>
                    <div>
                        <a href="{{ route('user.ticket.index') }}" class="btn btn-primary loaderBtn inline-flex items-center justify-center">
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
