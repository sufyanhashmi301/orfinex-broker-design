<!-- resources/views/ib_data_modal.blade.php -->

<div>
    <h4>IB Data for {{ $user->full_name }}</h4>
    <br>
    <ul>
        @foreach(json_decode($ibData->fields) as $question => $answer)
            <li>
                <strong>{{ $question }}:</strong><br>
                @if(is_array($answer))
                    <ul>
                        @foreach($answer as $option)
                            <li>{{ $option }}</li>
                        @endforeach
                    </ul>
                @else
                    {{ $answer }}
                @endif
            </li>
            <br>
        @endforeach
    </ul>
</div>
