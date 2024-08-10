@extends('frontend::user.setting.index')
@section('title')
    {{ __('Profile Settings') }}
@endsection
@section('settings-content')

    {{--profile settings--}}
    @include('frontend::user.setting.include.__profile')

@endsection
@section('script')
    <script src="{{ asset('frontend/js/intlTelInput.min.js') }}"></script>
    <script>
        const input = document.querySelector("#phone");
        window.intlTelInput(input, {
            showSelectedDialCode: true,
            utilsScript: "{{ asset('frontend/js/utils.js') }}",
        });
        // $('#profile-update-save').on('click', function (event) {
        //     event.preventDefault();
        //     var form = $("#profile-update-form");
        //     // var input = $("#phone");
        //     // Get the selected country data
        //     var countryData = input.intlTelInput('getSelectedCountryData');
        //
        //     // Get the full phone number, including the country code
        //     var fullPhoneNumber = "+" + countryData.dialCode + input.val();
        //
        //     // You can now use 'fullPhoneNumber' to save it into your form or perform other actions
        //     console.log("Full Phone Number:", fullPhoneNumber);
        //
        //     // For example, if you want to save it to a hidden input field before submitting the form
        //     var hiddenInput = $("<input>")
        //         .attr("type", "hidden")
        //         .attr("name", "fullPhoneNumber")
        //         .val(fullPhoneNumber);
        //
        //     // Append the hidden input to the form
        //     form.append(hiddenInput);
        //
        //     // Now, you can submit the form with the updated data
        //     form.submit();
        // });
    </script>
@endsection
