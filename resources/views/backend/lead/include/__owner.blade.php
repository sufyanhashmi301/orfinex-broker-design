<div class="flex items-center">
    <div class="flex-none">
        <div class="w-8 h-8 rounded-[100%] ltr:mr-3 rtl:ml-3">
            <img src="{{ getFilteredPath($owner['avatar'], 'global/materials/user.png') }}" alt="" class="w-full h-full rounded-[100%] object-cover">
        </div>
    </div>
    <div class="flex-1 text-start">
        <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">
            {{ $owner['first_name'].' '.$owner['last_name'] }}
        </h4>
    </div>
</div>
