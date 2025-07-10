<div>
    <div class="relative">
        <div class="dropdown relative">
            <button class="text-xl text-center block w-full " type="button" id="tableDropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                <iconify-icon icon="heroicons-outline:dots-vertical"></iconify-icon>
            </button>
            <ul class="dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                @can('customer-edit')
                    <li>
                        <a href="{{route('admin.user.edit',$id)}}"
                           class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse">
                            <iconify-icon icon="clarity:note-edit-line"></iconify-icon>
                            <span>{{ __('Edit User') }}</span>
                        </a>
                    </li>
                @endcan
                @can('customer-mail-send')
                    <li>
                        <a href="#" type="button"
                           class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse send-mail"
                           data-id="{{$id}}"
                           data-name="{{ $first_name.' '. $last_name }}">
                            <iconify-icon icon="lucide:mail"></iconify-icon>
                            <span>{{ __('Send Email') }}</span>
                        </a>
                    </li>
                @endcan
                @can('customer-change-password')
                    <li>
                        <a href="#" type="button"
                           class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse reset-password-btn"
                           data-id="{{ $id }}"
                           data-name="{{ $first_name.' '.$last_name }}"
                           data-email="{{ $email }}">
                            <iconify-icon icon="lucide:lock"></iconify-icon>
                            <span>{{ __('Reset password') }}</span>
                        </a>
                    </li>
                @endcan
                @can('transaction-list')
                    <li>
                        <a href="{{ route('admin.transactions.user-summary', $id) }}" target="_blank" class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse">
                            <iconify-icon icon="lucide:chart-pie"></iconify-icon>
                            <span>{{ __('Payment stats') }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.referral-network.report', ['email' => $email]) }}" target="_blank" class="hover:bg-slate-900 dark:hover:bg-slate-600 dark:hover:bg-opacity-70 hover:text-white w-full border-b border-b-gray-500 border-opacity-10 px-4 py-2 text-sm dark:text-slate-300 last:mb-0 cursor-pointer first:rounded-t last:rounded-b flex space-x-2 items-center capitalize rtl:space-x-reverse">
                            <iconify-icon icon="lucide:network"></iconify-icon>
                            <span>{{ __('Network stats') }}</span>
                        </a>
                    </li>
                @endcan
            </ul>
        </div>
    </div>
</div>
