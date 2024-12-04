<div class="flex flex-col items-center justify-center gap-5">
    <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 24 24">
        <g fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M2 16c0-2.828 0-4.243.879-5.121C3.757 10 5.172 10 8 10h8c2.828 0 4.243 0 5.121.879C22 11.757 22 13.172 22 16s0 4.243-.879 5.121C20.243 22 18.828 22 16 22H8c-2.828 0-4.243 0-5.121-.879C2 20.243 2 18.828 2 16Z"/>
            <circle cx="12" cy="16" r="2" opacity="0.5"/>
            <path stroke-linecap="round" d="M6 10V8a6 6 0 1 1 12 0v2" opacity="0.5"/>
        </g>
    </svg>
    <p class="text-xl text-center font-semibold dark:text-white">
        {{ __('🚫 Feature Locked') }}
    </p>
    <p class="text-lg text-center text-slate-600 dark:text-slate-100">
        {{ __('This feature is currently unavailable for your account. To access this functionality, please consider upgrading your plan or reaching out to activate it.') }}
        <span class="text-sm block mt-2">
            {{ __('For assistance or to learn more about unlocking this feature, please contact our support team or visit our website at ') }}
            <a href="https://brokeret.com/" class="btn-link" target="_blank">{{ __('www.brokeret.com.') }}</a>
        </span>
    </p>
</div>
