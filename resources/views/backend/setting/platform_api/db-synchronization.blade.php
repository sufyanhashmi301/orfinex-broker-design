@extends('backend.setting.platform_api.index')
@section('title')
    {{ __('Database Synchronization') }}
@endsection
@section('title-desc')
    {{ __('Effortlessly connect and synchronize your application with a variety of database types. Choose your database system associated with your trading platform to ensure seamless integration and real-time data synchronization for optimal performance.') }}
@endsection
@section('platform-api-content')
    <div class="innerMenu card p-2 mb-5">
        <ul class="nav nav-pills grid md:grid-cols-2 grid-cols-1 gap-3 pl-0">
            <li class="nav-item w-full">
                <a href="javascript:;" class="nav-link relative flex flex-col items-center justify-center font-medium font-Inter text-base text-center leading-tight capitalize rounded-md p-5 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300" data-route="mysql">
                    <img src="https://cdn.brokeret.com/crm-assets/admin/db/mysql.webp" class="h-20" alt="">
                    <div class="absolute top-3 right-3">
                        <span class="badge badge-primary capitalize">
                            {{ __('Recommended') }}
                        </span>
                    </div>
                </a>
            </li>
            <li class="nav-item w-full">
                <a href="javascript:;" class="nav-link flex flex-col items-center justify-center font-medium font-Inter text-base text-center leading-tight capitalize rounded-md p-5 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300" data-route="postgreSQL">
                    <img src="https://cdn.brokeret.com/crm-assets/admin/db/postgreSQL.webp" class="h-20" alt="">
                </a>
            </li>
            <li class="nav-item w-full">
                <a href="javascript:;" class="nav-link flex flex-col items-center justify-center font-medium font-Inter text-base text-center leading-tight capitalize rounded-md p-5 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300" data-route="sqlserver">
                    <img src="https://cdn.brokeret.com/crm-assets/admin/db/sqlserver.webp" class="h-20" alt="">
                </a>
            </li>
            <li class="nav-item w-full">
                <a href="javascript:;" class="nav-link flex flex-col items-center justify-center font-medium font-Inter text-base text-center leading-tight capitalize rounded-md p-5 focus:outline-none focus:ring-0 dark:bg-slate-900 dark:text-slate-300" data-route="oracle">
                    <img src="https://cdn.brokeret.com/crm-assets/admin/db/oracle.webp" class="h-20" alt="">
                </a>
            </li>
        </ul>
    </div>
    <div class="card form-card hidden" id="form-mysql">
        <div class="card-body p-6">
            <form action="{{ route('admin.settings.update') }}" method="post" id="db-credentials-form">
                @csrf
                <input type="hidden" name="section" value="mt5_db_credentials">
                <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-5">
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('Database Host') }}
                        </label>
                        <input type="text" name="database_host" class=" form-control " value="{{ setting('database_host','mt5_db_credentials') }}" placeholder="Database Host">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('Database Port') }}
                        </label>
                        <input type="text" name="database_port" class=" form-control " value="{{ setting('database_port','mt5_db_credentials') }}" placeholder="3306">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('Database Name') }}
                        </label>
                        <input type="text" name="database_name" class=" form-control " value="{{ setting('database_name','mt5_db_credentials') }}" placeholder="Database Name">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('Database Username') }}
                        </label>
                        <input type="text" name="database_username" class=" form-control " value="{{ setting('database_username','mt5_db_credentials') }}" placeholder="Database Username">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('Database Password') }}
                        </label>
                        <input type="password" name="database_password" class=" form-control " value="{{ setting('database_password','mt5_db_credentials') }}" placeholder="********">
                    </div>
                </div>
                <div class="flex justify-between mt-10">
                    <button type="submit" class="btn btn-dark inline-flex items-center justify-center">
                        {{ __('Save Changes') }}
                    </button>
                    <button type="button" class="btn btn-outline-dark inline-flex items-center justify-center" id="test-connection-btn">
                        {{ __('Test Connection') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card form-card hidden" id="form-postgreSQL">
        <div class="card-body basicTable_wrapper items-center justify-center gap-5 p-6">
            <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            <p class="text-xl text-center font-semibold dark:text-white">
                {{ __('Feature Not Available') }}
            </p>
            <p class="text-lg text-center text-slate-600 dark:text-slate-100">
                {{ __('This feature is not currently associated with your account or plan. To unlock this feature, please contact us to upgrade your account or activate it.') }}
                <span class="text-sm block mt-2">
                    {{ __('For assistance, reach out to our support team or visit ') }}
                    <a href="https://brokeret.com/" class="btn-link" target="_blank">{{ __('www.brokeret.com') }}</a>
                </span>
            </p>
        </div>
    </div>
    <div class="card form-card hidden" id="form-sqlserver">
        <div class="card-body basicTable_wrapper items-center justify-center gap-5 p-6">
            <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            <p class="text-xl text-center font-semibold dark:text-white">
                {{ __('Feature Not Available') }}
            </p>
            <p class="text-lg text-center text-slate-600 dark:text-slate-100">
                {{ __('This feature is not currently associated with your account or plan. To unlock this feature, please contact us to upgrade your account or activate it.') }}
                <span class="text-sm block mt-2">
                    {{ __('For assistance, reach out to our support team or visit ') }}
                    <a href="https://brokeret.com/" class="btn-link" target="_blank">{{ __('www.brokeret.com') }}</a>
                </span>
            </p>
        </div>
    </div>
    <div class="card form-card hidden" id="form-oracle">
        <div class="card-body basicTable_wrapper items-center justify-center gap-5 p-6">
            <svg width="52" height="53" viewBox="0 0 52 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M26 19.875V30.9167" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M25.9999 47.2804H12.8699C5.3516 47.2804 2.20994 41.8037 5.84994 35.1125L12.6099 22.7017L18.9799 11.0417C22.8366 3.95291 29.1633 3.95291 33.0199 11.0417L39.3899 22.7237L46.1499 35.1346C49.7899 41.8258 46.6266 47.3025 39.1299 47.3025H25.9999V47.2804Z" stroke="#FF0000" stroke-opacity="0.66" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M25.988 37.5417H26.0075" stroke="#FF0000" stroke-opacity="0.66" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            <p class="text-xl text-center font-semibold dark:text-white">
                {{ __('Feature Not Available') }}
            </p>
            <p class="text-lg text-center text-slate-600 dark:text-slate-100">
                {{ __('This feature is not currently associated with your account or plan. To unlock this feature, please contact us to upgrade your account or activate it.') }}
                <span class="text-sm block mt-2">
                    {{ __('For assistance, reach out to our support team or visit ') }}
                    <a href="https://brokeret.com/" class="btn-link" target="_blank">{{ __('www.brokeret.com') }}</a>
                </span>
            </p>
        </div>
    </div>

    <div id="notification-container" class="fixed top-0 right-0 mt-4 mr-4 space-y-2 z-50">
        <!-- Notifications will be added here dynamically -->
    </div>
@endsection
@section('style')
    <style>
        .nav-pills .nav-link.active {
            background: transparent;
            border: 1px solid;
            border-color: rgba({{ implode(' ', getColorFromSettings('primary_color')) }});
        }
    </style>
@endsection
@section('platform-script')
    <script>
        $(document).ready(function () {
            $('.nav .nav-link').click(function () {
                var clickedBtn = $(this).data('route');

                $('.form-card').addClass('hidden');
                $('.nav .nav-link').removeClass('active');

                $('#form-' + clickedBtn).removeClass('hidden');
                $(this).addClass('active');
            });
        });

        function showNotification(message, type) {
            const container = document.getElementById('notification-container');

            // Create a new notification element
            const notification = document.createElement('div');
            notification.className = `p-4 mb-2 rounded-md text-white text-sm ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
            notification.textContent = message;

            // Append the notification to the container
            container.appendChild(notification);

            // Automatically remove the notification after a few seconds
            setTimeout(() => {
                notification.remove();
            }, 5000); // 5 seconds
        }

        document.getElementById('test-connection-btn').addEventListener('click', function() {
            const form = document.getElementById('db-credentials-form');
            const formData = new FormData(form);

            fetch('{{ route('admin.settings.testConnection') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => showNotification('An error occurred while testing the connection.', 'error'));
        });
    </script>
@endsection
