<div class="grid md:grid-cols-3 grid-cols-1 gap-5 mt-6">
    <a href="mailto:{{ setting('support_email', 'global') }}" class="card text-center px-3 py-6 lg:px-10 lg:py-12">
        <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-[#E5F9FF] dark:bg-slate-900 text-info-500 mx-auto mb-4">
            <iconify-icon icon="lucide:mail"></iconify-icon>
        </div>
        <h6 class="text-xl text-slate-900 dark:text-white mb-2">
            {{ __('Email Support') }}
        </h6>
        <span class="block text-base text-slate-600 dark:text-white">
            {{ __('Get fast and reliable help directly in your inbox, perfect for detailed queries that need thorough answers.') }}
        </span>
    </a>

    <a href="{{ route('user.ticket.index') }}" class="card text-center px-3 py-6 lg:px-10 lg:py-12">
        <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-[#FFEDE6] dark:bg-slate-900 text-warning-500 mx-auto mb-4">
            <iconify-icon icon="lucide:message-circle-more"></iconify-icon>
        </div>
        <h6 class="text-xl text-slate-900 dark:text-white mb-2">
            {{ __('Direct Text Contact') }}
        </h6>
        <span class="block text-base text-slate-600 dark:text-white">
            {{ __('Reach us instantly through text for quick updates, clarifications, or immediate support.') }}
        </span>
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

    <a href="{{ $link }}" target="_blank" class="card text-center px-3 py-6 lg:px-10 lg:py-12">
        <div class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-[#EAE6FF] dark:bg-slate-900 text-[#5743BE] mx-auto mb-4">
            <iconify-icon icon="lucide:phone"></iconify-icon>
        </div>
        <h6 class="text-xl text-slate-900 dark:text-white mb-2">
            {{ __('Personal Consultation') }}
        </h6>
        <span class="block text-base text-slate-600 dark:text-white">
            {{ __('Book a one-on-one session with our experts for tailored advice and personalized guidance.') }}
        </span>
    </a>
</div>