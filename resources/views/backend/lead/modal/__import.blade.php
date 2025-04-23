<div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
     id="importModal"
     tabindex="-1"
     aria-labelledby="importModal"
     aria-hidden="true"
>
    <div class="modal-dialog modal-lg top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
        <div class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white dark:bg-dark bg-clip-padding rounded-md outline-none text-current">
            <div class="modal-body">
                <div class="flex items-center justify-between p-5">
                    <h3 class="text-xl font-medium dark:text-white capitalize">
                        {{ __('Import Leads') }}
                    </h3>
                    <button type="button" class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-slate-600 dark:hover:text-white" data-bs-dismiss="modal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="#000000" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="popup-body-text p-5 pt-0">
                    <div class="alert alert-warning light-mode mb-5">
                        {{ __('Date format should be in Y-m-d (e.g. 2022-04-21) format. Make sure the date format is correct in the CSV file.') }}
                        <a href="{{ asset('global/json/leads_data.csv') }}" class="router-link-active router-link-exact-active underline text-alert-warning-900 dark:text-slate-300" download>
                            {{ __('Download Sample Import File') }}
                        </a>
                    </div>
                    <form action="{{ route('admin.lead.import') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="input-area">
                            <label for="" class="form-label">
                                {{ __('Upload File:') }}
                            </label>
                            <div class="wrap-custom-file h-full">
                                <input
                                    type="file"
                                    name="import_file"
                                    id="import-file"
                                    accept=".xls, .xlsx, .csv"
                                />
                                <label for="import-file">
                                    <img
                                        class="upload-icon"
                                        src="{{asset('global/materials/upload.svg')}}"
                                        alt=""
                                    />
                                    <span>{{ __('Upload File') }}</span>
                                </label>
                            </div>
                            <p class="text-xs dark:text-white">
                                file must be a file of type: <span class="font-medium">xls, xlsx, csv</span> format.
                            </p>
                        </div>
                        <div class="action-btns text-right mt-10">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __('Import Leads') }}
                            </button>
                            <a
                                href="#"
                                class="btn btn-danger inline-flex items-center justify-center"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                {{ __('Close') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
