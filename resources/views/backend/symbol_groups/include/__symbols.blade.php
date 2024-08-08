<ul class="flex flex-wrap items-center gap-3">
    @foreach($symbols as $symbol)
    <li class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 uppercase">
        {{ $symbol }}
    </li>
    @endforeach
</ul>
