<script>
var callSid = 00; // Variable to store the call SID

$(document).ready(function() {
    $('#receiver_number').on('input', function() {
        // Remove any non-numeric characters
        $(this).val($(this).val().replace(/\D/g, ''));
    });

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
        var digit = $(this).text();
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
    assignDetails();
    $('#call_modal').modal('show');
});
$(document).on('click', '.call-initiate', function(event) {
    event.preventDefault();
    initiateCall();
    assignDetails();
    $(this).removeClass('call-initiate');
    trigger_dialer();
});


function assignDetails() {
    var name = $('.open_call_modal').attr('name');
    var number = $('.open_call_modal').attr('number');
    $('#receiver_number').val(number);
    $('#receiver_name').text(name);
    $('.contact-name').text(name);
    $('.contact-number').text(number);
    $('.ca-name').text(name);
    $('.ca-number').text(number);
}


// function endCall() {
//     // Make an AJAX request to end the call
//     axios.post('{{ route("end.call") }}', {
//             call_sid: callSid
//         })
//         .then(function(response) {
//             toastr.options.positionClass = 'toast-bottom-right';
//             toastr.success('Call ended successfully');
//             $('#call_modal').modal('hide');
//         })
//         .catch(function(error) {
//             console.error('Error ending call:', error);
//         });
// }


$(document).on('click', '.close-call-icon', function(event) {
    event.preventDefault();
    event.stopPropagation();
    $.ajax({
        beforeSend: function() {
            Swal.fire({
                title: "Disconnecting...",
                text: "Bitte warten",
                imageUrl: base_url + 'assets/calendar/loading.gif',
                imageHeight: 60,
                imageWidth: 60,
                showConfirmButton: false,
                allowOutsideClick: false
            });
        },
        url: '{{ route("end.call") }}',
        method: 'POST', // Change to POST method
        data: {
            call_sid: callSid
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            Swal.fire({
                title: 'Can you achived the customer?',
                text: 'The email will be sent to the user to set a password',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ja',
                html: '<label for="customerType"></label>' +
                    '<select id="customerType" name="customerType" class="custom-select">' +
                    '<option value="YES">YES</option>' +
                    '<option value="NO">NO</option>' +
                    '</select>'
            }).then((result) => {
                if (result.isConfirmed) {
                    let status = $('#customerType').val();
                    let deal_id = $('.open_call_modal').attr('deal_id');
                    if (status) {
                        $.ajax({
                            beforeSend: function() {
                                Swal.fire({
                                    title: "History inserting...",
                                    text: "Bitte warten",
                                    imageUrl: base_url +
                                        'assets/calendar/loading.gif',
                                    imageHeight: 60,
                                    imageWidth: 60,
                                    showConfirmButton: false,
                                    allowOutsideClick: false
                                });
                            },
                            url: '{{ route("insert.call.history") }}',
                            method: 'POST', // Change to POST method
                            data: {
                                status,
                                deal_id,
                                call_sid: callSid
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content')
                            },
                            success: function(response) {
                                Swal.close();
                                toastr.options.positionClass =
                                    'toast-bottom-right';
                                toastr.success('Call history inserted');
                                dialair_show();
                                $('#call_modal').modal('hide');
                            }
                        });
                    } else {
                        Swal.fire("Please select a customer type.", "", "warning");
                    }
                } else {
                    dialair_show();
                    $('#call_modal').modal('hide');
                    Swal.close();
                }
            });
        }
    });
});



function trigger_dialer() {
    $('.dial-pad').css('height', '500px')
    $('.number-dig').hide();
    $('#receiver_number').hide();
    $('.igit-div').show();
    $('.addPerson').hide();
    $('.goBack').hide();
    $('.call-change').css('background-color', '#f44336');
    $('.call-icon').addClass('close-call-icon').removeClass('call-initiate').css('top', '13px').css('left', '-2px');
}

function dialair_show() {
    $('.dial-pad').css('height', '610px')
    $('.number-dig').show();
    $('#receiver_number').show();
    $('.igit-div').hide();
    $('.addPerson').show();
    $('.goBack').show();
    $('.call-change').css('background-color', '#3de066');
    $('.call-icon').css('top', '13px').css('left', '-2px').removeClass('close-call-icon').addClass('call-initiate');
}


//TRY TYPING IN ONE OF THESE NUMBERS:
//
// 1234567890
// 0651985833
//




var timeoutTimer = true;
var timeCounter = 0;
var timeCounterCounting = true;

$('.action-dig').click(function() {
    //add animation
    addAnimationToButton(this);
    if ($(this).hasClass('goBack')) {
        var currentValue = $('.phoneString input').val();
        var newValue = currentValue.substring(0, currentValue.length - 1);
        $('.phoneString input').val(newValue);
        checkNumber();
    } else if ($(this).hasClass('call')) {
        if ($('.call-pad').hasClass('in-call')) {
            setTimeout(function() {
                setToInCall();
            }, 500);
            timeCounterCounting = false;
            timeCounter = 0;
            hangUpCall();
            $('.pulsate').toggleClass('active-call');

            $('.phoneString input').val('');
            checkNumber();
        } else {
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
        }
    } else {

    }
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

var addAnimationToButton = function(thisButton) {
    //add animation
    $(thisButton).removeClass('clicked');
    var _this = thisButton;
    setTimeout(function() {
        $(_this).addClass('clicked');
    }, 1);
};

// var checkNumber = function() {
//     var numberToCheck = $('.phoneString input').val();
//     var contactMatt = {
//         name: 'Matt Sich',
//         number: '123456789',
//         image: 'https://s3-us-west-2.amazonaws.com/s.cdpn.io/378978/profile/profile-80_1.jpg',
//         desc: 'CodePenner'
//     };
//     var contactHellogiov = {
//         name: 'hellogiov',
//         number: '0651985833',
//         image: 'http://avatars-cdn.producthunt.com/207787/220',
//         desc: 'Publicis Nurun'
//     };
//     if (numberToCheck.length > 0 && contactMatt.number.substring(0, numberToCheck.length) == numberToCheck) {
//         //show this contact!
//         showUserInfo(contactMatt);
//     } else if (numberToCheck.length > 0 && contactHellogiov.number.substring(0, numberToCheck.length) ==
//         numberToCheck) {
//         showUserInfo(contactHellogiov);
//     } else {
//         hideUserInfo();
//     }
// };

var showUserInfo = function(userInfo) {
    $('.avatar').attr('style', "background-image: url(" + userInfo.image + ")");
    if (!$('.contact').hasClass('showContact')) {
        $('.contact').addClass('showContact');
    }
    $('.contact-name').text(userInfo.name);
    $('.contact-position').text(userInfo.desc);
    var matchedNumbers = $('.phoneString input').val();
    var remainingNumbers = userInfo.number.substring(matchedNumbers.length);
    $('.contact-number').html("<span>" + matchedNumbers + "</span>" + remainingNumbers);

    //update call elements
    $('.ca-avatar').attr('style', 'background-image: url(' + userInfo.image + ')');
    $('.ca-name').text(userInfo.name);
    $('.ca-number').text(userInfo.number);

};

var hideUserInfo = function() {
    $('.contact').removeClass('showContact');
};




function initiateCall() {
    var phoneNumber = $('#receiver_number').val();

    // Make an AJAX request to initiate the call
    $.ajax({
        url: '{{ route("initiate.call") }}',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'POST',
        data: {
            phone_number: +8801752474197
        },
        success: function(response) {
            // Display the call duration section, "End Call" button, and "Mute Microphone" button
            console.log(response);
            if (response.success) {
                toastr.options.positionClass = 'toast-bottom-right';
                toastr.success(response.message);
                setInterval(function() {
                    var currentTime = new Date();
                    var duration = Math.floor((currentTime - startTime) / 1000);
                    var minutes = Math.floor(duration / 60);
                    var seconds = duration % 60;
                    $('#duration').text(minutes + ':' + (seconds < 10 ? '0' : '') + seconds);
                }, 200);
                var startTime = new Date();
                callSid = response.call_sid; // Store the call SID
            } else {
                $('.ca-status').hide();
                $('.ca-message').html('<span class="badge badge-danger">Disconnected</span>');
                toastr.options.positionClass = 'toast-bottom-right';
                toastr.error(response.message);
            }
        },
        error: function(error) {
            console.error('Error initiating call:', error);
        }
    });
}


function muteMicrophone() {
    // Make an AJAX request to mute the microphone
    axios.post('{{ route("mute.microphone") }}', {
            call_sid: callSid
        })
        .then(function(response) {
            console.log('Microphone muted successfully');
        })
        .catch(function(error) {
            console.error('Error muting microphone:', error);
        });
}
</script>