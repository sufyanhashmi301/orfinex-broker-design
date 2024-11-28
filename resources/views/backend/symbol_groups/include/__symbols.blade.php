<ul class="flex flex-wrap items-center gap-3">
    @foreach($symbols as $symbol)
    <li class="badge badge-secondary uppercase">
        {{ $symbol }}
    </li>
    @endforeach
</ul>
