@extends('backend.setting.platform_api.index')
@section('title')
    {{ __('MySQL Database Credentials') }}
@endsection
@section('platform-content')
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
                        {{ __('Save') }}
                    </button>
                    <button type="button" class="btn btn-outline-dark inline-flex items-center justify-center" id="test-connection-btn">
                        {{ __('Test Connection') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('setting-script')
    <script>
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
                    if(data.status === 'success') {
                        displayMessage(data.message);
                    } else {
                        displayMessageError(data.message);
                    }
                })
                .catch(error => displayMessageError('An error occurred while testing the connection.'));
        });

        function displayMessage(message) {
            toastr.success(message, 'Success');
        }

        function displayMessageError(message) {
            toastr.error(message, 'Error');
        }
    </script>
@endsection
