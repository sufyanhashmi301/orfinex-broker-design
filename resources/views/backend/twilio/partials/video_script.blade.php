<script src="https://media.twiliocdn.com/sdk/js/video/releases/2.13.0/twilio-video.min.js"></script>
<script>
$(document).on('click', '.open_video_modal', function(event) {
    event.preventDefault();
    assignDetails();
    const guest_name = $('.open_call_modal').attr('name').trim();
    $('#guest_name').text(guest_name);
    $('#video_modal').modal('show');
});


$(document).ready(function() {
    let token = null;
    let room = null;
    const localMediaContainer = $('#local-media-container');
    const remoteMediaContainer = $('#remote-media-container');
    const usernameInput = $('#username');
    const joinBtn = $('#joinBtn');
    const leaveBtn = $('#leave-meeting-btn');

    joinBtn.on('click', function() {
        $(this).text('Joining...');
        const username = $('.open_call_modal').attr('name').trim();
        $.ajax({
            url: '/generate-token',
            type: 'POST',
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                    'content'), // Include the CSRF token in the headers
            },
            data: JSON.stringify({
                identity: username
            }),
            success: function(data) {
                console.log(data);
                token = data.token;
                initializeVideo();
                $("#camera-toggle-btn, #audio-toggle-btn, #leave-meeting-btn, #timer").css(
                    "display",
                    "inline-block");
                $("#joinBtn").css('display', 'none');

                // Start the timer
                let seconds = 0;
                timer = setInterval(function() {
                    seconds++;
                    $("#timer").text(formatTime(seconds));
                }, 1000);
            },
            error: function(error) {
                console.error('Error generating token:', error);
            }
        });
    });


    leaveBtn.on('click', function() {
        leaveConference();
    });

    function initializeVideo() {
        const Video = Twilio.Video;
        let localParticipant;

        Video.connect(token, {
            video: true,
            audio: true,
            name: 'room-name'
        }).then(newRoom => {
            room = newRoom;
            console.log(`Connected to Room: ${room.name}`);

            localParticipant = room.localParticipant;

            // Display local video feed
            Video.createLocalVideoTrack().then(videoTrack => {
                localMediaContainer.append(videoTrack.attach());
            });

            // Display local audio feed
            Video.createLocalAudioTrack().then(audioTrack => {
                localMediaContainer.append(audioTrack.attach());
            });

            // Listen for participant events
            room.on('participantConnected', handleParticipantConnected);
            room.on('participantDisconnected', handleParticipantDisconnected);

            // Iterate over existing participants and display their video and audio feeds
            room.participants.forEach(handleParticipantConnected);
        }).catch(function(error) {
            console.error(`Unable to connect to Room: ${error.message}`);
        });

        function handleParticipantConnected(participant) {
            console.log(`Participant "${participant.identity}" connected`);

            // Display participant's video and audio feeds
            participant.tracks.forEach(trackPublication => {
                handleTrackPublication({
                    trackPublication,
                    participant
                });
            });

            participant.on('trackPublished', handleTrackPublication);
        }

        function handleParticipantDisconnected(participant) {
            console.log(`Participant "${participant.identity}" disconnected`);
            // Remove participant's video and audio feeds from UI
            // (Implement this according to your UI structure)
        }

        function handleTrackPublication({
            trackPublication,
            participant
        }) {
            trackPublication.on('subscribed', track => {
                if (track.kind === 'video') {
                    trackSubscribed(track, participant);
                } else if (track.kind === 'audio') {
                    trackSubscribed(track,
                        participant); // You can create a separate function for audio if needed
                }
            });

            // Additional handling for 'unsubscribed' event if needed
            trackPublication.on('unsubscribed', track => {
                // Handle the removal of the track from the UI
                // (Implement this according to your UI structure)
            });
        }

        function trackSubscribed(track, participant) {
            // Display the participant's video or audio feed
            const remoteMediaContainer = document.getElementById('remote-media-container');
            const mediaElement = document.createElement(track.kind === 'video' ? 'div' : 'audio');
            mediaElement.className = track.kind === 'video' ? 'remote-video' : 'remote-audio';
            mediaElement.appendChild(track.attach());
            remoteMediaContainer.appendChild(mediaElement);
        }
    }


    function leaveConference() {
        if (room) {
            room.disconnect();
            console.log('Left the Room');
        }
        joinBtn.text('Join Meeting').show();
        localMediaContainer.html('');
        $("#camera-toggle-btn, #audio-toggle-btn, #leave-meeting-btn, #timer").css("display", "none");
        console.log(localVideoTrack.isEnabled);
    }

    function toggleCamera() {
        // Add logic to toggle camera
        console.log("Toggle Camera");
    }

    function toggleAudio() {
        // Add logic to toggle audio
        console.log("Toggle Audio");
    }

    function leaveMeeting() {
        // Add logic to leave the meeting
        console.log("Leave Meeting");
    }

    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = seconds % 60;
        return `${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
    }

});



// $(document).ready(function() {
//     let timer;

//     $("#join-meeting-btn").click(function(event) {
//         event.preventDefault();
//         $("#camera-toggle-btn, #audio-toggle-btn, #leave-meeting-btn, #timer").css("display",
//             "inline-block");
//         $(this).css('display', 'none');

//         // Start the timer
//         let seconds = 0;
//         timer = setInterval(function() {
//             seconds++;
//             $("#timer").text(formatTime(seconds));
//         }, 1000);
//     });

//     $("#camera-toggle-btn").click(function() {
//         // Toggle camera logic
//     });

//     $("#audio-toggle-btn").click(function() {
//         // Toggle audio logic
//     });

//     $("#leave-meeting-btn").click(function() {
//         // Add functionality for leaving the meeting
//         clearInterval(timer);
//         alert('Leaving the meeting...');
//     });

//     function formatTime(seconds) {
//         const minutes = Math.floor(seconds / 60);
//         const remainingSeconds = seconds % 60;
//         return `${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
//     }
// });
</script>