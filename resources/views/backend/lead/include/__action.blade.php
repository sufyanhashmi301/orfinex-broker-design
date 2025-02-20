<div class="dropdown">
    <button class="btn btn-sm inline-flex justify-center btn-outline-dark items-center cursor-default relative !pr-14" type="button" id="cardDropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
        {{ __('actions') }}
        <span class="cursor-pointer absolute ltr:border-l rtl:border-r border-dark-500 h-full ltr:right-0 rtl:left-0 px-2 flex items-center justify-center leading-none">
            <iconify-icon class="leading-none text-xl" icon="ic:round-keyboard-arrow-down"></iconify-icon>
        </span>
    </button>
    <ul class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
        <li>
            <a href="{{ route('admin.lead.show', $id) }}" class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse">
                <iconify-icon icon="lucide:eye"></iconify-icon>
                <span>{{ __('View') }}</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.lead.edit', $id) }}" class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse">
                <iconify-icon icon="clarity:note-edit-line"></iconify-icon>
                <span>{{ __('Edit') }}</span>
            </a>
        </li>
        <li>
            <a href="#" type="button" data-id="{{ $id }}" class="deleteLeadBtn hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse">
                <iconify-icon icon="fluent:delete-28-regular"></iconify-icon>
                <span>{{ __('Delete') }}</span>
            </a>
        </li>
        <li>
            <a href="#" type="button" data-lead-id="{{ $id }}" class="loseLeadBtn hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse">
                <iconify-icon icon="material-symbols:close-rounded"></iconify-icon>
                <span>{{ __('Close As Lose') }}</span>
            </a>
        </li>
    </ul>
</div>
