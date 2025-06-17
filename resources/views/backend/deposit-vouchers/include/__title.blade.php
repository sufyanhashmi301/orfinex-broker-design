<div class="d-flex align-items-center">
    <div>
        <h6 class="mb-0">{{ $title }}</h6>
        @if($description)
            <small class="text-muted">{{ Str::limit($description, 50) }}</small>
        @endif
    </div>
</div> 