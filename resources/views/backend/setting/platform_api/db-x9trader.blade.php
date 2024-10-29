@extends('backend.setting.platform_api.index')
@section('title')
    <div>
        <h4 class="font-medium text-xl capitalize dark:text-white inline-block ltr:pr-4 rtl:pl-4 mb-1">
            {{ __('X9 Database Credentials') }}
        </h4>
        <p class="text-sm text-slate-500 dark:text-slate-300">
            {{ __('Configure your database connection settings') }}
        </p>
    </div>
@endsection
@section('platform-api-content')
    <div class="card">
        <div class="card-body p-6">
            <form action="{{ route('admin.settings.update') }}" method="post" id="db-credentials-form">
                @csrf
                <input type="hidden" name="section" value="mt5_db_credentials">
                <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-5">
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('Database Host') }}
                        </label>
                        <input type="text" name="x9_database_host" class=" form-control " value="{{ setting('x9_database_host','x9_db_credentials') }}" placeholder="Database Host">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('Database Port') }}
                        </label>
                        <input type="text" name="x9_database_port" class=" form-control " value="{{ setting('x9_database_port','x9_db_credentials') }}" placeholder="3306">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('Database Name') }}
                        </label>
                        <input type="text" name="x9_database_name" class=" form-control " value="{{ setting('x9_database_name','x9_db_credentials') }}" placeholder="Database Name">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('Database Username') }}
                        </label>
                        <input type="text" name="x9_database_username" class=" form-control " value="{{ setting('x9_database_username','x9_db_credentials') }}" placeholder="Database Username">
                    </div>
                    <div class="input-area relative">
                        <label for="" class="form-label">
                            {{ __('Database Password') }}
                        </label>
                        <input type="password" name="x9_database_password" class=" form-control " value="{{ setting('x9_database_password','x9_db_credentials') }}" placeholder="********">
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
    <div id="notification-container" class="fixed top-0 right-0 mt-4 mr-4 space-y-2 z-50">
        <!-- Notifications will be added here dynamically -->
    </div>
@endsection

@section('platform-script')
    <script>
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
