<!DOCTYPE html>
<html>
<head>
<title>Page Title</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    .audio-player {
    background-color: #002d51;
    color: white;
    padding: 15px;
    border-radius: 8px;
    display: inline-block;
    width: 300px;
    font-family: Arial, sans-serif;
}

.controls {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.play-btn {
    background-color: white;
    color: #002d51;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.play-btn:hover {
    background-color: #ccc;
}

.time {
    font-size: 12px;
    margin: 0 5px;
}

.progress {
    flex-grow: 1;
    margin: 0 10px;
    cursor: pointer;
}

   </style>
</head>
<body>

    <div class="audio-player" id="play_1">
        <audio class="audio" src="{{ $link }}"></audio>
        <div class="controls">
            <button class="play-pause play-btn"><i class="fa fa-play"></i></button>
            <span class="current-time time">00:00</span>
            <input type="range" class="progress-bar progress" value="0" min="0" max="100">
            <span class="duration time">{{ $duration }}</span>
        </div>
    </div>

    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function () {
            // Initialize each audio player
            function initializeAudioPlayer(audioPlayer) {
                var audio = audioPlayer.find('.audio')[0];
                var playPauseBtn = audioPlayer.find('.play-pause');
                var progressBar = audioPlayer.find('.progress-bar');
                var currentTimeEl = audioPlayer.find('.current-time');
                var durationEl = audioPlayer.find('.duration');
        
                // Play/Pause functionality
                playPauseBtn.on('click', function () {
                    // Pause all other players
                    $('.audio').each(function () {
                        if (!this.paused && this !== audio) {
                            this.pause();
                            var otherPlayer = $(this).closest('.audio-player');
                            otherPlayer.find('.play-pause').html('<i class="fa fa-play"></i>');
                        }
                    });
        
                    // Play or pause the clicked player
                    if (audio.paused) {
                        audio.play();
                        playPauseBtn.html('<i class="fa fa-pause"></i>');
                    } else {
                        audio.pause();
                        playPauseBtn.html('<i class="fa fa-play"></i>');
                    }
                });
        
                // Update progress bar and current time
                $(audio).on('timeupdate', function () {
                    var progress = (audio.currentTime / audio.duration) * 100;
                    progressBar.val(progress || 0);
                    currentTimeEl.text(formatTime(audio.currentTime));
                });
        
                // Set duration when metadata is loaded
                $(audio).on('loadedmetadata', function () {
                    // durationEl.text(formatTime(audio.duration));
                });
        
                // Seek functionality for progress bar
                progressBar.on('input', function () {
                    audio.currentTime = (this.value / 100) * audio.duration;
                });
        
                // Format time (MM:SS)
                function formatTime(seconds) {
                    var mins = Math.floor(seconds / 60);
                    var secs = Math.floor(seconds % 60);
                    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                }
            }
        
            // Initialize all audio players on the page
            $('.audio-player').each(function () {
                initializeAudioPlayer($(this));
            });
        
            // Reinitialize players if dynamically added via AJAX
            $(document).on('ajaxComplete', function () {
                $('.audio-player').each(function () {
                    if (!$(this).data('initialized')) {
                        initializeAudioPlayer($(this));
                        $(this).data('initialized', true);
                    }
                });
            });
        });
        
        
        </script>
</body>
</html>