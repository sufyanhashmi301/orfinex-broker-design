<ul class="flex flex-wrap items-center gap-3">
    @foreach($forexSchemas as $schema)
        <li class="badge badge-secondary uppercase">
            <a href="{{ route('admin.accountType.index') }}?filter_schema={{ $schema['id'] }}&title={{ urlencode($schema['title']) }}" 
               class="text-primary hover:text-primary-dark cursor-pointer">
                {{ $schema['title'] }}
            </a>
        </li>
    @endforeach
</ul>