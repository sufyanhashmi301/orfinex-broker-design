<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto" id="accountDetails" tabindex="-1" aria-labelledby="accountDetails" aria-hidden="true">
    <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
        rounded-md outline-none text-current">
            <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600">
                    <div>
                        <h3 class="text-xl font-medium dark:text-white capitalize" id="account-info-account_name">
                            {{ __('Account Title') }}
                        </h3>
                        <p class="dark:text-white">
                            <span id="account-info-login"></span>/<span id="account-schema-title"></span>/<span id="account-type"></span>
                        </p>
                    </div>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5 fill-black dark:fill-white" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <ul class="account-details-list divide-y divide-slate-100 dark:divide-slate-700 h-full">
                        <li class="flex items-center py-3">
                            <span class="font-medium dark:text-white">Server :</span>
                            <span class="ml-auto dark:text-white">
                                <span id="account-info-server">Banex Capital</span>
                            </span>
                        </li>
                        <li class="flex items-center py-3">
                            <span class="font-medium dark:text-white">Leverage :</span>
                            <span class="ml-auto dark:text-white">
                                1:<span id="account-info-leverage" class="dropdown-update-leverage"
                                        data-login="" data-id=""
                                        data-action="{{route('user.forex.get.leverage')}}"></span>
                            </span>
                        </li>
                        <li class="flex items-center py-3">
                            <span class="font-medium dark:text-white">Balance :</span>
                            <span class="ml-auto dark:text-white" id="account-info-balance">16.45</span>
                        </li>
                        <li class="flex items-center py-3">
                            <span class="font-medium dark:text-white">Free Margin :</span>
                            <span class="ml-auto dark:text-white" id="account-info-free-margin">24.68</span>
                        </li>
                        <li class="flex items-center py-3">
                            <span class="font-medium dark:text-white">Equity :</span>
                            <span class="ml-auto dark:text-white">
                                <span id="account-info-equity">24.68</span>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
