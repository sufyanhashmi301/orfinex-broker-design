@extends('backend.layouts.app')
@section('title')
    {{ __('Manage Levels') }}
@endsection
@section('content')
    <div class="pageTitle flex justify-between flex-wrap items-center mb-6">
        <h4 class="font-medium text-xl capitalize text-slate-500 dark:text-slate-400 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
            @yield('title')
        </h4>
    </div>
    <div id="schemaDetails"></div>
    <div class="card basicTable_wrapper items-center justify-center">
        <div class="max-w-xl w-full relative">
            <label for="" class="form-label">{{ __('Please Select Account') }}</label>
            <select name="" class="form-control flex-1" id="schemaSelect">
                <option value="">{{ __('Select an Account') }}</option>
                @foreach($schemas as $schema)
                    <option value="{{ $schema->id }}">{{ $schema->title }}</option>
                @endforeach
            </select>
        </div>
    </div>

@endsection
@section('script')
    <script>
        $('body').on('change', '#schemaSelect', function() {
            var schemaId = $(this).val();
            if (schemaId) {
                // Construct the route
                var route = "{{ route('admin.multi-level.view', '') }}/" + schemaId;

                // Perform AJAX request
                $.ajax({
                    url: route,
                    type: 'GET',
                    beforeSend: function() {
                        $('#schemaDetails').html('<p>Loading...</p>');
                    },
                    success: function(response) {
                        // Render the response into the div
                        $('.basicTable_wrapper').css('display', 'none');
                        $('#schemaDetails').html(response);
                        $(".select2").select2({
                            placeholder: "Select an Option"
                        });
                    },
                    error: function(xhr, status, error) {
                        $('#schemaDetails').html('<p>Error occurred. Please try again.</p>');
                        console.error('Error occurred: ' + error);
                    }
                });
            } else {
                // Clear the div if no schema is selected
                $('#schemaDetails').html('');
            }
        });


        $(document).ready(function() {

            $('body').on('change', '.toggle-checkbox', function() {
                var target = $(this).data('target');
                $(target).toggleClass('hidden');
            })

            $('body').on('click', '.editSwapBased', function (event) {
                "use strict";
                event.preventDefault();
                $('#edit-swap-based-body').empty();
                var id = $(this).data('id');
                console.log(id,'id')
                var url = '{{ route("admin.swap-multi-level.edit", ":id") }}';
                url = url.replace(':id', id);
                $.get(url, function (data) {
                    $('#editSwapBasedModal').modal('show');
                    $('#edit-swap-based-body').append(data);
                    $('.select2').select2();
                });
            })
            $('body').on('click', '.deleteSwapBased', function (event) {

                "use strict";
                event.preventDefault();
                var id = $(this).data('id');
                var name = $(this).data('name');

                var url = '{{ route("admin.swap-multi-level.destroy", ":id") }}';
                url = url.replace(':id', id);
                $('#swapBasedDeleteForm').attr('action', url)

                $('.name').html(name);
                $('#deleteSwapBased').modal('show');
            })

        });

        $('body').on('submit', '#swapMultiLevelForm', function (event) {
            event.preventDefault(); // Prevent default form submission

            let formData = $(this).serialize(); // Serialize form data

            $.ajax({
                url: "{{ route('admin.swap-multi-level.store') }}", // Your route here
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('#addLevelModal').modal('hide'); // Hide the modal
                    $('#swapMultiLevelForm')[0].reset(); // Reset the form
                },
                error: function(xhr) {
                    // Handle error response
                    let errors = xhr.responseJSON.errors;
                    $('.invalid-feedback').hide(); // Clear previous errors
                    for (let key in errors) {
                        $(`#${key}-groups`).text(errors[key]).show(); // Show error messages
                    }
                }
            });
        });

    </script>
@endsection
