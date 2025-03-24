@extends('backend.setting.payment.index')
@section('title')
   {{ __('All Bonuses') }}
@endsection
@section('payment-content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
        @can('bonus-create')
        <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
            <a href="{{ route('admin.bonus.create') }}" class="btn btn-dark btn-sm inline-flex items-center justify-center" type="button">
                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                {{ __('Add New Bonus') }}
            </a>
        </div>
        @endcan
    </div>

    @include('backend.bonus.include.__menu')

    <div class="card">
        <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class=" col-span-8  hidden"></span>
                <span class="  col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden ">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="data-table">
                            <thead>
                                <tr>
                                    <th scope="col" class="table-th">{{ __('Bonus Title') }}</th>
                                    <th scope="col" class="table-th">{{ __('Type') }}</th>
                                    <th scope="col" class="table-th">{{ __('Process') }}</th>
                                    <th scope="col" class="table-th">{{ __('Applicable by') }}</th>
                                    <th scope="col" class="table-th">{{ __('Start Date') }}</th>
                                    <th scope="col" class="table-th">{{ __('End Date') }}</th>
                                    <th scope="col" class="table-th">{{ __('Forex Account Types') }}</th>
                                    <th scope="col" class="table-th">{{ __('Status') }}</th>
                                    <th scope="col" class="table-th">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach ($bonuses as $bonus)
                                    <tr>
                                        <td class="table-td">
                                            <strong>{{ $bonus->bonus_name }}</strong>
                                        </td>
                                        <td class="table-td">
                                            <span class="badge bg-slate-900 text-white capitalize">{{ $bonus->type == 'percentage' ? 'In Percentage' : 'In Fixed Amount' }}</span>
                                        </td>
                                        <td class="table-td">On <span style="text-transform: capitalize">{{ $bonus->process }}</span></td>
                                        <td class="table-td">{{ $bonus->applicable_by == 'auto' ? 'Auto Apply' : 'Client Apply' }}</td>
                                        <td class="table-td">{{ date('M d Y', strtotime($bonus->start_date)) }}</td>
                                        <td class="table-td">{{ date('M d Y', strtotime($bonus->last_date)) }}</td>
                                        <td class="table-td">
                                            @foreach ($bonus->forex_schemas as $schema)
                                                <span class="badge bg-slate-900 text-white capitalize">{{ $schema->title }}</span>
                                            @endforeach

                                        </td>
                                        <td class="table-td">
                                            <span class="badge bg-{{ $bonus->status == '1' ? 'success' : 'danger' }} text-white capitalize">{{ $bonus->status == '1' ? 'Active' : 'Inactive' }}</span>
                                        </td>
                                        <td class="table-td">
                                            @can('bonus-edit')
                                            <a href="{{ route('admin.bonus.edit', ["bonu" => $bonus->id]) }}" class="action-btn mr-1" data-bs-toggle="tooltip" style="float: left" title="" data-bs-original-title="Edit Record" aria-label="Edit Record" target="_blank">
                                                <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                            </a>
                                            @endcan

                                            @can('bonus-delete')
                                            <button type="button" data-id="{{ $bonus->id }}" data-name="{{ $bonus->bonus_name }}" class="action-btn deleteBonus" style="float: left">
                                                <iconify-icon icon="lucide:trash-2"></iconify-icon>
                                            </button>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for Delete deleteBonus -->
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
        id="deleteBonus" tabindex="-1" aria-labelledby="deleteBonus" aria-hidden="true">
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
            <div
                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div class="modal-body p-6 py-8 text-center space-y-5">
                    <div
                        class="info-icon h-16 w-16 rounded-full inline-flex items-center justify-center bg-danger text-danger bg-opacity-30">
                        <iconify-icon class="text-4xl" icon="lucide:alert-triangle"></iconify-icon>
                    </div>
                    <div class="title">
                        <h4 class="text-xl font-medium dark:text-white capitalize">
                            {{ __('Are you sure?') }}
                        </h4>
                    </div>
                    <p>
                        {{ __('Do you want to Delete') }}
                        <strong class="name"></strong> {{ __(' Bonus?') }}
                    </p>
                    <form method="post" id="bonusDeleteForm">
                        @method('DELETE')
                        @csrf
                        <div class="action-btns">
                            <button type="submit" class="btn btn-dark inline-flex items-center justify-center mr-2">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:check"></iconify-icon>
                                {{ __(' Confirm') }}
                            </button>
                            <a href="" class="btn btn-danger inline-flex items-center justify-center" type="button"
                                class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2" icon="lucide:x"></iconify-icon>
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Delete deleteBonus-->
@endsection
@section('payment-script')
    <script>

        $('#data-table').DataTable().destroy();
        $('#data-table').DataTable({
            dom: "<'min-w-full't><'flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto'lip>",
            searching: false,
            lengthChange: false,
            info: true,
            language: {
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    previous: "<iconify-icon icon=\"ic:round-keyboard-arrow-left\"></iconify-icon>",
                    next: "<iconify-icon icon=\"ic:round-keyboard-arrow-right\"></iconify-icon>"
                },
                search: "Search:"
            },
            autoWidth: false,
        });


        $('.deleteBonus').on('click', function(e) {
            "use strict";
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            var url = '{{ route('admin.bonus.destroy', ':id') }}';
            url = url.replace(':id', id);
            $('#bonusDeleteForm').attr('action', url)

            $('.name').html(name);
            $('#deleteBonus').modal('show');
        })
    </script>
@endsection

