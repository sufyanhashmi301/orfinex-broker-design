<ul class="flex flex-wrap items-center gap-3">
    @foreach($ibGroups as $ibGroup)
        <li class="badge badge-secondary uppercase">
            <a href="{{ route('admin.ib-group.index') }}?filter_group={{ $ibGroup['id'] }}&global_search={{ urlencode($ibGroup['name']) }}" 
               class="text-primary hover:text-primary-dark cursor-pointer">
                {{ $ibGroup['name'] }}
            </a>
        </li>
    @endforeach
</ul>
