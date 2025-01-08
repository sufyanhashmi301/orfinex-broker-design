
@extends('frontend::layouts.user')
@section('title')
    {{ __('Certificates') }}
@endsection
@section('content')
    <div class="card mb-6">
        <div class="card-body p-6">
            <h4 class="card-title mb-2">
                {{ __('Welcome to :siteTitle Certificate', ['siteTitle' => setting('site_title', 'global')]) }}
            </h4>
            <p class="card-text">
                {{ __('Show everyone your trading skills by unlocking certificates as the proof of your mastery over charts.') }}
            </p>
        </div>
    </div>

    {{-- <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden ">
                <div class="flex justify-between flex-wrap items-center mb-3">
                    <ul class="nav nav-tabs custom-tabs inline-flex items-center overflow-hidden rounded list-none border-0 pl-0">
                        <li class="nav-item">
                            <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary !text-nowrap active">
                                All
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary !text-nowrap">
                                Challenges
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary !text-nowrap">
                                Payout
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary !text-nowrap">
                                Max Allocation
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:;" class="btn btn-sm inline-flex justify-center btn-outline-primary !text-nowrap">
                                Lifetime Payout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div> --}}

    @if(count($certificates) != 0)
        <div class="card p-6 mb-6">

            <div class="grid md:grid-cols-3 grid-cols-1 gap-7">

                @foreach ($rewarded_certificates as $certificate)

                    <div class="card h-[350px] rounded-xl overflow-hidden cert-container">
                        <div class="card-body relative h-full bg-no-repeat bg-cover bg-center flex items-center justify-center"
                            style="background-image: url('{{ asset($certificate->certificate_image) }}');">
                            <div class="absolute w-full h-full top-0 bg-slate-900 bg-opacity-30"></div>
                            
                        </div>

                        <div class="flex flex-col items-center w-full z-10 space-y-5 cert-details">
                            <a href="{{ asset($certificate->certificate_image) }}" target="__blank" class="btn btn-lg" style="color: #fff; background: rgb(80 199 147)">
                                {{ $certificates->where('hook', $certificate->hook)->first()->title }}
                            </a>
                        </div>

                    </div>
                @endforeach

                @foreach ($certificates as $certificate)

                    @php
                        if (in_array($certificate->hook, $rewarded_certificates_hooks)) {
                            continue;
                        }
                    @endphp

                    <div class="card h-[350px] rounded-xl overflow-hidden cert-container">
                        <div class="card-body relative h-full bg-no-repeat bg-cover bg-center flex items-center justify-center"
                            style="background-image: url('{{ asset($certificate->image) }}'); filter: blur(8px)">
                            <div class="absolute w-full h-full top-0 bg-slate-900 bg-opacity-30"></div>
                            
                        </div>

                        <div class="flex flex-col items-center w-full z-10 space-y-5 cert-details">
                            <svg width="45" height="42" viewBox="0 0 35 34" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M27.229 11.3333V14.3083C26.6057 14.2233 25.8973 14.1808 25.104 14.1666V11.3333C25.104 6.87081 23.8432 3.89581 17.6665 3.89581C11.4898 3.89581 10.229 6.87081 10.229 11.3333V14.1666C9.43567 14.1808 8.72734 14.2233 8.104 14.3083V11.3333C8.104 7.22498 9.09567 1.77081 17.6665 1.77081C26.2373 1.77081 27.229 7.22498 27.229 11.3333Z"
                                    fill="#F5F5F5" />
                                <path
                                    d="M27.1616 14.8038L27.1616 14.8038L27.1688 14.8047C28.9038 15.0156 29.8802 15.5288 30.4652 16.4397C31.0797 17.3964 31.3333 18.8903 31.3333 21.25V24.0834C31.3333 26.9203 30.9666 28.493 30.0631 29.3965C29.1596 30.3 27.5869 30.6667 24.75 30.6667H10.5833C7.74643 30.6667 6.17372 30.3 5.27022 29.3965C4.36672 28.493 4 26.9203 4 24.0834V21.25C4 18.8903 4.25366 17.3964 4.8681 16.4397C5.45312 15.5288 6.42954 15.0156 8.16451 14.8047L8.16451 14.8048L8.17172 14.8038C8.76747 14.7225 9.45312 14.6807 10.2336 14.6667H25.0997C25.8802 14.6807 26.5659 14.7225 27.1616 14.8038ZM13.3497 24.0355L13.3595 24.0262L13.3688 24.0164C13.7058 23.6607 13.9167 23.1714 13.9167 22.6667C13.9167 22.4067 13.857 22.1572 13.7649 21.936C13.6716 21.7121 13.5414 21.5085 13.3775 21.3264L13.3687 21.3166L13.3594 21.3073C12.823 20.7709 11.9866 20.5963 11.2726 20.9004C11.0203 20.999 10.8299 21.136 10.6597 21.2892L10.6401 21.3068L10.6225 21.3264C10.4586 21.5085 10.3284 21.7121 10.2351 21.936C10.143 22.1572 10.0833 22.4067 10.0833 22.6667C10.0833 23.1714 10.2942 23.6607 10.6312 24.0164L10.6449 24.0308L10.6597 24.0442C10.8306 24.198 11.0218 24.3355 11.2757 24.4342C11.4953 24.5248 11.7425 24.5834 12 24.5834C12.5047 24.5834 12.994 24.3725 13.3497 24.0355ZM19.0164 24.0355L19.0308 24.0218L19.0441 24.007C19.2081 23.8248 19.3382 23.6213 19.4315 23.3973C19.5237 23.1762 19.5833 22.9267 19.5833 22.6667C19.5833 22.1453 19.3706 21.6707 19.0355 21.317L19.0285 21.3097L19.0213 21.3026C18.2915 20.592 17.0293 20.5853 16.3073 21.3073L16.3072 21.3072L16.2979 21.317C15.9627 21.6707 15.75 22.1453 15.75 22.6667C15.75 22.9267 15.8097 23.1762 15.9018 23.3973C15.9951 23.6213 16.1252 23.8248 16.2892 24.007L16.3025 24.0218L16.317 24.0355C16.6707 24.3706 17.1453 24.5834 17.6667 24.5834C18.1714 24.5834 18.6606 24.3725 19.0164 24.0355ZM24.683 24.0355L24.6928 24.0262L24.7021 24.0164C25.0391 23.6607 25.25 23.1714 25.25 22.6667C25.25 22.5453 25.2314 22.4153 25.2186 22.3263L25.2166 22.3126L25.2168 22.3126L25.2149 22.3012C25.1936 22.1738 25.1538 22.0519 25.0992 21.9362C25.0582 21.8231 24.9982 21.7035 24.9142 21.5938C24.8492 21.498 24.7854 21.4182 24.7333 21.3531L24.7296 21.3485L24.7123 21.3269L24.6927 21.3073C23.9707 20.5853 22.7085 20.592 21.9787 21.3026L21.9715 21.3097L21.9645 21.317C21.6294 21.6707 21.4167 22.1453 21.4167 22.6667C21.4167 23.1881 21.6294 23.6626 21.9645 24.0164L21.9738 24.0262L21.9836 24.0355C22.3394 24.3725 22.8286 24.5834 23.3333 24.5834C23.838 24.5834 24.3273 24.3725 24.683 24.0355Z"
                                    fill="#F5F5F5" stroke="#F5F5F5" />
                            </svg>
                            <a href="javascript:void(0)" class="btn bg-primary btn-lg cursor-default" style="color: #fff">
                                {{$certificate->title}}
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>

        </div>
    @else
        <div class="card">
            <div class="max-w-xl text-center py-10 mx-auto space-y-5">
                <div class="w-20 h-20 bg-danger-500 text-white rounded-full inline-flex items-center justify-center">
                    <iconify-icon icon="fa6-solid:box-open" class="text-5xl"></iconify-icon>
                </div>
                <h4 class="text-3xl text-slate-900 dark:text-white">
                    {{ __('No Certificates Available') }}
                </h4>
                <p class="text-slate-600 dark:text-slate-100">
                    {{ __('But no worries, you can have one by passing our challenge. Are you ready?') }}
                </p>
                <a href="{{ route('user.schema') }}" class="btn btn-dark inline-flex items-center justify-center">
                    Start a new challenge
                </a>
            </div>
        </div>
    @endif

    <style>
        .cert-container {
            position: relative;
        }
        .cert-details {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%)
        }
    </style>

    
@endsection
