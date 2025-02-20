<ul class="contact-details__list space-y-3">
    <li class="text-sm text-slate-600 dark:text-slate-300 py-2">
        <div class="flex justify-between">
            <span class="font-medium">{{ __('Lead Contact:') }}</span>
            <span class="w-2/3">
                {{ $lead->first_name.' '.$lead->last_name }}
            </span>
        </div>
    </li>
    <li class="text-sm text-slate-600 dark:text-slate-300 py-2">
        <div class="flex justify-between">
            <span class="font-medium">{{ __('Email:') }}</span>
            <span class="w-2/3">
                {{ $lead->client_email }}
            </span>
        </div>
    </li>
    <li class="text-sm text-slate-600 dark:text-slate-300 py-2">
        <div class="flex justify-between">
            <span class="font-medium">{{ __('Mobile:') }}</span>
            <span class="w-2/3">
                {{ $lead->phone }}
            </span>
        </div>
    </li>
    <li class="text-sm text-slate-600 dark:text-slate-300 py-2">
        <div class="flex justify-between">
            <span class="font-medium">{{ __('Company Name:') }}</span>
            <span class="w-2/3">
                {{ $lead->company_name }}
            </span>
        </div>
    </li>
    <li class="text-sm text-slate-600 dark:text-slate-300 py-2">
        <div class="flex justify-between">
            <span class="font-medium">{{ __('Company Phone:') }}</span>
            <span class="w-2/3">{{ $lead->office_phone_number }}</span>
        </div>
    </li>
    <li class="text-sm text-slate-600 dark:text-slate-300 py-2">
        <div class="flex justify-between">
            <span class="font-medium">{{ __('Website:') }}</span>
            <span class="w-2/3">{{ $lead->website }}</span>
        </div>
    </li>
    <li class="text-sm text-slate-600 dark:text-slate-300 py-2">
        <div class="flex justify-between">
            <span class="font-medium">{{ __('Address:') }}</span>
            <span class="w-2/3">{{ $lead->address }}</span>
        </div>
    </li>
</ul>
