<div class="tab-pane fade space-y-5" id="pills-note" role="tabpanel" aria-labelledby="pills-note-tab">
    @can('customer-basic-manage')
    <div class="flex justify-end items-center mb-3">
        <button class="btn btn-primary btn-sm inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#addNotesModal">
            {{ __('Add Notes') }}
        </button>
    </div>
    <div class="card">
        <div class="card-body p-6 pt-3">
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Description') }}</th>
                                    <th scope="col" class="table-th">{{ __('Added From') }}</th>
                                    <th scope="col" class="table-th">{{ __('Date Added') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                <tr>
                                    <td class="table-td">
                                        <span class="inline-flex max-w-xl">
                                            {{ __('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.') }}
                                        </span>
                                    </td>
                                    <td class="table-td">{{ __('Dante Rutherford') }}</td>
                                    <td class="table-td">{{ __('2024-07-23 14:24:23') }}</td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <a href="" class="action-btn">
                                                <iconify-icon icon="lucide:edit"></iconify-icon>
                                            </a>
                                            <button type="button" class="action-btn">
                                                <iconify-icon icon="lucide:trash"></iconify-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-td">
                                        <span class="inline-flex max-w-xl">
                                            {{ __('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.') }}
                                        </span>
                                    </td>
                                    <td class="table-td">{{ __('Dante Rutherford') }}</td>
                                    <td class="table-td">{{ __('2024-07-23 14:24:23') }}</td>
                                    <td class="table-td">
                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                            <a href="" class="action-btn">
                                                <iconify-icon icon="lucide:edit"></iconify-icon>
                                            </a>
                                            <button type="button" class="action-btn">
                                                <iconify-icon icon="lucide:trash"></iconify-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>

@include('backend.user.include.__add_notes')
