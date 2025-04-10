@extends('backend.layouts.app')
@section('title')
    {{ __('Discount Codes') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>

        @can('discount-code-create')
            <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                <a href="javascript:;" class="btn btn-primary inline-flex items-center justify-center" type="button" data-bs-toggle="modal" data-bs-target="#newDiscountModal">
                    <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="lucide:plus"></iconify-icon>
                    {{ __('New Discount Code') }}
                </a>
            </div>    
        @endcan
        
    </div>
    <div class="card">
        <div class="card-body relative px-6 pt-3">
            <div class="overflow-x-auto -mx-6 dashcode-data-table">
                <span class="col-span-8 hidden"></span>
                <span class="col-span-4 hidden"></span>
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden basicTable_wrapper" style="min-height: calc(-241px + 100vh);">
                        <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700" id="dataTable">
                            <thead>
                            <tr>
                                <th scope="col" class="table-th">{{ __('Code Name') }}</th>
                                <th scope="col" class="table-th">{{ __('Code') }}</th>
                                <th scope="col" class="table-th">{{ __('Discount') }}</th> <!-- New Column -->
                                <th scope="col" class="table-th" style="width: 500px">{{ __('Applies To') }}</th>
                                <th scope="col" class="table-th">{{ __('Usage Limit') }}</th>
                                <th scope="col" class="table-th">{{ __('Multiple Levels') }}</th>
                                <th scope="col" class="table-th">{{ __('Expiry At') }}</th>
                                <th scope="col" class="table-th">{{ __('Status') }}</th>
                                <th scope="col" class="table-th">{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                @foreach ($discount_codes as $discount_code)
                                    <tr>
                                        <td class="table-td">{{ $discount_code->code_name }}</td>
                                        <td class="table-td">{{ $discount_code->code }}</td>
                                        <td class="table-td">
                                            @if($discount_code->type === 'percentage')
                                                {{ $discount_code->percentage }}%
                                            @elseif($discount_code->type === 'fixed')
                                                ${{ $discount_code->fixed_amount }}
                                            @endif
                                        </td>
                                        <td class="table-td">

                                            @if (in_array('all', $discount_code->applied_to))
                                                <span class="badge badge-primary mb-1 mr-2">All Plans</span>
                                            @else
                                                @foreach ($discount_code->applied_to as $account_type_id)
                                                    @if (in_array($account_type_id, $account_types->pluck('id')->toArray()))
                                                        <span class="badge badge-primary mb-1 mr-2">{{ $account_types->find($account_type_id)->title }}</span>
                                                    @endif
                                                @endforeach
                                            @endif

                                            
                                        </td>
                                        <td class="table-td" style="text-transform: none">{{ $discount_code->usage_limit }} time(s)</td>
                                        <td class="table-td" style="text-transform: none">{{ count($discount_code->discount_levels ?? []) }} levels</td>
                                        <td class="table-td">{{ date('jS F, Y', strtotime($discount_code->expire_at)) }}</td>
                                        <td class="table-td">
                                            @switch($discount_code->status)
                                                @case(1)
                                                    <span class="badge bg-success-500 text-success-500 bg-opacity-30 capitalize">{{ __('Active') }}</span>
                                                    @break
                                                @case(0)
                                                    <span class="badge bg-danger-500 text-danger-500 bg-opacity-30 capitalize">{{ __('Inactive') }}</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td class="table-td">
                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                @can('discount-code-edit')
                                                    <a href="javascript:void(0)" data-id="{{ $discount_code->id }}" onclick="editDiscount({{ $discount_code->id }})" data-bs-toggle="modal" data-bs-target="#editDiscountModal" class="action-btn">
                                                        <iconify-icon icon="lucide:edit-3"></iconify-icon>
                                                    </a>
                                                @endcan
                                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#discountLevels{{ $discount_code->id }}" class="action-btn">
                                                    <iconify-icon icon="lucide:list-plus"></iconify-icon>
                                                </a>
                                                @can('discount-code-delete')
                                                    <a href="javascript:void(0)" class="action-btn delete-schema-btn" data-id="{{ $discount_code->id }}" data-name="{{ $discount_code->code_name }}" data-bs-toggle="modal" data-bs-target="#deleteDiscountModal" onclick="deleteDiscount({{ $discount_code->id }}, '{{ $discount_code->code_name }}')">
                                                        <iconify-icon icon="lucide:trash"></iconify-icon>
                                                    </a>
                                                @endcan
                                                
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @foreach ($discount_codes as $discount_code)
                            @include('backend.discounts.include.__discount_levels_modal')
                        @endforeach

                        <div class="flex flex-wrap justify-between items-center border-t border-slate-100 dark:border-slate-700 gap-3 px-4 mt-auto">
                            <div>
                                @php
                                    $from = $discount_codes->firstItem();
                                    $to = $discount_codes->lastItem();
                                    $total = $discount_codes->total();
                                @endphp
                                <p class="text-sm text-gray-700 py-3">
                                    Showing <span class="font-medium">{{ $from }}</span> to <span
                                        class="font-medium">{{ $to }}</span> of <span
                                        class="font-medium">{{ $total }}</span> results
                                </p>
                            </div>
                            {{ $discount_codes->appends(request()->except('page'))->links() }}
                        </div>

                    </div>
                </div>
            </div>
            
        </div>
    </div>

    {{--Modal for discount create--}}
    @include('backend.discounts.include.__create')

    @can('discount-code-edit')
        {{--Modal for discount update--}}
        @include('backend.discounts.include.__edit')
    @endcan
    
    @can('discount-code-delete')
        {{--Modal for discount delete--}}
        @include('backend.discounts.include.__delete')
    @endcan

    
    

@endsection
@section('script')
    <script>

        let addDiscountLevel = (index) => {
            return `<div class="option-remove-row grid grid-cols-12 gap-5 discount-level">
                      <div class="xl:col-span-3 col-span-12">
                        <div class="input-area">
                          <input name="data[${index}][amount_from]" min="0" class="form-control" required type="number" placeholder="0">
                        </div>
                      </div>
                      <div class="xl:col-span-3 col-span-12">
                        <div class="input-area">
                          <input name="data[${index}][amount_to]" min="0" class="form-control" required type="number" placeholder="100">
                        </div>
                      </div>
                      <div class="xl:col-span-3 col-span-12">
                        <div class="input-area">
                          <input name="data[${index}][amount]" min="0" class="form-control" required type="number" placeholder="10">
                        </div>
                      </div>
                      <div class="xl:col-span-2 col-span-12">
                        <div class="input-area">
                          <select name="data[${index}][type]" class="form-control w-full mb-3">
                            <option value="fixed" selected="">$</option>
                            <option value="percentage">%</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-span-1">
                        <button class="btn-dark h-[32px] w-[32px] flex items-center justify-center rounded-full text-xl delete-option-row delete_desc" type="button">
                          <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12z"></path>
                          </svg>
                        </button>
                      </div>
                    </div>`
        }

        $(".generateCreate").on('click', function() {

            let discount = $(this).parents('.modal-body')
            let discount_levels = discount.find('.discount-level').length - 1

            if(discount_levels == -1) {
                discount.find('.discount-levels').html('');    
            }
            
            let form = addDiscountLevel(discount_levels + 1)
            
            discount.find('.discount-levels').append(form);
            
        });

        let noDiscountLevelNotice = () => {
            for(let i=0; i < $('.discount-levels').length; i++) {
                let discount_level = $('.discount-levels').eq(i)
                if(discount_level.find('.discount-level').length == 0) {
                    discount_level.html(`<center class="text-sm py-5" style="color: #888">No levels defined for this discount code. Default values will be used.</center>`)
                }
            }
        }
        noDiscountLevelNotice()

        $(document).on('click', '.delete-option-row', function() {
            $(this).closest('.option-remove-row').remove();
            noDiscountLevelNotice()
        });

        $(document).on('click', '.discount-levels input', function() {
            $(this).css('border-color', '#E2E8F0')
        })
        
        $(document).ready(function() {
            function toggleDiscountDiv() {
                // Hide all discount type divs
                $('.discount-type').addClass('hidden');

                // Get the selected value from the type dropdown
                const selectedValue = $('#discounttype').val();

                // Show the corresponding div based on selected type
                if (selectedValue) {
                    $('.discount-type[data-div="' + selectedValue + '"]').removeClass('hidden');
                }
            }

            // Attach the change event to the dropdown
            $('#discounttype').change(toggleDiscountDiv);


        });
        function editDiscount(id) {
            $.get("{{ route('admin.discounts.edit', ':id') }}".replace(':id', id), function(html) {
                $('#editDiscountModal .modal-body').html(html);
                $('.select2').select2()
                $('#editDiscountModal').modal('show');
                
            });
        }

        function deleteDiscount(id, name) {
            let url = "{{ route('admin.discounts.destroy', ':id') }}".replace(':id', id);  // Generate the delete URL
            $('#discountCodeDeleteForm').attr('action', url);  // Set the form action to the delete route
            $('.name').text(name);  // Set the discount name in the modal
            $('#deleteDiscountModal').modal('show');  // Show the delete modal
        }

    </script>
@endsection
