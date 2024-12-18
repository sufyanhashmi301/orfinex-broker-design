
<ul class="flex flex-wrap items-center gap-3">
    @foreach($forexSchemas as $schema)
        <li class="badge badge-secondary uppercase">
            {{ $schema }}
        </li>
    @endforeach
</ul>
