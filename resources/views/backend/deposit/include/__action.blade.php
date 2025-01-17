@can('deposit-action')
    <span type="button" data-id="{{$id}}" id="deposit-action">
        <button class="round-icon-btn action-btn red-btn" data-bs-toggle="tooltip" title="" data-bs-original-title="Approval Process">
            <iconify-icon class="" style="font-size: 16px" icon="lucide:eye"></iconify-icon>
        </button>

        
    </span>

    <span type="button" class="ml-1" data-id="{{$id}}" id="show-invoice">
        <a href="{{ route('admin.invoice.show', ["id" => $id]) }}" target="__blank" class="round-icon-btn action-btn red-btn" data-bs-toggle="tooltip" title="" data-bs-original-title="Invoice">
            <iconify-icon class="" style="font-size: 16px" icon="lucide:receipt"></iconify-icon>
        </a>
    </span>

@endcan
