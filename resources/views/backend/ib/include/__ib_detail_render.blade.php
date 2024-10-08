<!-- resources/views/ib_data_modal.blade.php -->

<div>
    <h4 class="text-lg font-medium mb-3">IB Data for {{ $user->full_name }}</h4>
    <ul class="space-y-4">
        @foreach(json_decode($ibData->fields) as $question => $answer)
            <li class="text-sm">
                <span class="font-medium dark:text-slate-300">{{ $question }}:</span>
                @if(is_array($answer))
                    <ul class="list-by-slash mt-1">
                        @foreach($answer as $option)
                            <li class="dark:text-slate-300">{{ $option }}</li>
                        @endforeach
                    </ul>
                @else
                    {{ $answer }}
                @endif
            </li>
        @endforeach
    </ul>
</div>
