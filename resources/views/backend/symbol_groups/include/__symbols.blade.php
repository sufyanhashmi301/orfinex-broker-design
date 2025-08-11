<ul class="flex flex-wrap items-center gap-3">
    @foreach($symbols as $symbol)
    <li class="badge badge-secondary uppercase">
        <a href="{{ route('admin.symbols.index') }}?filter_symbol={{ $symbol->id }}&global_search={{ urlencode($symbol->symbol) }}" 
           class="text-primary hover:text-primary-dark cursor-pointer">
            {{ $symbol->symbol }}
        </a>
    </li>
    @endforeach
</ul>
