@extends('layouts.admin.app')
@push('head')
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
@endpush
@section('content')
    <div class="container">
        <h2>Twilio Call Recordings</h2>

        @if(isset($error))
            <p class="text-danger">{{ $error }}</p>
        @else
            <h4>Call Details</h4>
            <ul>
                <li><strong>Call SID:</strong> {{ $callDetails['sid'] }}</li>
                <li><strong>From:</strong> {{ $callDetails['from'] }}</li>
                <li><strong>To:</strong> {{ $callDetails['to'] }}</li>
                <li><strong>Status:</strong> {{ $callDetails['status'] }}</li>
                <li><strong>Duration:</strong> {{ $callDetails['duration'] }} seconds</li>
            </ul>

            <h4>Recordings</h4>
            @if(!empty($recordings))
                <ul class="list-group">
                    @foreach($recordings as $recording)
                        <li class="list-group-item">
                            <p><strong>Recording SID:</strong> {{ $recording['sid'] }}</p>
                            <p><strong>Link:</strong> {{ $recording['url'] }}</p>
                            <div class="audio-player">
                                <audio id="audio" src="{{ route('recording.stream', ['recordingSid' => $recording['sid']]) }}"></audio>
                                <div class="controls">
                                    <button id="play-pause" class="play-btn"><i class="fa fa-play"></i></button>
                                    <span id="current-time" class="time">00:00</span>
                                    <input type="range" id="progress-bar" class="progress" value="0" min="0" max="100">
                                    <span id="duration" class="time">00:00</span>
                                </div>
                            </div>
                            
                         </li>
                    @endforeach
                </ul>
            @else
                <p>No recordings found for this call.</p>
            @endif
        @endif
    </div>
@endsection

@push('bottom')
<script>
    $(document).ready(function () {
    var audio = $('#audio')[0];
    var playPauseBtn = $('#play-pause');
    var progressBar = $('#progress-bar');
    var currentTimeEl = $('#current-time');
    var durationEl = $('#duration');

    // Play/Pause functionality
    playPauseBtn.on('click', function () {
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

    // Update duration
    $(audio).on('loadedmetadata', function () {
        durationEl.text(formatTime(audio.duration));
    });

    // Seek functionality
    progressBar.on('input', function () {
        audio.currentTime = (this.value / 100) * audio.duration;
    });

    // Format time (MM:SS)
    function formatTime(seconds) {
        var mins = Math.floor(seconds / 60);
        var secs = Math.floor(seconds % 60);
        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }
});

</script>
@endpush