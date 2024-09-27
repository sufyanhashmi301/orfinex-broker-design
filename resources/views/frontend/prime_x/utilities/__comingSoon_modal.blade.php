<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
     id="comingSoonModal"
     tabindex="-1"
     aria-labelledby="comingSoonModal"
     aria-hidden="true"
     data-bs-backdrop="static"
     data-bs-keyboard="false">
    <div class="modal-dialog modal-lg top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body px-10 py-10 pt-0 text-center space-y-5">
                <img src="{{ asset('frontend/images/confetti-gif.gif') }}" class="inline-flex" alt="" style="max-width: 250px">
                <div class="title">
                    <h4 class="text-xl font-medium dark:text-white capitalize">
                        {{ __('New feature on the way!') }}
                    </h4>
                </div>
                <p class="text-sm dark:text-slate-50">
                    {{ __("We’re adding more value to your experience with this upcoming feature, set to launch in just a few weeks. Get ready to explore and make the most out of your trading journey!") }}
                </p>
                <p class="text-sm dark:text-slate-50">
                    {{ __('Stay tuned for more exciting updates!') }}
                </p>
                <a href="{{ route('user.dashboard') }}" class="btn btn-primary inline-flex items-center justify-center">
                    {{ __('Go To Dashboard') }}
                </a>
            </div>
        </div>
    </div>
</div>
