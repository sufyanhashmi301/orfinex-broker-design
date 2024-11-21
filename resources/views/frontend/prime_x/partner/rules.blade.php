@extends('frontend::layouts.partner')
@section('title')
    {{ __('Partner Dashboard') }}
@endsection?@section('content')

    <div class="flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-700 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ __('IB Distribution rules') }}
        </h4>
    </div>

    <div class="card mb-6">
        <div class="card-body divide-y divide-slate-100 dark:divide-slate-700 px-6">

            <div class="py-6">
                <div class="flex justify-between flex-wrap items-center mb-5">
                    <h4 class="card-title"> {{ __('Referral Rules') }}</h4>
                    <div class="input-area relative min-w-[184px]">
                        <select name="level_order" class="select2 form-control w-full">

                            @for ($i = 1; $i <= $maxLevelOrderCount; $i++)
                                <option value="{{ $i }}">{{ __('Level ' . $i) }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <span id="schemes">
                    @include('frontend.prime_x.partner.include.__scheme_rules')
                </span>
            </div>

        </div>
    </div>

    @include('frontend.prime_x.partner.include.__share_modal')

@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('select[name="level_order"]').on('change', function () {
                var selectedLevel = $(this).val();

                $.ajax({
                    url: "{{ route('user.multi-level.ib.get.scheme.rules') }}",
                    type: "POST",
                    data: {
                        level_order: selectedLevel,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        // Inject the returned HTML into the container
                        $('#schemes').html(response.html);
                    }
                });
            });
        });

        let currentTotalShareSwap = {{ $currentTotalShareSwap }};
        let currentTotalShareSwapFree = {{ $currentTotalShareSwapFree }};

        // Handle edit button click to open modal with current values
            $('body').on('click', '.edit-share-btn', function () {
            let levelId = $(this).data('id');
            let currentShare = $(this).data('share');
            let context = $(this).data('context'); // Determine if it's swap or swapFree

            $('#levelId').val(levelId);
            $('#sharePercentage').val(currentShare);
            $('#context').val(context);  // Pass context to hidden input
        });

        // Real-time validation before form submission
        $('#sharePercentage').on('input', function () {
            let newShare = parseFloat($(this).val());
            let levelId = $('#levelId').val();
            let context = $('#context').val(); // Get current table context (swap or swapFree)

            // Choose the correct current total based on context
            let currentTotalShare = (context === 'swap') ? currentTotalShareSwap : currentTotalShareSwapFree;

            // Calculate the new total share percentage
            let totalShare = currentTotalShare - parseFloat($(`button[data-id="${levelId}"][data-context="${context}"]`).data('share')) + newShare;

            if (totalShare > 100) {
                tNotify('warning',  "Total share percentage across all levels cannot exceed 100%.");

                $('#sharePercentage').val(''); // Clear the input if it exceeds
            }
        });

        // AJAX form submission for share update
            $('body').on('submit', '#editShareForm', function (e) {
            e.preventDefault();

            let levelId = $('#levelId').val();
            let sharePercentage = parseFloat($('#sharePercentage').val());
            let context = $('#context').val(); // Get current table context

            $.ajax({
                url: "{{ route('user.ib.rule.store') }}",
                type: "POST",
                data: {
                    level_id: levelId,
                    share_percentage: sharePercentage,
                    context: context,  // Send context in the request
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    // console.log(response)
                    if (response.success) {
                        $('#editShareModal').modal('hide');
                        tNotify('success', 'Share percentage updated successfully!');

                        $('select[name="level_order"]').trigger('change');

                        // Update the appropriate current total based on context
                        // if (context === 'swap') {
                        //     currentTotalShareSwap = response.newTotalShare; // Update current total for swap
                        // } else {
                        //     currentTotalShareSwapFree = response.newTotalShare; // Update current total for swapFree
                        // }
                    } else {
                        tNotify('warning', response.message || "Error updating share percentage");
                        // alert(response.message || "Error updating share percentage");
                    }
                }
            });
        });


    </script>
@endsection
