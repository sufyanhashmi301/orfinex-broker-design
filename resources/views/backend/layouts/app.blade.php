<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" class="light">
@include('backend.include.__head')

<body class="font-inter dashcode-app" id="body_class">
    <div class="page-loader">
        <div class="dot bg-primary"></div>
        <div class="dot bg-primary"></div>
        <div class="dot bg-primary"></div>
    </div>
    <!--Full Layout-->
    <main class="app-wrapper">

        <x:notify-messages/>

        <!--Side Nav-->
        <div class="sidebar-wrapper group dark:shadow-slate-700">
            @include('backend.include.__side_nav')
        </div>
        <!--/Side Nav-->

        <div class="flex flex-col justify-between min-h-screen">
            <div>
                <!--Header-->
                @include('backend.include.__header')
                <!--/Header-->

                <div class="content-wrapper transition-all duration-150 ltr:ml-[248px] rtl:mr-[248px]" id="content_wrapper">
                    <!--Page Content-->
                    <div class="page-content">
                        <div class="transition-all duration-150 container-fluid" id="page_layout">
                            <div id="content_layout">
                                <div>
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Page Content-->
                </div>
            </div>
            <footer class="md:block sticky bottom-0" id="footer">
                <div class="site-footer px-6 text-slate-500 dark:text-slate-300 py-2 ltr:ml-[248px] rtl:mr-[248px]" style="height: 48px;">
                    <div class="flex items-center justify-between gap-5">
                        @if(!setting('is_whitelabel', 'global'))
                            <a href="https://brokeret.com/" target="_blank" class="text-primary font-semibold ml-1">
                                <img src="{{ asset('backend/images/brokeret_logo.png') }}" class="h-6 inline-flex" alt="">
                            </a>
                        @endif
                        <div class="ltr:md:text-right rtl:md:text-end text-center text-sm ml-auto">
                            <span class="toolTip onTop" style="line-height: 0" data-tippy-content="Your data is fully secure with advanced end-to-end encryption between you and your broker, ensuring that all sensitive client information and trading activities remain confidential. Technology Provider guarantees no access to or visibility of your encrypted data, safeguarding your privacy and trust.">
                                <span id="secure-data" style="display: inline-flex; width: 24px; height: 24px;"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </main>
    <!--/Full Layout-->

    @include('backend.include.__script')

    {{-- SMTP Failure Persistent Alert (Using existing Notify system) --}}
    @if(!empty($smtpFailureActive) && $smtpFailureActive)
    <script>
        $(document).ready(function() {
            // Show persistent SMTP failure notification using existing Notify system
            const smtpNotification = new Notify({
                status: 'error',
                title: '⚠️ SMTP Service Failure',
                text: `{{ $smtpFailureData['message'] ?? 'Email service is experiencing issues' }}<br><br>
                    <strong>Total Failures:</strong> {{ $smtpFailureData['count'] ?? 0 }}<br>
                    <strong>First detected:</strong> {{ $smtpFailureData['timestamp'] ?? 'Unknown' }}<br>
                    <strong>Last updated:</strong> {{ $smtpFailureData['last_updated'] ?? 'Unknown' }}<br><br>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.smtp.monitoring.logs') }}" class="btn btn-sm btn-outline-primary inline-flex items-center justify-center">View Logs</a>
                        <a href="{{ route('admin.smtp.monitoring.clear-alert') }}" class="btn btn-sm btn-outline-danger inline-flex items-center justify-center clear-smtp-alert">Clear Alert</a>
                    </div>`,
                effect: 'slide',
                speed: 300,
                customClass: 'smtp-failure-alert',
                customIcon: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.73 18 12 2 2.27 18Z M12 9v4 M12 17h.01"/></svg>',
                showIcon: false,
                showCloseButton: true,
                autoclose: true, // Persistent - won't auto-close
                autotimeout: 9000,
                gap: 20,
                distance: 20,
                type: 1,
                position: 'right top',
                customWrapper: '',
            });

            // Handle close button to clear session
            $(document).on('click', '.smtp-failure-alert .clear-smtp-alert', function(e) {
                e.preventDefault(); // Prevent default link navigation
                
                const $notification = $(this).closest('.notify');
                
                fetch('{{ route('admin.smtp.monitoring.clear-alert') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('SMTP failure alert cleared successfully');
                        // Close the notification
                        $notification.fadeOut(300, function() {
                            $(this).remove();
                        });
                    }
                })
                .catch(error => {
                    console.error('Failed to clear SMTP alert:', error);
                    // Still close notification even if request fails
                    $notification.fadeOut(300, function() {
                        $(this).remove();
                    });
                });
            });
        });
    </script>
    @endif

</body>
</html>
