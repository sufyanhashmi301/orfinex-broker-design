<div class="space-y-6">
    {{-- Account Information --}}
    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4">
        <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-4">{{ __('Account Information') }}</h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-600 dark:text-slate-300">{{ __('Method Name:') }}</span>
                <span class="text-sm text-slate-900 dark:text-white">{{ $account->method_name }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-600 dark:text-slate-300">{{ __('Currency:') }}</span>
                <span class="text-sm text-slate-900 dark:text-white">{{ $account->method->currency ?? 'N/A' }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-600 dark:text-slate-300">{{ __('Status:') }}</span>
                <span class="text-sm">
                    @if($account->status === 'pending')
                        <span class="badge badge-warning capitalize">{{ __('Pending') }}</span>
                    @elseif($account->status === 'approved')
                        <span class="badge badge-success capitalize">{{ __('Approved') }}</span>
                    @elseif($account->status === 'rejected')
                        <span class="badge badge-danger capitalize">{{ __('Rejected') }}</span>
                    @endif
                </span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-600 dark:text-slate-300">{{ __('Created At:') }}</span>
                <span class="text-sm text-slate-900 dark:text-white">{{ $account->created_at->format('Y-m-d H:i:s') }}</span>
            </div>
        </div>
    </div>

    {{-- Account Details --}}
    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4">
        <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-4">{{ __('Account Details') }}</h3>
        <div class="space-y-3">
            @if(count($credentials) > 0)
                @foreach($credentials as $name => $field_data)
                    <div class="flex items-start justify-between">
                        <span class="text-sm font-medium text-slate-600 dark:text-slate-300">{{ $name }}:</span>
                        <div class="text-right max-w-xs">
                            @if(isset($field_data['type']) && $field_data['type'] == 'file')
                                @if(!empty($field_data['value']) && is_string($field_data['value']))
                                    <div class="space-y-2">
                                        <div class="h-32 bg-no-repeat bg-contain bg-center bg-slate-100 dark:bg-slate-700 rounded border"
                                             style="background-image: url('{{ asset($field_data['value']) }}')"></div>
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ asset($field_data['value']) }}" class="text-xs text-primary hover:underline" download>
                                                {{ __('Download') }}
                                            </a>
                                            <a href="{{ asset($field_data['value']) }}" class="text-xs text-primary hover:underline" target="_blank">
                                                {{ __('View') }}
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-sm text-slate-500 dark:text-slate-400">{{ __('No file uploaded') }}</span>
                                @endif
                            @else
                                <span class="text-sm text-slate-900 dark:text-white break-words">{{ $field_data['value'] ?? 'N/A' }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-4">
                    <span class="text-sm text-slate-500 dark:text-slate-400">{{ __('No account details available') }}</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Description (if exists) --}}
    @if(!empty($account->description))
    <div class="bg-slate-50 dark:bg-slate-800 rounded-lg p-4">
        <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-4">{{ __('Description') }}</h3>
        <div class="bg-white dark:bg-slate-700 rounded border p-3">
            <p class="text-sm text-slate-700 dark:text-slate-300">{{ $account->description }}</p>
        </div>
    </div>
    @endif
</div> 