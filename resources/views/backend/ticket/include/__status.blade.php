<div class="dropdown">
    <button class="btn btn-sm inline-flex justify-between btn-outline-light items-center" type="button" id="statusDropdownMenuButton_{{ $id }}" data-bs-toggle="dropdown" aria-expanded="false">
        <span>{{ $status }}</span>
        <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="ic:round-keyboard-arrow-down"></iconify-icon>
    </button>
    <ul class="dropdown-menu absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none" style="min-width: 110px;">
        <li>
            <form action="{{ route('admin.ticket.close', $id) }}" method="POST" class="block w-full">
                @csrf
                @method('PATCH')
                <button class="text-left text-slate-600 dark:text-white block w-full font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white @if($status == 'closed') bg-slate-100 dark:bg-slate-600 dark:text-white @endif">
                    {{ __('Close') }}
                </button>
            </form>
        </li>
        <li>
            <form action="{{ route('admin.ticket.reopen', $id) }}" method="POST" class="block w-full">
                @csrf
                @method('PATCH')
                <button class="text-left text-slate-600 dark:text-white block w-full font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white @if($status == 'open') bg-slate-100 dark:bg-slate-600 dark:text-white @endif">
                    {{ __('Open') }}
                </button>
            </form>
        </li>
        <li>
            <form action="{{ route('admin.ticket.archive', $id) }}" method="POST" class="block w-full">
                @csrf
                @method('PATCH')
                <button class="text-left text-slate-600 dark:text-white block w-full font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white @if($status == 'archived') bg-slate-100 dark:bg-slate-600 dark:text-white @endif">
                    {{ __('Archive') }}
                </button>
            </form>
        </li>
    </ul>
</div>
