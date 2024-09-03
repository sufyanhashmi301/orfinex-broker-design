<div class="flex space-x-3 rtl:space-x-reverse">
    <a href="{{ route('admin.symbol-groups.edit',$id) }}" data-id="{{ $id }}" class="action-btn editSymbolGroup">
        <iconify-icon icon="lucide:edit-3"></iconify-icon>
    </a>
    <button type="button" data-id="{{ $id }}"  class="action-btn deleteSymbolGroup">
        <iconify-icon icon="lucide:trash-2"></iconify-icon>
     </button>
</div>
