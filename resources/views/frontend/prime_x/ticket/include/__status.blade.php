<span class="block text-left">
    <span class="inline-block text-center mx-auto py-1">
        <span class="flex items-center space-x-3 rtl:space-x-reverse">
            @if($status == 'open')
                <span class="h-[6px] w-[6px] bg-warning rounded-full inline-block ring-4 ring-opacity-30 ring-warning-500"></span>
                <span>{{ __('Opened') }}</span>
            @elseif($status == 'closed')
                <span class="h-[6px] w-[6px] bg-success rounded-full inline-block ring-4 ring-opacity-30 ring-success-500"></span>
                <span>{{ __('Completed') }}</span>
            @endif
        </span>
    </span>
</span>
