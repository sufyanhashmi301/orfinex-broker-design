<ul class="flex flex-wrap items-center gap-3">
    @foreach($ibGroups as $ibGroup)
        <li class="badge badge-secondary uppercase">
            {{ $ibGroup }}
        </li>
    @endforeach
</ul>
