@extends('backend.setting.customer.index')
@section('title')
    {{ __('Customer Groups') }}
@endsection
@section('title-btns')
    <a href="{{route('admin.customer-groups.create')}}" class="btn btn-sm btn-primary inline-flex items-center justify-center">
        <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
        {{ __('Add New Group') }}
    </a>
@endsection
@section('customer-content')
    @if($customerGroups->isNotEmpty())
        <div class="card">
            <div class="card-body px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                    <span class=" col-span-8  hidden"></span>
                    <span class="  col-span-4 hidden"></span>
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden basicTable_wrapper">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                <thead>
                                    <tr>
                                        <th scope="col" class="table-th">{{ __('Group Name') }}</th>
                                        <th scope="col" class="table-th">{{ __('Status') }}</th>
                                        <th scope="col" class="table-th">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach($customerGroups as $customerGroup)
                                    <tr>
                                        <td class="table-td">
                                            <strong>{{ $customerGroup->name }}</strong>
                                        </td>
                                        <td class="table-td">
                                            @if( $customerGroup->status==1)
                                                <div class="badge bg-success text-success bg-opacity-30 capitalize">{{ __('Active') }}</div>
                                            @else
                                                <div class="badge bg-danger text-danger bg-opacity-30 capitalize">{{ __('Disabled') }}</div>
                                            @endif
                                        </td>
                                        <td class="table-td">
                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                <a href="{{ route('admin.customer-groups.edit',$customerGroup->id) }}" class="action-btn">
                                                    <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                                </a>
                                                <button type="button" data-id="{{ $customerGroup->id }}" data-name="{{ $customerGroup->name }}" class="action-btn deleteCustomerGroup">
                                                    <iconify-icon icon="lucide:trash-2"></iconify-icon>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 py-5 mt-auto">
                                <div>
                                    @php
                                        $from = $customerGroups->firstItem(); // The starting item number on the current page
                                        $to = $customerGroups->lastItem(); // The ending item number on the current page
                                        $total = $customerGroups->total(); // The total number of items
                                    @endphp

                                    <p class="text-sm text-gray-700">
                                        Showing
                                        <span class="font-medium">{{ $from }}</span>
                                        to
                                        <span class="font-medium">{{ $to }}</span>
                                        of
                                        <span class="font-medium">{{ $total }}</span>
                                        results
                                    </p>
                                </div>
                                {{ $customerGroups->links() }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @else
        <div class="card basicTable_wrapper items-center justify-center">
            <div class="card-body p-6">
                <div class="max-w-4xl mx-auto text-center">
                    <div id="lottie-container" style="display: inline-flex; width: 350px; height: 350px;"></div>
                    <p class="text-base font-semibold mb-3 dark:text-white">
                        {{ __('Customer Group Management') }}
                    </p>
                    <p class="card-text">
                        {{ __('Customer Group Management enables you to categorize clients for targeted service and streamlined management. Use this feature to efficiently organize customer segments while optimizing your workflow.') }}
                    </p>
                    <div class="text-center mt-5">
                        <a href="{{route('admin.customer-groups.create')}}" type="submit" class="btn btn-sm btn-dark inline-flex items-center justify-center">
                            {{ __('Create New Group') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@include('backend.customer_groups.include.__delete')
@endsection
@section('user-management-script')
    <script>

        var animation = lottie.loadAnimation({
            container: document.getElementById('lottie-container'), // ID of the div where the animation will render
            renderer: 'svg',  // Render the animation in SVG format
            loop: true,       // Loop the animation
            autoplay: true,   // Autoplay the animation
            path: '{{ asset('global/json/customer-groups.json') }}' // Path to your JSON file
        });

        $('.deleteCustomerGroup').on('click',function (e) {
            "use strict";
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');

            var url = '{{ route("admin.customer-groups.destroy", ":id") }}';
            url = url.replace(':id', id);
            $('#customerGroupForm').attr('action', url)

            $('.name').html(name);
            $('#deleteCustomerGroup').modal('show');
        })

    </script>
@endsection
