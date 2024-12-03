<div class="flex space-x-3 rtl:space-x-reverse">
    @can('symbol-group-edit')
    <a href="{{ route('admin.symbol-groups.edit',$id) }}" data-id="{{ $id }}" class="action-btn editSymbolGroup">
        <iconify-icon icon="lucide:edit-3"></iconify-icon>
    </a>
    @endcan
    @can('symbol-group-delete')
    <button type="button" data-id="{{ $id }}"  class="action-btn deleteSymbolGroup">
        <iconify-icon icon="lucide:trash-2"></iconify-icon>
     </button>
     @endcan
</div>
