@extends('backend.setting.communication.index')

@section('communication-content')
    <?php
        $section = 'smtp_monitoring';
        $fields = config('setting.smtp_monitoring');
        //   dd($fields);
    ?>

    <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
        <div>
            <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4">
                {{ __('SMTP Monitoring Settings') }}
            </h4>
            <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">
                {{ __('Configure SMTP failure detection and alerting preferences') }}
            </p>
        </div>
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ route('admin.settings.mail') }}" class="btn btn-sm btn-outline-dark inline-flex items-center justify-center">
                <iconify-icon icon="lucide:settings" class="text-base ltr:mr-2 rtl:ml-2"></iconify-icon>
                <span>{{ __('Configuration') }}</span>
            </a>
            <a href="{{ route('admin.smtp.monitoring.logs') }}" class="btn btn-sm btn-outline-dark inline-flex items-center justify-center">
                <iconify-icon icon="heroicons:list-bullet-20-solid" class="text-base ltr:mr-2 rtl:ml-2"></iconify-icon>
                <span>{{ __('View Logs') }}</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-5">
        <div class="xl:col-span-8 lg:col-span-7 col-span-12">
            <div class="card">
                <div class="card-body px-6 pb-6">
                    <form action="{{ route('admin.settings.update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="section" value="{{$section}}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @foreach($fields['elements'] as $field)
                                @if($field['type'] == 'checkbox')
                                    <div class="col-span-2 my-6">
                                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                                            <div>
                                                <label class="font-semibold text-slate-700 dark:text-slate-300">
                                                    {{ $field['label'] }}
                                                </label>
                                                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                                    {{ $field['description'] }}
                                                </p>
                                            </div>
                                            <div>
                                                <div class="form-switch ps-0">
                                                    <input type="hidden" value="0" name="{{ $field['name'] }}">
                                                    <label
                                                        class="relative inline-flex h-6 w-[46px] items-center rounded-full transition-all duration-150 cursor-pointer">
                                                        <input type="checkbox" name="{{ $field['name'] }}" value="1" class="sr-only peer"
                                                            @checked(oldSetting($field['name'], $section, $field['value']))>
                                                        <span
                                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none ring-0 rounded-full peer dark:bg-gray-900 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-black-500"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($field['type'] == 'number')
                                    <div>
                                        <label class="form-label" for="{{ $field['name'] }}">
                                            {{ $field['label'] }}
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" 
                                                name="{{ $field['name'] }}" 
                                                id="{{ $field['name'] }}" 
                                                value="{{ oldSetting($field['name'], $section, $field['value']) }}" 
                                                class="form-control" required>
                                        <p class="text-xs text-slate-500 mt-1">
                                            {{ $field['description'] }}
                                        </p>
                                    </div>
                                @elseif($field['type'] == 'select')
                                    <div>
                                        <label class="form-label" for="{{ $field['name'] }}">
                                            {{ $field['label'] }}
                                        </label>
                                        <select name="{{ $field['name'] }}" id="{{ $field['name'] }}" class="form-control">
                                            @foreach($field['options'] as $value => $label)
                                                <option value="{{ $value }}" 
                                                    {{ oldSetting($field['name'], $section, $field['value']) == $value ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="text-xs text-slate-500 mt-1">
                                            {{ $field['description'] }}
                                        </p>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-4 mt-10">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center space-x-2">
                                <iconify-icon icon="lucide:check" class="text-lg"></iconify-icon>
                                <span>{{ __('Save Settings') }}</span>
                            </button>

                            @if(session('smtp_failure_active'))
                                <button type="button"
                                    onclick="handleClearAlert(this)"
                                    class="btn btn-danger inline-flex items-center justify-center space-x-2">
                                    <iconify-icon icon="lucide:x" class="text-lg"></iconify-icon>
                                    <span>{{ __('Clear Active Alert') }}</span>
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="xl:col-span-4 lg:col-span-5 col-span-12">
            <div class="card h-full">
                <div class="card-header">
                    <h4 class="card-title">{{ __('Statistic') }}</h4>
                </div>
                <div class="card-body p-6">
                    <ul class="divide-y divide-slate-100 dark:divide-slate-700 space-y-2">
                        <li class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-300 py-2 first:uppercase">
                            <div class="flex justify-between">
                                <span>{{ __('Alert Status') }}</span>
                                <span>{{ session('smtp_failure_active') ? 'Active' : 'Normal' }}</span>
                            </div>
                        </li>
                        <li class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-300 py-2 first:uppercase">
                            <div class="flex justify-between">
                                <span>{{ __('Total Failures') }}</span>
                                <span>{{ \App\Models\SmtpFailureLog::count() }}</span>
                            </div>
                        </li>
                        <li class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-300 py-2 first:uppercase">
                            <div class="flex justify-between">
                                <span>{{ __('Last 24 Hours') }}</span>
                                <span>{{ \App\Models\SmtpFailureLog::where('created_at', '>=', now()->subDay())->count() }}</span>
                            </div>
                        </li>
                        <li class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-300 py-2 first:uppercase">
                            <div class="flex justify-between">
                                <span>{{ __('Last 7 Days') }}</span>
                                <span>{{ \App\Models\SmtpFailureLog::where('created_at', '>=', now()->subDays(7))->count() }}</span>
                            </div>
                        </li>
                        <li class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-300 py-2 first:uppercase">
                            <div class="flex justify-between">
                                <span>{{ __('This Month') }}</span>
                                <span>{{ \App\Models\SmtpFailureLog::where('created_at', '>=', now()->startOfMonth())->count() }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('single-script')
    <script>
        // Reuse the same clear alert logic from layouts/app.blade.php
        function handleClearAlert(btn) {
            if (!confirm('Are you sure you want to clear the active SMTP failure alert?')) {
                return;
            }
            
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<iconify-icon icon="" class="text-lg animate-spin"></iconify-icon><span>Clearing...</span>';
            
            fetch('{{ route('admin.smtp.monitoring.clear-alert') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    tNotify('success', 'Alert cleared successfully');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                    tNotify('error', 'Failed to clear alert');
                }
            })
            .catch(error => {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                tNotify('error', 'Failed to clear alert: ' + error.message);
            });
        }
    </script>
@endpush
