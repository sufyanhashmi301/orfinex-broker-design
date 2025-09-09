@foreach($wallets as $raw)
    <div class="grid p-4 gap-2 rounded-lg border border-gray-200 cursor-pointer hover:shadow-theme-lg dark:border-gray-800
        [grid-template:'header_amount'20px_'header_status'_'from-to_from-to'_/minmax(0,1fr)_minmax(0,min-content)]
        lg:p-6 
        lg:[grid-template:'header_from-to_status_amount'_/minmax(0,3fr)_minmax(0,4fr)_minmax(0,1fr)_minmax(0,1fr)]
        lg:gap-x-4">
        <div class="flex items-center gap-3" style="grid-area: header;">
            <div class="">
                <i data-lucide="download" class="text-success-500"></i>
            </div>
            <div class="space-y-0.5">
                <span class="block text-nowrap text-gray-800 text-theme-sm dark:text-white/90">
                    {{ $raw->description }}
                </span>
                <span class="block text-gray-500 text-theme-xs dark:text-gray-400">
                    {{ $raw->created_at }}
                </span>
                <span class="block text-gray-500 text-theme-xs dark:text-gray-400">
                    {{ __('Transaction ID :tnx', ['tnx' => $raw->tnx]) }}
                </span>
            </div>
        </div>

        <div class="flex items-center gap-3" style="grid-area: from-to;">
            <div class="flex items-center gap-3">
                <span class="text-nowrap text-gray-500 text-theme-sm dark:text-gray-400">
                    {{ transaction_method_name($raw) }}
                </span>
            </div>
            <i data-lucide="move-right" class="text-gray-500 dark:text-gray-400"></i>
            <div class="flex items-center gap-3">
                <span class="text-gray-500 text-theme-sm dark:text-gray-400">
                    {{ $raw->target_id }}
                </span>
            </div>
        </div>

        <div class="flex items-center justify-end" style="grid-area: amount;">
            <span class="font-medium text-gray-800 dark:text-white/90">
                +{{$raw->amount.' '.$currency }}
            </span>
        </div>
        
        <div class="flex items-center gap-3" style="grid-area: status;">
            @switch($raw->status->value)
                @case('pending')
                <x-frontend::badge variant="warning" style="light" size="md">
                    {{ __('Pending') }}
                </x-frontend::badge>
                @break
                @case('success')
                <x-frontend::badge variant="success" style="light" size="md">
                    {{ __('Success') }}
                </x-frontend::badge>
                @break
                @case('failed')
                <x-frontend::badge variant="error" style="light" size="md">
                    {{ __('Canceled') }}
                </x-frontend::badge>
                @break
            @endswitch
        </div>
    </div>
@endforeach