@extends('frontend::layouts.partner')
@section('title')
    {{ __('Partner Dashboard') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-700 inline-block ltr:pr-4 rtl:pl-4 mb-4 sm:mb-0 flex space-x-3 rtl:space-x-reverse">
            {{ __('IB Distribution rules') }}
        </h4>
    </div>

    <div id="schemes">
        @include('frontend.prime_x.partner.include.__scheme_rules')
    </div>

    @include('frontend.prime_x.partner.include.__share_modal')

@endsection
@section('script')
    <script>
        $(document).ready(function () {
            $('body').on('click', '.edit-share-btn', function () {
                let ruleId = $(this).data('id');
                let currentShare = $(this).data('share');

                $('#userIbRuleId').val(ruleId);
                $('#subIbShare').val(currentShare);
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#editShareForm').on('submit', function (e) {
                e.preventDefault();

                const formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('user.ib.rule.store') }}", // Replace with your route
                    method: "POST",
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            tNotify('success', response.message); // Show success notification
                            location.reload(); // Reload to reflect changes
                        } else {
                            tNotify('warning', response.message); // Show warning notification
                        }
                    },
                    error: function (xhr) {
                        // Handle errors and display the error message
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            tNotify('error', xhr.responseJSON.message); // Show error notification
                        } else {
                            tNotify('error', "An error occurred. Please try again later.");
                        }
                    }
                });
            });
        });


    </script>
@endsection
