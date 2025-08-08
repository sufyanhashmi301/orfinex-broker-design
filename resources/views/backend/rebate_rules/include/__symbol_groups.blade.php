<ul class="flex flex-wrap items-center gap-3">
    @foreach($symbolGroups as $symbolGroup)
        <li class="badge badge-secondary uppercase">
            <a href="{{ route('admin.symbol-groups.index', ['filter_symbol_group_id' => $symbolGroup['id'], 'filter_symbol_group_title' => $symbolGroup['title']]) }}"
               class="text-primary hover:text-primary-dark cursor-pointer">
                {{ $symbolGroup['title'] }}
            </a>
        </li>
    @endforeach
</ul>