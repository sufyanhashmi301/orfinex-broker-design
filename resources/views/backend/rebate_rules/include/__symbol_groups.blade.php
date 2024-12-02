<ul class="flex flex-wrap items-center gap-3">
    @foreach($symbolGroups as $symbolGroup)
        <li class="badge badge-secondary uppercase">
            {{ $symbolGroup }}
        </li>
    @endforeach
</ul>
