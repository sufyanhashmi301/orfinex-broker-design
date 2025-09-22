<div class="grid md:grid-cols-3 grid-cols-1 gap-5 gap-x-10 mb-5">
    <a href="mailto:{{ setting('support_email', 'global') }}">
        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <i data-lucide="mail"></i>
        </div>
        <h4 class="mb-2 text-theme-lg font-medium text-gray-800 dark:text-white/90">
            {{ __('Email Support') }}
        </h4>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('Get fast and reliable help directly in your inbox, perfect for detailed queries that need thorough answers.') }}
        </p>
    </a>

    <a href="{{ route('user.ticket.index') }}">
        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <i data-lucide="message-circle-more"></i>
        </div>
        <h4 class="mb-2 text-theme-lg font-medium text-gray-800 dark:text-white/90">
            {{ __('Direct Text Contact') }}
        </h4>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('Reach us instantly through text for quick updates, clarifications, or immediate support.') }}
        </p>
    </a>

    @php
        $calendly = plugin_active('Calendly');
        $user = auth()->user();

        if ($calendly) {
            $data = json_decode($calendly->data, true);

            if (isset($data['link'])) {
                $baseLink = $data['link'];
                $link = $baseLink . '?name=' . urlencode($user->full_name) . '&email=' . urlencode($user->email);
            } else {
                $link = 'javascript:void(0)';
            }
        } else {
            $link = 'javascript:void(0)';
        }
    @endphp

    <a href="{{ $link }}" target="_blank">
        <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
            <i data-lucide="phone"></i>
        </div>
        <h4 class="mb-2 text-theme-lg font-medium text-gray-800 dark:text-white/90">
            {{ __('Personal Consultation') }}
        </h4>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('Book a one-on-one session with our experts for tailored advice and personalized guidance.') }}
        </p>
    </a>
</div>