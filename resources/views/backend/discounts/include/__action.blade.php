<div class="dropdown relative">
    <div class="groupButtons tableActionsDropdown">
        <a href="javascript:void(0)" data-id="{{ $id }}" class="btn btn-sm inline-flex justify-center btn-outline-light" data-bs-toggle="modal" data-bs-target="#editDiscountModal" onclick="editDiscount({{ $id }})">
            <iconify-icon class="text-base mr-2" icon="lucide:pencil"></iconify-icon>
            {{ __('Edit') }}
        </a>
        <button class="btn btn-sm inline-flex justify-center btn-outline-light actionsDropdownBtn" type="button" id="actionDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
            <iconify-icon class="leading-none text-lg" icon="ic:round-keyboard-arrow-down"></iconify-icon>
        </button>
        <ul class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
            <li>
                <a href="javascript:void(0)" data-id="{{ $id }}" data-name="{{ $code_name }}" data-bs-toggle="modal" data-bs-target="#deleteDiscountModal" onclick="deleteDiscount({{ $id }}, '{{ $code_name }}')" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-dark dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal">
                    <iconify-icon icon="lucide:trash" class="relative top-[2px] text-base mr-1"></iconify-icon>
                    {{ __('Delete') }}
                </a>
            </li>
            <li>
                <a href="#" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-dark dark:hover:text-white font-inter text-sm text-slate-600 dark:text-white font-normal">
                    <iconify-icon icon="mdi:do-not-disturb-alt" class="relative top-[2px] text-base mr-1"></iconify-icon>
                    {{ __('Disable') }}
                </a>
            </li>
        </ul>
    </div>
</div>
