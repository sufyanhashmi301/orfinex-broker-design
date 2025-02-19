<script>
var callSid = 00; // Variable to store the call SID
let callStartTime;
let callTimerInterval;
let activeConnection = null

document.addEventListener('DOMContentLoaded', function() {
    const tokenEndpoint = "{{ route('admin.generate.token') }}";
    fetch(tokenEndpoint) // Endpoint to get the access token from your server
        .then(response => response.json())
        .then(data => {
            Twilio.Device.setup(data.token);
        });

    Twilio.Device.ready(function(device) {
        console.log('Twilio.Device is ready to make calls!');
    });

    Twilio.Device.error(function(error) {
        console.log('Twilio.Device Error: ' + error.message);
    });

    Twilio.Device.connect(function(conn) {
        console.log('Successfully established call!');
        $('.activity_section').show();
        $('.add_new_contact').hide();
        if (conn.parameters && 'CallSid' in conn.parameters) {
            callSid = conn.parameters.CallSid;
            console.log(callSid);
            // var number = conn.message.To;
            // createNewCustomer(number, 'NO');
        } else {
            console.log('CallSid is not available at this time.');
        }
    });

    Twilio.Device.incoming((connection) => {
        console.log('Incoming call from:', connection.parameters.From);

        // Update the caller number in the modal
        $('#incomingCallNumber').text(connection.parameters.From);

        // Show the modal
        $('#incomingCallModal').removeClass('hidden');

        // Store the connection for future use
        activeConnection = connection;

        // Decline the call
        $('#declineCallBtn').off('click').on('click', () => {
            console.log('Call declined');
            connection.reject();
            $('#incomingCallModal').addClass('hidden');
        });

        // Answer the call
        $('#answerCallBtn').off('click').on('click', () => {
            console.log('Call answered');
            connection.accept();
            startCallTimer(); // Start the call timer

            // Update buttons
            $('#declineCallBtn').html('<i class="fas fa-phone-slash"></i> End Call').removeClass('bg-red-500').addClass('bg-red-600');
            $('#answerCallBtn').hide();

            // Handle ending the call
            $('#declineCallBtn').off('click').on('click', () => {
            console.log('Call ended');
            connection.disconnect(); // End the call
            $('#incomingCallModal').addClass('hidden');
            stopCallTimer();
            sendHistory('Yes', connection.parameters.CallSid); // Assuming sendHistory is defined elsewhere
            });
        });
    });

    // Close modal button
    $('#closeModalBtn').on('click', () => {
        $('#incomingCallModal').addClass('hidden');
    });

    Twilio.Device.disconnect(function(conn) {
        $('#recieved_dev').hide();
        $('#call_dev').show();

        if (conn.parameters && 'CallSid' in conn.parameters) {
            callSid = conn.parameters.CallSid;
            sendHistory('Yes', callSid);
        }

        console.log('Call ended.');
    });
});




function makeCall(customer_number) {
    // Specify the customer's phone number or client identifier here
    const params = {
        To: customer_number
    };

    console.log('Calling ' + params.To);
    var connection = Twilio.Device.connect(params);

    connection.on("error", (error) => {
        console.error("Connection error:", error);
    });

}

function hangUp() {
    Twilio.Device.disconnectAll();
    console.log('Hang up the call');
}


function startCallTimer() {
    const timerElement = document.getElementById('timer');
    const callTimerDiv = document.getElementById('callTimer');
    callTimerDiv.style.display = 'block';

    callStartTime = new Date();
    callTimerInterval = setInterval(() => {
        const now = new Date();
        const elapsedSeconds = Math.floor((now - callStartTime) / 1000);
        const minutes = String(Math.floor(elapsedSeconds / 60)).padStart(2, '0');
        const seconds = String(elapsedSeconds % 60).padStart(2, '0');
        timerElement.textContent = `${minutes}:${seconds}`;
    }, 1000);
}

function stopCallTimer() {
    clearInterval(callTimerInterval);
    document.getElementById('callTimer').style.display = 'none';
    document.getElementById('timer').textContent = '00:00';
}



$(document).ready(function() {

    var clickSound = new Audio('button-click.mp3'); // Ensure the path to your mp3 file is correct

    $('#receiver_number').on('input', function() {
        // Remove any non-numeric characters
        // $(this).val($(this).val().replace(/\D/g, ''));
    });

    $('#receiver_number').val('+');

    // Function to move cursor to the end of the input field
    $.fn.setCursorPosition = function(pos) {
        this.each(function(index, elem) {
            if (elem.setSelectionRange) {
                elem.setSelectionRange(pos, pos);
            } else if (elem.createTextRange) {
                var range = elem.createTextRange();
                range.collapse(true);
                range.moveEnd('character', pos);
                range.moveStart('character', pos);
                range.select();
            }
        });
        return this;
    };

    // Set cursor position to the end of the input field when page loads
    $('#receiver_number').setCursorPosition($('#receiver_number').val().length);

    $('#receiver_number').on('click', function(event) {
        var input = $(this)[0];
        var text = $(this).val();
        var position = 0;

        if (input.setSelectionRange) {
            var start = input.selectionStart;
            var end = input.selectionEnd;
            position = start + (end - start);
        } else if (document.selection) {
            input.focus();
            var range = document.selection.createRange();
            var rangeLength = range.text.length;
            range.moveStart('character', -input.value.length);
            position = range.text.length - rangeLength;
        }

        $(this).setCursorPosition(position);
    });

    $('.typing-icon').on('click', function() {
        $('#receiver_number').focus().setCursorPosition($('#receiver_number').val().length);
    });

    // Handle backspace key press to remove digit from any position
    $('#receiver_number').on('keydown', function(event) {
        if (event.keyCode === 8) { // Backspace key code
            var currentCursorPosition = this.selectionStart;
            var currentValue = $(this).val();
            if (currentCursorPosition > 0) {
                var newValue = currentValue.substring(0, currentCursorPosition - 1) + currentValue
                    .substring(currentCursorPosition);
                $(this).val(newValue);
                $(this).setCursorPosition(currentCursorPosition - 1);
                event.preventDefault(); // Prevent the default backspace behavior (navigation)
            }
        }
    });

    // Handle digit button click to insert the digit at the current cursor position
    $('.number-dig').on('click', function() {
        var id = $(this).attr('name');
        var digit = $('#' + id).text();
        var currentCursorPosition = $('#receiver_number').get(0).selectionStart;
        var currentValue = $('#receiver_number').val();
        var newValue = currentValue.substring(0, currentCursorPosition) + digit + currentValue
            .substring(currentCursorPosition);
        $('#receiver_number').val(newValue);
        $('#receiver_number').setCursorPosition(currentCursorPosition + 1);
    });
});



$(document).on('click', '.open_call_modal', function(event) {
    event.preventDefault();
    assignDetails('open_call_modal');
    $('.add_new_contact').hide();
    $('.activity_section').show();
    $('.make_new_appoint').show();
    $('.dailpad').show();
    $('.call_history').hide();
    $('#call_modal').modal('show');

});

$(document).on('click', '.open_randam_call_modal', function(event) {
    event.preventDefault();
    assignDetails('open_randam_call_modal');
    $('.make_new_appoint').hide();
    $('.activity_section').show();
    $('.add_new_contact').hide();
    $('.dailpad').show();
    $('.call_history').hide();
    $('#call_modal').modal('show');
});



$(document).on('click', '.make_new_appoint', function(event) {
    event.preventDefault();
    console.log('yea');

    $('#close_appointment').attr('data-phone', 1);

});

$(document).on('click', '.add_new_contact', function(event) {
    event.preventDefault();

    $('#close_contact_form').attr('data-phone', 1);

});



$(document).on('click', '#callhistory', function(event) {
    event.preventDefault();
    $('.call_history').show();

    const number = $('#receiver_number').val();
    console.log(number);
    fetchCallHistory(number);
});

$(document).on('click', '#dailpad', function(event) {
    event.preventDefault();
    $('.dailpad').show();
    $('.call_history').hide();
});


function fetchCallHistory(number) {

  // Show the call_history section and clear old data

  $('.add_new_contact').hide();
  $('#call-history-list').empty();
  $('#loader').removeClass('d-none');
  $.ajax({
    url: '{{ route("admin.get.call.history") }}',
    type: "GET",
    data: { number: number },
    success: function(response) {
      $('#loader').hide();
      $('#call-history-list').html(response.html);
    },
    error: function() {
    $('#loader').addClass('d-none');
      $('#call-history-list').html('<li class="list-group-item">Error fetching call history.</li>');
    }
  });
}


$("#receiver_number").on("input", function() {
    let inputVal = this.value;
    
    // Allow only numbers and "+" at the beginning
    let numericVal = inputVal.replace(/(?!^\+)[^\d]/g, '');

    // Ensure "+" is only at the start
    if (numericVal.startsWith('+')) {
        this.value = numericVal;
    } else {
        this.value = numericVal.replace(/\+/g, ''); // Remove "+" if not at the start
    }

    // Show error if any invalid characters were removed
    if (inputVal !== this.value) {
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.error('Only numbers and a "+" at the beginning are allowed!');
    }
});


$(document).on('click', '.call-initiate', function(event) {
    event.preventDefault();

    var phoneNumber = $('#receiver_number').val();
    // $('.ca-status').show();
    // $('.ca-status').text('Calling');

    if (!phoneNumber.trim()) {
        Swal.fire("Error", "Phone number cannot be empty", "error");
        return;
    }

    var code = $('.new_text').attr('data-code');
    if (!phoneNumber.startsWith(code) && phoneNumber != '+8801752474197') {
        phoneNumber = code + phoneNumber;
    }

    makeCall(phoneNumber);


    $('#recieved_dev').show();
    $('#call_dev').hide();
    $('.new_text').text(phoneNumber);

    // Make an AJAX request to initiate the call
    $.ajax({
        url: '{{ route("admin.initiate.call") }}',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        data: {
            phone_number: phoneNumber
        },
        success: function(response) {
            // Display the call duration section, "End Call" button, and "Mute Microphone" button
            console.log(response);
            if (response.success) {
                // callSid = response.call_sid;
                $('.ca-status').text('Calling');
                setTimeout(function() {
                    setToInCall();
                    timeoutTimer = true;
                    looper();
                    //showActiveCallAfterAFewSeconds
                    setTimeout(function() {
                        timeoutTimer = false;
                        timeCounterCounting = true;
                        timeCounterLoop();

                        $('.pulsate').toggleClass('active-call');
                        $('.ca-status').animate({
                            opacity: 0,
                        }, 1000, function() {
                            $(this).text('00:00');
                            $('.ca-status').attr('data-dots', '');

                            $('.ca-status').animate({
                                opacity: 1,
                            }, 1000);
                        });
                    }, 3000);
                }, 500);
            } else {
                toastr.options.positionClass = 'toast-bottom-right';
                toastr.error(response.message);
                $('#recieved_dev').hide();
                $('#call_dev').show();
            }
        },
        error: function(error) {
            console.error('Error initiating call:', error);
        }
    });
});

function assignDetails(identifier) {

    var name            =   $('.' + identifier).attr('name');
    var number          =   $('.' + identifier).attr('number');
    var profile_img     =   $('.' + identifier).attr('profile_pic');

    $('#receiver_number').val(number);
    $('#receiver_name').text(name);
    $('.contact-name').text(name);
    $('.call-profile-img').attr('src', profile_img);
    $('.contact-number').text(number);
    $('.ca-name').text(name);
    $('.ca-number').text(number);
    var newNumber = $('#receiver_number').val();
    console.log('dsf', newNumber);
    $('.ca_number').text(newNumber);
}


$(document).on('click', '.close-call-icon', function(event) {
    event.preventDefault();
    event.stopPropagation();
    endCall();
});


function endCall(){
    $.ajax({
        beforeSend: function() {
            Swal.fire({
                title: "Disconnecting...",
                text: "Please wait",
                imageHeight: 60,
                imageWidth: 60,
                showConfirmButton: false,
                allowOutsideClick: false
            });
        },
        url: '{{ route("admin.end.call") }}',
        method: 'POST', // Change to POST method
        data: {
            call_sid: callSid
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // sendHistory('Yes', callSid);
        }
    });
}


function sendHistory(status, callSid) {
    hangUp(); //disconnect the twilio call

    var contact_id = $('.make_new_appoint').attr('deal_id');
    var toNumber = $('#receiver_number').val();
    $.ajax({
        beforeSend: function() {
            Swal.fire({
                title: "History inserting...",
                text: "Please wait",
                imageHeight: 60,
                imageWidth: 60,
                showConfirmButton: false,
                allowOutsideClick: false
            });
        },
        url: '{{ route("admin.insert.call.history") }}',
        method: 'POST', // Change to POST method
        data: {
            contact_id,
            status,
            toNumber,
            call_sid: callSid
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                'content')
        },
        success: function(response) {
            Swal.close();
            $('#recieved_dev').hide();
            $('#call_dev').show();
        }
    });
}


function createNewCustomer(number, status) {
    $.ajax({
        url: '{{ route("admin.insert.new.customer") }}',
        method: 'POST', // Change to POST method
        data: {
            status,
            number
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                'content')
        },
        success: function(response) {
            $('#note').attr('data-contact_id', response.contact_id);
            $('#call_history').attr('data-contact_id', response.contact_id);
            console.log(response);
        }
    });
}

var timeoutTimer = true;
var timeCounter = 0;
var timeCounterCounting = true;

$('.action-dig').click(function() {
    setTimeout(function() {
        setToInCall();
    }, 500);
    timeCounterCounting = false;
    timeCounter = 0;
    hangUpCall();
    $('.pulsate').toggleClass('active-call');

    $('.ca-status').text('Calling');
    setTimeout(function() {
        setToInCall();
        timeoutTimer = true;
        looper();
        //showActiveCallAfterAFewSeconds
        setTimeout(function() {
            timeoutTimer = false;
            timeCounterCounting = true;
            timeCounterLoop();

            $('.pulsate').toggleClass('active-call');
            $('.ca-status').animate({
                opacity: 0,
            }, 1000, function() {
                $(this).text('00:00');
                $('.ca-status').attr('data-dots', '');

                $('.ca-status').animate({
                    opacity: 1,
                }, 1000);
            });
        }, 3000);
    }, 500);
});

var timeCounterLoop = function() {

    if (timeCounterCounting) {
        setTimeout(function() {
            var timeStringSeconds = '';
            var minutes = Math.floor(timeCounter / 60.0);
            var seconds = timeCounter % 60;
            if (minutes < 10) {
                minutes = '0' + minutes;
            }
            if (seconds < 10) {
                seconds = '0' + seconds;
            }
            $('.ca-status').text(minutes + ':' + seconds);

            timeCounter += 1;

            timeCounterLoop();
        }, 2000);
    }
};

var setToInCall = function() {
    $('.call-pad').toggleClass('in-call');
    // $('.call-icon').toggleClass('in-call');
    // $('.call-change').toggleClass('in-call');
    $('.ca-avatar').toggleClass('in-call');
};

var dots = 0;
var looper = function() {
    if (timeoutTimer) {

        setTimeout(function() {
            if (dots > 3) {
                dots = 0;
            }
            var dotsString = '';
            for (var i = 0; i < dots; i++) {
                dotsString += '.';
            }
            $('.ca-status').attr('data-dots', dotsString);
            dots += 1;

            looper();
        }, 500);
    }
};

var hangUpCall = function() {
    timeoutTimer = false;
};


$(document).ready(function () {
    const inputField = $("#receiver_number");
    const digits = $(".number-dig");
    const maxLength = 10;
    const apiEndpoint = "/get-user-by-contact";


    fetchCallHistory(0);
    $('.call_history').show();

    digits.on("click", function () {
        const value = $(this).attr("name");
         console.log('type..');

        if (inputField.val().length == maxLength) {
            searchContact(inputField.val());
            digits.prop("disabled", true);
        }
    });

    let typingTimer; // Timer identifier
    const typingDelay = 500; // Delay in milliseconds (0.5 seconds)
    function processInput() {
        const value = inputField.val().trim().replace(/\s+/g, '');
        
        if ($.isNumeric(value)) {
            // searchContact(value); // Call the function with the number
            console.log(value);
        }
    }

    // Function to handle typing and pasting events
    function handleInput() {
        clearTimeout(typingTimer); // Clear the timer on each event
        typingTimer = setTimeout(processInput, typingDelay); // Set a new timer
    }

    inputField.on("change input paste", handleInput);

    // Function to search for the contact based on the phone number
    function searchContact(phoneNumber) {
        console.log("Searching for contact with number:", phoneNumber);

        // AJAX request to fetch contact data
        $.ajax({
            url: apiEndpoint,
            type: "POST",
            dataType: "json",
            data: {
                phone: phoneNumber,
                _token: $('meta[name="csrf-token"]').attr("content"), // CSRF token for Laravel
            },
            success: function (response) {
                $(".add_new_contact").show();
                if (response) {
                    // Populate fields with response data
                    $('.add_new_contact #email_address').val(response.email || '');
                    $('.add_new_contact #name').val(response.name || '');
                    $('.add_new_contact #last_name').val(response.last_name || '');
                    $('.add_new_contact #organization').val(response.company || '');
                    $('.add_new_contact #pipeline_id').val(response.pipeline_id || '');
                    $('.add_new_contact #stage_id').val(response.stage_id || '');
                    $('.make_new_appoint').attr('deal_id', response.deal_id);
                    $('.make_new_appoint').attr({
                        "deal_id": response.deal_id,
                        "stage_id": response.stage_id,
                        "data-email": response.email,
                        "data-user_id": response.user_id,
                        "data-name": response.name,
                        "data-last_name": response.last_name,
                    }).show();
                } else {
                    // Clear fields if response is null or
                    makeFieldsEmpty();
                }
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
                makeFieldsEmpty();
            }

        });
    }
});


function makeFieldsEmpty(){
    $('.make_new_appoint').hide();
    $('.activity_section').show();
    $(".add_new_contact").show();
    $("#submit_new_deal").show();
    $('.add_new_contact #email_address').val('');
    $('.add_new_contact #name').val('');
    $('.add_new_contact #last_name').val('');
    $('.add_new_contact #organization').val('');
    $('.add_new_contact #pipeline_id').val('');
    $('.add_new_contact #stage_id').val('');
}



$(".add_new_contact #pipeline_id").click(function(event) {
    event.preventDefault();
    console.log('poipein');
    let id = $(this).val();
    loadStage(id);
});

$("#submit_deal").click(function() {

    $.ajax({
        url: "{{ url('save-deal') }}?send_hubspot=1",
        method: 'POST',
        dataType: 'JSON',
        data: $('#deal_form').serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('.new_lead_invitation').attr('data-deal_id', response.deal_id);
                $('#reminder_deal_id').val(response.deal_id);
                $('#activateService').attr('data-deal_id', response.deal_id);



                // addDeal(response.data);
                // updateCardPrice();
                // priceCalculation(response.data.stage_id, response.data.deal_amount);
                toastr.options.positionClass = 'toast-bottom-right';
                toastr.success(response.message);
                $('#create_deal').modal('hide');
                // $('#invitation_modal').modal('show');

                $('.add_new_contact').hide();
                $('.make_new_appoint').show();
                $('.activity_section').show();
                $('.make_new_appoint').attr({
                    "deal_id": response.deal_id,
                    "stage_id": response.data.stage_id,
                    "data-email": response.data.Email,
                    "data-user_id": response.user_id,
                    "data-name": response.data.name,
                    "data-last_name": response.data.last_name,
                });


                $('#agency-pipeline-section').DataTable().ajax.reload(null, false);

                $('#deal_form')[0].reset();
                $('#create_deal').modal('hide');
                // location.reload();
                Swal.fire({
                    title: 'Success!',
                    text: 'Lead added successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            } else {
                toastr.options.positionClass = 'toast-bottom-right';
                toastr.error(response.message);
            }
        },
        error: function(err) {
            if (err.responseJSON) {
                if (err.responseJSON.message) {
                    toastr.error(err.responseJSON.message);
                }
                if (err.responseJSON.errors) {
                    $.each(err.responseJSON.errors, function(el_k, item) {
                        $('#' + el_k).addClass('is_invalid').focus();
                        toastr.error(item);
                    });
                }
            }
        }
    });
});



$("#submit_new_deal").click(function() {

    const formData = {
            number: $(".receiver_number").val(),
            company: $(".add_new_contact #organization").val(),
            Email: $(".add_new_contact  #email_address").val(),
            name: $(".add_new_contact  #name").val(),
            last_name: $(".add_new_contact  #last_name").val(),
            title: name+' '+last_name,
            pipeline_id: $(".add_new_contact  #pipeline_id").val(),
            stage_id: $(".add_new_contact  #stage_id").val(),
        };

    $.ajax({
        url: "{{ url('save-deal') }}?send_hubspot=1",
        method: 'POST',
        dataType: 'JSON',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('.add_new_contact').hide();
                $('.make_new_appoint').show();
                $('.activity_section').show();
                $('.make_new_appoint').attr({
                    "deal_id": response.deal_id,
                    "stage_id": response.data.stage_id,
                    "data-email": response.data.Email,
                    "data-user_id": response.user_id,
                    "data-name": response.data.name,
                    "data-last_name": response.data.last_name,
                });
                toastr.options.positionClass = 'toast-bottom-right';
                toastr.success(response.message);
                // location.reload();
            } else {
                toastr.options.positionClass = 'toast-bottom-right';
                toastr.error(response.message);
            }
        },
        error: function(err) {
            if (err.responseJSON) {
                if (err.responseJSON.message) {
                    toastr.error(err.responseJSON.message);
                }
                if (err.responseJSON.errors) {
                    $.each(err.responseJSON.errors, function(el_k, item) {
                        $('#' + el_k).addClass('is_invalid').focus();
                        toastr.error(item);
                    });
                }
            }
        }
    });
});

</script>
