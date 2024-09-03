<ul class="flex flex-wrap items-center gap-3">
    @foreach($symbolGroups as $symbolGroup)
        <li class="badge bg-secondary-500 text-secondary-500 bg-opacity-30 uppercase">
            {{ $symbolGroup }}
        </li>
    @endforeach
</ul>
